<?php

$config = array(
    'admin/connect_handler' => array(
        array(
            'field' => 'login',
            'label' => 'Login',
            'rules' => 'trim|required|alpha_dash|max_length[255]|callback__is_admin[pwd]'
        ),
        array(
            'field' => 'pwd',
            'label' => 'Password',
            'rules' => 'required|max_length[255]'
        )
    ),

    'admin/config_handler' => array(
        array(
            'field' => 'login',
            'label' => 'Login',
            'rules' => 'trim|required|alpha_dash|max_length[255]'
        ),
        array(
            'field' => 'pwd_1',
            'label' => 'Password',
            'rules' => 'max_length[255]|matches[pwd_2]'
        ),
        array(
            'field' => 'pwd_2',
            'label' => 'Password Confirmation',
            'rules' => 'max_length[255]|matches[pwd_1]'
        ),
        array(
            'field' => 'viadeo_api[client_id]',
            'label' => 'Client ID',
            'rules' => 'trim|required|max_length[255]'
        ),
        array(
            'field' => 'viadeo_api[client_secret]',
            'label' => 'Client Secret',
            'rules' => 'trim|required|max_length[255]'
        )
    ),

    'main/search_handler' => array(
        array(
            'field' => 'domain',
            'label' => 'Domaine',
            'rules' => 'trim|required|max_length[255]|checkdnsrr'
        ),
        array(
            'field' => 'page',
            'label' => 'Page',
            'rules' => 'trim|required|integer|greater_than[0]|less_than[51]'
        ),
        array(
            'field' => 'department',
            'label' => 'Département',
            'rules' => 'trim|integer|max_length[2]'
        ),
        array(
            'field' => 'position',
            'label' => 'Fonction',
            'rules' => 'trim|max_length[255]'
        ),
        array(
            'field' => 'company',
            'label' => 'Société',
            'rules' => 'trim|max_length[255]'
        ),
    ),

    'main/search_count' => array(
        array(
            'field' => 'domain',
            'label' => 'Domaine',
            'rules' => 'trim|required|max_length[255]|callback__is_valid_domain'
        ),
        array(
            'field' => 'department',
            'label' => 'Département',
            'rules' => 'trim|integer|max_length[2]'
        ),
        array(
            'field' => 'position',
            'label' => 'Fonction',
            'rules' => 'trim|max_length[255]'
        ),
        array(
            'field' => 'company',
            'label' => 'Société',
            'rules' => 'trim|max_length[255]'
        ),
    )


);