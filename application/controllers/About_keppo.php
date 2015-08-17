<?php defined('BASEPATH') OR exit('No direct script access allowed');

class About_keppo extends CI_Controller {
    
    public function index() {
        
        $this->load->view('template/header');
        $this->load->view('page/about-keppo');
        $this->load->view('template/footer');
        
    }
    
}