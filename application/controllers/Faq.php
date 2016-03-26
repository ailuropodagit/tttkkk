<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller
{
    public function index()
    {
        $this->data['page_path_name'] = 'page/faq';
        $this->load->view('template/layout', $this->data);
    }
}
