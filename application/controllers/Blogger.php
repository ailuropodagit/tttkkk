<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blogger extends CI_Controller
{
    public function index()
    {
        //QUERY USER
        $data['query_user'] = $this->albert_model->read_blogger($where = NULL);
        //TEMPLATE
        $data['page_path_name'] = 'blogger';
        $this->load->view('template/layout', $data);
    }
}