<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_state');
    }

    public function index() {
        
        $data['state'] = $this->m_state->get_state();
        
        $this->load->view('template/header');
        $this->load->view('template/search', $data);
        $this->load->view('home');
        $this->load->view('template/footer');
    }

}