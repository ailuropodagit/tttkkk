<?php defined('BASEPATH') OR exit('No direct script access allowed');

class All extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->album_merchant_profile = $this->config->item('album_merchant_profile');
        $this->album_admin = $this->config->item('album_admin');
        $this->album_merchant = $this->config->item('album_merchant');
        $this->album_user_profile = $this->config->item('album_user_profile');
        $this->album_user_merchant = $this->config->item('album_user_merchant');
        $this->album_user = $this->config->item('album_user');
        $this->group_id_merchant = $this->config->item('group_id_merchant');
        $this->group_id_supervisor = $this->config->item('group_id_supervisor');
        $this->group_id_user = $this->config->item('group_id_user');
        $this->group_id_admin = $this->config->item('group_id_admin');
        $this->group_id_worker = $this->config->item('group_id_worker');
        $this->login_type = 0;
        $this->temp_folder = $this->config->item('folder_image_temp');     
        if ($this->ion_auth->logged_in())
        {
            $this->login_type = $this->session->userdata('user_group_id');
        }
    }

    function hotdeal_list($sub_category_id)
    {
        $this->data['share_hotdeal_redemption_list'] = $this->m_custom->getAdvertise('hot', $sub_category_id);       
        $this->data['title'] = "Hot Deal";
        if (!IsNullOrEmptyString($sub_category_id))
        {
            $this->data['main_category'] = $this->m_custom->display_main_category($sub_category_id);
            $this->data['sub_category'] = $this->m_custom->display_category($sub_category_id);
        }
        //ADVERTISE SUGGESTION
        $where_read_category = array('category_id'=>$sub_category_id);
        $main_category_id = $this->albert_model->read_category($where_read_category)->row()->main_category_id;        
        $where_read_category2 = array('main_category_id'=>$main_category_id);
        $result_array_sub_category_id = $this->albert_model->read_category($where_read_category2)->result_array();
        $array_sub_category_id_all = array_column($result_array_sub_category_id, 'category_id');  
        $array_sub_category_id_exclude = array_diff($array_sub_category_id_all, array($sub_category_id));
        $this->data['query_advertise_suggestion'] = $this->albert_model->read_advertise_hot_deal_suggestion($array_sub_category_id_exclude);
        $this->data['advertise_suggestion_page_path_name'] = 'all/hot_deal_list_suggestion';
        $this->data['advertise_suggestion_page_title'] = 'Hot Deal Suggestion';
        //NORMAL PAGE
        $this->data['page_path_name'] = 'share/hot_deal_grid_list4';
        $this->load->view('template/index_left_category', $this->data);
    }

    function promotion_list()
    {
        $sub_category_id = $this->uri->segment(3);
        $this->data['share_hotdeal_redemption_list'] = $this->m_custom->getAdvertise('pro', $sub_category_id, NULL, 0, NULL, NULL, 0, 0, 0, 0, 1);
        $this->data['title'] = "Redemption";
        if (!IsNullOrEmptyString($sub_category_id))
        {
            $this->data['main_category'] = $this->m_custom->display_main_category($sub_category_id);
            $this->data['sub_category'] = $this->m_custom->display_category($sub_category_id);
        }
        //ADVERTISE SUGGESTION
        $where_read_category = array('category_id'=>$sub_category_id);
        $main_category_id = $this->albert_model->read_category($where_read_category)->row()->main_category_id;        
        $where_read_category2 = array('main_category_id'=>$main_category_id);
        $result_array_sub_category_id = $this->albert_model->read_category($where_read_category2)->result_array();
        $array_sub_category_id_all = array_column($result_array_sub_category_id, 'category_id');  
        $array_sub_category_id_exclude = array_diff($array_sub_category_id_all, array($sub_category_id));
        $this->data['query_advertise_suggestion'] = $this->albert_model->read_advertise_redemption_suggestion($array_sub_category_id_exclude);
        $this->data['advertise_suggestion_page_path_name'] = 'all/hot_deal_list_suggestion';
        $this->data['advertise_suggestion_page_title'] = 'Hot Deal Suggestion';
        //NORMAL PAGE
        $this->data['page_path_name'] = 'share/redemption_grid_list4';
        $this->load->view('template/index_left_category', $this->data);
    }

    function redemption_list()
    {
        $sub_category_id = $this->uri->segment(3);
        $this->data['hotdeal_list'] = $this->m_custom->getAdvertise('adm', $sub_category_id);
        $this->data['title'] = "Redemption";
        if (!IsNullOrEmptyString($sub_category_id))
        {
            $this->data['main_category'] = $this->m_custom->display_main_category($sub_category_id);
            $this->data['sub_category'] = $this->m_custom->display_category($sub_category_id);
        }
        $this->data['page_path_name'] = 'all/advertise_list';
        $this->load->view('template/layout_category', $this->data);
    }

    function advertise($advertise_id, $advertise_type = NULL, $sub_category_id = NULL, $merchant_id = NULL, $show_expired = 0)
    {
        $the_row = $this->m_custom->getOneAdvertise($advertise_id);
        if ($the_row)
        {
            $message_info = '';
            if ($this->ion_auth->logged_in())
            {
                $login_id = $this->ion_auth->user()->row()->id;
                $login_data = $this->m_custom->get_one_table_record('users', 'id', $login_id);
                $this->m_custom->activity_view($advertise_id, 'adv');
            }
            $merchant_row = $this->m_merchant->getMerchant($the_row['merchant_id']);
            $this->data['merchant_dashboard_url'] = base_url() . "all/merchant-dashboard/" . $merchant_row['slug'];
            $this->data['advertise_id'] = $advertise_id;
            $this->data['merchant_name'] = $merchant_row['company'];
            $this->data['title'] = $the_row['title'];
            $this->data['description'] = $the_row['description'];
            $this->data['image_url'] = base_url($this->album_merchant . $the_row['image']);
            $this->data['sub_category'] = $this->m_custom->display_category($the_row['sub_category_id']);
            $this->data['start_date'] = displayDate($the_row['start_time']);
            $this->data['end_date'] = displayDate($the_row['end_time']);
            $this->data['like_url'] = $this->m_custom->generate_like_link($advertise_id, 'adv');
            $this->data['comment_url'] = $this->m_custom->generate_comment_link($advertise_id, 'adv');
            $this->data['average_rating'] = $this->m_custom->activity_rating_average($advertise_id, 'adv');
            $this->data['phone_required'] = $the_row['phone_required'];
            $this->data['extra_term'] = $the_row['extra_term'];
            
            $this->data['show_extra_info'] = $the_row['show_extra_info'];
            $this->data['price_before'] = $the_row['price_before'];
            $this->data['price_before_show'] = $the_row['price_before_show'];
            $this->data['price_after'] = $the_row['price_after'];
            $this->data['price_after_show'] = $the_row['price_after_show'];
            $this->data['voucher_worth'] = $the_row['voucher_worth'];
            $this->data['get_off_percent'] = $the_row['get_off_percent'];
            $this->data['how_many_buy'] = $the_row['how_many_buy'];
            $this->data['how_many_get'] = $the_row['how_many_get'];
            
            $this->data['advertise_suggestion_list'] = $this->m_custom->getAdvertise_suggestion($advertise_type, $sub_category_id, $advertise_id);
            $this->data['advertise_type'] = $advertise_type;
            
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['item_id'] = array(
                'type' => 'hidden',
                'name' => 'item_id',
                'id' => 'item_id',
                'value' => $advertise_id,
            );
            $this->data['item_type'] = array(
                'type' => 'hidden',
                'name' => 'item_type',
                'id' => 'item_type',
                'value' => 'adv',
            );
            if (check_correct_login_type($this->group_id_user)) //Check if user logged in
            {
                $this->data['radio_level'] = " ";
            }
            else
            {
                $this->data['radio_level'] = "disabled";
            }
            $row_advertise_type = $the_row['advertise_type'];
            if ($row_advertise_type == "pro")
            {
                $is_history = 0;
                if (time() > strtotime($the_row['end_time']))
                {
                    $is_history = 1;
                }
                $this->data['is_history'] = $is_history;
                $this->data['voucher_candie'] = $the_row['voucher_candie'];
                $this->data['expire_date'] = displayDate($the_row['voucher_expire_date']);
                $this->data['candie_term'] = $this->m_custom->many_get_childlist_detail('candie_term', $advertise_id, 'dynamic_option');
                $this->data['candie_branch'] = $this->m_custom->many_get_childlist_detail('candie_branch', $advertise_id, 'merchant_branch');
                $this->data['page_path_name'] = 'all/promotion';
            }
            else if ($row_advertise_type == "hot")
            {
                $this->data['post_hour'] = $the_row['post_hour'];
                $this->data['end_time'] = displayDate($the_row['end_time'], 1, 1);
                $this->data['page_path_name'] = 'all/hotdeal';
            }
            else if ($row_advertise_type == "adm")
            {
                //For admin promotion, overwrite some info
                $this->data['image_url'] = base_url($this->album_admin . $the_row['image']);
                $this->data['voucher_not_need'] = $the_row['voucher_not_need'];                
                $this->data['voucher_candie'] = $the_row['voucher_candie'];
                $this->data['expire_date'] = displayDate($the_row['voucher_expire_date']);
                $this->data['candie_term'] = $this->m_custom->many_get_childlist_detail('candie_term', $advertise_id, 'dynamic_option');
                $this->data['page_path_name'] = 'all/promotion_admin';
            }
            if ($advertise_type != NULL)
            {
                $advertise_current_list = $this->m_custom->getAdvertise($advertise_type, $sub_category_id, $merchant_id, $show_expired);                
                $advertise_id_array = get_key_array_from_list_array($advertise_current_list, 'advertise_id');
                $previous_id = get_previous_id($advertise_id, $advertise_id_array);
                $next_id = get_next_id($advertise_id, $advertise_id_array);
                if ($previous_id)
                {
                    $this->data['previous_url'] = base_url() . "all/advertise/" . $previous_id . "/" . $advertise_type . "/" . $sub_category_id . "/" . $merchant_id . "/" . $show_expired;
                }
                if ($next_id)
                {
                    $this->data['next_url'] = base_url() . "all/advertise/" . $next_id . "/" . $advertise_type . "/" . $sub_category_id . "/" . $merchant_id . "/" . $show_expired;
                }                
            }            
            if ($this->ion_auth->logged_in())
            {
                $this->load->view('template/index', $this->data);
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

    function monitor_remove(){
        if (check_correct_login_type($this->group_id_merchant) || check_correct_login_type($this->group_id_admin) || check_correct_login_type($this->group_id_worker))
        {
            $user_id = $this->ion_auth->user()->row()->id;

            $notification_list = $this->m_custom->display_row_monitor();
            $this->data['notification_list'] = $notification_list;
            $this->data['page_path_name'] = 'all/monitoring';
            $this->load->view('template/index', $this->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }
    
    function monitor_process()
    {
        $current_url = '/';
        if (isset($_POST) && !empty($_POST))
        {
            $current_url = $this->input->post('current_url');
            $the_id = $this->input->post('the_id');
            if ($this->input->post('button_action') == "removed_approve")
            {
                $this->m_custom->approve_row_monitor($the_id);
            }
            else if ($this->input->post('button_action') == "removed_recover")
            {
                $this->m_custom->recover_row_monitor($the_id);
            }
        }
        redirect($current_url, 'refresh');
    }
    
    function notification()
    {
        if ($this->ion_auth->logged_in())
        {
            $user_id = $this->ion_auth->user()->row()->id;
            if (check_correct_login_type($this->group_id_supervisor))
            {
                $user_id = $this->ion_auth->user()->row()->su_merchant_id;
            }
            $notification_list = $this->m_custom->notification_display($user_id);
            $this->data['notification_list'] = $notification_list;
            $this->data['page_path_name'] = 'all/notification';
            $this->load->view('template/index', $this->data);
            if ($this->config->item('notification_auto_mark_as_read') == 1)
            {
                $this->m_custom->notification_read($user_id);
            }
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

    function notification_process()
    {
        $current_url = '/';
        if (isset($_POST) && !empty($_POST))
        {
            $current_url = $this->input->post('current_url');
            $noti_id = $this->input->post('noti_id');
            if ($this->input->post('button_action') == "hide_notification")
            {
                $this->m_custom->notification_hide($noti_id);
            }
            else if ($this->input->post('button_action') == "read_notification")
            {
                $this->m_custom->notification_read_toggle($noti_id);
            }
        }
        redirect($current_url, 'refresh');
    }
    
    public function create_user_follow()
    {
        $current_url = $_POST['current_url'];
        $follower_id = $_POST['follower_id'];
        $follower_main_id = $_POST['follower_main_id'];
        $follower_group_id = $_POST['follower_group_id'];
        $following_id = $_POST['following_id'];
        $following_main_id = $_POST['following_main_id'];
        $following_group_id = $_POST['following_group_id'];
        $data = array(
            'follower_id' => $follower_id,
            'follower_main_id' => $follower_main_id,
            'follower_group_id' => $follower_group_id,
            'following_id' => $following_id,
            'following_main_id' => $following_main_id,
            'following_group_id' => $following_group_id
        );
        $this->albert_model->create_user_follow($data);
        if ($this->db->affected_rows() > 0)
        {
            $new_id = $this->db->insert_id();
            $this->m_custom->notification_process('user_follow', $new_id, $following_main_id);
            $this->session->set_flashdata('message', 'Follow Success');
        }
        redirect($current_url, 'refresh');
    }

    public function delete_user_follow()
    {
        $current_url = $_POST['current_url'];
        $follower_main_id = $_POST['follower_main_id'];
        $following_main_id = $_POST['following_main_id'];
        $where_delete_user = array (
            'follower_main_id' => $follower_main_id,
            'following_main_id' => $following_main_id
        );
        $this->albert_model->delete_user_follow($where_delete_user);
        if ($this->db->affected_rows() > 0)
        {
            $this->session->set_flashdata('message', 'Unfollow Success');
        }
        redirect($current_url, 'refresh');
    }

    function voucher($advertise_id = NULL, $redeem_id = NULL)
    {
        if($advertise_id == NULL || $redeem_id == NULL){
            redirect('/', 'refresh');
        }
        
        if ($this->ion_auth->logged_in())
        {
            $login_id = $this->ion_auth->user()->row()->id;
            $login_data = $this->m_custom->get_one_table_record('users', 'id', $login_id, 1);
        }
        else
        {
            redirect('/', 'refresh');
        }

        $the_row = $this->m_custom->getOneAdvertise($advertise_id);
        if ($the_row)
        {
            $advertise_type = $the_row['advertise_type'];
            if (($advertise_type != "pro" && $advertise_type != "adm") || !$this->m_user->user_redemption_check($login_id, $advertise_id))
            {
                redirect('/', 'refresh');
            }

            $merchant_row = $this->m_merchant->getMerchant($the_row['merchant_id']);
            $this->data['merchant_dashboard_url'] = base_url() . "all/merchant-dashboard/" . $merchant_row['slug'];
            $redeem_row = $this->m_custom->getOneUserRedemption($redeem_id);
            
//            if (check_correct_login_type($this->group_id_user)) 
//            {
//                $this->data['user_id'] = $login_id;
//                $this->data['user_name'] = $this->m_custom->display_users($login_id);
//                $this->data['user_dob'] = displayDate($login_data['us_birthday']);
//                $this->data['user_email'] = $login_data['email'];
//                $this->data['current_candie'] = $this->m_user->candie_check_balance($login_id);
//            }

            $this->data['advertise_type'] = $advertise_type;
            $this->data['advertise_id'] = $advertise_id;
            $this->data['merchant_name'] = $merchant_row['company'];
            $this->data['title'] = $the_row['title'];
            $this->data['description'] = $the_row['description'];
            $this->data['extra_term'] = $the_row['extra_term'];
            if ($advertise_type == "adm")
            {
                $this->data['image_url'] = base_url($this->album_admin . $the_row['image']);
            }
            else
            {
                $this->data['image_url'] = base_url($this->album_merchant . $the_row['image']);
            }
            $this->data['sub_category'] = $this->m_custom->display_category($the_row['sub_category_id']);
            $this->data['start_date'] = displayDate($the_row['start_time']);
            $this->data['end_date'] = displayDate($the_row['end_time']);
            $this->data['message'] = $this->session->flashdata('message');
            
            $this->data['voucher'] = $redeem_row['voucher'];
            $this->data['voucher_not_need'] = $the_row['voucher_not_need'];
            $this->data['voucher_worth'] = $the_row['voucher_worth'];           
            $this->data['voucher_barcode'] = base_url("barcode/generate/" . $redeem_row['voucher']);
            $this->data['voucher_candie'] = $the_row['voucher_candie'];
            $this->data['expire_date'] = displayDate($the_row['voucher_expire_date']);
            $this->data['candie_term'] = $this->m_custom->many_get_childlist_detail('candie_term', $advertise_id, 'dynamic_option');
            $this->data['candie_branch'] = $this->m_custom->many_get_childlist_detail('candie_branch', $advertise_id, 'merchant_branch');
            
            $this->data['page_path_name'] = 'all/voucher';
            $template_used = 'template/index';
            $this->load->library('user_agent');
            if ($this->agent->is_browser('Safari'))
            {
                $template_used = 'template/body';
            }

            $this->load->view($template_used, $this->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

    function merchant_category($sub_category_id = NULL)
    {
        //PAGE PATH NAME
        $this->data['page_path_name'] = 'share/merchant_grid_list5';
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['title'] = $this->m_custom->display_category($sub_category_id);
        $this->data['review_list'] = $this->m_merchant->getMerchantList_by_subcategory($sub_category_id);
        $this->load->view('template/index_background_blank', $this->data);
    }

    function merchant_user_picture($picture_id, $user_id = NULL, $merchant_id = NULL)
    {

        $this->data['page_title'] = "Merchant Album";
        $the_row = $this->m_custom->getOneMUA($picture_id);
        if ($the_row)
        {
            $message_info = '';
            if ($this->ion_auth->logged_in())
            {
                $login_id = $this->ion_auth->user()->row()->id;
                //$login_data = $this->m_custom->getUser($login_id);
                $this->m_custom->activity_view($picture_id, 'mua');
            }

            //$user_row = $this->m_custom->getUser($the_row['user_id']);    //Temporary hide because no use, but maybe future will use
            $merchant_row = $this->m_merchant->getMerchant($the_row['merchant_id']);
            $this->data['merchant_dashboard_url'] = base_url() . "all/merchant-dashboard/" . $merchant_row['slug'];

            $this->data['picture_id'] = $picture_id;
            $this->data['merchant_name'] = $merchant_row['company'];
            $this->data['picture_user_id'] = $the_row['user_id'];
            $this->data['user_name_url'] = $this->m_custom->generate_user_link($the_row['user_id']);
            $this->data['upload_by_user_id'] = $the_row['user_id'];
            $this->data['title'] = $the_row['title'];
            $this->data['description'] = $the_row['description'];
            $this->data['image_url'] = base_url($this->album_user_merchant . $the_row['image']);

            $this->data['like_url'] = $this->m_custom->generate_like_link($picture_id, 'mua');
            $this->data['comment_url'] = $this->m_custom->generate_comment_link($picture_id, 'mua');
            $this->data['average_rating'] = $this->m_custom->activity_rating_average($picture_id, 'mua');
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['item_id'] = array(
                'type' => 'hidden',
                'name' => 'item_id',
                'id' => 'item_id',
                'value' => $picture_id,
            );
            $this->data['item_type'] = array(
                'type' => 'hidden',
                'name' => 'item_type',
                'id' => 'item_type',
                'value' => 'mua',
            );

            if (check_correct_login_type($this->group_id_user)) //Check if user logged in
            {
                $this->data['radio_level'] = " ";
            }
            else
            {
                $this->data['radio_level'] = "disabled";
            }

            $this->data['page_path_name'] = 'all/picture';

            if ($user_id != NULL || $merchant_id != NULL)
            {
                $current_list = $this->m_custom->getAlbumUserMerchant($user_id, $merchant_id);
                $id_array = get_key_array_from_list_array($current_list, 'merchant_user_album_id');
                $previous_id = get_previous_id($picture_id, $id_array);
                $next_id = get_next_id($picture_id, $id_array);
                if ($previous_id)
                {
                    $this->data['previous_url'] = base_url() . "all/merchant_user_picture/" . $previous_id . "/" . $user_id . "/" . $merchant_id;
                }
                if ($next_id)
                {
                    $this->data['next_url'] = base_url() . "all/merchant_user_picture/" . $next_id . "/" . $user_id . "/" . $merchant_id;
                }
            }

            if ($this->ion_auth->logged_in())
            {
                $this->load->view('template/index', $this->data);
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

        $this->data['page_path_name'] = 'all/picture';
    }

    function user_picture($picture_id, $user_id = NULL)
    {

        $this->data['page_title'] = "User Album";
        $the_row = $this->m_custom->getOneUserPicture($picture_id);
        if ($the_row)
        {
            $message_info = '';
            if ($this->ion_auth->logged_in())
            {
                $login_id = $this->ion_auth->user()->row()->id;
                $login_data = $this->m_custom->getUser($login_id);
            }

            $user_row = $this->m_custom->getUser($the_row['user_id']);

            $this->data['picture_id'] = $picture_id;
            $this->data['picture_user_id'] = $the_row['user_id'];
            $this->data['user_name_url'] = $this->m_custom->generate_user_link($the_row['user_id']);
            $this->data['title'] = $the_row['title'];
            $this->data['description'] = $the_row['description'];
            $this->data['image_url'] = base_url($this->album_user . $the_row['image']);

            $this->data['like_url'] = $this->m_custom->generate_like_link($picture_id, 'usa');
            $this->data['comment_url'] = $this->m_custom->generate_comment_link($picture_id, 'usa');
            $this->data['average_rating'] = $this->m_custom->activity_rating_average($picture_id, 'usa');
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['item_id'] = array(
                'type' => 'hidden',
                'name' => 'item_id',
                'id' => 'item_id',
                'value' => $picture_id,
            );
            $this->data['item_type'] = array(
                'type' => 'hidden',
                'name' => 'item_type',
                'id' => 'item_type',
                'value' => 'usa',
            );

            if (check_correct_login_type($this->group_id_user)) //Check if user logged in
            {
                $this->data['radio_level'] = " ";
            }
            else
            {
                $this->data['radio_level'] = "disabled";
            }

            $this->data['page_path_name'] = 'all/picture_user';

            if ($user_id != NULL)
            {
                $current_list = $this->m_custom->getAlbumUser($user_id);
                $id_array = get_key_array_from_list_array($current_list, 'user_album_id');
                $previous_id = get_previous_id($picture_id, $id_array);
                $next_id = get_next_id($picture_id, $id_array);
                if ($previous_id)
                {
                    $this->data['previous_url'] = base_url() . "all/user_picture/" . $previous_id . "/" . $user_id;
                }
                if ($next_id)
                {
                    $this->data['next_url'] = base_url() . "all/user_picture/" . $next_id . "/" . $user_id;
                }
            }

            if ($this->ion_auth->logged_in())
            {
                $this->load->view('template/index', $this->data);
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

        $this->data['page_path_name'] = 'all/picture_user';
    }

    function album_user_merchant($user_id = NULL, $merchant_id = NULL)
    {
        $this->data['album_list'] = $this->m_custom->getAlbumUserMerchant($user_id, $merchant_id);

        $this->data['title'] = "Merchant Album";
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'all/album_user_merchant';
        if ($this->ion_auth->logged_in())
        {
            $this->load->view('template/index', $this->data);
        }
        else
        {
            $this->load->view('template/layout', $this->data);
        }
    }

    function album_user($user_id = NULL)
    {
        $album_list = '';
        if ($user_id != NULL)
        {
            $album_list = $this->m_custom->getAlbumUser($user_id);
        }else{
            $album_list = $this->m_custom->getAlbumUser();
        }
        $this->data['album_list'] = $album_list;

        $this->data['title'] = "My Album";
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'all/album_user';
        if ($this->ion_auth->logged_in())
        {
            $this->load->view('template/index', $this->data);
        }
        else
        {
            $this->load->view('template/layout', $this->data);
        }
    }

    function album_merchant($slug = NULL, $page = 1)
    {
        $this->load->library("pagination");
        $config = array();
        $merchant_id = 0;
        $base_url = base_url() . "all/album_merchant";
        if ($slug != NULL)
        {
            $the_row = $this->m_custom->get_one_table_record('users', 'slug', $slug);
            $merchant_id = $the_row->id;
            $base_url = base_url() . "all/album_merchant/" . $slug;
        }
        else if (check_correct_login_type($this->group_id_merchant))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;
            $the_row = $this->m_custom->get_one_table_record('users', 'id', $merchant_id);
            $base_url = base_url() . "all/album_merchant/" . $the_row->slug;
        }
        else if (check_correct_login_type($this->group_id_supervisor))
        {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;    //For supervisor is taking different id for it merchant id
            $the_row = $this->m_custom->get_one_table_record('users', 'id', $merchant_id);
            $base_url = base_url() . "all/album_merchant/" . $the_row->slug;
        }
        //For setting the pagination function
        $config["per_page"] = $this->config->item('custom_per_page');
        $config['num_links'] = 5;
        $config['use_page_numbers'] = TRUE;
        $config['num_tag_open'] = '&nbsp';
        $config['num_tag_close'] = '&nbsp';
        $config['next_link'] = ' Next ';
        $config['prev_link'] = ' Previous ';
        $config["base_url"] = $base_url;
        $config["total_rows"] = count($this->m_custom->getAdvertise('hot', NULL, $merchant_id, 1));  //To get the total row              
        $this->pagination->initialize($config);
        $this->data["paging_links"] = $this->pagination->create_links();
        $start_index = $page == 1 ? $page : (($page - 1) * $config["per_page"]);  //For calculate page number to start index
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['share_hotdeal_redemption_list'] = $this->m_custom->getAdvertise('hot', NULL, $merchant_id, 1, $config["per_page"], $start_index);   //To get the limited result only for that current page
        $this->data['title'] = "Hot Deal Advertise's Album";
        $this->data['page_path_name'] = 'share/hot_deal_grid_list5';
        $this->load->view('template/index_background_blank', $this->data);
    }

    function album_redemption($slug = NULL, $page = 1)
    {
        $this->load->library("pagination");
        $config = array();
        $merchant_id = 0;
        $base_url = base_url() . "all/album_redemption";
        if ($slug != NULL)
        {
            $the_row = $this->m_custom->get_one_table_record('users', 'slug', $slug);
            $merchant_id = $the_row->id;
            $base_url = base_url() . "all/album_redemption/" . $slug;
        }
        else if (check_correct_login_type($this->group_id_merchant))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;
            $the_row = $this->m_custom->get_one_table_record('users', 'id', $merchant_id);
            $base_url = base_url() . "all/album_redemption/" . $the_row->slug;
        }
        else if (check_correct_login_type($this->group_id_supervisor))
        {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;    //For supervisor is taking different id for it merchant id
            $the_row = $this->m_custom->get_one_table_record('users', 'id', $merchant_id);
            $base_url = base_url() . "all/album_redemption/" . $the_row->slug;
        }
        //For setting the pagination function
        $config["per_page"] = $this->config->item('custom_per_page');
        $config['num_links'] = 5;
        $config['use_page_numbers'] = TRUE;
        $config['num_tag_open'] = '&nbsp';
        $config['num_tag_close'] = '&nbsp';
        $config['next_link'] = ' Next ';
        $config['prev_link'] = ' Previous ';
        $config["base_url"] = $base_url;
        $config["total_rows"] = count($this->m_custom->getAdvertise('pro', NULL, $merchant_id, 1));  //To get the total row              
        $this->pagination->initialize($config);
        $this->data["paging_links"] = $this->pagination->create_links();
        $start_index = $page == 1 ? $page : (($page - 1) * $config["per_page"]);  //For calculate page number to start index
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['share_hotdeal_redemption_list'] = $this->m_custom->getAdvertise('pro', NULL, $merchant_id, 1, $config["per_page"], $start_index);   //To get the limited result only for that current page
        $this->data['title'] = "Candie Voucher's Album";
        $this->data['page_path_name'] = 'share/redemption_grid_list5';
        $this->load->view('template/index_background_blank', $this->data);
    }

    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    function user_click_like($refer_id, $refer_type)
    {
        if (!$this->m_custom->activity_like_is_exist($refer_id, $refer_type))
        {
            $this->m_custom->activity_like($refer_id, $refer_type);
            $like_url = $this->m_custom->generate_like_link($refer_id, $refer_type);
            echo json_encode(array("code" => "Success", "msg" => "Your Like has been count", "like_url" => $like_url));
        }
        else
        {
            $like_url = $this->m_custom->generate_like_link($refer_id, $refer_type);
            echo json_encode(array("code" => "Error", "msg" => "You have already liked this item before", 'like_url' => $like_url));
        }
    }

    function user_redeem_voucher()
    {
        $current_url = '/';
        if (isset($_POST) && !empty($_POST))
        {
            if (check_correct_login_type($this->config->item('group_id_user')))
            {
                $current_url = $this->input->post('current_url');
                $advertise_id = $this->input->post('item_id');
                $user_email = $this->session->userdata('email');
                $phone_required = $this->input->post('phone_required');
                $top_up_phone = NULL;
                if ($phone_required == 1)
                {
                    $top_up_phone = $this->input->post('phone');
                    if (!$this->m_custom->check_valid_phone($top_up_phone))
                    {
                        $this->session->set_flashdata('message', 'The top up phone number is incorrect, please set a real phone number');
                        redirect($current_url, 'refresh');
                    }
                }

                $redeem_info = $this->m_user->user_redemption_insert($advertise_id, $top_up_phone);

                if ($redeem_info['redeem_status'])
                {
                    $mail_info = array(
                        'advertise_id' => $advertise_id,
                        'return_url' => $current_url,
                        'email' => $user_email,
                        'message' => $redeem_info['redeem_message'],
                        'redeem_info' => $redeem_info,
                    );
                    $this->session->set_flashdata('mail_info', $mail_info);
                    redirect('all/send_redeem_mail_process', 'refresh');
                }
                else
                {
                    $this->session->set_flashdata('message', $redeem_info['redeem_message']);
                    redirect($current_url, 'refresh');
                }
            }
        }
        redirect($current_url, 'refresh');
    }

    function send_redeem_mail_process()
    {
        $mail_info = $this->session->flashdata('mail_info');

        $mail_voucher_code = $mail_info['redeem_info']['redeem_voucher_not_need'] == 0 ? 'Voucher Code : ' . $mail_info['redeem_info']['redeem_voucher'] . ' ("Snap" and show this voucher code)<br/>' : NULL;
        $mail_expire_code = $mail_info['redeem_info']['redeem_expire'] == NULL ? NULL : 'Expire Date : ' . $mail_info['redeem_info']['redeem_expire'];
        $mail_top_up_phone = $mail_info['redeem_info']['redeem_top_up_phone'] == NULL ? NULL : 'Top Up Phone : ' . $mail_info['redeem_info']['redeem_top_up_phone'] . '<br/>';

        $mail_message = 'Merchant : ' . $mail_info['redeem_info']['redeem_merchant'] . '<br/>'
                . 'Promotion Title : ' . $mail_info['redeem_info']['redeem_title'] . '<br/>'
                . $mail_voucher_code
                . $mail_top_up_phone
                . $mail_expire_code . '<br/><br/>'
                . $mail_info['message']
                . '<br/>Congrats : You have earn another ' . $this->m_custom->display_trans_config(9) . ' candies and RM'. $this->m_custom->display_trans_config(24) . '<br/>';
        $get_status = send_mail_simple($mail_info['email'], $mail_info['redeem_info']['redeem_email_subject'], $mail_message, 'keppo_redeem_send_email_success');
        if ($get_status)
        {
            set_simple_message('Thank you!', 'Please check your e-mail (Inbox/Junk) or keppo.my redemption "active". <br/><br/>Please present this voucher before you purchase.<br/><br/>Enjoy your gift from merchants & Keppo.my.', 
                    $mail_message, $mail_info['return_url'], 'Back', 'all/simple_message');
        }
        else
        {
            $this->session->set_flashdata('message', $mail_info['message']);
            redirect($mail_info['return_url'], 'refresh');
        }
    }

    function simple_message()
    {
        display_simple_message();
    }

    function like_list($refer_id = NULL, $refer_type = NULL)
    {
        if ($refer_id != NULL && $refer_type != NULL)
        {
            $like_list = $this->m_custom->activity_like_user_list($refer_id, $refer_type);
            $data['like_list'] = $like_list;
            $data['post_title'] = $this->m_custom->post_title($refer_id, $refer_type);
            $data['page_path_name'] = 'all/like_list';
            if ($this->ion_auth->logged_in())
            {
                $this->load->view('template/index', $data);
            }
            else
            {
                $this->load->view('template/layout', $data);
            }
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

    function promo_code_list($promo_code = NULL, $trans_conf_id = NULL)
    {
        if ($promo_code != NULL)
        {
            $result_list = $this->m_custom->promo_code_history_display($promo_code, $trans_conf_id);
            $data['result_list'] = $result_list;
            $data['post_title'] = $promo_code;
            $data['page_path_name'] = 'all/promo_code_list';
            if ($this->ion_auth->logged_in())
            {
                $this->load->view('template/index', $data);
            }
            else
            {
                $this->load->view('template/layout', $data);
            }
        }
        else
        {
            redirect('/', 'refresh');
        }
    }
    
    //Refer type: adv = Advertise, mua = Merchant User Album, usa = User Album
    function user_rating($refer_id = NULL, $refer_type = NULL)
    {

        $rate = $this->input->post("rate_val", true);
        $refer_id = $this->input->post("refer_id", true);
        $refer_type = $this->input->post("refer_type", true);

        unset($this->layout); //Block template Layout
        if (check_correct_login_type($this->group_id_user))   //get_user_id() return login user id
        {
            if (!$this->m_custom->activity_rating_is_exist($refer_id, $refer_type))
            {
                if ($this->m_custom->activity_rating($refer_id, $refer_type, $rate))
                {
                    echo json_encode(array("code" => "Success", "msg" => "Your Rating has been saved"));
                }
                else
                {
                    echo json_encode(array("code" => "Error", "msg" => "There was a problem on rating"));
                }
            }
            else
            {
                $this_user_rating = $this->m_custom->activity_rating_this_user($refer_id, $refer_type);
                echo json_encode(array("code" => "Error", "msg" => "You have already rated this item as " . $this_user_rating . " star before"));
            }
        }
        else
        {
            echo json_encode(array("code" => "Error", "msg" => "You have to login as user to rate the item"));
        }
        exit(0);
    }

    //USER DASHBOARD
    function user_dashboard($user_id = NULL, $page = NULL)
    {
        if ($user_id)
        {
            //QUERY USERS
            $query_users_where = array('id' => $user_id, 'main_group_id' => $this->config->item('group_id_user'));
            $data['query_users'] = $this->albert_model->read_user($query_users_where);
            $num_rows_users = $data['query_users']->num_rows();
            //USER EXISTS
            if ($num_rows_users)
            {
                $data['user_id'] = $user_id;
                $data['browser_title'] = $this->m_custom->display_users($user_id);
                $data['page_path_name'] = 'user/dashboard';
                $data['page_message'] = NULL;
                //FOLLOWER or FOLLOWING COUNT
                $data['follower_count'] = $this->albert_model->follower_count($user_id);
                $data['following_count'] = $this->albert_model->following_count($user_id);
                $data['temp_folder'] = $this->temp_folder; 
                if (!$page)
                {
                    //USER ALBUM
                    $data['title'] = "User Album";
                    $data['bottom_path_name'] = 'all/album_user';
                    $where_user_album = array('user_id' => $user_id, 'hide_flag' => '0');
                    $query_user_album = $this->albert_model->read_user_album($where_user_album);
                    $data['album_list'] = $query_user_album->result_array();
                }
                else
                {
                    //USER MERCHANT ALBUM
                    $data['title'] = "Merchant Album";
                    $data['bottom_path_name'] = 'all/album_user_merchant';
                    $where_merchant_user_album = array('user_id' => $user_id, 'hide_flag' => '0');
                    $query_merchant_user_album = $this->albert_model->read_merchant_user_album($where_merchant_user_album);
                    $data['album_list'] = $query_merchant_user_album->result_array();
                }
                if ($this->ion_auth->logged_in())
                {
                    //LOGGED IN
                    $this->load->view('template/index', $data);
                }
                else
                {
                    //NOT LOGGED IN
                    $this->load->view('template/layout', $data);
                }
            }
            else
            {
                //REDIRECT TO ROOT
                redirect('/', 'refresh');
            }
        }
        else
        {
            //REDIRECT TO ROOT
            redirect('/', 'refresh');
        }
    }

    function user_review($users_id = NULL)
    {
        if ($users_id == NULL)
        {
            redirect('/', 'refresh');
        }
        //QUERY USERS
        $query_users_where = array('id' => $users_id);
        $data['query_users'] = $this->albert_model->read_user($query_users_where);
        //PAGE PATH NAME
        $data['page_path_name'] = 'all/advertise_list';
        //BOTTON PATH NAME
        $data['title'] = "Review";
        //QUERY HOTDEAL LIST
        $hotdeal_list_where = array('act_by_id' => $users_id);
        $data['hotdeal_list'] = $this->albert_model->red_activity_history_inner_join_advertise($hotdeal_list_where)->result_array();
        if ($this->ion_auth->logged_in())
        {
            //LOGGED IN
            $this->load->view('template/index', $data);
        }
        else
        {
            //NOT LOGGED IN
            $this->load->view('template/layout', $data);
        }
    }

    public function comment_add()
    {
        $message_info = '';
        $current_url = '/';
        if (isset($_POST) && !empty($_POST))
        {
            $current_url = $this->input->post('current_url');
            $refer_id = $this->input->post('item_id');
            $refer_type = $this->input->post('item_type');
            $comment = $this->input->post('comment');
            if (IsNullOrEmptyString($comment))
            {
                $message_info = add_message_info($message_info, 'Comment cannot be empty.');
                $current_url = '/';
            }
            else
            {
                $this->m_custom->activity_comment($refer_id, $refer_type, $comment);
                $message_info = add_message_info($message_info, 'Comment success add.');
            }
            $this->session->set_flashdata('message', $message_info);
        }
        redirect($current_url, 'refresh');
    }

    public function comment_edit($act_history_id = NULL)
    {
        if ($act_history_id != NULL && $this->ion_auth->logged_in())
        {
            $the_comment = $this->m_custom->activity_comment_select($act_history_id);
            if ($the_comment == FALSE)
            {
                redirect('/', 'refresh');
            }
            $data['act_history_id'] = array(
                'type' => 'hidden',
                'name' => 'act_history_id',
                'id' => 'act_history_id',
                'value' => $act_history_id,
            );
            $data['comment'] = array(
                'name' => 'comment',
                'id' => 'comment',
                'value' =>  $the_comment['comment'],
            );            
            $data['return_url'] = array(
                'type' => 'hidden',
                'name' => 'return_url',
                'id' => 'return_url',
                'value' => base_url() . "all/advertise/" . $the_comment['act_refer_id'],
            );
            $data['post_title'] = 'Edit Comment';
            $data['page_path_name'] = 'all/comment_edit';
            $this->load->view('template/index', $data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

    public function comment_update()
    {
        $return_url = '/';
        if (isset($_POST) && !empty($_POST))
        {
            $return_url = $this->input->post('return_url');
            $act_history_id = $this->input->post('act_history_id');
            $comment = $this->input->post('comment');
            $this->m_custom->activity_comment_update($act_history_id, $comment);
        }
        redirect($return_url, 'refresh');
    }

    public function comment_hide()
    {
        $current_url = '/';
        if (isset($_POST) && !empty($_POST))
        {
            $current_url = $this->input->post('current_url');
            $act_history_id = $this->input->post('act_history_id');
            $this->m_custom->activity_comment_hide($act_history_id);
        }
        redirect($current_url, 'refresh');
    }

    public function merchant_dashboard($slug = NULL, $bottom_part = NULL)
    {
        $the_row = $this->m_custom->get_one_table_record('users', 'slug', $slug);
        if ($the_row)
        {
            $user_id = $the_row->id;
            $this->data['image_path'] = $this->album_merchant_profile;
            $this->data['image'] = $the_row->profile_image;
            $this->data['company_name'] = $the_row->company;   
            $this->data['browser_title'] = $the_row->company;
            $this->data['address'] = $the_row->address;
            $this->data['description'] = $the_row->description;
            $this->data['phone'] = $the_row->phone;
            $this->data['show_outlet'] = base_url() . 'all/merchant_outlet/' . $slug;
            $this->data['website_url'] = $the_row->me_website_url;
            $this->data['facebook_url'] = $the_row->me_facebook_url;
            //$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['message'] = NULL;
            $this->data['page_path_name'] = 'merchant/dashboard';
            $this->data['hot_deal'] = base_url() . 'all/merchant-dashboard/' . $slug;
            $this->data['candie_promotion'] = base_url() . 'all/merchant-dashboard/' . $slug . '/promotion';
            $this->data['user_picture'] = base_url() . 'all/merchant-dashboard/' . $slug . '/picture';
            $this->data['user_upload_for_merchant'] = base_url() . 'user/upload_for_merchant/' . $slug;
            $this->data['show_expired'] = "<a href='" . base_url() . "all/album_merchant/'. $slug>Show Expired</a><br/>";
            $this->data['user_id'] = $user_id;
            //FOLLOWER or FOLLOWING COUNT
            $this->data['follower_count'] = $this->albert_model->follower_count($user_id);
            $this->data['following_count'] = $this->albert_model->following_count($user_id);
            $this->data['temp_folder'] = $this->temp_folder; 
            
            if ($bottom_part == NULL)
            {
                $this->data['hotdeal_list'] = $this->m_custom->getAdvertise('hot', NULL, $user_id, 1);                
                $this->data['title'] = "Hot Deal";
                $this->data['bottom_path_name'] = 'all/advertise_list';
                //ADVERTISE SUGGESTION
                $where_user = array('slug'=>$slug);
                $main_category_id = $this->albert_model->read_user($where_user)->row()->me_category_id;
                $where_read_category2 = array('main_category_id'=>$main_category_id);
                $result_array_sub_category_id = $this->albert_model->read_category($where_read_category2)->result_array();
                $array_sub_category_id = array_column($result_array_sub_category_id, 'category_id');  
                $this->data['query_advertise_suggestion'] = $this->albert_model->read_advertise_hot_deal_suggestion($array_sub_category_id);
                $this->data['advertise_suggestion_page_path_name'] = 'all/hot_deal_list_suggestion';
                $this->data['advertise_suggestion_page_title'] = 'Hot Deal Suggestion';
            }
            else if ($bottom_part == 'promotion')
            {
                $this->data['hotdeal_list'] = $this->m_custom->getAdvertise('pro', NULL, $user_id, 1, NULL, NULL, 0, 0, 0, 0, 1);
                $this->data['title'] = "Redemption";
                $this->data['bottom_path_name'] = 'all/advertise_list';
                //ADVERTISE SUGGESTION
                $where_user = array('slug'=>$slug);
                $main_category_id = $this->albert_model->read_user($where_user)->row()->me_category_id;
                $where_read_category2 = array('main_category_id'=>$main_category_id);
                $result_array_sub_category_id = $this->albert_model->read_category($where_read_category2)->result_array();
                $array_sub_category_id = array_column($result_array_sub_category_id, 'category_id');  
                $this->data['query_advertise_suggestion'] = $this->albert_model->read_advertise_redemption_suggestion($array_sub_category_id);
                $this->data['advertise_suggestion_page_path_name'] = 'all/hot_deal_list_suggestion';
                $this->data['advertise_suggestion_page_title'] = 'Redemption Suggestion';
            }
            else if ($bottom_part == 'picture')
            {
                $this->data['album_list'] = $this->m_custom->getAlbumUserMerchant(NULL, $user_id);
                $this->data['title'] = "User's Pictures";
                $this->data['bottom_path_name'] = 'all/album_user_merchant';
            }
            if ($this->ion_auth->logged_in())
            {
                $this->data['message'] = $this->session->flashdata('message');
                $this->load->view('template/index', $this->data);
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
            $this->data['show_outlet'] = base_url() . 'merchant_outlet/' . $slug;
            $this->data['view_map_path'] = 'all/merchant-map/';
            $this->data['website_url'] = $the_row->me_website_url;
            $this->data['facebook_url'] = $the_row->me_facebook_url;
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
                $this->load->view('template/index', $this->data);
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
                    $this->load->view('template/index', $this->data);
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

    public function upload_image_temp()
    {
        $temp_folder = $this->temp_folder;
        $upload_rule = array(
            'upload_path' => $temp_folder,
            'allowed_types' => $this->config->item('allowed_types_image'),
            'max_size' => $this->config->item('max_size'),
            'max_width' => $this->config->item('max_width'),
            'max_height' => $this->config->item('max_height'),
        );

        $this->load->library('upload', $upload_rule);
        $post_file = $this->input->post('file_name');
        $post_image_box = $this->input->post('image_box_id');
        
        if (!is_dir($temp_folder))
        {
            mkdir($temp_folder, 0777, TRUE);
        }

        if (!empty($_FILES[$post_file]['name']))
        {
            $this->upload->do_upload($post_file);
            $image_data = array('upload_data' => $this->upload->data());
        }
        echo json_encode(array($image_data['upload_data']['file_name'], $post_image_box));
    }  

    public function home_search()
    {
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "search")
            {
                $search_value = $this->input->post('search_word');
                $state_id = $this->input->post('me_state_id');
                if (IsNullOrEmptyString($search_value))
                {
                    $search_value = 0;
                }
                $this->data['home_search_merchant'] = $this->m_custom->home_search_merchant($search_value, $state_id);
                $this->data['home_search_hotdeal'] = $this->m_custom->home_search_hotdeal($search_value, $state_id);
                $this->data['home_search_promotion'] = $this->m_custom->home_search_promotion($search_value, $state_id);

                $this->data['state_name'] = "";
                if ($state_id != 0)
                {
                    $this->data['state_name'] = " : " . $this->m_custom->display_static_option($state_id);
                }

                $this->data['page_path_name'] = 'all/search_result';

                $this->load->view('template/index_background_blank', $this->data);
            }
        }
        //redirect('/', 'refresh');
    }

}
