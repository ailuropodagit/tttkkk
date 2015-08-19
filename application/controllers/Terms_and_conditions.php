<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Terms_and_conditions extends CI_Controller {
    
    public function index() {
        $this->load->view('template/header.php');
        $this->load->view('page/terms_and_conditions.php');
        $this->load->view('template/footer.php');
    }
    
}