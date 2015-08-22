<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct() {
        parent::__construct();

        //$this->load->library(array('form_validation'));
    }

    public function index() {
        //
        $data['page_path_name'] = 'home';
            $data['state_list'] = $this->m_custom->get_static_option_array('state','0','All');

            $data['me_state_id'] = array(
                'name' => 'me_state_id',
                'id' => 'me_state_id',
            );
        //load template
        $this->load->view('template/layout', $data);
    }

}