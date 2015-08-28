<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
        //DATA
        $data['left_path_name'] = 'template/sidebar_left';
        $data['page_path_name'] = 'home';
        
        //LOAD TEMPLATE
        $this->load->view('template/layout_advertisement', $data);
        
    }

    public function category() {
        
        //DATA
        $data['page_path_name'] = 'category';
        
        //LOAD TEMPLATE
        $this->load->view('template/layout_advertisement', $data);
        
    }
    
}
