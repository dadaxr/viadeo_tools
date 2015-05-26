<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller
{
    private $table_name = "entries";
    private $list_elapsed_times = array();
    private $viadeo_api_result_max_limit;

    public function __construct(){
        parent::__construct();
        $this->load->library('ViadeoAPI');

        if(!$this->session->userdata('viadeo_api_client_id')){
            $list_viadeo_param_names = array('viadeo_api_client_id', 'viadeo_api_client_secret', 'viadeo_api_result_max_limit');
            $list_viadeo_params = $this->db->where_in('name', $list_viadeo_param_names)->get('params')->result();
            foreach($list_viadeo_params as $param){
                $this->session->set_userdata($param->name,$param->value);
            }
        }

        $this->viadeoapi->init(array(
            'store'            => true,
            'client_id'        => $this->session->userdata('viadeo_api_client_id'),
            'client_secret'    => $this->session->userdata('viadeo_api_client_secret')
        ));

        $this->viadeo_api_result_max_limit = $this->session->userdata('viadeo_api_result_max_limit');

        /*$current_ts = mktime();
        $expiration_ts = 1353193200; //18/11/12
        if($current_ts >= $expiration_ts){
            header('Content-type: text/html; charset=utf-8');
            die("désolé, il n'est plus possible d'utiliser l'application, vous avez dépassé le délai imparti pour payer");
        }*/

    }

    public function index()
    {
        $this->connect();
    }

    public function connect()
    {
        if($this->viadeoapi->isAuthenticated()){
            redirect('main/search');
        }

        $viadeo_authorization_code = $this->input->get('code');
        if(!$viadeo_authorization_code){
            $template_title = 'Viadeo Tools - Connexion';
            $this->template->title($template_title)->build('main/index');
        }else{
            $this->viadeoapi->setAuthorizationCode($viadeo_authorization_code);
            $this->viadeoapi->setRedirectURI(base_url('/main/connect'));


            // Use the Viadeo API automated OAuth workflow management ---------------------
            try {
                $this->viadeoapi->OAuth_auto();
            } catch (ViadeoException $e) {
                echo "An error occured during Viadeo API authentication: $e";
            }

            redirect('main/search');
        }
    }

    public function disconnect()
    {
        // Manage with disconnect -----------------------------------------------------
        $this->viadeoapi->disconnect();

        redirect('main/connect');
    }


    public function search(){
        if(!$this->viadeoapi->isAuthenticated()){
            redirect('main/connect');
        }

        $template_title = 'Viadeo Tools - Formulaire de recherche';

        $data = array();
        $query = $this->db->order_by("created", "desc")->get($this->table_name,5);
        $data['list_last_entries'] = $query->result();
        $data['search_results'] = $this->session->flashdata('search_results');
        $data['list_elapsed_times'] = $this->session->flashdata('list_elapsed_times');
        $this->template->set($data);
        $this->template->title($template_title)->build('main/form');
    }

    public function search_handler()
    {
        if (!$this->viadeoapi->isAuthenticated())
            redirect('main/connect');

        $api_request = $this->_compute_param_request();
        if(!$api_request){
            return $this->search();
        }

		ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 300);
        ini_set('max_input_time', 300);

        $post_data = $this->input->post(null, TRUE); // returns all POST items
        $list_local_matching_entries = $this->_get_local_matching_entries($post_data);

        $this->benchmark->mark('viadeo_request_start');
        //$api_request->setParam('page', $post_data['page']);
        $api_result = $api_request->execute();
        $this->db->reconnect(); //tres important permet de rétablir la connexion à la db
        $this->benchmark->mark('viadeo_request_end');
        $this->list_elapsed_times['viadeo_request'] = $this->benchmark->elapsed_time('viadeo_request_start','viadeo_request_end');

        $list_remote_entries = array();

        if($post_data['department']){
            foreach($api_result->data as $entry){
                if(substr($entry->zipcode,0,2) == $post_data['department']){
                    $list_remote_entries[] = $entry;
                }
            }
        }else{
            $list_remote_entries = $api_result->data;
        }

        $list_updated_viadeo_ids = $this->_update_entries($post_data, $list_remote_entries);
        $list_deleted_viadeo_ids = array();
        if(count($list_local_matching_entries) > $this->viadeo_api_result_max_limit){
            $list_deleted_viadeo_ids = $this->_remove_entries($list_local_matching_entries, $list_updated_viadeo_ids);
        }
        $list_added_rows = $this->_add_entries($list_remote_entries, $post_data, $list_updated_viadeo_ids);

        $search_results = array(
            'nb_updated' => count($list_updated_viadeo_ids),
            'nb_deleted' => count($list_deleted_viadeo_ids),
            'nb_added' => count($list_added_rows),
        );

        $this->session->set_flashdata('search_results', $search_results);
        $this->session->set_flashdata('list_elapsed_times', $this->list_elapsed_times);

        redirect('main/search');
    }

    public function search_count(){
        if(!$this->input->is_ajax_request()){
            die();
        }

        $data = array(
            'html' => '',
            'show_form_pagination' => false,
            'errors' => false,
        );
        $view_data = array();

        $api_request = $this->_compute_param_request();
        if(!$api_request){
            $view_data['errors'] = validation_errors();
            if(!empty($view_data['errors'])){
                $data['show_form_pagination'] = true;
            }
        }else{
            $api_request->setParam('limit', 0);
			try{
				$api_result = $api_request->execute();
				$view_data['nb_results'] = intval($api_result->count);
				$too_many_results = $view_data['nb_results'] > $this->viadeo_api_result_max_limit;
				/*$view_data['nb_pages'] = ceil($view_data['nb_results']/$this->viadeo_api_result_max_limit);
				$view_data['nb_pages'] = $view_data['nb_pages'] > 50 ? 50 : $view_data['nb_pages'];
				if($view_data['nb_pages'] > 1){
					$data['show_form_pagination'] = true;
				}*/
				if($too_many_results){
					$data['show_form_pagination'] = true;
				}
				
			}catch(Exception $e){
				$view_data['errors'] = $e->getMessage();
				$data['show_form_pagination'] = true;
			}
        }

        if( $data['show_form_pagination'] ){
            $this->template->set_layout('ajax');
            $this->template->set($view_data);
            $data['html'] = $this->template->build('main/form_pagination',array(),true);
        }

        $data = utf8_encode(json_encode($data));
        echo $data;
    }

    private function _compute_param_request(){
        $post_data = $this->input->post(null, TRUE); // returns all POST items
        if ($this->form_validation->run() == FALSE)
        {
            return false;
        }

        $this->viadeoapi->setAccessToken($this->viadeoapi->getAccessToken());
        $api_request = $this->viadeoapi->get("/search/users");
        $api_request->setParam('country', 'fr');
        $api_request->setParam('company', $post_data['company']);
        $api_request->setParam('company_option', 'current|'.$post_data['company_option']);
        $api_request->setParam('position', $post_data['position']);
        $api_request->setParam('position_option', 'current|'.$post_data['position_option']);
        $api_request->setParam('user_detail', 'full'); //'partial'
        $api_request->setParam('connections', 'career');
        $api_request->setParam('limit', $this->viadeo_api_result_max_limit);

        return $api_request;
    }

    private function _get_local_matching_entries($post_data){
        $this->benchmark->mark('get_local_matching_entries_start');

        $where_values = array();
        if(!empty($post_data['company'])){
            $where_values['company'] = $post_data['company'];
        }
        if(!empty($post_data['position'])){
            $where_values['position'] = $post_data['position'];
        }

        $this->db->where($where_values);
        if(!empty($post_data['department'])){
            $this->db->like('zipcode',$post_data['department'],'after');
        }
        $query =  $this->db->get($this->table_name);
        $result = $query->result();
        
        $this->benchmark->mark('get_local_matching_entries_end');
        return $result;
    }

    private function _update_entries($post_data, $list_remote_entries){
        $this->benchmark->mark('update_entries_start');
        $list_remote_viadeo_ids = array();
        $list_remote_entries_by_viadeo_id = array();
        $list_updated_entries = array();
        if(!empty($list_remote_entries)){
            foreach($list_remote_entries as $entry){
                $list_remote_viadeo_ids[] = $entry->id;
                $list_remote_entries_by_viadeo_id[$entry->id] = $entry;
            }
            $nb_loop = ceil(count($list_remote_viadeo_ids) / 500);
            for($i = 0; $i <= $nb_loop ; $i++){
                $offset = $i*500;
                $w_partial_list_viadeo_ids = array_slice($list_remote_viadeo_ids, $offset, 500);
                if(empty($w_partial_list_viadeo_ids)) continue;
                $query = $this->db->select('viadeo_id')->where_in('viadeo_id', $w_partial_list_viadeo_ids)->get($this->table_name);
                $result = $query->result();
                foreach($result as $entry){
                    $list_updated_entries[] = $entry->viadeo_id;
                    $row = $this->_build_db_row($post_data, $list_remote_entries_by_viadeo_id[$entry->viadeo_id]);
                    if($row){
                        $this->db->where('viadeo_id', $entry->viadeo_id)->update($this->table_name, $row);
                    }
                }
            }
        }
        $this->benchmark->mark('update_entries_end');
        $this->list_elapsed_times['update_entries'] = $this->benchmark->elapsed_time('update_entries_start','update_entries_end');
        return $list_updated_entries;
    }

    private function _remove_entries($list_local_entries, $list_updated_viadeo_ids){
        $this->benchmark->mark('remove_entries_start');
        $list_to_delete_viadeo_ids = array();
        foreach($list_local_entries as $entry){
            if(in_array($entry->viadeo_id, $list_updated_viadeo_ids)){continue;}
            $list_to_delete_viadeo_ids[] = $entry->viadeo_id;
        }
        if(!empty($list_to_delete_viadeo_ids)){

            $nb_loop = ceil(count($list_to_delete_viadeo_ids) / 500);
            for($i = 0; $i <= $nb_loop ; $i++){
                $offset = $i*500;
                $w_partial_list_viadeo_ids = array_slice($list_to_delete_viadeo_ids, $offset, 500);
                if(empty($w_partial_list_viadeo_ids)) continue;
                $this->db->where_in('viadeo_id', $w_partial_list_viadeo_ids)->delete($this->table_name);
            }
        }
        $this->benchmark->mark('remove_entries_end');
        $this->list_elapsed_times['remove_entries'] = $this->benchmark->elapsed_time('remove_entries_start','remove_entries_end');
        return $list_to_delete_viadeo_ids;
    }

    private function _add_entries($list_remote_entries, $post_data, $list_updated_viadeo_ids){
        $this->benchmark->mark('add_entries_start');
        $list_rows = array();
        foreach($list_remote_entries as $entry){
            if(in_array($entry->id, $list_updated_viadeo_ids)){continue;}
            $row = $this->_build_db_row($post_data ,$entry);
            if($row){
                $list_rows[] = $row;
            }
        }

        if(!empty($list_rows)){
            $nb_loop = ceil(count($list_rows) / 500);
            for($i = 0; $i <= $nb_loop ; $i++){
                $offset = $i*500;
                $w_partial_list_rows = array_slice($list_rows, $offset, 500);
                if(empty($w_partial_list_rows)) continue;
                $this->db->insert_batch($this->table_name,$w_partial_list_rows);
            }
        }

        $this->benchmark->mark('add_entries_end');
        $this->list_elapsed_times['add_entries'] = $this->benchmark->elapsed_time('add_entries_start','add_entries_end');
        return $list_rows;
    }

    private function _build_db_row($post_data, $remote_entry){

        $pattern = '#\(|\)|\!|\?|\.#';
        $is_firstname_and_lastname_invalid = preg_match($pattern,$remote_entry->first_name) || preg_match($pattern,$remote_entry->last_name);
        if($is_firstname_and_lastname_invalid){
            return false;
        }

        //tentative de récupération des vraies info de company et de position
        //valeur par défaut de la company et de la position
        $company_fqdn = $post_data['company'];
        $position_fqdn = $post_data['position'];
        foreach($remote_entry->connections as $entry_connection){
            if($entry_connection->key == 'career'){
                foreach($entry_connection->value->data as $a_company){
                    $company_pattern = '#'.$post_data['company'].'#i';
                    if( $a_company->still_in_position == true && preg_match($company_pattern,$a_company->company_name) ){
                        $company_fqdn = $a_company->company_name;
                        $position_fqdn = $a_company->position;
                        break;
                    }
                }
            }
        }

        $row = array(
            'viadeo_id'      => $remote_entry->id,
            'created'         => date("Y-m-j H:i:s"),
            'first_name'      => $remote_entry->first_name,
            'last_name'       => $remote_entry->last_name,
            'company'         => $post_data['company'],
            'company_fqdn'    => $company_fqdn,
            'position'        => $post_data['position'],
            'position_fqdn'   => $position_fqdn,
            'city'            => $remote_entry->location->city,
            'zipcode'         => $remote_entry->location->zipcode,
            'gender'          => $remote_entry->gender,
            'twitter_account' => $remote_entry->twitter_account,
            'introduction'    => $remote_entry->introduction,
            'domain'          => $post_data['domain']
        );
        $row['mail'] = $this->_generate_mail($remote_entry->first_name, $remote_entry->last_name, $post_data['domain'], $post_data['mail_pattern']);

        return $row;
    }

    private function _generate_mail($fn, $ln, $domain, $mail_pattern){
        $mail = '';

        $domain = mb_strtolower($domain);

        $pattern = '/ |_|\-|\./';

        $fn = remove_accents(mb_strtolower($fn));
        $fn = preg_replace($pattern,'-',$fn);

        $ln = remove_accents(mb_strtolower($ln));
        $ln = preg_replace($pattern,'-',$ln);

        $list_pattern = array(
            "prenom.nom" => $fn.'.'.$ln,
            "prenom-nom" => $fn.'-'.$ln,
            "prenom_nom" => $fn.'_'.$ln,
            "prenomnom" => $fn.$ln,

            "nom.prenom" => $ln.'.'.$fn,
            "nom-prenom" => $ln.'-'.$fn,
            "nom_prenom" => $ln.'_'.$fn,
            "nomprenom" => $ln.$fn,

            "p.nom" => $this->_get_initial($fn).'.'.$ln,
            "p-nom" => $this->_get_initial($fn).'-'.$ln,
            "p_nom" => $this->_get_initial($fn).'_'.$ln,
            "pnom" => $this->_get_initial($fn).$ln,

            "prenom.n" => $fn.'.'.$this->_get_initial($ln),
            "prenom-n" => $fn.'-'.$this->_get_initial($ln),
            "prenom_n" => $fn.'_'.$this->_get_initial($ln),
            "prenomn" => $fn.$this->_get_initial($ln),

            "prenom" => $fn,
            "nom" => $ln
        );

        if(!empty($list_pattern[$mail_pattern])){
            $mail = $list_pattern[$mail_pattern].'@'.$domain;;
        }

        return $mail;
    }

    function _get_initial($item) {
        $callback = array($this,'_first_letter');
        return join('', array_map($callback, explode('-',$item)));
    }

    function _first_letter($str){
        return substr($str,0,1);
    }

    function _is_valid_domain($domain){
        $is_domain_ok = dns_check_record($domain);
        if(!$is_domain_ok){
            $this->form_validation->set_message("_is_valid_domain", "Le domaine saisi n'existe pas");
        }
        return $is_domain_ok;
    }
}
