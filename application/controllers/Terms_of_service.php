<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Terms_of_service extends CI_Controller
{
    public function index()
    {
        $this->data['page_path_name'] = 'page/terms_of_service.php';
        $this->load->view('template/layout', $this->data);
    }
}
