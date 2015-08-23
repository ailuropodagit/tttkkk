<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_us extends CI_Controller {
    
    public function index() {
        
        $this->data['page_path_name'] = 'page/contact-us';
        $this->load->view('template/layout', $this->data);
    }
    
}