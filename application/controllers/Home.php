<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
        //DATA
        $data['page_path_name'] = 'home';
        $data['category_array'] = $this->m_custom->getCategory();
        $data['category_merchant_array'] = $this->m_custom->getMerchantList_by_category('1');
        
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
