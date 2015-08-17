<?php defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {
    
    public function index() {
        
        $this->load->view('template/header');
        $this->load->view('page/news');
        $this->load->view('template/footer');
        
    }
    
}