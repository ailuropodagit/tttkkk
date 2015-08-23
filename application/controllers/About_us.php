<?php defined('BASEPATH') OR exit('No direct script access allowed');

class About_us extends CI_Controller {
    
    public function index() {
        
        $this->data['page_path_name'] = 'page/about-us';
        $this->load->view('template/layout', $this->data);
    }
    
}