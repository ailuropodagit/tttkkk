<?php defined('BASEPATH') OR exit('No direct script access allowed');

class About_us extends CI_Controller {
    
    public function index() {
        
        $this->load->view('template/header');
        $this->load->view('page/about-us');
        $this->load->view('template/footer');
        
    }
    
}