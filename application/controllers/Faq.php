<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller
{
    public function index()
    {
        $data['query_faq'] = $this->albert_model->read_faq();
        
        $data['page_path_name'] = 'page/faq';
        $this->load->view('template/layout', $data);
    }
}
