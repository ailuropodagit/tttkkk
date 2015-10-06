<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index($main_category_id = 1)
    {
        //PAGE PATH NAME
        $data['page_path_name'] = 'home'; 
        //QUERY CATEGORY  
        $data['query_category'] = $this->albert_model->read_main_category();
        //QUERY MERCHANT 
        $merchant_where = array('me_category_id'=>$main_category_id);
        $data['query_merchant'] = $this->albert_model->read_merchant($merchant_where);
        //QUERY BANNER
        $banner_where_position_1 = array('category_id'=>$main_category_id, 'banner_position'=>1);
        $data['query_banner_position_1'] = $this->albert_model->read_banner($banner_where_position_1);
        $banner_where_position_2 = array('category_id'=>$main_category_id, 'banner_position'=>2);
        $data['query_banner_position_2'] = $this->albert_model->read_banner($banner_where_position_2);
        $banner_where_position_3 = array('category_id'=>$main_category_id, 'banner_position'=>3);
        $data['query_banner_position_3'] = $this->albert_model->read_banner($banner_where_position_3);
        $banner_where_position_4 = array('category_id'=>$main_category_id, 'banner_position'=>4);
        $data['query_banner_position_4'] = $this->albert_model->read_banner($banner_where_position_4);
        $banner_where_position_5 = array('category_id'=>$main_category_id, 'banner_position'=>5);
        $data['query_banner_position_5'] = $this->albert_model->read_banner($banner_where_position_5);
        $banner_where_position_6 = array('category_id'=>$main_category_id, 'banner_position'=>6);
        $data['query_banner_position_6'] = $this->albert_model->read_banner($banner_where_position_6);
        $banner_where_position_7 = array('category_id'=>$main_category_id, 'banner_position'=>7);
        $data['query_banner_position_7'] = $this->albert_model->read_banner($banner_where_position_7);
        $banner_where_position_8 = array('category_id'=>$main_category_id, 'banner_position'=>8);
        $data['query_banner_position_8'] = $this->albert_model->read_banner($banner_where_position_8);
        $data['main_category_id'] = $main_category_id;
        
//        if ($main_category_id == NULL)
//        {
//            //DATA
//            $data['category_merchant_array'] = $this->m_custom->getMerchantList_by_category('1',0,1);
//        }
//        else
//        {
//            //DATA
//            $data['category_merchant_array'] = $this->m_custom->getMerchantList_by_category($slug,0,1);
//        }
        //LOAD TEMPLATE
        $this->load->view('template/layout_advertisement', $data);
    }
    
    public function get_merchant_list()
    {
        if (isset($_POST) && !empty($_POST))
        {
            $term = $this->input->post('term', TRUE);
            $this->m_custom->home_search_get_merchant($term);
        }
    }

}
