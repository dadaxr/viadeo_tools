<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: dadoux
 * Date: 12/07/12
 * Time: 19:13
 * To change this template use File | Settings | File Templates.
 */


class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $theme_path = base_url().$this->template->get_theme_path();
        $this->template->set('theme_path', $theme_path);
    }
}