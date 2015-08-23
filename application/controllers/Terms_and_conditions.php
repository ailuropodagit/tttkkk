<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Terms_and_conditions extends CI_Controller {
    
    public function index() {
        $this->data['page_path_name'] = 'page/terms_and_conditions.php';
        $this->load->view('template/layout', $this->data);
    }
    
}