<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hotdeal extends CI_Controller
{

    function __construct()
    {
        parent::__construct();     
        $this->load->library(array('ion_auth'));
        $this->main_group_id = $this->config->item('group_id_merchant');
        $this->album_merchant = $this->config->item('album_merchant');
        $this->login_type = 0;
        if ($this->ion_auth->logged_in())
        {
            $this->login_type = $this->session->userdata('user_group_id');
        }
    }
    
    function hotdeal_list(){
        
        
        $this->data['left_path_name'] = 'template/sidebar_left_full';    
        $this->data['page_path_name'] = 'all/hotdeal_list';
        $this->load->view('template/layout_right', $this->data);
    }
    
}