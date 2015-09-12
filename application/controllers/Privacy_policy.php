<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy_policy extends CI_Controller
{
    public function index()
    {
        $this->data['page_path_name'] = 'page/privacy_policy.php';
        $this->load->view('template/layout', $this->data);
    }
}
