<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index($main_category_id = 0)
    {
        //QUERY MAIN CATEGORY  
        $data['query_main_category'] = $this->albert_model->read_main_category();
        //QUERY MERCHANT 
        $where_read_merchant = array('me_category_id'=>$main_category_id);
        $data['query_merchant'] = $this->albert_model->read_merchant($where_read_merchant);
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
        //TEMPLATE
        $data['page_path_name'] = 'home'; 
        $this->load->view('template/index_home', $data);
    }
    
    public function home_banner_ajax() 
    {
        //POST
        $main_category_id = $_POST['category_id'];
        //PAGE PATH NAME
        $data['page_path_name'] = 'home_banner_ajax'; 
        //READ SUB CATEGORY WITH MERCHANT
        $where_read_sub_category_with_merchant = array('main_category_id'=>$main_category_id);
        $query_read_sub_category_with_merchant = $this->albert_model->read_sub_category_with_merchant($where_read_sub_category_with_merchant);
        $data['query_read_sub_category_with_merchant'] = $query_read_sub_category_with_merchant;
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
        //TEMPLATE
        $this->load->view('home_banner_ajax', $data);
    }
}
