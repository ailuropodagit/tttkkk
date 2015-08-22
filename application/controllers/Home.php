<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('m_state');
    }

    public function index() {
        //
        $data['page_path_name'] = 'home';
        $data['state'] = $this->m_state->get_state();
        //load template
        $this->load->view('template/template', $data);
    }

}