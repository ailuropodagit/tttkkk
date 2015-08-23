<?php defined('BASEPATH') OR exit('No direct script access allowed');

class News extends CI_Controller {
    
    public function index() {
        
        $this->data['page_path_name'] = 'page/news';
        $this->load->view('template/layout', $this->data);
    }
    
}