<?php defined('BASEPATH') OR exit('No direct script access allowed');

class All extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('ion_auth'));
        $this->album_merchant_profile = $this->config->item('album_merchant_profile');
        $this->album_merchant = $this->config->item('album_merchant');
        $this->album_user_profile = $this->config->item('album_user_profile');
        $this->login_type = 0;
        if ($this->ion_auth->logged_in())
        {
            $this->login_type = $this->session->userdata('user_group_id');
        }
    }
    
    function hotdeal_list(){
        $sub_category_id = $this->uri->segment(3);
        $this->data['hotdeal_list'] = $this->m_custom->getAdvertise('hot',$sub_category_id);
        $this->data['advertise_title'] = "Hot Deals";
        $this->data['left_path_name'] = 'template/sidebar_left_full';    
        $this->data['page_path_name'] = 'all/advertise_list';
        $this->load->view('template/layout_right', $this->data);
    }
    
     function advertise($advertise_id){
         $this->data['page_path_name'] = 'all/hot_deal';
         $this->load->view('template/layout', $this->data);
     }
    
    //View the user dashboard upper part
    function user_dashboard($user_id)
    {
        $the_row = $this->m_custom->get_one_table_record('users', 'id', $user_id);
        if ($the_row)
        {
            $this->data['image_path'] = $this->album_user_profile;
            $this->data['image'] = $the_row->profile_image;
            $this->data['first_name'] = $the_row->first_name;
            $this->data['last_name'] = $the_row->last_name;
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['page_path_name'] = 'user/dashboard';
            if ($this->ion_auth->logged_in())
            {
                $this->load->view('template/layout_right_menu', $this->data);
            }
            else
            {
                $this->load->view('template/layout', $this->data);
            }
        }
        else
        {
            redirect('/', 'refresh');
        }
    }
    
    public function merchant_dashboard($slug, $user_picture = NULL)
    {
        $the_row = $this->m_custom->get_one_table_record('users', 'slug', $slug);        
        if ($the_row)
        {
            $this->data['image_path'] = $this->album_merchant_profile;
            $this->data['image'] = $the_row->profile_image;
            $this->data['company_name'] = $the_row->company;
            $this->data['address'] = $the_row->address;
            $this->data['phone'] = $the_row->phone;
            $this->data['show_outlet'] = base_url() . 'all/merchant-outlet/' . $slug;
            $this->data['website_url'] = $the_row->me_website_url;
            $this->data['facebook_url'] = $the_row->me_facebook_url;
            //$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['message'] = "";
            $this->data['page_path_name'] = 'merchant/dashboard';
            $this->data['offer_deal'] = base_url() . 'all/merchant-dashboard/' . $slug;
            $this->data['user_picture'] = base_url() . 'all/merchant-dashboard/' . $slug . '/user-picture';
            
            $this->data['hotdeal_list'] = $this->m_custom->getAdvertise('all', NULL, $the_row->id);

            if ($user_picture == NULL)
            {
                $this->data['advertise_title'] = "Offer Deals";
                $this->data['bottom_path_name'] = 'all/advertise_list';
            }
            else
            {
                $this->data['advertise_title'] = "User Pictures";
                $this->data['bottom_path_name'] = ''; //To put user uploaded merchant picture
            }

            if ($this->ion_auth->logged_in())
            {                
                $this->load->view('template/layout_right_menu', $this->data);
            }
            else
            {
                $this->load->view('template/layout', $this->data);
            }
        }
        else
        {
            redirect('/', 'refresh');
        }
    }
    
    public function merchant_outlet($slug) 
    {
        $the_row = $this->m_custom->get_one_table_record('users', 'slug', $slug);
        if ($the_row)
        {
            $this->data['image_path'] = $this->album_merchant_profile;
            $this->data['image'] = $the_row->profile_image;
            $this->data['company_name'] = $the_row->company;
            $this->data['address'] = $the_row->address;
            $this->data['phone'] = $the_row->phone;
            $this->data['show_outlet'] = base_url() . 'merchant-outlet/' . $slug;
            $this->data['view_map_path'] = 'all/merchant-map/';
            $this->data['website_url'] = $the_row->me_website_url;
            $this->data['facebook_url'] = $the_row->me_facebook_url;
            $this->data['message'] = "";

            if (isset($_POST) && !empty($_POST))
            {
                $search_word = $this->input->post('search_word');
                $this->data['branch_list'] = $this->m_custom->getBranchList_with_search($the_row->id, $search_word);
            }
            else
            {
                $this->data['branch_list'] = $this->m_custom->getBranchList($the_row->id);
            }
            $this->data['page_path_name'] = 'merchant/outlet';
            if ($this->ion_auth->logged_in())
            {
                $this->load->view('template/layout_right_menu', $this->data);
            }
            else
            {
                $this->load->view('template/layout', $this->data);
            }
        }
        else
        {
            redirect('/', 'refresh');
        }
    }
    
    function merchant_map($branch_id = NULL)
    {
        if (!empty($branch_id))
        {
            $the_branch = $this->m_custom->get_one_table_record('merchant_branch', 'branch_id', $branch_id);
            if ($the_branch)
            {
                $the_merchant = $this->m_custom->get_one_table_record('users', 'id', $the_branch->merchant_id);
                $this->data['image_path'] = $this->album_merchant_profile;
                $this->data['image'] = $the_merchant->profile_image;
                $this->data['company_name'] = $the_merchant->company;
                $this->data['phone'] = $the_branch->phone;

                $this->data['address'] = $the_branch->address;
                $this->data['googlemap_url'] = 'https://www.google.com/maps/place/' . $the_branch->google_map_url;
                $this->load->library('googlemaps');

                $location = $the_branch->google_map_url;
                if (IsNullOrEmptyString($location))
                {
                    $location = $the_branch->address;
                }

                $config['center'] = $location;
                $config['zoom'] = '17';
                $this->googlemaps->initialize($config);

                $marker = array();
                $marker['position'] = $location;
                $this->googlemaps->add_marker($marker);
                $this->data['map'] = $this->googlemaps->create_map();
                $this->data['page_path_name'] = 'merchant/map';

                if ($this->ion_auth->logged_in())
                {
                    $this->load->view('template/layout_right_menu', $this->data);
                }
                else
                {
                    $this->load->view('template/layout', $this->data);
                }
            }
        }
        else
        {
            redirect('/', 'refresh');
        }
    }
}