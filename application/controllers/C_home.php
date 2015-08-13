<?php defined('BASEPATH') OR exit('No direct script access allowed');

class C_home extends CI_Controller {

    public function index() {
        $this->load->view('template/header');
        $this->load->view('template/search');
        $this->load->view('home');
        $this->load->view('template/footer');
    }

}