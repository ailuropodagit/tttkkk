<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller
{
    public function index() 
    {
        //DATA
        $data['page_path_name'] = 'all/categories';
        //LOAD TEMPLATE
        $this->load->view('template/layout_advertisement', $data);
    }
}
