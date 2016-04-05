<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Help_guide extends CI_Controller
{
    public function user()
    {
        $this->load->view('user/help_guide');
    }
    
    public function merchant()
    {
        $this->load->view('merchant/help_guide');
    }
}
