<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index($slug = NULL)
    {
        //DATA
        $data['page_path_name'] = 'home';
        $data['category_array'] = $this->m_custom->getCategory();
        $data['slug'] = $slug;
        if ($slug == NULL)
        {
            //DATA
            $data['category_merchant_array'] = $this->m_merchant->getMerchantList_by_category('1',0,1);
        }
        else
        {
            //DATA
            $data['category_merchant_array'] = $this->m_merchant->getMerchantList_by_category($slug,0,1);
        }
        //LOAD TEMPLATE
        $this->load->view('template/layout_advertisement', $data);
    }
}
