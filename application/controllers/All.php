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
        $this->album_user_merchant = $this->config->item('album_user_merchant');
        $this->album_user = $this->config->item('album_user');
        $this->group_id_merchant = $this->config->item('group_id_merchant');
        $this->group_id_supervisor = $this->config->item('group_id_supervisor');
        $this->group_id_user = $this->config->item('group_id_user');
        $this->login_type = 0;
        if ($this->ion_auth->logged_in())
        {
            $this->login_type = $this->session->userdata('user_group_id');
        }
    }

    function hotdeal_list()
    {
        $sub_category_id = $this->uri->segment(3);
        $this->data['hotdeal_list'] = $this->m_custom->getAdvertise('hot', $sub_category_id);
        $this->data['title'] = "Hot Deals";
        if (!IsNullOrEmptyString($sub_category_id))
        {
            $this->data['main_category'] = $this->m_custom->display_main_category($sub_category_id);
            $this->data['sub_category'] = $this->m_custom->display_category($sub_category_id);
        }
        $this->data['page_path_name'] = 'all/advertise_list';
        $this->load->view('template/layout_category', $this->data);
    }

    function promotion_list()
    {
        $sub_category_id = $this->uri->segment(3);
        $this->data['hotdeal_list'] = $this->m_custom->getAdvertise('pro', $sub_category_id);
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
                $this->m_custom->activity_view($advertise_id);
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

            if ($the_row['advertise_type'] == "pro")
            {
                $this->data['voucher'] = $the_row['voucher'];
                $this->data['voucher_barcode'] = base_url("barcode/generate/" . $the_row['voucher']);
                $this->data['voucher_candie'] = $the_row['voucher_candie'];
                $this->data['expire_date'] = displayDate($the_row['voucher_expire_date']);
                $this->data['candie_term'] = $this->m_custom->many_get_childlist_detail('candie_term', $advertise_id, 'dynamic_option', 'option_id');
                $this->data['candie_branch'] = $this->m_custom->many_get_childlist_detail('candie_branch', $advertise_id, 'merchant_branch', 'branch_id');
                $this->data['page_path_name'] = 'all/promotion';
            }
            else
            {
                $this->data['end_time'] = displayDate($the_row['end_time'], 1, 1);
                $this->data['page_path_name'] = 'all/hotdeal';
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
                $login_data = $this->m_custom->getUser($login_id);
            }

            //$user_row = $this->m_custom->getUser($the_row['user_id']);    //Temporary hide because no use, but maybe future will use
            $merchant_row = $this->m_merchant->getMerchant($the_row['merchant_id']);
            $this->data['merchant_dashboard_url'] = base_url() . "all/merchant-dashboard/" . $merchant_row['slug'];

            $this->data['picture_id'] = $picture_id;
            $this->data['merchant_name'] = $merchant_row['company'];
            $this->data['user_name_url'] = $this->m_custom->generate_user_link($the_row['user_id']);
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
            $this->load->view('template/layout_right_menu', $this->data);
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
        }
        $this->data['album_list'] = $album_list;

        $this->data['title'] = "User Picture Album";
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'all/album_user';
        if ($this->ion_auth->logged_in())
        {
            $this->load->view('template/layout_right_menu', $this->data);
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
        $config["total_rows"] = count($this->m_custom->getAdvertise('all', NULL, $merchant_id, 1));  //To get the total row              
        $this->pagination->initialize($config);
        $this->data["paging_links"] = $this->pagination->create_links();
        $start_index = $page == 1 ? $page : (($page - 1) * $config["per_page"]);  //For calculate page number to start index

        $this->data['hotdeal_list'] = $this->m_custom->getAdvertise('all', NULL, $merchant_id, 1, $config["per_page"], $start_index);   //To get the limited result only for that current page

        $this->data['title'] = "Merchant Album";
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'all/advertise_list';

        if ($this->ion_auth->logged_in())
        {
            $this->load->view('template/layout_right_menu', $this->data);
        }
        else
        {
            $this->load->view('template/layout', $this->data);
        }
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

    //To do todo add submit way, and email send
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
                $redeem_info = $this->m_user->user_redemption_insert($advertise_id);

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
        $mail_message = 'Merchant : ' . $mail_info['redeem_info']['redeem_merchant'] . '<br/>'
                . 'Promotion Title : ' . $mail_info['redeem_info']['redeem_title'] . '<br/>'
                . 'Voucher Code : ' . $mail_info['redeem_info']['redeem_voucher'] . '<br/>'
                . 'End Date : ' . $mail_info['redeem_info']['redeem_expire'] . '<br/><br/>'
                . $mail_info['message'];
        $get_status = send_mail_simple($mail_info['email'], $mail_info['redeem_info']['redeem_email_subject'], $mail_message, 'keppo_redeem_send_email_success');
        if ($get_status)
        {
            set_simple_message('Thank you!', 'Please check your e-mail (Inbox/Junk) or keppo.my redemption "active". <br/><br/>Please present this voucher before you purchase.<br/><br/>Enjoy your gift from merchants & Keppo.my.', $mail_message, $mail_info['return_url'], 'Back', 'all/simple_message');
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
                $this->load->view('template/layout_right_menu', $data);
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

    //View the user dashboard upper part
    function user_dashboard($users_id = NULL, $album = NULL)
    {
        if($users_id)
        {
            //QUERY USERS
            $query_users_where = array('id' => $users_id);
            $data['query_users'] = $this->albert_model->get_users($query_users_where);
            $num_rows_users = $data['query_users']->num_rows();
            //USER EXISTS
            if($num_rows_users)
            {
                //PAGE PATH NAME
                $data['page_path_name'] = 'user/dashboard';
                //QUERY USER FOLLOW FOLLOWER
                $where_user_follow_follower = array('follow_to_id'=>$users_id);
                $data['query_user_follow_follower'] = $this->albert_model->get_user_follow($where_user_follow_follower);
                //QUERY USER FOLLOW FOLLOWING
                $where_user_follow_following = array('follow_from_id'=>$users_id);
                $data['query_user_follow_following'] = $this->albert_model->get_user_follow($where_user_follow_following);
                
                if(!$album)
                {
                    //USER ALBUM
                    $data['title'] = "User Album";
                    $data['bottom_path_name'] = 'all/album_user';
                    $where_user_album = array('user_id'=>$users_id);
                    $query_user_album = $this->albert_model->get_user_album($where_user_album);
                    $data['album_list'] = $query_user_album->result_array();
                }
                else
                {
                    //USER MERCHANT ALBUM
                    $data['title'] = "Merchant Album";
                    $data['bottom_path_name'] = 'all/album_user_merchant';
                    $where_merchant_user_album = array('user_id'=>$users_id);
                    $query_merchant_user_album = $this->albert_model->get_merchant_user_album($where_merchant_user_album);
                    $data['album_list'] = $query_merchant_user_album->result_array();
                }
                
                if ($this->ion_auth->logged_in())
                {
                    //LOGGED IN
                    $this->load->view('template/layout_right_menu', $data);
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
        if($users_id == NULL){
            redirect('/', 'refresh');
        }
        //QUERY USERS
        $query_users_where = array('id' => $users_id);
        $data['query_users'] = $this->albert_model->get_users($query_users_where);
        //PAGE PATH NAME
        $data['page_path_name'] = 'all/advertise_list';
        //BOTTON PATH NAME
        $data['title'] = "Review";
        //QUERY HOTDEAL LIST
        $hotdeal_list_where = array('act_by_id' => $users_id);
        $data['hotdeal_list'] = $this->albert_model->get_activity_history_inner_join_advertise($hotdeal_list_where)->result_array();
        if ($this->ion_auth->logged_in())
        {
            //LOGGED IN
            $this->load->view('template/layout_right_menu', $data);
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

    public function comment_hide()
    {
        $current_url = '/';
        if (isset($_POST) && !empty($_POST))
        {
            $current_url = $this->input->post('current_url');
            $act_history_id = $this->input->post('act_history_id');
            $this->m_custom->activity_hide($act_history_id);
        }
        redirect($current_url, 'refresh');
    }

    public function merchant_dashboard($slug = NULL, $user_picture = NULL)
    {
        $the_row = $this->m_custom->get_one_table_record('users', 'slug', $slug);
        if ($the_row)
        {
            $this->data['image_path'] = $this->album_merchant_profile;
            $this->data['image'] = $the_row->profile_image;
            $this->data['company_name'] = $the_row->company;
            $this->data['address'] = $the_row->address;
            $this->data['phone'] = $the_row->phone;
            $this->data['show_outlet'] = base_url() . 'all/merchant_outlet/' . $slug;
            $this->data['website_url'] = $the_row->me_website_url;
            $this->data['facebook_url'] = $the_row->me_facebook_url;
            //$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['message'] = "";
            $this->data['page_path_name'] = 'merchant/dashboard';
            $this->data['offer_deal'] = base_url() . 'all/merchant-dashboard/' . $slug;
            $this->data['user_picture'] = base_url() . 'all/merchant-dashboard/' . $slug . '/user-picture';
            $this->data['user_upload_for_merchant'] = base_url() . 'user/upload_for_merchant/' . $slug;
            $this->data['show_expired'] = "<a href='" . base_url() . "all/album_merchant/'. $slug>Show Expired</a><br/>";
            $this->data['hotdeal_list'] = $this->m_custom->getAdvertise('all', NULL, $the_row->id);
            if ($user_picture == NULL)
            {
                $this->data['title'] = "Offer Deals";
                $this->data['bottom_path_name'] = 'all/advertise_list';
            }
            else
            {
                $this->data['album_list'] = $this->m_custom->getAlbumUserMerchant(NULL, $the_row->id);
                $this->data['title'] = "User's Pictures";
                $this->data['bottom_path_name'] = 'all/album_user_merchant';
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
            $this->data['show_outlet'] = base_url() . 'merchant_outlet/' . $slug;
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
