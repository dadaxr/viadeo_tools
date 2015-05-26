<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller
{
    private $table_name = "entries";
    private $is_authenticated = false;
    private $admin = null;

    public function __construct(){
        parent::__construct();
        $this->is_authenticated = $this->session->userdata('admin.is_authenticated');
    }

    public function index()
    {
        $this->connect();
    }

    public function connect(){
        if($this->is_authenticated){
            redirect('admin/config');
        }
        $template_title = 'Viadeo Tools - Administration - Connexion';
        $this->template->title($template_title)->build('admin/connect');
    }

    public function connect_handler()
    {
        if($this->is_authenticated){
            redirect('admin/config');
        }

        $post_data = $this->input->post(null, TRUE); // returns all POST items
        $redirect = false;

        if ($this->form_validation->run() == FALSE)
        {
            $redirect = true;
        }

        if ($redirect)
            return $this->connect();


        $list_session_data = array(
            'admin.is_authenticated' => true,
            'admin.user' => $this->admin
        );
        $this->session->set_userdata($list_session_data);
        $this->is_authenticated = true;

        $this->config();
    }

    public function disconnect()
    {
        $this->session->unset_userdata('admin.is_authenticated');
        redirect('admin/connect');
    }

    public function config(){
        if(!$this->is_authenticated){
            redirect('admin/connect');
        }

        $list_db_params = $this->db->get('params')->result();
        $list_params = array();
        foreach($list_db_params as $a_param){
            $list_params[$a_param->name] = $a_param;
        }

        $data = array();
        $data['admin'] = $this->session->userdata('admin.user');
        $data['list_params'] = $list_params;

        $template_title = 'Viadeo Tools - Administration';
        $this->template->set($data);
        $this->template->title($template_title)->build('admin/config');
    }

    public function config_handler()
    {
        $redirect = false;

        if(!$this->is_authenticated){
            redirect('admin/connect');
        }

        if ($this->form_validation->run() == FALSE)
        {
            return $this->config();
        }

        $post_data = $this->input->post(null, TRUE); // returns all POST items

        if($redirect)
            redirect('admin/config');

        if(!empty($post_data['pwd_1'])){
            //on met à jour les infos de l'admin
            $row = array(
                'login' => $post_data['login'],
                'pwd' => $this->crypt_password($post_data['pwd_1'])
            );
            $this->db->where('login', $post_data['login'])->update('users', $row);
        }

        foreach($post_data['viadeo_api'] as $param_name => $param_value){
            $param_name = 'viadeo_api_'.$param_name;
            $row = array(
                'name' => $param_name,
                'value' => $param_value
            );
            $this->db->where('name', $param_name)->update('params', $row);
            
            $this->session->set_userdata($param_name,$param_value);

        }

        redirect('admin/config');
    }

    public function _is_admin($login,$pwd_key){
        $pwd = $this->input->post($pwd_key);
        $where_values = array(
            'login' => $login,
            'pwd' => $this->crypt_password($pwd),
            'is_admin' => 1
        );
        $list_admins = $this->db->where($where_values)->limit(1)->get('users')->result();
        if( empty($list_admins) ){
            $this->form_validation->set_message("_is_admin", "Echec de l'authentification");
            return FALSE;
        }else{
            $this->admin = $list_admins[0];
            return TRUE;
        }

    }

    private function crypt_password($pwd){
        $crypted_pwd = sha1($this->config->item('encryption_key').$pwd);
        return $crypted_pwd;
    }
}
