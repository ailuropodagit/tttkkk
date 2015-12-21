<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'language'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->main_group_id = $this->config->item('group_id_merchant');
        $this->group_id_merchant = $this->config->item('group_id_merchant');
        $this->group_id_supervisor = $this->config->item('group_id_supervisor');
        $this->album_merchant_profile = $this->config->item('album_merchant_profile');
        $this->album_merchant = $this->config->item('album_merchant');
        $this->folder_merchant_ssm = $this->config->item('folder_merchant_ssm');
        $this->folder_image = $this->config->item('folder_image');
        $this->box_number = $this->config->item('merchant_upload_box_per_page');
        $this->temp_folder = $this->config->item('folder_image_temp');
    }

    // redirect if needed, otherwise display the user list
    function index()
    {
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('merchant/login', 'refresh');
        }
        elseif (!$this->ion_auth->is_admin())
        {
            // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }
        else
        {
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            //list the users
            $this->data['users'] = $this->ion_auth->users()->result();
            foreach ($this->data['users'] as $k => $user)
            {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }
            $this->_render_page('merchant/index', $this->data);
        }
    }

    // log the user in
    function login()
    {
        $this->data['title'] = "Log In";
        //validate form input
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        setcookie('visit_first_time', 'no');
        
        //validate success
        if ($this->form_validation->run() == true)
        {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember, $this->main_group_id))
            {
                //if the login is successful
                //redirect them back to the home page
                //$this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->update_whole_year_balance();
                $user_id = $this->session->userdata('user_id');
                $this->m_custom->promo_code_insert_merchant($user_id);
                redirect('all/merchant_dashboard/' . $this->session->userdata('company_slug'), 'refresh');
            }
            else if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember, $this->group_id_supervisor))
            {
                //$this->session->set_flashdata('message', $this->ion_auth->messages());
                $user_id = $this->ion_auth->user()->row()->su_merchant_id;
                $this->m_custom->promo_code_insert_merchant($user_id);
                redirect('all/merchant_dashboard/' . $this->session->userdata('company_slug'), 'refresh');
            }
            else
            {
                // if the login was un-successful
                // redirect them back to the login page
                if ($this->ion_auth->errors() != "")
                {
                    $this->session->set_flashdata('message', $this->lang->line('login_unsuccessful'));
                }
                //$this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('merchant/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        }
        else
        {
            // the user is not logging in so display the login page
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['identity'] = array('name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
            );
            $this->data['page_path_name'] = 'merchant/login';
            $this->load->view('template/layout', $this->data);
        }
    }

    // log the user out
    function logout()
    {
        $admin_login_as = $this->session->userdata('admin_login_as');  //If is admin login as this user

        $this->data['title'] = "Logout";

        if ($admin_login_as != 0)
        {
            //If is admin login as this user, then redirect back to admin portal
            $user_login_info = $this->m_custom->getUserLoginInfo($admin_login_as);
            if ($user_login_info)
            {
                if ($this->ion_auth->login($user_login_info['username'], $user_login_info['password_visible'], FALSE, $user_login_info['main_group_id']))
                {
                    redirect('admin/merchant_management', 'refresh');
                }
            }
        }
        else
        {
            // log the user out        
            $logout = $this->ion_auth->logout();
        }

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('merchant/login', 'refresh');
    }

    // change password
    function change_password()
    {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }

        $user = $this->ion_auth->user()->row();
        $function_use_for = 'merchant/change_password';

        if ($this->form_validation->run() == false)
        {
            // display the form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );
            $this->data['function_use_for'] = $function_use_for;

            $this->data['page_path_name'] = 'all/change_password';
            $this->load->view('template/index', $this->data);
        }
        else
        {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change)
            {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                //$this->logout();
                set_simple_message('Thank you!', 'Your Password has been saved!', '', 'all/merchant_dashboard/' . $this->session->userdata('company_slug'), 'Back to Dashboard', 'all/simple_message', 1, 3);
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect($function_use_for, 'refresh');
            }
        }
    }

    //FOLLOWER
    function follower($user_type, $user_id)
    {
        //CONFIG DATA
        $group_id_merchant = $this->config->item('group_id_merchant');
        $group_id_supervisor = $this->config->item('group_id_supervisor');
        //POST
        if ($this->input->post('search'))
        {
            //POST VALUE
            $keyword = $this->input->post('keyword');
        }
        else
        {
            //POST VALUE 
            $keyword = '';
        }
        //FORM DATA
        $data['keyword'] = array(
            'name' => 'keyword',
            'placeholder' => 'Search',
            'value' => $keyword
        );
        //READ USER
        $where_read_user = array('id' => $user_id);
        $query_read_user = $this->albert_model->read_user($where_read_user);
        $group_id = $query_read_user->row()->main_group_id;
        if ($group_id == $group_id_merchant)
        {
            $user_name = $query_read_user->row()->company;
        }
        if ($group_id == $group_id_supervisor)
        {
            $su_merchant_id = $query_read_user->row()->su_merchant_id;
            $where_read_user2 = array('id' => $su_merchant_id);
            $query_read_user2 = $this->albert_model->read_user($where_read_user2);
            $user_id = $query_read_user2->row()->id;
            $user_name = $query_read_user2->row()->company;
        }
        //DATA
        $data['page_title'] = $user_name . ' User Followers';
        //QUERY USER FOLLOWER        
        $where_user_follower = array('following_main_id' => $user_id);
        $data['query_follow'] = $this->albert_model->read_follower($where_user_follower, $keyword);
        //COUNT 
        $data['user_follower_count'] = $this->albert_model->user_follower_count($user_id);
        $data['user_following_count'] = $this->albert_model->user_following_count($user_id);
        //DATA
        $data['user_id'] = $user_id;
        //TEMPLATE
        $data['page_path_name'] = 'merchant/follow';
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

    //FOLLOWING
    function following($user_type, $user_id)
    {
        //CONFIG DATA
        $group_id_merchant = $this->config->item('group_id_merchant');
        $group_id_supervisor = $this->config->item('group_id_supervisor');
        //POST
        if ($this->input->post('search'))
        {
            //POST VALUE
            $keyword = $this->input->post('keyword');
        }
        else
        {
            //POST VALUE 
            $keyword = '';
        }
        //FORM DATA
        $data['keyword'] = array(
            'name' => 'keyword',
            'placeholder' => 'Search',
            'value' => $keyword
        );
        //READ USER
        $where_read_user = array('id' => $user_id);
        $query_read_user = $this->albert_model->read_user($where_read_user);
        $group_id = $query_read_user->row()->main_group_id;
        if ($group_id == $group_id_merchant)
        {
            $user_name = $query_read_user->row()->company;
        }
        if ($group_id == $group_id_supervisor)
        {
            $su_merchant_id = $query_read_user->row()->su_merchant_id;
            $where_read_user2 = array('id' => $su_merchant_id);
            $query_read_user2 = $this->albert_model->read_user($where_read_user2);
            $user_id = $query_read_user2->row()->id;
            $user_name = $query_read_user2->row()->company;
        }
        //DATA
        $data['page_title'] = $user_name . ' Merchant Following';
        //QUERY USER FOLLOWING
        $where_user_following = array('follower_main_id' => $user_id);
        $data['query_follow'] = $this->albert_model->read_following($where_user_following, $keyword);
        //COUNT 
        $data['user_follower_count'] = $this->albert_model->user_follower_count($user_id);
        $data['user_following_count'] = $this->albert_model->user_following_count($user_id);
        //DATA
        $data['user_id'] = $user_id;
        //TEMPLATE
        $data['page_path_name'] = 'merchant/follow';
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

    function update_whole_year_balance()
    {
        if (check_correct_login_type($this->main_group_id))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;
            for ($i = 1; $i < 13; $i++)
            {
                $this->m_merchant->merchant_balance_update($merchant_id, $i);
            }
        }
    }

    function payment_page()
    {
        if (check_correct_login_type($this->main_group_id))
        {
            if (isset($_POST) && !empty($_POST))
            {
                if ($this->input->post('button_action') == "search_history")
                {
                    $search_month = $this->input->post('the_month');
                }
            }

            $month_list = limited_month_select(5, 1);
            $this->data['month_list'] = $month_list;
            $this->data['the_month'] = array(
                'name' => 'the_month',
                'id' => 'the_month',
            );
            $selected_month = empty($search_month) ? date_for_db_search() : $search_month;
            $this->data['the_month_selected'] = $selected_month;
            $selected_month_text = $this->m_custom->explode_year_month($selected_month);
            $this->data['the_month_selected_text'] = $selected_month_text['month_year_text'];
            $month_last_date = $selected_month_text['month_last_date'];
            $this->data['previous_month_selected_text'] = $this->m_custom->explode_year_month(month_previous($month_last_date, 1));

            $merchant_id = $this->ion_auth->user()->row()->id;
            $this->m_merchant->merchant_balance_update($merchant_id);
            $this->data['previous_end_month_balance'] = $this->m_merchant->merchant_check_balance($merchant_id, 1, month_previous($month_last_date));
            $this->data['end_month_balance'] = $this->m_merchant->merchant_check_balance($merchant_id, 1, $month_last_date);
            $this->data['current_balance'] = $this->m_merchant->merchant_check_balance($merchant_id, 0, $month_last_date);
            $this->data['this_month_transaction'] = $this->m_merchant->merchant_this_month_transaction($merchant_id, $selected_month);

            $this->data['message'] = $this->session->flashdata('message');
            $this->data['page_path_name'] = 'merchant/payment';
            $this->load->view('template/index', $this->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

    function payment_charge_page($search_type = NULL)
    {
        if (check_correct_login_type($this->main_group_id))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;

            if (isset($_POST) && !empty($_POST))
            {
                if ($this->input->post('button_action') == "search_history")
                {
                    $search_type = $this->input->post('the_adv_type');
                }
            }

            $adv_type_list = array(
                '' => 'All Type',
                'hot' => 'Hot Deal Advertise',
                'pro' => 'Candie Voucher',
                'mua' => 'User Upload Picture'
            );
            $this->data['adv_type_list'] = $adv_type_list;
            $this->data['the_adv_type'] = array(
                'name' => 'the_adv_type',
                'id' => 'the_adv_type',
            );
            $this->data['the_adv_type_selected'] = empty($search_type) ? "" : $search_type;

            $the_result = $this->m_merchant->money_spend_on_list($merchant_id, $search_type);
            usort($the_result, function($a, $b)
            {
                $ad = new DateTime($a['create_date']);
                $bd = new DateTime($b['create_date']);

                if ($ad == $bd)
                {
                    return 0;
                }

                return $ad < $bd ? 1 : -1;
            });
            $this->data['the_result'] = $the_result;

            $this->data['message'] = $this->session->flashdata('message');
            $this->data['page_path_name'] = 'merchant/payment_charge';
            $this->load->view('template/index', $this->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

    function retrieve_password()
    {
        $this->form_validation->set_rules('username_email', $this->lang->line('forgot_password_username_email_label'), 'required');
        if ($this->form_validation->run() == false)
        {
            // setup the input
            $this->data['username_email'] = array('name' => 'username_email',
                'id' => 'username_email',
            );
            $this->data['identity_label'] = $this->lang->line('forgot_password_username_email_label');
            // set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['page_path_name'] = 'merchant/retrieve_password';
            $this->load->view('template/layout', $this->data);
        }
        else
        {
            $the_input = $this->input->post('username_email');
            $the_id = $this->ion_auth->get_id_by_email_or_username($the_input);
            $identity = $this->ion_auth->where('id', $the_id)->where('main_group_id', $this->main_group_id)->users()->row();
            if (empty($identity))
            {
                $this->ion_auth->set_error('forgot_password_username_email_not_found');
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("merchant/retrieve_password", 'refresh');
            }
            else
            {
                $this->session->set_flashdata('mail_info', $identity);
                redirect('merchant/send_mail_process', 'refresh');
            }
        }
    }

    function send_mail_process()
    {
        $identity = $this->session->flashdata('mail_info');
        $get_status = send_mail_simple($identity->email, 'Your Keppo Account Login Info', 'Company Name:' . $identity->company . '<br/>Username:' . $identity->username . '<br/>Email:' . $identity->email . '<br/>Password:' . $identity->password_visible, 'forgot_password_send_email_success');
        if ($get_status)
        {
            set_simple_message('Thank you!', 'An email will be sent to your registered email address.', "If you don't receive in the next 10 minutes, please check your spam folder and if you still haven't received it please try again...", 'merchant/login', 'Go to Log In Page', 'all/simple_message');
        }
        else
        {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("merchant/retrieve_password", 'refresh');
        }
    }

    // forgot password
    function forgot_password()
    {
        // setting validation rules by checking wheather identity is username or email
        if ($this->config->item('identity', 'ion_auth') != 'email')
        {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        }


        if ($this->form_validation->run() == false)
        {
            // setup the input
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
            );

            if ($this->config->item('identity', 'ion_auth') != 'email')
            {
                $this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
            }
            else
            {
                $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            // set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->_render_page('merchant/forgot_password', $this->data);
        }
        else
        {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $identity = $this->ion_auth->where($identity_column, $this->input->post('email'))->users()->row();

            if (empty($identity))
            {

                if ($this->config->item('identity', 'ion_auth') != 'email')
                {
                    $this->ion_auth->set_error('forgot_password_identity_not_found');
                }
                else
                {
                    $this->ion_auth->set_error('forgot_password_email_not_found');
                }

                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("merchant/forgot_password", 'refresh');
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten)
            {
                // if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("merchant/login", 'refresh'); //we should display a confirmation page here instead of the login page
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("merchant/forgot_password", 'refresh');
            }
        }
    }

    // reset password - final step for forgotten password
    public function reset_password($code = NULL)
    {
        if (!$code)
        {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user)
        {
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false)
            {
                // display the form
                // set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                // render
                $this->_render_page('merchant/reset_password', $this->data);
            }
            else
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
                {

                    // something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));
                }
                else
                {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change)
                    {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("merchant/login", 'refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('merchant/reset_password/' . $code, 'refresh');
                    }
                }
            }
        }
        else
        {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("merchant/forgot_password", 'refresh');
        }
    }

    // activate the user
    function activate($id, $code = false)
    {
        if ($code !== false)
        {
            $activation = $this->ion_auth->activate($id, $code);
        }
        else if ($this->ion_auth->is_admin())
        {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation)
        {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("merchant", 'refresh');
        }
        else
        {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("merchant/forgot_password", 'refresh');
        }
    }

    // deactivate the user
    function deactivate($id = NULL)
    {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            // redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }

        $id = (int) $id;

        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() == FALSE)
        {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();
            $this->_render_page('merchant/deactivate_user', $this->data);
        }
        else
        {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes')
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
                {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
                {
                    $this->ion_auth->deactivate($id);
                }
            }

            // redirect them back to the auth page
            redirect('merchant', 'refresh');
        }
    }

    // create a new user
    function create_user()
    {
        $controller = $this->uri->segment(2);
        $function_use_for = 'merchant/create_user';
        if ($controller == 'create_user')
        {
            $this->data['title'] = "Create Merchant";
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
            {
                redirect('merchant', 'refresh');
            }
        }
        else
        {
            $this->data['title'] = "Merchant Sign Up";
            $function_use_for = 'merchant/register';
        }
        $this->data['function_use_for'] = $function_use_for;
        $tables = $this->config->item('tables', 'ion_auth');
        if (isset($_POST) && !empty($_POST))
        {
            $_POST['slug'] = generate_slug($_POST['company']);
            $slug = $_POST['slug'];
        }
        // validate form input
        $this->form_validation->set_rules('accept_terms', '...', 'callback_accept_terms');
        $this->form_validation->set_rules('company_main', $this->lang->line('create_merchant_validation_company_main_label'), "trim|required|min_length[3]");
        $this->form_validation->set_rules('me_ssm', $this->lang->line('create_merchant_validation_companyssm_label'), 'required');
        $this->form_validation->set_rules('company', $this->lang->line('create_merchant_validation_company_label'), "trim|required|min_length[3]");
        $this->form_validation->set_rules('slug', $this->lang->line('create_merchant_validation_company_label'), 'trim|is_unique[' . $tables['users'] . '.slug]');      
        $this->form_validation->set_rules('address', $this->lang->line('create_merchant_validation_address_label'), 'required');
        $this->form_validation->set_rules('postcode', $this->lang->line('create_merchant_validation_postcode_label'), 'required|numeric');
        $this->form_validation->set_rules('me_state_id', $this->lang->line('create_merchant_validation_state_label'), 'callback_check_state_id');
        $this->form_validation->set_rules('me_country', $this->lang->line('create_merchant_validation_country_label'), 'required');
        $this->form_validation->set_rules('me_category_id', $this->lang->line('create_merchant_category_label'), 'callback_check_main_category');
        $this->form_validation->set_rules('me_sub_category_id', $this->lang->line('create_merchant_sub_category_label'), 'callback_check_sub_category');
        $this->form_validation->set_rules('phone', $this->lang->line('create_merchant_validation_phone_label'), 'required|valid_contact_number');
        $this->form_validation->set_rules('username', $this->lang->line('create_merchant_validation_username_label'), 'trim|required|is_unique[' . $tables['users'] . '.username]');
        $this->form_validation->set_rules('email', $this->lang->line('create_merchant_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('password', $this->lang->line('create_merchant_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_merchant_validation_password_confirm_label'), 'required');        
        if ($this->form_validation->run() == true)
        {
            //$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $company_main = $this->input->post('company_main');
            $company = $this->input->post('company');
            $me_ssm = $this->input->post('me_ssm');
            $address = $this->input->post('address');
            $postcode = $this->input->post('postcode');
            $state = $this->input->post('me_state_id');
            $country = $this->input->post('me_country');
            //$phone = '+60' . $this->input->post('phone');    
            $phone = $this->input->post('phone'); 
            $additional_data = array(
                'username' => $username,
                'company_main' => $company_main,
                'company' => $company,
                'slug' => $slug,
                'address' => $address,
                'postcode' => $postcode,
                'country' => $country,
                'me_state_id' => $state,
                'me_category_id' => $this->input->post('me_category_id'),
                'me_sub_category_id' => $this->input->post('me_sub_category_id'),
                'phone' => $phone,
                'me_ssm' => $me_ssm,
                //'profile_image' => $this->config->item(''),
                //'me_website_url' => $this->input->post('website'),
                'main_group_id' => $this->main_group_id,
                'password_visible' => $password
            );
        }

        $group_ids = array(
            $this->main_group_id
        );

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids))
        {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            $get_status = send_mail_simple($email, 'Your Keppo Merchant Account Success Created', 'Company Name : ' . $company_main .
                    '<br/>Register No(SSM) : ' . $me_ssm .
                    '<br/>Shop Name : ' . $company .
                    '<br/>Company Address : ' . $address .
                    '<br/>Contact Number : ' . $phone .
                    '<br/>Username : ' . $username .
                    '<br/>E-mail : ' . $email .
                    '<br/>Password : ' . $password, 'create_user_send_email_success');
            if ($get_status)
            {
                redirect("merchant/login", 'refresh');
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("merchant/register", 'refresh');
            }
        }
        else
        {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['username'] = array(
                'name' => 'username',
                'id' => 'username',
                'type' => 'text',
                'value' => $this->form_validation->set_value('username'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company_main'] = array(
                'name' => 'company_main',
                'id' => 'company_main',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company_main'),
            );
            $this->data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['address'] = array(
                'name' => 'address',
                'id' => 'address',
                'value' => $this->form_validation->set_value('address'),
            );
            $this->data['postcode'] = array(
                'name' => 'postcode',
                'id' => 'postcode',
                'type' => 'text',
                'value' => $this->form_validation->set_value('postcode'),
            );
            $this->data['state_list'] = $this->m_custom->get_static_option_array('state', '0', 'Please Select');
            $this->data['me_state_id'] = array(
                'name' => 'me_state_id',
                'id' => 'me_state_id',
                'value' => $this->form_validation->set_value('me_state_id'),
            );
            $this->data['country_list'] = array('Malaysia'=>'Malaysia');
            $this->data['me_country'] = array(
                'name' => 'me_country',
                'id' => 'me_country',
                'value' => $this->form_validation->set_value('me_country'),
            );
            $me_category_id = $this->form_validation->set_value('me_category_id') == '' ? '' : $this->form_validation->set_value('me_category_id');
            $this->data['category_list'] = $this->m_custom->getCategoryList('0', 'Please Select');
            $this->data['me_category_id'] = array(
                'name' => 'me_category_id',
                'id' => 'me_category_id',
                'value' => $me_category_id,
                'onChange' => "get_SubCategory()",
            );
            $this->data['sub_category_list'] = $this->m_custom->getSubCategoryList(NULL, NULL, $me_category_id);
            $this->data['me_sub_category_id'] = array(
                'name' => 'me_sub_category_id',
                'id' => 'me_sub_category_id',
                'value' => $this->form_validation->set_value('me_sub_category_id'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
                'class' => 'phone_blur',
            );
            $this->data['me_ssm'] = array(
                'name' => 'me_ssm',
                'id' => 'me_ssm',
                'type' => 'text',
                'value' => $this->form_validation->set_value('me_ssm'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );
            $this->data['page_path_name'] = 'merchant/create_user';
            $this->load->view('template/layout', $this->data);
        }
    }

    function accept_terms()
    {
        if (isset($_POST['accept_terms'])){
            return true;
        }
        $this->form_validation->set_message('accept_terms', 'Please read and accept our Terms of Service and Privacy Policy.');
        return false;
    }

    function check_state_id($dropdown_selection)
    {
        if ($dropdown_selection == 0)
        {
            $this->form_validation->set_message('check_state_id', 'The State field is required');
            return FALSE;
        }
        return TRUE;
    }
    
    function check_main_category($dropdown_selection)
    {
        if ($dropdown_selection == 0)
        {
            $this->form_validation->set_message('check_main_category', 'The Company Category field is required');
            return FALSE;
        }
        return TRUE;
    }

    function check_sub_category($dropdown_selection)
    {
        if ($dropdown_selection == 0)
        {
            $this->form_validation->set_message('check_sub_category', 'The Default Sub Category field is required');
            return FALSE;
        }
        return TRUE;
    }

    public function get_sub_category_by_category($selected_category = NULL)
    {
        $sub_category_list = array();
        if ($selected_category != '0')
        {
            $query = $this->m_custom->getSubCategory($selected_category);

            foreach ($query as $item)
            {
                $sub_category_list[$item->category_id] = $item->category_label;
            }
        }

        $me_sub_category_id = array(
            'name' => 'me_sub_category_id',
            'id' => 'me_sub_category_id',
        );
        $output = form_dropdown($me_sub_category_id, $sub_category_list);
        echo $output;
    }

    //merchant profile view and edit page
    function profile()
    {
        if (!check_correct_login_type($this->main_group_id) && !check_correct_login_type($this->group_id_supervisor))
        {
            redirect('/', 'refresh');
        }
        $merchant_id = $this->ion_auth->user()->row()->id;
        $is_supervisor = 0;
        $branch = FALSE;
        //for supervisor view merchant profile because supervisor don't have own profile
        if (check_correct_login_type($this->group_id_supervisor))
        {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
            $is_supervisor = 1;
            $supervisor = $this->ion_auth->user()->row();
            $branch = $this->m_custom->get_one_table_record('merchant_branch', 'branch_id', $supervisor->su_branch_id);
        }
        $user = $this->ion_auth->user($merchant_id)->row();
        $this->form_validation->set_rules('postcode', 'Postcode', 'required|numeric');
        $this->form_validation->set_rules('phone', $this->lang->line('create_merchant_validation_phone_label'), 'required|valid_contact_number');
        $this->form_validation->set_rules('description', $this->lang->line('create_merchant_validation_description_label'));
        $this->form_validation->set_rules('website', $this->lang->line('create_merchant_validation_website_label'));
        $this->form_validation->set_rules('facebook_url', $this->lang->line('create_merchant_validation_facebook_url_label'));
        $this->form_validation->set_rules('person_incharge', $this->lang->line('create_merchant_validation_person_incharge_label'), 'required');
        $this->form_validation->set_rules('person_contact', $this->lang->line('create_merchant_validation_person_contact_label'), 'required|valid_contact_number');
        
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "confirm")
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $merchant_id != $this->input->post('id'))
                {
                    show_error($this->lang->line('error_csrf'));
                }
                if ($this->form_validation->run() === TRUE)
                {

                    $data = array(                      
                        'address' => $this->input->post('address'),
                        'postcode' => $this->input->post('postcode'),
                        'me_state_id' => $this->input->post('me_state_id'),
                        'country' => $this->input->post('me_country'),                     
                        'description' => $this->input->post('description'),
                        'phone' => $this->input->post('phone'),
                        'company' => $this->input->post('company'),
                        'slug' => generate_slug($this->input->post('company')),
                        //'me_category_id' => $this->input->post('me_category_id'),
                        'me_sub_category_id' => $this->input->post('me_sub_category_id'),
                        'me_person_incharge' => $this->input->post('person_incharge'),
                        'me_person_contact' => $this->input->post('person_contact'),
                        'me_website_url' => $this->input->post('website'),
                        'me_facebook_url' => $this->input->post('facebook_url'),
                    );

                    // check to see if we are updating the user
                    if ($this->ion_auth->update($user->id, $data))
                    {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        $user = $this->ion_auth->user($merchant_id)->row();
                        redirect('all/merchant_dashboard/' . $user->slug, 'refresh');
                    }
                    else
                    {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }
            }
            else if ($this->input->post('button_action') == "view_branch")
            {
                redirect('merchant/branch', 'refresh');
            }
            else if ($this->input->post('button_action') == "add_branch")
            {
                redirect('merchant/branch/add', 'refresh');
            }
            else if ($this->input->post('button_action') == "view_supervisor")
            {
                redirect('merchant/supervisor', 'refresh');
            }
            else if ($this->input->post('button_action') == "add_supervisor")
            {
                redirect('merchant/supervisor/add', 'refresh');
            }
        }
        $this->data['image_path'] = $this->album_merchant_profile;
        $this->data['image'] = $user->profile_image;
        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();
        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        // pass the user to the view
        $this->data['user'] = $user;
        $this->data['is_supervisor'] = $is_supervisor;
        $this->data['company_main'] = array(
            'name' => 'company_main',
            'id' => 'company_main',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $this->form_validation->set_value('company_main', $user->company_main),
        );
        $this->data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            //'readonly' => 'true',
            'value' => $this->form_validation->set_value('company', $user->company),
        );
        $promo_code = $this->m_custom->promo_code_get('merchant', $user->id, 1);
        $this->data['promo_code_no'] = array(
            'name' => 'promo_code_no',
            'id' => 'promo_code_no',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $promo_code,
        );
        $this->data['promo_code_url'] = $this->m_custom->generate_promo_code_list_link($promo_code, 33);
        $this->data['me_ssm'] = array(
            'name' => 'me_ssm',
            'id' => 'me_ssm',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $this->form_validation->set_value('me_ssm', $user->me_ssm),
        );
        //If is not changeable then change to text box read only
//        $this->data['category_list'] = $this->ion_auth->get_main_category_list();
//        $this->data['me_category_id'] = array(
//            'name' => 'me_category_id',
//            'id' => 'me_category_id',
//            'value' => $this->form_validation->set_value('me_category_id'),
//        );
        $this->data['me_category_id'] = array(
            'name' => 'me_category_id',
            'id' => 'me_category_id',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $this->m_custom->get_one_table_record('category', 'category_id', $user->me_category_id)->category_label,
        );
        $this->data['sub_category_selected'] = $user->me_sub_category_id;
        $this->data['sub_category_list'] = $this->m_custom->getSubCategoryList(NULL, NULL, $user->me_category_id);
        $this->data['me_sub_category_id'] = array(
            'name' => 'me_sub_category_id',
            'id' => 'me_sub_category_id',
        );
        $this->data['address'] = array(
            'name' => 'address',
            'id' => 'address',
            'readonly' => 'true',
            'value' => $this->form_validation->set_value('address', $user->address),
        );
        $this->data['postcode'] = array(
            'name' => 'postcode',
            'id' => 'postcode',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $this->form_validation->set_value('postcode', $user->postcode),
        );
        $this->data['state_selected'] = $user->me_state_id;
        $this->data['state_list'] = $this->ion_auth->get_static_option_list('state');
        $this->data['me_state_id'] = array(
            'name' => 'me_state_id',
            'id' => 'me_state_id',
            'value' => $this->form_validation->set_value('me_state_id'),
        );
        $this->data['country_selected'] = $user->country;
        $this->data['country_list'] = array('Malaysia'=>'Malaysia');
        $this->data['me_country'] = array(
            'name' => 'me_country',
            'id' => 'me_country',
            'value' => $this->form_validation->set_value('me_country'),
        );
        $this->data['description'] = array(
            'name' => 'description',
            'id' => 'description',
            'value' => $this->form_validation->set_value('description', $user->description),
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $this->data['person_incharge'] = array(
            'name' => 'person_incharge',
            'id' => 'person_incharge',
            'type' => 'text',
            'value' => $this->form_validation->set_value('person_incharge', $user->me_person_incharge),
        );
        $this->data['person_contact'] = array(
            'name' => 'person_contact',
            'id' => 'person_contact',
            'type' => 'text',
            'value' => $this->form_validation->set_value('person_contact', $user->me_person_contact),
        );
        $this->data['website'] = array(
            'name' => 'website',
            'id' => 'website',
            'type' => 'text',
            'value' => $this->form_validation->set_value('website', $user->me_website_url),
        );
        $this->data['facebook_url'] = array(
            'name' => 'facebook_url',
            'id' => 'facebook_url',
            'type' => 'text',
            'value' => $this->form_validation->set_value('facebook_url', $user->me_facebook_url),
        );
        $this->data['branch_name'] = array(
            'name' => 'branch_name',
            'id' => 'branch_name',
            'readonly' => 'true',
            'value' => ($branch) ? $branch->name : '',
        );
        $this->data['branch_address'] = array(
            'name' => 'branch_address',
            'id' => 'branch_address',
            'readonly' => 'true',
            'value' => ($branch) ? $branch->address : '',
        );
        $this->data['branch_phone'] = array(
            'name' => 'branch_phone',
            'id' => 'branch_phone',
            'readonly' => 'true',
            'value' => ($branch) ? $branch->phone : '',
        );
        $this->data['branch_state'] = array(
            'name' => 'branch_state',
            'id' => 'branch_state',
            'readonly' => 'true',
            'value' => ($branch) ? $this->m_custom->display_static_option($branch->state_id) : '',
        );
        $this->data['supervisor_username'] = array(
            'name' => 'supervisor_username',
            'id' => 'supervisor_username',
            'readonly' => 'true',
            'value' => $is_supervisor == 1 ? $supervisor->username : $user->username,
        );
        $this->data['supervisor_password'] = array(
            'name' => 'supervisor_password',
            'id' => 'supervisor_password',
            'readonly' => 'true',
            'value' => $is_supervisor == 1 ? $supervisor->password_visible : $user->password_visible,
        );

        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['page_path_name'] = 'merchant/profile';
        $this->load->view('template/index', $this->data);
    }

    public function update_profile_image()
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $merchant_id = $this->ion_auth->user()->row()->id;
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "change_image")
            {
                $upload_rule = array(
                    'upload_path' => $this->album_merchant_profile,
                    'allowed_types' => $this->config->item('allowed_types_image'),
                    'max_size' => $this->config->item('max_size'),
                    'max_width' => $this->config->item('max_width'),
                    'max_height' => $this->config->item('max_height'),
                );

                $this->load->library('upload', $upload_rule);

                if (!$this->upload->do_upload())
                {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('message', $this->upload->display_errors());
                }
                else
                {
                    $image_data = array('upload_data' => $this->upload->data());
                    //$this->ion_auth->set_message('image_upload_successful');

                    $data = array(
                        'profile_image' => $this->upload->data('file_name'),
                    );

                    if ($this->ion_auth->update($merchant_id, $data))
                    {
                        $this->session->set_flashdata('message', 'Merchant logo success update.');
                    }
                    else
                    {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }
                redirect('all/merchant_dashboard/' . $this->session->userdata('company_slug'), 'refresh');
            }
        }
    }

    public function merchant_redemption_page($show_used = 0)
    {
        if (check_correct_login_type($this->group_id_merchant) || check_correct_login_type($this->group_id_supervisor))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;
            $is_supervisor = 0;
            $supervisor_id = 0;
            if (check_correct_login_type($this->group_id_supervisor))
            {
                $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
                $merchant = $this->m_custom->getUser($merchant_id);
                $is_supervisor = 1;
                $supervisor_id = $this->ion_auth->user()->row()->id;
                $supervisor = $this->m_custom->getUser($supervisor_id);
            }

            $search_word = '';
            if (isset($_POST) && !empty($_POST))
            {
                if ($this->input->post('button_action') == "search")
                {
                    $search_word = $this->input->post('search_word');
                }
            }
            $this->data['search_word'] = $search_word;

            $this->data['title'] = "User Redemption";
            if ($show_used == 1)
            {
                $this->data['title'] = "Redemption Used History";
            }
            else if ($show_used == 2)
            {
                $this->data['title'] = "Redemption Mark As Expired";
            }

            $this->data['show_used'] = $show_used;
            $this->data['promotion_list'] = $this->m_custom->getPromotion($merchant_id, $supervisor_id);

            $this->data['message'] = $this->session->flashdata('message');
            $this->data['page_path_name'] = 'merchant/redemption';
            $this->load->view('template/index', $this->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

    public function redeem_done()
    {
        $current_url = '/';
        if (isset($_POST) && !empty($_POST))
        {
            $current_url = $this->input->post('current_url');
            $redeem_id = $this->input->post('redeem_id');
            $user_id = $this->input->post('user_id');
            $advertise_id = $this->input->post('advertise_id');
            $voucher = $this->input->post('voucher');
            $user_name = $this->m_custom->display_users($user_id);
            if ($this->input->post('button_action') == "submit_used")
            {
                if ($this->m_merchant->user_redemption_done($redeem_id))
                {
                    $this->session->set_flashdata('message', 'Thanks You!!! ' . $voucher . ' voucher approved for ' . $user_name);
                }
                else
                {
                    $this->session->set_flashdata('message', 'Sorry, redeem fail. Please check with admin...');
                }
            }
            else if ($this->input->post('button_action') == "submit_expired")
            {
                if ($this->m_merchant->user_redemption_done($redeem_id, 1))
                {
                    $this->session->set_flashdata('message', 'You mark ' . $voucher . ' voucher for ' . $user_name . ' as expired');
                }
            }
        }
        redirect($current_url, 'refresh');
    }

    function branch()
    {
        $merchant_id = $this->ion_auth->user()->row()->id;
        $allowed_list = $this->m_custom->get_list_of_allow_id('merchant_branch', 'merchant_id', $merchant_id, 'branch_id');
        $check_id = $this->uri->segment(3) == 'success' ? NULL : $this->uri->segment(4);
        if (!check_correct_login_type($this->main_group_id, $allowed_list, $check_id))
        {
            redirect('/', 'refresh');
        }
        $this->load->view('template/layout_management', $this->branch_management());
    }

    function branch_management()
    {
        $merchant_id = $this->ion_auth->user()->row()->id;

        //for supervisor view the branch of merchant
        if (check_correct_login_type($this->group_id_supervisor))
        {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
        }

        $this->load->library('grocery_CRUD');
        try
        {
            $crud = new grocery_CRUD();

            $this->load->library('user_agent');
            if ($this->agent->is_mobile() && $this->agent->is_tablet() === FALSE)
            {
                $crud->set_theme('bootstrap');
                $crud->unset_search();
            }
            else
            {
                $crud->set_theme('datatables');    //datatables, flexigrid, bootstrap
                //$crud->add_action('Add Supervisor', '', 'merchant/supervisor/add','ui-icon-pencil');  //No use this because cannot open new tab
            }

            //($this->agent->is_tablet() === TRUE) ? $is_tablet = "Yes" : $is_tablet = "No"; echo "Using tablet: $is_tablet";

            $crud->set_table('merchant_branch');
            $crud->set_subject('Branch');

            $crud->columns('name', 'address', 'phone', 'state_id', 'supervisor');
            $crud->required_fields('name', 'address', 'state_id');
            $crud->fields('name', 'address', 'phone', 'state_id', 'google_map_url');
            $crud->display_as('state_id', 'State');
            $crud->display_as('google_map_url', 'Google Map Coordinate');
            $crud->unset_fields('merchant_id');
            $crud->unset_texteditor('address', 'google_map_url');
            $crud->field_type('state_id', 'dropdown', $this->ion_auth->get_static_option_list('state'));
            $crud->callback_insert(array($this, 'branch_insert_callback'));
            $crud->callback_add_field('phone', array($this, 'add_field_for_phone'));
            $crud->callback_column('name', array($this, '_branch_map'));
            $crud->callback_column('address', array($this, '_full_text'));
            $crud->callback_column('supervisor', array($this, '_branch_supervisor'));

            $controller = 'merchant';
            $function = 'profile';
            $crud->set_lang_string('insert_success_message', 'Your data has been successfully stored into the database.
		 <script type="text/javascript">
                 var originallocation = window.location.pathname;
                 if(originallocation.indexOf("/branch/add") > -1)
                {
		  window.location = "' . site_url($controller . '/' . $function) . '";
                }
		 </script>
		 <div style="display:none">
		 '
            );
            $crud->set_lang_string('update_success_message', 'Your data has been successfully stored into the database.
		 <script type="text/javascript">
                 var originallocation = window.location.pathname;
                 if(originallocation.indexOf("/branch/edit") > -1)
                {
                window.location = "' . site_url($controller . '/' . $function) . '";
                 }
		 </script>
		 <div style="display:none">
		 '
            );

            $crud->set_lang_string('form_save_and_go_back', 'Save and View Branch');
            $crud->set_lang_string('form_update_and_go_back', 'Update and View Branch');

            $crud->unset_export();
            $crud->unset_print();

            $state = $crud->getState();

            //Temporary use this to skip the bootstrap top right search not working bug after got $crud->where() function
//            if ($state == 'ajax_list') {
//                if (!empty($crud->getStateInfo())) {
//                    $state_info = $crud->getStateInfo();
//                    if (!empty($state_info->search->text)) {
//                        
//                    } else {
//                        $crud->where('merchant_id', $id);
//                    }
//                }
//            } else {
            $crud->where('merchant_id', $merchant_id);
//            }

            if ($state == 'read')
            {
                $crud->set_relation('state_id', 'static_option', '{option_text}');
            }
            $output = $crud->render();
            return $output;
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function add_field_for_phone()
    {
        return '<input type="text" maxlength="50" value="" name="phone" >';
    }

    function _branch_map($value, $row)
    {
        return "<a href='" . base_url() . "all/merchant-map/" . $row->branch_id . "' target='_blank'>" . $row->name . "</a>";
    }

    function _full_text($value, $row)
    {
        return wordwrap($row->address);
    }

    function _branch_supervisor($value, $row)
    {
        return $this->ion_auth->get_branch_supervisor_list($row->branch_id);
    }

    function branch_insert_callback($post_array, $primary_key)
    {
        $post_array['merchant_id'] = $this->ion_auth->user()->row()->id;
        if (check_correct_login_type($this->group_id_supervisor))
        {
            $post_array['merchant_id'] = $this->ion_auth->user()->row()->su_merchant_id;
        }
        return $this->db->insert('merchant_branch', $post_array);
    }

    function supervisor()
    {
        $merchant_id = $this->ion_auth->user()->row()->id;
        $allowed_list = $this->m_custom->get_list_of_allow_id('users', 'su_merchant_id', $merchant_id, 'id');
        $have_exception_segment = 0;
        if ($this->uri->segment(3) == 'success' || $this->uri->segment(3) == 'add')
        {
            $have_exception_segment = 1;
        }
        $check_id = $have_exception_segment == 1 ? NULL : $this->uri->segment(4);
        if (!check_correct_login_type($this->main_group_id, $allowed_list, $check_id))
        {
            redirect('/', 'refresh');
        }
        $this->load->view('template/layout_management', $this->supervisor_management());
    }

    function supervisor_management()
    {
        $id = $this->ion_auth->user()->row()->id;
        $this->load->library('grocery_CRUD');
        try
        {
            $crud = new grocery_CRUD();

            $this->load->library('user_agent');
            if ($this->agent->is_mobile() && $this->agent->is_tablet() === FALSE)
            {
                $crud->set_theme('bootstrap');
                $crud->unset_search();
            }
            else
            {
                $crud->set_theme('datatables');    //datatables, flexigrid, bootstrap             
            }

            $crud->set_table('users');
            $crud->set_subject('Supervisor');
            $crud->columns('username', 'password_visible', 'su_branch_id', 'su_can_uploadhotdeal');
            $crud->required_fields('username', 'password_visible', 'su_branch_id');
            $crud->fields('username', 'password_visible', 'su_branch_id', 'su_can_uploadhotdeal');
            $crud->display_as('password_visible', 'Password');
            $crud->display_as('su_branch_id', 'Branch');
            $crud->display_as('su_can_uploadhotdeal', 'Able to upload picture');
            $crud->callback_add_field('su_branch_id', array($this, '_selected_branch_callback'));   //For add page set pre-selected value if got pass in brach id
            $crud->field_type('su_branch_id', 'dropdown', $this->ion_auth->get_merchant_branch_list($id));  //For view show the branch list text
            $crud->callback_insert(array($this, 'supervisor_insert_callback'));
            $crud->callback_update(array($this, 'supervisor_update_callback'));
            $crud->set_rules('username', 'Username', 'trim|required|callback_supervisor_username_check');
            $crud->field_type('su_can_uploadhotdeal', 'true_false');

            $controller = 'merchant';
            $function = 'profile';
            $crud->set_lang_string('insert_success_message', 'Your data has been successfully stored into the database.
		 <script type="text/javascript">
                 var originallocation = window.location.pathname;
                 if(originallocation.indexOf("/supervisor/add") > -1)
                {
		  window.location = "' . site_url($controller . '/' . $function) . '";
                }
		 </script>
		 <div style="display:none">
		 '
            );
            $crud->set_lang_string('update_success_message', 'Your data has been successfully stored into the database.
		 <script type="text/javascript">
                 var originallocation = window.location.pathname;
                 if(originallocation.indexOf("/supervisor/edit") > -1)
                {
                window.location = "' . site_url($controller . '/' . $function) . '";
                 }
		 </script>
		 <div style="display:none">
		 '
            );

            $crud->set_lang_string('form_save_and_go_back', 'Save and View Supervisor');
            $crud->set_lang_string('form_update_and_go_back', 'Update and View Supervisor');

            $crud->unset_export();
            $crud->unset_print();
            $crud->unset_read();

            $state = $crud->getState();

            //filter that this is supervisor type user and it is under this merchant
            $crud->where('su_merchant_id', $id);
            $crud->where('main_group_id', $this->group_id_supervisor);

            $output = $crud->render();
            return $output;
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function _selected_branch_callback($post_array)
    {
        $id = $this->ion_auth->user()->row()->id;
        $selected = $this->uri->segment(4);
        return form_dropdown('su_branch_id', $this->ion_auth->get_merchant_branch_list($id), $selected);
    }

    function supervisor_insert_callback($post_array, $primary_key)
    {

//        if(!$this->m_custom->check_is_value_unique('users','username',$post_array['username'])){
//            return FALSE;
//        }
        $su_can_uploadhotdeal = $post_array['su_can_uploadhotdeal'] == '1' ? 1 : 0;
        $additional_data = array(
            'username' => $post_array['username'],
            'su_merchant_id' => $this->ion_auth->user()->row()->id,
            'su_branch_id' => $post_array['su_branch_id'],
            'main_group_id' => $this->group_id_supervisor,
            'password_visible' => $post_array['password_visible'],
            'su_can_uploadhotdeal' => $su_can_uploadhotdeal,
        );

        return $this->ion_auth->register($post_array['username'], $post_array['password_visible'], $post_array['username'] . $this->config->item('keppo_email_domain'), $additional_data, $this->group_id_supervisor);
    }

    function supervisor_update_callback($post_array, $primary_key)
    {

//        if(!$this->m_custom->check_is_value_unique('users','username',$post_array['username'],'id',$primary_key)){
//            return FALSE;
//        }
        $su_can_uploadhotdeal = $post_array['su_can_uploadhotdeal'] == '1' ? 1 : 0;
        $additional_data = array(
            'username' => $post_array['username'],
            'email' => $post_array['username'] . $this->config->item('keppo_email_domain'),
            'password' => $post_array['password_visible'],
            'password_visible' => $post_array['password_visible'],
            'su_branch_id' => $post_array['su_branch_id'],
            'su_can_uploadhotdeal' => $su_can_uploadhotdeal,
        );

        return $this->ion_auth->update($primary_key, $additional_data);
    }

    public function supervisor_username_check($str)
    {
        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id))
        {
            $username_old = $this->db->where("id", $id)->get('users')->row()->username;
            $this->db->where("username !=", $username_old);
        }

        $num_row = $this->db->where('username', $str)->get('users')->num_rows();
        if ($num_row >= 1)
        {
            $this->form_validation->set_message('supervisor_username_check', 'The username already exists');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function upload_ssm()
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $merchant_id = $this->ion_auth->user()->row()->id;
        $me_ssm_file = $this->ion_auth->user()->row()->me_ssm_file;
        $this->data['me_ssm_url'] = base_url() . $this->folder_merchant_ssm . $me_ssm_file;
        $this->data['me_ssm_file'] = $me_ssm_file;
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "upload_ssm")
            {
                $upload_rule = array(
                    'upload_path' => $this->folder_merchant_ssm,
                    'allowed_types' => $this->config->item('allowed_types_file'),
                    'max_size' => $this->config->item('max_size'),
                );

                $this->load->library('upload', $upload_rule);

                if (!$this->upload->do_upload())
                {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('message', $this->upload->display_errors());
                }
                else
                {
                    $image_data = array('upload_data' => $this->upload->data());
                    //$this->ion_auth->set_message('image_upload_successful');

                    $data = array(
                        'me_ssm_file' => $this->upload->data('file_name'),
                    );

                    if ($this->ion_auth->update($merchant_id, $data))
                    {
                        $this->session->set_flashdata('message', 'Merchant SSM success update.');
                        redirect('merchant/profile', 'refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }
            }
            else if ($this->input->post('button_action') == "download_ssm")
            {
                $this->load->helper('download');
                $file_path = $this->folder_merchant_ssm . $me_ssm_file;
                force_download($file_path, NULL);
            }
            else
            {
                
            }
        }
        $this->data['page_path_name'] = 'merchant/upload_ssm';
        $this->load->view('template/index', $this->data);
    }

    function candie_promotion($promotion_id = NULL)
    {
        if (!check_correct_login_type($this->main_group_id) && !check_correct_login_type($this->group_id_supervisor))
        {
            redirect('/', 'refresh');
        }
        $message_info = '';
        $merchant_id = $this->ion_auth->user()->row()->id;

        $is_supervisor = 0;

        //if is login by supervisor then need change some setting
        if (check_correct_login_type($this->group_id_supervisor))
        {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
            $is_supervisor = 1;
            $supervisor = $this->ion_auth->user()->row();
        }
        
        $is_history = 0;       
        if ($promotion_id != NULL)
        {
            $allowed_list = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id', 'advertise_type', 'pro');
            if (!check_allowed_list($allowed_list, $promotion_id))
            {
                redirect('/', 'refresh');
            }
            
            //To check if it is history promotion, then disable the Save Button
            $promotionAdvertise = $this->m_custom->getOneAdvertise($promotion_id);
            if (!$promotionAdvertise)
            {
                $is_history = 1;
            }
            else
            {
                if ($promotionAdvertise['year'] < get_part_of_date('year') || ($promotionAdvertise['year'] == get_part_of_date('year') && $promotionAdvertise['month_id'] < get_part_of_date('month')))
                {
                    $is_history = 1;
                }
            }
        }

        $do_by_type = $this->main_group_id;
        $do_by_id = $merchant_id;
        $merchant_data = $this->m_custom->get_one_table_record('users', 'id', $merchant_id);
        $candie_branch = $this->m_custom->get_keyarray_list('merchant_branch', 'merchant_id', $merchant_id, 'branch_id', 'name');
        $candie_term = $this->m_custom->get_dynamic_option_array('candie_term', NULL, NULL, $merchant_data->company, NULL, 0, 3);
        $month_list = $this->ion_auth->get_static_option_list('month');
        $year_list = generate_number_option(get_part_of_date('year', $merchant_data->created_on, 1), get_part_of_date('year'));
        $search_month = NULL;
        $search_year = NULL;      

        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "submit")
            {
                $upload_rule = array(
                    'upload_path' => $this->album_merchant,
                    'allowed_types' => $this->config->item('allowed_types_image'),
                    'max_size' => $this->config->item('max_size'),
                    'max_width' => $this->config->item('max_width'),
                    'max_height' => $this->config->item('max_height'),
                );

                $this->load->library('upload', $upload_rule);

                $candie_id = $this->input->post('candie_id');
                $sub_category_id = $this->input->post('candie_category');
                $title = $this->input->post('candie_title');
                $description = $this->input->post('candie_desc');
                $upload_file = "candie-file";
                $start_date = validateDate($this->input->post('start_date'));
                $end_date = validateDate($this->input->post('end_date'));
                $search_month = $this->input->post('candie_month');
                $search_year = $this->input->post('candie_year');
                $candie_point = check_is_positive_numeric($this->input->post('candie_point'));
                $expire_date = validateDate($this->input->post('expire_date'));
                $show_extra_info = $this->input->post('show_extra_info');
                $price_before = check_is_positive_decimal($this->input->post('price_before'));
                $price_after = check_is_positive_decimal($this->input->post('price_after'));
                //$price_before_show = $this->input->post('price_before_show');
                //$price_after_show = $this->input->post('price_after_show');
                if ($show_extra_info == 121)
                {
                    $price_before_show = 1;
                    $price_after_show = 1;
                }
                else
                {
                    $price_before_show = 0;
                    $price_after_show = 0;
                }
                $get_off_percent = check_is_positive_decimal($this->input->post('get_off_percent'));
                $how_many_buy = check_is_positive_numeric($this->input->post('how_many_buy'));
                $how_many_get = check_is_positive_numeric($this->input->post('how_many_get'));
                $adv_worth = check_is_positive_decimal($this->input->post('adv_worth'));
                $candie_extra_term = $this->input->post('candie_extra_term');
                $image_data = NULL;

                $candie_term_selected = array();      
                $post_candie_term = $this->input->post('candie_term');
                if (!empty($post_candie_term))
                {
                    foreach ($post_candie_term as $key => $value)
                    {
                        $candie_term_selected[] = $value;
                    }
                }

                $candie_branch_selected = array();
                $post_candie_branch = $this->input->post('candie_branch');
                if (!empty($post_candie_branch))
                {
                    foreach ($post_candie_branch as $key => $value)
                    {
                        $candie_branch_selected[] = $value;
                    }
                }

                if ($candie_id == 0)
                {
                    if (!empty($_FILES[$upload_file]['name']))
                    {
                        if (!$this->upload->do_upload($upload_file))
                        {
                            $message_info = add_message_info($message_info, $this->upload->display_errors(), $title);
                        }
                        else
                        {
                            $image_data = array('upload_data' => $this->upload->data());
                        }
                    }
                    $data = array(
                        'advertise_type' => 'pro',
                        'merchant_id' => $merchant_id,
                        'sub_category_id' => $sub_category_id,
                        'title' => $title,
                        'description' => $description,
                        'image' => empty($image_data) ? '' : $image_data['upload_data']['file_name'],
                        'start_time' => $start_date,
                        'end_time' => $end_date,
                        'month_id' => $search_month,
                        'year' => $search_year,
                        //'voucher' => $this->m_merchant->generate_voucher($merchant_id),
                        'voucher_candie' => $candie_point,
                        'voucher_expire_date' => $expire_date,
                        'show_extra_info' => $show_extra_info,
                        'price_before' => $price_before,
                        'price_after' => $price_after,
                        'price_before_show' => $price_before_show,
                        'price_after_show' => $price_after_show,
                        'get_off_percent' => $get_off_percent,
                        'how_many_buy' => $how_many_buy,
                        'how_many_get' => $how_many_get,
                        'voucher_worth' => $adv_worth,
                        'extra_term' => $candie_extra_term,
                    );

                    $new_id = $this->m_custom->get_id_after_insert('advertise', $data);
                    if ($new_id)
                    {
                        $this->m_custom->insert_row_log('advertise', $new_id, $do_by_id, $do_by_type);
                        $this->m_custom->many_insert_or_remove('candie_term', $new_id, $candie_term_selected);
                        $this->m_custom->many_insert_or_remove('candie_branch', $new_id, $candie_branch_selected);
                        $message_info = add_message_info($message_info, 'Candie Promotion for ' . $search_year . ' ' . $this->m_custom->display_static_option($search_month) . ' success create.');
                        $candie_id = $new_id;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                    }
                }
                else
                {
                    $previous_image_name = $this->m_custom->get_one_table_record('advertise', 'advertise_id', $candie_id)->image;

                    //To check old deal got change image or not, if got then upload the new one and delete previous image
                    if (!empty($_FILES[$upload_file]['name']))
                    {

                        if (!$this->upload->do_upload($upload_file))
                        {
                            $message_info = add_message_info($message_info, $this->upload->display_errors());
                        }
                        else
                        {
                            $image_data = array('upload_data' => $this->upload->data());
                            if (!IsNullOrEmptyString($previous_image_name))
                            {
                                delete_file($this->album_merchant . $previous_image_name);
                            }
                        }
                    }

                    //To update previous hot deal
                    $data = array(
                        'sub_category_id' => $sub_category_id,
                        'title' => $title,
                        'description' => $description,
                        'image' => empty($image_data) ? $previous_image_name : $image_data['upload_data']['file_name'],
                        'start_time' => $start_date,
                        'end_time' => $end_date,
                        'voucher_candie' => $candie_point,
                        'voucher_expire_date' => $expire_date,
                        'show_extra_info' => $show_extra_info,
                        'price_before' => $price_before,
                        'price_after' => $price_after,
                        'price_before_show' => $price_before_show,
                        'price_after_show' => $price_after_show,
                        'get_off_percent' => $get_off_percent,
                        'how_many_buy' => $how_many_buy,
                        'how_many_get' => $how_many_get,
                        'voucher_worth' => $adv_worth,
                        'extra_term' => $candie_extra_term,
                    );

                    if ($this->m_custom->simple_update('advertise', $data, 'advertise_id', $candie_id))
                    {
                        $this->m_custom->update_row_log('advertise', $candie_id, $do_by_id, $do_by_type);
                        $this->m_custom->many_insert_or_remove('candie_term', $candie_id, $candie_term_selected);
                        $this->m_custom->many_insert_or_remove('candie_branch', $candie_id, $candie_branch_selected);
                        $message_info = add_message_info($message_info, 'Candie Promotion for ' . $search_year . ' ' . $this->m_custom->display_static_option($search_month) . ' success update.');
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                    }
                }
                $this->session->set_flashdata('message', $message_info);
                redirect('merchant/candie_promotion/' . $candie_id, 'refresh');
            }
            else if ($this->input->post('button_action') == "search_voucher")
            {
                $search_month = $this->input->post('candie_month');
                $search_year = $this->input->post('candie_year');
                if ($search_year < get_part_of_date('year') ||
                        ($search_year == get_part_of_date('year') && $search_month < get_part_of_date('month')) ||
                        ($search_year == get_part_of_date('year') && $search_month > (get_part_of_date('month') + 1)))
                {
                    $is_history = 1;
                }
                $promotion_id = NULL;
            }          
            if ($this->input->post('button_action') == "frozen_hotdeal")
            {
                $promotion_id = $this->input->post('promotion_id');
                $this->m_custom->update_frozen_flag(1, 'advertise', $promotion_id);
                $message_info = add_message_info($message_info, 'Candie Voucher success frozen.');
                $this->session->set_flashdata('message', $message_info);
                redirect('merchant/candie_promotion/' . $promotion_id, 'refresh');   
            }
            if ($this->input->post('button_action') == "unfrozen_hotdeal")
            {
                $promotion_id = $this->input->post('promotion_id');
                $this->m_custom->update_frozen_flag(0, 'advertise', $promotion_id);
                $message_info = add_message_info($message_info, 'Candie Voucher success unfrozen.');
                $this->session->set_flashdata('message', $message_info);
                redirect('merchant/candie_promotion/' . $promotion_id, 'refresh');   
            }              
        }

        //To get this month candie promotion if already create before
        $this_month_candie = $this->m_merchant->get_merchant_monthly_promotion($merchant_id, $search_month, $search_year, $promotion_id);
        $this->data['is_history'] = $is_history;
        $this->data['candie_term_current'] = empty($this_month_candie) ? array() : $this->m_custom->many_get_childlist('candie_term', $this_month_candie['advertise_id']);
        $this->data['candie_branch_current'] = empty($this_month_candie) ? array() : $this->m_custom->many_get_childlist('candie_branch', $this_month_candie['advertise_id']);

        $this->data['candie_id'] = array(
            'candie_id' => empty($this_month_candie) ? '0' : $this_month_candie['advertise_id'],
            'current_month' => get_part_of_date('month'),
        );

        $this->data['sub_category_list'] = $this->ion_auth->get_sub_category_list($merchant_data->me_category_id);
        $this->data['candie_category'] = array(
            'name' => 'candie_category',
            'id' => 'candie_category',
        );
        $this->data['candie_category_selected'] = empty($this_month_candie) ? $merchant_data->me_sub_category_id : $this_month_candie['sub_category_id'];

        $this->data['candie_title'] = array(
            'name' => 'candie_title',
            'id' => 'candie_title',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['title'],
        );

        $this->data['candie_desc'] = array(
            'name' => 'candie_desc',
            'id' => 'candie_desc',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['description'],
        );

        $this->data['candie_image'] = empty($this_month_candie) ? $this->config->item('empty_image') : $this->album_merchant . $this_month_candie['image'];

        $this->data['start_date'] = array(
            'name' => 'start_date',
            'id' => 'start_date',
            'readonly' => 'true',
            'value' => empty($this_month_candie) ? '' : displayDate($this_month_candie['start_time']),
        );

        $this->data['end_date'] = array(
            'name' => 'end_date',
            'id' => 'end_date',
            'readonly' => 'true',
            'value' => empty($this_month_candie) ? '' : displayDate($this_month_candie['end_time']),
        );

        $this->data['year_list'] = $year_list;
        $this->data['candie_year'] = array(
            'name' => 'candie_year',
            'id' => 'candie_year',
        );
        $this->data['candie_year_selected'] = empty($search_year) ? get_part_of_date('year') : $search_year;

        $this->data['month_list'] = $month_list;
        $this->data['candie_month'] = array(
            'name' => 'candie_month',
            'id' => 'candie_month',
        );
        $this->data['candie_month_selected'] = empty($search_month) ? get_part_of_date('month') : $search_month;

        $this->data['candie_point'] = array(
            'name' => 'candie_point',
            'id' => 'candie_point',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['voucher_candie'],
        );

        $this->data['expire_date'] = array(
            'name' => 'expire_date',
            'id' => 'expire_date',
            'readonly' => 'true',
            'value' => empty($this_month_candie) ? '' : displayDate($this_month_candie['voucher_expire_date']),
        );

        $this->data['show_extra_info_list'] = $this->m_custom->get_static_option_array('adv_extra_info', '0', 'Select Extra Info To Show', 0, NULL, 1);
        $this->data['show_extra_info'] = array(
            'name' => 'show_extra_info',
            'id' => 'show_extra_info',
            'onchange' => 'showextrainfodiv()',
        );
        $this->data['show_extra_info_selected'] = empty($this_month_candie) ? '' : $this_month_candie['show_extra_info'];
        
        $this->data['promotion_price_before'] = array(
            'name' => 'price_before',
            'id' => 'price_before',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['price_before'],
            'onkeypress' => 'return isNumber(event)',
        );
        
        $this->data['promotion_price_after'] = array(
            'name' => 'price_after',
            'id' => 'price_after',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['price_after'],
            'onkeypress' => 'return isNumber(event)',
        );
        
        $price_before_show = $this_month_candie['price_before_show'];
        $this->data['price_before_show'] = array(
            'name' => 'price_before_show',
            'id' => 'price_before_show',
            'checked' => $price_before_show == "1"? TRUE : FALSE,        
            'value' => empty($this_month_candie) ? '' : $this_month_candie['advertise_id'],
        );
        
        $price_after_show = $this_month_candie['price_after_show'];
        $this->data['price_after_show'] = array(
            'name' => 'price_after_show',
            'id' => 'price_after_show',
            'checked' => $price_after_show == "1"? TRUE : FALSE,      
            'value' => empty($this_month_candie) ? '' : $this_month_candie['advertise_id'],
        );
        
        $this->data['get_off_percent'] = array(
            'name' => 'get_off_percent',
            'id' => 'get_off_percent',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['get_off_percent'],
            'onkeypress' => 'return isNumber(event)',
        );
        
        $this->data['how_many_buy'] = array(
            'name' => 'how_many_buy',
            'id' => 'how_many_buy',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['how_many_buy'],
            'onkeypress' => 'return isNumber(event)',
            'style' => 'width:70px',
        );
        
        $this->data['how_many_get'] = array(
            'name' => 'how_many_get',
            'id' => 'how_many_get',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['how_many_get'],
            'onkeypress' => 'return isNumber(event)',
            'style' => 'width:70px',
        );
        
        $this->data['adv_worth'] = array(
            'name' => 'adv_worth',
            'id' => 'adv_worth',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['voucher_worth'],
            'onkeypress' => 'return isNumber(event)',
        );
        
        $this->data['extra_term'] = array(
            'name' => 'candie_extra_term',
            'id' => 'candie_extra_term',
            'value' => empty($this_month_candie) ? '' : $this_month_candie['extra_term'],
            'cols' => 90,
            'placeholder' => 'Add extra T&C seperate by Enter, one line one T&C',
        );

        $this->data['promotion_id'] = empty($this_month_candie) ? '' : $this_month_candie['advertise_id'];
        $this->data['promotion_frozen'] = empty($this_month_candie) ? '' : $this_month_candie['frozen_flag'];
        
        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['candie_term'] = $candie_term;
        $this->data['candie_branch'] = $candie_branch;
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'merchant/candie_promotion';
        $this->load->view('template/index', $this->data);
    }

    function edit_hotdeal($hotdeal_id = NULL)
    {
        if (!check_correct_login_type($this->main_group_id) && !check_correct_login_type($this->group_id_supervisor))
        {
            redirect('/', 'refresh');
        }
        $message_info = '';
        $merchant_id = $this->ion_auth->user()->row()->id;
        $do_by_type = $this->main_group_id;
        $do_by_id = $merchant_id;
        $is_supervisor = 0;

        //if is login by supervisor then need change some setting
        if (check_correct_login_type($this->group_id_supervisor))
        {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
            $is_supervisor = 1;
            $supervisor = $this->ion_auth->user()->row();
            $do_by_type = $this->group_id_supervisor;
        }

        $allowed_list = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id', 'advertise_type', 'hot');
        if (!check_allowed_list($allowed_list, $hotdeal_id))
        {
            redirect('/', 'refresh');
        }

        $merchant_data = $this->m_custom->get_one_table_record('users', 'id', $merchant_id);

        if (isset($_POST) && !empty($_POST))
        {
            if (IsNullOrEmptyString($hotdeal_id))
            {
                redirect('merchant/upload_hotdeal', 'refresh');
            }
            $upload_rule = array(
                'upload_path' => $this->album_merchant,
                'allowed_types' => $this->config->item('allowed_types_image'),
                'max_size' => $this->config->item('max_size'),
                'max_width' => $this->config->item('max_width'),
                'max_height' => $this->config->item('max_height'),
            );

            $this->load->library('upload', $upload_rule);

            $hotdeal_id = $this->input->post('hotdeal_id');
            $hotdeal_file = "hotdeal-file";

            $sub_category_id = $this->input->post('category');
            $title = $this->input->post('title');
            $description = $this->input->post('desc');
            $hotdeal_hour = check_is_positive_numeric($this->input->post('hour'));
            $hotdeal_hour = $hotdeal_hour * 24;
            $hotdeal_price_before = check_is_positive_decimal($this->input->post('price_before'));
            $hotdeal_price_after = check_is_positive_decimal($this->input->post('price_after'));
            $price_before_show = $this->input->post('price_before_show');
            $price_after_show = $this->input->post('price_after_show');
            
            if ($this->input->post('button_action') == "edit_hotdeal")
            {
                if ($hotdeal_hour > 1440)
                {
                    $message_info = add_message_info($message_info, 'Hot Deal please put in a valid hour between 0 to 60(Max 2 months only).', $title);
                    $hotdeal_hour = 0;
                }

                $image_data = NULL;
                $previous_image_name = $this->m_custom->get_one_table_record('advertise', 'advertise_id', $hotdeal_id)->image;

                //To check old deal got change image or not, if got then upload the new one and delete previous image
                if (!empty($_FILES[$hotdeal_file]['name']))
                {

                    if (!$this->upload->do_upload($hotdeal_file))
                    {
                        //$error = array('error' => $this->upload->display_errors());
                        $message_info = add_message_info($message_info, $this->upload->display_errors(), $title);
                    }
                    else
                    {
                        $image_data = array('upload_data' => $this->upload->data());
                        if (!IsNullOrEmptyString($previous_image_name))
                        {
                            delete_file($this->album_merchant . $previous_image_name);
                        }
                    }
                }

                $previous_start_time = $this->m_custom->get_one_table_record('advertise', 'advertise_id', $hotdeal_id)->start_time;

                //To update previous hot deal
                $data = array(
                    'sub_category_id' => $sub_category_id,
                    'title' => $title,
                    'description' => $description,
                    'image' => empty($image_data) ? $previous_image_name : $image_data['upload_data']['file_name'],
                    'post_hour' => $hotdeal_hour,
                    'price_before' => $hotdeal_price_before,
                    'price_after' => $hotdeal_price_after,
                    'price_before_show' => $price_before_show,
                    'price_after_show' => $price_after_show,
                    'end_time' => $hotdeal_hour == 0 ? add_hour_to_date(99999, $previous_start_time) : add_hour_to_date($hotdeal_hour, $previous_start_time),
                );

                if ($this->m_custom->simple_update('advertise', $data, 'advertise_id', $hotdeal_id))
                {
                    $this->m_custom->update_row_log('advertise', $hotdeal_id, $do_by_id, $do_by_type);
                    $message_info = add_message_info($message_info, 'Hot Deal success update.', $title);
                }
                else
                {
                    $message_info = add_message_info($message_info, $this->ion_auth->errors(), $title);
                }
                $this->session->set_flashdata('message', $message_info);
                redirect('all/advertise/' . $hotdeal_id, 'refresh');
            }
            if ($this->input->post('button_action') == "remove_hotdeal")
            {
                $data = array(
                    'hide_flag' => 1,
                );
                if ($this->m_custom->simple_update('advertise', $data, 'advertise_id', $hotdeal_id))
                {
                    $this->m_custom->remove_row_log('advertise', $hotdeal_id, $do_by_id, $do_by_type);
                    $message_info = add_message_info($message_info, 'Hot Deal success remove.', $title);
                    $this->session->set_flashdata('message', $message_info);
                    //redirect('merchant/upload_hotdeal', 'refresh');
                    redirect('all/merchant_dashboard/' . $this->session->userdata('company_slug'), 'refresh');
                }
                else
                {
                    $message_info = add_message_info($message_info, $this->ion_auth->errors(), $title);
                }
            }
            if ($this->input->post('button_action') == "frozen_hotdeal")
            {
                $this->m_custom->update_frozen_flag(1, 'advertise', $hotdeal_id);
                $message_info = add_message_info($message_info, 'Hot Deal success frozen.', $title);
            }
            if ($this->input->post('button_action') == "unfrozen_hotdeal")
            {
                $this->m_custom->update_frozen_flag(0, 'advertise', $hotdeal_id);
                $message_info = add_message_info($message_info, 'Hot Deal success unfrozen.', $title);
            }
            $this->session->set_flashdata('message', $message_info);
        }

        $hotdeal_result = $this->m_custom->getOneAdvertise($hotdeal_id);

        $this->data['sub_category_list'] = $this->ion_auth->get_sub_category_list($merchant_data->me_category_id);
        $this->data['hotdeal_date'] = empty($hotdeal_result) ? '' : displayDate($hotdeal_result['start_time']);

        $this->data['hotdeal_title'] = array(
            'name' => 'title',
            'id' => 'title',
            'value' => empty($hotdeal_result) ? '' : $hotdeal_result['title'],
        );

        $this->data['hotdeal_image'] = empty($hotdeal_result) ? $this->config->item('empty_image') : $this->album_merchant . $hotdeal_result['image'];

        $this->data['hotdeal_category'] = array(
            'name' => 'category',
            'id' => 'category',
        );

        $this->data['hotdeal_category_selected'] = empty($hotdeal_result) ? '' : $hotdeal_result['sub_category_id'];

        $this->data['hotdeal_desc'] = array(
            'name' => 'desc',
            'id' => 'desc',
            'value' => empty($hotdeal_result) ? '' : $hotdeal_result['description'],
        );

        $this->data['hotdeal_hour'] = array(
            'name' => 'hour',
            'id' => 'hour',
            'value' => empty($hotdeal_result) ? '' : ($hotdeal_result['post_hour']/24),
            'onkeypress' => 'return isNumber(event)',
        );

        $this->data['hotdeal_price_before'] = array(
            'name' => 'price_before',
            'id' => 'price_before',
            'value' => empty($hotdeal_result) ? '' : $hotdeal_result['price_before'],
            'onkeypress' => 'return isNumber(event)',
        );
        
        $this->data['hotdeal_price_after'] = array(
            'name' => 'price_after',
            'id' => 'price_after',
            'value' => empty($hotdeal_result) ? '' : $hotdeal_result['price_after'],
            'onkeypress' => 'return isNumber(event)',
        );
        
        $price_before_show = $hotdeal_result['price_before_show'];
        $this->data['price_before_show'] = array(
            'name' => 'price_before_show',
            'id' => 'price_before_show',
            'checked' => $price_before_show == "1"? TRUE : FALSE,        
            'value' => empty($hotdeal_result) ? '' : $hotdeal_result['advertise_id'],
        );
        
        $price_after_show = $hotdeal_result['price_after_show'];
        $this->data['price_after_show'] = array(
            'name' => 'price_after_show',
            'id' => 'price_after_show',
            'checked' => $price_after_show == "1"? TRUE : FALSE,      
            'value' => empty($hotdeal_result) ? '' : $hotdeal_result['advertise_id'],
        );
        
        $advertise_id = empty($hotdeal_result) ? '0' : $hotdeal_result['advertise_id'];
        $this->data['advertise_id_value'] = $advertise_id;

        $this->data['hotdeal_id'] = array(
            'hotdeal_id' => $advertise_id,
        );

//        $this->data['hotdeal_hide'] = array(
//            'name' => 'hotdeal_hide',
//            'id' => 'hotdeal_hide',
//            'value' => $advertise_id,
//        );

        $this->data['hotdeal_frozen'] = empty($hotdeal_result) ? '' : $hotdeal_result['frozen_flag'];

        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'merchant/edit_hotdeal';
        $this->load->view('template/index', $this->data);
    }

    function remove_mua_picture()
    {
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "hide_picture")
            {
                $login_id = $this->ion_auth->user()->row()->id;
                $login_type = $this->session->userdata('user_group_id');
                $merchant_id = $login_id;
                if (check_correct_login_type($this->group_id_supervisor))
                {
                    $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
                }
                $picture_id = $this->input->post('hid_picture_id');
                $upload_by_user_id = $this->input->post('hid_upload_by_user_id');
                $hide_remark = $this->input->post('hide_remark');
                $group_id_merchant = $this->config->item('group_id_merchant');
                $group_id_supervisor = $this->config->item('group_id_supervisor');
                $merchant_allowed_list = $this->m_custom->get_list_of_allow_id('merchant_user_album', 'merchant_id', $merchant_id, 'merchant_user_album_id', 'post_type', 'mer');

                if (check_allowed_list($merchant_allowed_list, $picture_id))
                {
                    $merchant = $this->m_custom->getMerchantInfo($merchant_id);
                    $data = array(
                        'hide_flag' => 1,
                        'hide_remark' => $hide_remark,
                    );
                    if ($this->m_custom->simple_update('merchant_user_album', $data, 'merchant_user_album_id', $picture_id))
                    {
                        $this->m_custom->remove_row_log('merchant_user_album', $picture_id, $login_id, $login_type);
                        $this->m_merchant->mua_hide($picture_id);
                        $this->m_user->user_trans_history_insert($upload_by_user_id, 22, $picture_id);
                    }
                    redirect('all/merchant_dashboard/' . $merchant['slug'] . "/picture", 'refresh');
                }
            }
        }
        redirect('/', 'refresh');
    }

    function upload_hotdeal()
    {
        if (!check_correct_login_type($this->main_group_id) && !check_correct_login_type($this->group_id_supervisor))
        {
            redirect('/', 'refresh');
        }
        $message_info = '';
        $merchant_id = $this->ion_auth->user()->row()->id;
        $do_by_type = $this->main_group_id;
        $do_by_id = $merchant_id;   //merchant or supervisor also can use this assign because this is depend on login
        //if is login by supervisor then need change some setting
        if (check_correct_login_type($this->group_id_supervisor))
        {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
            $do_by_type = $this->group_id_supervisor;
        }

        $merchant_data = $this->m_custom->get_one_table_record('users', 'id', $merchant_id);
        $hotdeal_per_day = $this->m_custom->web_setting_get('merchant_max_hotdeal_per_day');
        $search_date = NULL;
        //$search_date = '31-08-2015';
        //$search_date = toggle_date_format($search_date);

        //If more then 5 active hotdeal for today uploaded already, auto increase 1 more upload box
        $box_number_update = $this->box_number;
        $hotdeal_today_count = $this->m_merchant->get_merchant_today_hotdeal($merchant_id, 1, $search_date);
        if($hotdeal_today_count >= $box_number_update){
            $box_number_update = $hotdeal_today_count + 1;
        }
        
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "upload_hotdeal")
            {
                $upload_rule = array(
                    'upload_path' => $this->album_merchant,
                    'allowed_types' => $this->config->item('allowed_types_image'),
                    'max_size' => $this->config->item('max_size'),
                    'max_width' => $this->config->item('max_width'),
                    'max_height' => $this->config->item('max_height'),
                );

                $this->load->library('upload', $upload_rule);

                //To loop hotdeal box dynamic
                for ($i = 0; $i < $box_number_update; $i++)
                {

                    $hotdeal_today_count_update = $this->m_merchant->get_merchant_today_hotdeal($merchant_id, 1, $search_date, 1);

                    $hotdeal_id = $this->input->post('hotdeal_id-' . $i);
                    $hotdeal_file = "hotdeal-file-" . $i;

                    $sub_category_id = $this->input->post('category-' . $i);
                    $title = $this->input->post('title-' . $i);
                    $description = $this->input->post('desc-' . $i);
                    $hotdeal_hour = check_is_positive_numeric($this->input->post('hour-' . $i));
                    $hotdeal_hour = $hotdeal_hour * 24;
                    $hotdeal_price_before = check_is_positive_decimal($this->input->post('price_before-' . $i));
                    $hotdeal_price_after = check_is_positive_decimal($this->input->post('price_after-' . $i));
                    $price_before_show = $this->input->post('price_before_show-' . $i) == null? 0 : 1;
                    $price_after_show = $this->input->post('price_after_show-' . $i) == null? 0 : 1;

                    if ($hotdeal_hour > 1440)
                    {
                        $message_info = add_message_info($message_info, 'Hot Deal please put in a valid hour between 0 to 60(Max 2 months only).', $title);
                        $hotdeal_hour = 0;
                    }

                    //To check is this an old hot deal or new hot deal, if new hot deal is 0
                    if ($hotdeal_id == 0)
                    {
                        if ($hotdeal_today_count_update >= $hotdeal_per_day)
                        {
                            $message_info = add_message_info($message_info, 'Already reach max ' . $hotdeal_per_day . ' hot deal per day.');
                            //redirect('merchant/upload_hotdeal', 'refresh');
                            goto direct_go;
                        }

                        //To check new hot deal is it got image upload or not
                        if (!empty($_FILES[$hotdeal_file]['name']))
                        {

                            if (!$this->upload->do_upload($hotdeal_file))
                            {
                                //$error = array('error' => $this->upload->display_errors());
                                $message_info = add_message_info($message_info, $this->upload->display_errors(), $title);
                            }
                            else
                            {
                                $image_data = array('upload_data' => $this->upload->data());
                                $data = array(
                                    'advertise_type' => 'hot',
                                    'merchant_id' => $merchant_id,
                                    'sub_category_id' => $sub_category_id,
                                    'title' => $title,
                                    'description' => $description,
                                    'image' => $image_data['upload_data']['file_name'],
                                    'post_hour' => $hotdeal_hour,
                                    'price_before' => $hotdeal_price_before,
                                    'price_after' => $hotdeal_price_after,
                                    'price_before_show' => $price_before_show,
                                    'price_after_show' => $price_after_show,
                                    'start_time' => get_part_of_date('all'),
                                    'end_time' => $hotdeal_hour == 0 ? add_hour_to_date(99999) : add_hour_to_date($hotdeal_hour),
                                    'month_id' => get_part_of_date('month'),
                                    'year' => get_part_of_date('year'),
                                );

                                $new_id = $this->m_custom->get_id_after_insert('advertise', $data);
                                if ($new_id)
                                {
                                    $this->m_custom->insert_row_log('advertise', $new_id, $do_by_id, $do_by_type);
                                    if ($do_by_type == $this->group_id_supervisor)
                                    {
                                        $this->m_custom->notification_process('advertise', $new_id);
                                    }
                                    $message_info = add_message_info($message_info, 'Hot Deal success create.', $title);
                                }
                                else
                                {
                                    $message_info = add_message_info($message_info, $this->ion_auth->errors(), $title);
                                }
                            }
                        }
                    }
                    else
                    {
                        $image_data = NULL;
                        $previous_image_name = $this->m_custom->get_one_table_record('advertise', 'advertise_id', $hotdeal_id)->image;

                        //To check old deal got change image or not, if got then upload the new one and delete previous image
                        if (!empty($_FILES[$hotdeal_file]['name']))
                        {

                            if (!$this->upload->do_upload($hotdeal_file))
                            {
                                //$error = array('error' => $this->upload->display_errors());
                                $message_info = add_message_info($message_info, $this->upload->display_errors(), $title);
                            }
                            else
                            {
                                $image_data = array('upload_data' => $this->upload->data());
                                if (!IsNullOrEmptyString($previous_image_name))
                                {
                                    delete_file($this->album_merchant . $previous_image_name);
                                }
                            }
                        }

                        $previous_start_time = $this->m_custom->get_one_table_record('advertise', 'advertise_id', $hotdeal_id)->start_time;

                        //To update previous hot deal
                        $data = array(
                            'sub_category_id' => $sub_category_id,
                            'title' => $title,
                            'description' => $description,
                            'image' => empty($image_data) ? $previous_image_name : $image_data['upload_data']['file_name'],
                            'post_hour' => $hotdeal_hour,
                            'price_before' => $hotdeal_price_before,
                            'price_after' => $hotdeal_price_after,
                            'price_before_show' => $price_before_show,
                            'price_after_show' => $price_after_show,
                            'end_time' => $hotdeal_hour == 0 ? add_hour_to_date(99999, $previous_start_time) : add_hour_to_date($hotdeal_hour, $previous_start_time),
                        );

                        $hotdeal_hide = $this->input->post('hotdeal_hide-' . $i);

                        if ($hotdeal_hide == null)
                        {
                            if ($this->m_custom->simple_update('advertise', $data, 'advertise_id', $hotdeal_id))
                            {
                                $this->m_custom->update_row_log('advertise', $hotdeal_id, $do_by_id, $do_by_type);
                                $message_info = add_message_info($message_info, 'Hot Deal success update.', $title);
                            }
                            else
                            {
                                $message_info = add_message_info($message_info, $this->ion_auth->errors(), $title);
                            }
                        }
                        else
                        {
                            //If this hot deal is being remove by tick the remove check box
                            $data = array(
                                'hide_flag' => 1,
                            );
                            if ($this->m_custom->simple_update('advertise', $data, 'advertise_id', $hotdeal_id))
                            {
                                $this->m_custom->remove_row_log('advertise', $hotdeal_id, $do_by_id, $do_by_type);
                                $this->m_merchant->hotdeal_hide($hotdeal_id);
                                $message_info = add_message_info($message_info, 'Hot Deal success remove.', $title);
                            }
                            else
                            {
                                $message_info = add_message_info($message_info, $this->ion_auth->errors(), $title);
                            }
                        }
                    }
                }
                direct_go:
                $this->session->set_flashdata('message', $message_info);
                $this->m_custom->remove_image_temp();
                //redirect('merchant/upload_hotdeal', 'refresh');
                redirect('all/merchant_dashboard/' . $this->session->userdata('company_slug'), 'refresh');
            }
        }

        //To get today hot deal result row
        $hotdeal_today_result = $this->m_merchant->get_merchant_today_hotdeal($merchant_id, 0, $search_date);
        $this->data['hotdeal_today_count'] = $this->m_merchant->get_merchant_today_hotdeal($merchant_id, 1, $search_date, 1);
        $this->data['hotdeal_today_count_removed'] = $this->m_merchant->get_merchant_today_hotdeal_removed($merchant_id, $search_date);
        //$this->data['hour_list'] = generate_number_option(1, 24);
        $this->data['sub_category_list'] = $this->ion_auth->get_sub_category_list($merchant_data->me_category_id);

        //To dynamic create the hot deal box
        for ($i = 0; $i < $box_number_update; $i++)
        {
            $hotdeal_title = 'hotdeal_title' . $i;
            $this->data[$hotdeal_title] = array(
                'name' => 'title-' . $i,
                'id' => 'title-' . $i,
                'value' => empty($hotdeal_today_result[$i]) ? '' : $hotdeal_today_result[$i]['title'],
            );

            $hotdeal_image = 'hotdeal_image' . $i;
            $this->data[$hotdeal_image] = empty($hotdeal_today_result[$i]) ? $this->config->item('empty_image') : $this->album_merchant . $hotdeal_today_result[$i]['image'];

            $hotdeal_category = 'hotdeal_category' . $i;
            $this->data[$hotdeal_category] = array(
                'name' => 'category-' . $i,
                'id' => 'category-' . $i,
            );

            $hotdeal_category_selected = 'hotdeal_category_selected' . $i;
            $this->data[$hotdeal_category_selected] = empty($hotdeal_today_result[$i]) ? $merchant_data->me_sub_category_id : $hotdeal_today_result[$i]['sub_category_id'];

            $hotdeal_desc = 'hotdeal_desc' . $i;
            $this->data[$hotdeal_desc] = 'desc-' . $i;
            $hotdeal_desc_value = 'hotdeal_desc_value' . $i;
            $this->data[$hotdeal_desc_value] = empty($hotdeal_today_result[$i]) ? '' : $hotdeal_today_result[$i]['description'];
//            $hotdeal_desc = 'hotdeal_desc' . $i;
//            $this->data[$hotdeal_desc] = array(
//                'name' => 'desc-' . $i,
//                'id' => 'desc-' . $i,
//                'value' => empty($hotdeal_today_result[$i]) ? '' : $hotdeal_today_result[$i]['description'],
//            );

            $hotdeal_hour = 'hotdeal_hour' . $i;
            $this->data[$hotdeal_hour] = array(
                'name' => 'hour-' . $i,
                'id' => 'hour-' . $i,
                'value' => empty($hotdeal_today_result[$i]) ? '' : ($hotdeal_today_result[$i]['post_hour']/24),
                'onkeypress' => 'return isNumber(event)',
            );

            //$hotdeal_hour_selected = 'hotdeal_hour_selected' . $i;
            //$this->data[$hotdeal_hour_selected] = empty($hotdeal_today_result[$i]) ? '' : $hotdeal_today_result[$i]['post_hour'];

            $hotdeal_price_before = 'hotdeal_price_before' . $i;
            $this->data[$hotdeal_price_before] = array(
                'name' => 'price_before-'. $i,
                'id' => 'price_before-'. $i,
                'value' => empty($hotdeal_today_result[$i]) ? '' : $hotdeal_today_result[$i]['price_before'],
                'onkeypress' => 'return isNumber(event)',
            );

            $hotdeal_price_after = 'hotdeal_price_after' . $i;
            $this->data[$hotdeal_price_after] = array(
                'name' => 'price_after-'. $i,
                'id' => 'price_after-'. $i,
                'value' => empty($hotdeal_today_result[$i]) ? '' : $hotdeal_today_result[$i]['price_after'],
                'onkeypress' => 'return isNumber(event)',
            );

            $price_before_show = 'price_before_show' . $i;
            $price_before_show_value = empty($hotdeal_today_result[$i]) ? '' : $hotdeal_today_result[$i]['price_before_show'];
            $this->data[$price_before_show] = array(
                'name' => 'price_before_show-'. $i,
                'id' => 'price_before_show-'. $i,
                'checked' => $price_before_show_value == "1" ? TRUE : FALSE,
                'value' => empty($hotdeal_today_result[$i]) ? '99' : $hotdeal_today_result[$i]['advertise_id'],
            );

            $price_after_show = 'price_after_show' . $i;
            $price_after_show_value = empty($hotdeal_today_result[$i]) ? '' : $hotdeal_today_result[$i]['price_after_show'];
            $this->data[$price_after_show] = array(
                'name' => 'price_after_show-'. $i,
                'id' => 'price_after_show-'. $i,
                'checked' => $price_after_show_value == "1" ? TRUE : FALSE,
                'value' => empty($hotdeal_today_result[$i]) ? '99' : $hotdeal_today_result[$i]['advertise_id'],
            );

            $advertise_id = empty($hotdeal_today_result[$i]) ? '0' : $hotdeal_today_result[$i]['advertise_id'];
            $advertise_id_value = 'advertise_id_value' . $i;
            $this->data[$advertise_id_value] = $advertise_id;

            $hotdeal_id = 'hotdeal_id' . $i;
            $this->data[$hotdeal_id] = array(
                'hotdeal_id-' . $i => $advertise_id,
            );

            $hotdeal_hide = 'hotdeal_hide' . $i;
            $this->data[$hotdeal_hide] = array(
                'name' => 'hotdeal_hide-' . $i,
                'id' => 'hotdeal_hide-' . $i,
                'value' => $advertise_id,
            );
        }

        $this->data['box_number'] = $box_number_update;
        $this->data['hotdeal_per_day'] = $this->m_custom->web_setting_get('merchant_max_hotdeal_per_day');
        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'merchant/upload_hotdeal';
        $this->load->view('template/index', $this->data);
    }

    // edit a user, no use
    function edit_user($id)
    {
        $this->data['title'] = "Edit User";

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
        {
            redirect('merchant', 'refresh');
        }

        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();
        $tables = $this->config->item('tables', 'ion_auth');

        // validate form input
        $this->form_validation->set_rules('username', $this->lang->line('edit_user_validation_username_label'), 'required|is_unique_edit[' . $tables['users'] . '.username.' . $id . ']');
        $this->form_validation->set_rules('email', $this->lang->line('edit_user_validation_email_label'), 'required|valid_email|is_unique_edit[' . $tables['users'] . '.email.' . $id . ']');
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
        $this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

        if (isset($_POST) && !empty($_POST))
        {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
            {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password'))
            {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE)
            {
                $username = strtolower($this->input->post('username'));
                $email = strtolower($this->input->post('email'));

                $data = array(
                    'username' => $username,
                    'email' => $email,
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone'),
                );

                // update the password if it was posted
                if ($this->input->post('password'))
                {
                    $data['password'] = $this->input->post('password');
                    $data['password_visible'] = $this->input->post('password');
                }



                // Only allow updating groups if user is admin
                if ($this->ion_auth->is_admin())
                {
                    //Update the groups user belongs to
                    $groupData = $this->input->post('groups');

                    if (isset($groupData) && !empty($groupData))
                    {

                        $this->ion_auth->remove_from_group('', $id);

                        foreach ($groupData as $grp)
                        {
                            $this->ion_auth->add_to_group($grp, $id);
                        }
                    }
                }

                // check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data))
                {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    if ($this->ion_auth->is_admin())
                    {
                        redirect('merchant', 'refresh');
                    }
                    else
                    {
                        redirect('/', 'refresh');
                    }
                }
                else
                {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    if ($this->ion_auth->is_admin())
                    {
                        redirect('merchant', 'refresh');
                    }
                    else
                    {
                        redirect('/', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
        $this->data['currentGroups'] = $currentGroups;

        $this->data['username'] = array(
            'name' => 'username',
            'id' => 'username',
            'type' => 'text',
            'value' => $this->form_validation->set_value('username', $user->username),
        );
        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $this->data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $user->email),
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password'
        );

        $this->_render_page('merchant/edit_user', $this->data);
    }

    function analysis_report($search_month = NULL, $search_year = NULL, $search_adv_type = NULL, $search_new_user = NULL)
    {
        $message_info = '';
        if (check_correct_login_type($this->main_group_id))
        {
            //$this->data['charts'] = $this->getChart($merchant_id);
            $merchant_id = $this->ion_auth->user()->row()->id;
            $merchant_data = $this->m_custom->getUser($merchant_id);

            if (isset($_POST) && !empty($_POST))
            {
                if ($this->input->post('button_action') == "search_history")
                {
                    $search_month = $this->input->post('the_month');
                    $search_year = $this->input->post('the_year');
                    $search_adv_type = $this->input->post('the_adv_type');
                    $search_new_user = $this->input->post('the_new_user');
                }
            }
            $year_list = generate_number_option(get_part_of_date('year', $merchant_data['created_on'], 1), get_part_of_date('year'));
            $this->data['year_list'] = $year_list;
            $this->data['the_year'] = array(
                'name' => 'the_year',
                'id' => 'the_year',
            );
            $selected_year = empty($search_year) ? get_part_of_date('year') : $search_year;
            $this->data['the_year_selected'] = $selected_year;

            $month_list = $this->m_custom->month_group_list();
            $this->data['month_list'] = $month_list;
            $this->data['the_month'] = array(
                'name' => 'the_month',
                'id' => 'the_month',
            );
            $selected_month = empty($search_month) ? get_part_of_date('month') : $search_month;
            $this->data['the_month_selected'] = $selected_month;

            $adv_type_list = array(
                '' => 'All Advertise',
                'hot' => 'Hot Deal',
                'pro' => 'Promotion'
            );
            $this->data['adv_type_list'] = $adv_type_list;
            $this->data['the_adv_type'] = array(
                'name' => 'the_adv_type',
                'id' => 'the_adv_type',
            );
            $this->data['the_adv_type_selected'] = empty($search_adv_type) ? "" : $search_adv_type;

            $new_user_list = array(
                '' => 'All User',
                '1' => 'New User Only',
            );
            $this->data['new_user_list'] = $new_user_list;
            $this->data['the_new_user'] = array(
                'name' => 'the_new_user',
                'id' => 'the_new_user',
            );
            $this->data['the_new_user_selected'] = empty($search_new_user) ? "" : $search_new_user;
            $first_day = displayDate(getFirstLastTime($selected_year, $selected_month));
            $last_day = displayDate(getFirstLastTime($selected_year, $selected_month, 1));
            $this->data['first_day'] = $first_day;
            $this->data['last_day'] = $last_day;
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['page_path_name'] = 'merchant/analysis_report';
            $this->load->view('template/index', $this->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

    function getChart_gender()
    {
        if (check_correct_login_type($this->main_group_id))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;
            $group_by = 'gender';

            $the_year = $this->input->post("the_year", true);
            $the_month = $this->input->post("the_month", true);
            $the_adv_type = $this->input->post("the_adv_type", true) == '' ? NULL : $this->input->post("the_adv_type", true);
            $the_new_user = $this->input->post("the_new_user", true) == '' ? 0 : $this->input->post("the_new_user", true);

            $view_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'view', $the_month, $the_year, $the_adv_type);
            $view_male_count = 0;
            $view_female_count = 0;
            foreach ($view_result as $row)
            {
                $user_id = $row['many_child_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);

                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $this->config->item('gender_id_male'))
                    {
                        $view_male_count++;
                    }
                    else
                    {
                        $view_female_count++;
                    }
                }
            }

            $like_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'like', $the_month, $the_year, $the_adv_type);
            $like_male_count = 0;
            $like_female_count = 0;
            foreach ($like_result as $row)
            {
                $user_id = $row['act_by_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $this->config->item('gender_id_male'))
                    {
                        $like_male_count++;
                    }
                    else
                    {
                        $like_female_count++;
                    }
                }
            }

            $rating_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'rating', $the_month, $the_year, $the_adv_type);
            $rating_male_count = 0;
            $rating_female_count = 0;
            foreach ($rating_result as $row)
            {
                $user_id = $row['act_by_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $this->config->item('gender_id_male'))
                    {
                        $rating_male_count++;
                    }
                    else
                    {
                        $rating_female_count++;
                    }
                }
            }

            $redeem_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'redeem', $the_month, $the_year, $the_adv_type);
            $redeem_male_count = 0;
            $redeem_female_count = 0;
            foreach ($redeem_result as $row)
            {
                $user_id = $row['user_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $this->config->item('gender_id_male'))
                    {
                        $redeem_male_count++;
                    }
                    else
                    {
                        $redeem_female_count++;
                    }
                }
            }

            $male_array = array();
            $male_array['name'] = 'Male';
            $male_array['data'][] = $view_male_count;
            $male_array['data'][] = $like_male_count;
            $male_array['data'][] = $rating_male_count;
            $male_array['data'][] = $redeem_male_count;

            $female_array = array();
            $female_array['name'] = 'Female';
            $female_array['data'][] = $view_female_count;
            $female_array['data'][] = $like_female_count;
            $female_array['data'][] = $rating_female_count;
            $female_array['data'][] = $redeem_female_count;

            $result = array();
            array_push($result, $female_array);
            array_push($result, $male_array);

            echo json_encode($result);
        }
        //$this->load->library('Highcharts');
//        $this->highcharts->set_title('Gender :');
//        $this->highcharts->set_dimensions(740, 300);
//        $this->highcharts->set_axis_titles('Activity', 'Count');
//        $this->highcharts->set_type('bar');
        //$credits->href = 'http://www.internetworldstats.com/stats7.htm';
        //$credits->text = "Article on Internet Wold Stats";
        //$this->highcharts->set_credits($credits);
        //$this->highcharts->render_to("content_top");
        //
        //$category = array('View', 'Like', 'Rating', 'Redeem');
        //$this->highcharts->push_xcategorie($category);
//        $serie['data'] = $result;
//        $this->highcharts->export_file("Code 2 Learn Chart" . date('d M Y'));
//        $this->highcharts->set_serie($result, "Male");
        //$this->highcharts->set_serie($series, "Female");
        //$this->output->set_content_type('application/json')
        //            ->set_output(json_encode($result));           
        //return $this->highcharts->render();
    }

    function getChart_race()
    {
        if (check_correct_login_type($this->main_group_id))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;
            $group_by = 'race';
            $race_id_malay = $this->config->item('race_id_malay');
            $race_id_chinese = $this->config->item('race_id_chinese');
            $race_id_india = $this->config->item('race_id_india');
            $race_id_other = $this->config->item('race_id_other');

            $the_year = $this->input->post("the_year", true);
            $the_month = $this->input->post("the_month", true);
            $the_adv_type = $this->input->post("the_adv_type", true) == '' ? NULL : $this->input->post("the_adv_type", true);
            $the_new_user = $this->input->post("the_new_user", true) == '' ? 0 : $this->input->post("the_new_user", true);

            $view_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'view', $the_month, $the_year, $the_adv_type);
            $view_count[0] = 0;
            $view_count[1] = 0;
            $view_count[2] = 0;
            $view_count[3] = 0;
            foreach ($view_result as $row)
            {
                $user_id = $row['many_child_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);

                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $race_id_malay)
                    {
                        $view_count[0] ++;
                    }
                    else if ($return == $race_id_chinese)
                    {
                        $view_count[1] ++;
                    }
                    else if ($return == $race_id_india)
                    {
                        $view_count[2] ++;
                    }
                    else if ($return == $race_id_other)
                    {
                        $view_count[3] ++;
                    }
                }
            }

            $like_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'like', $the_month, $the_year, $the_adv_type);
            $like_count[0] = 0;
            $like_count[1] = 0;
            $like_count[2] = 0;
            $like_count[3] = 0;
            foreach ($like_result as $row)
            {
                $user_id = $row['act_by_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $race_id_malay)
                    {
                        $like_count[0] ++;
                    }
                    else if ($return == $race_id_chinese)
                    {
                        $like_count[1] ++;
                    }
                    else if ($return == $race_id_india)
                    {
                        $like_count[2] ++;
                    }
                    else if ($return == $race_id_other)
                    {
                        $like_count[3] ++;
                    }
                }
            }

            $rating_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'rating', $the_month, $the_year, $the_adv_type);
            $rating_count[0] = 0;
            $rating_count[1] = 0;
            $rating_count[2] = 0;
            $rating_count[3] = 0;
            foreach ($rating_result as $row)
            {
                $user_id = $row['act_by_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $race_id_malay)
                    {
                        $rating_count[0] ++;
                    }
                    else if ($return == $race_id_chinese)
                    {
                        $rating_count[1] ++;
                    }
                    else if ($return == $race_id_india)
                    {
                        $rating_count[2] ++;
                    }
                    else if ($return == $race_id_other)
                    {
                        $rating_count[3] ++;
                    }
                }
            }

            $redeem_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'redeem', $the_month, $the_year, $the_adv_type);
            $redeem_count[0] = 0;
            $redeem_count[1] = 0;
            $redeem_count[2] = 0;
            $redeem_count[3] = 0;
            foreach ($redeem_result as $row)
            {
                $user_id = $row['user_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $race_id_malay)
                    {
                        $redeem_count[0] ++;
                    }
                    else if ($return == $race_id_chinese)
                    {
                        $redeem_count[1] ++;
                    }
                    else if ($return == $race_id_india)
                    {
                        $redeem_count[2] ++;
                    }
                    else if ($return == $race_id_other)
                    {
                        $redeem_count[3] ++;
                    }
                }
            }

            $malay_array = array();
            $malay_array['name'] = 'Malay';
            $malay_array['data'][] = $view_count[0];
            $malay_array['data'][] = $like_count[0];
            $malay_array['data'][] = $rating_count[0];
            $malay_array['data'][] = $redeem_count[0];

            $chinese_array = array();
            $chinese_array['name'] = 'Chinese';
            $chinese_array['data'][] = $view_count[1];
            $chinese_array['data'][] = $like_count[1];
            $chinese_array['data'][] = $rating_count[1];
            $chinese_array['data'][] = $redeem_count[1];

            $india_array = array();
            $india_array['name'] = 'India';
            $india_array['data'][] = $view_count[2];
            $india_array['data'][] = $like_count[2];
            $india_array['data'][] = $rating_count[2];
            $india_array['data'][] = $redeem_count[2];

            $other_array = array();
            $other_array['name'] = 'Other';
            $other_array['data'][] = $view_count[3];
            $other_array['data'][] = $like_count[3];
            $other_array['data'][] = $rating_count[3];
            $other_array['data'][] = $redeem_count[3];

            $result = array();
            array_push($result, $other_array);
            array_push($result, $india_array);
            array_push($result, $chinese_array);
            array_push($result, $malay_array);

            echo json_encode($result);
        }
    }

    function getChart_age()
    {
        if (check_correct_login_type($this->main_group_id))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;
            $group_by = 'age';
            $age_group0 = 15;
            $age_group1 = 21;
            $age_group2 = 31;
            $age_group3 = 41;

            $the_year = $this->input->post("the_year", true);
            $the_month = $this->input->post("the_month", true);
            $the_adv_type = $this->input->post("the_adv_type", true) == '' ? NULL : $this->input->post("the_adv_type", true);
            $the_new_user = $this->input->post("the_new_user", true) == '' ? 0 : $this->input->post("the_new_user", true);

            $view_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'view', $the_month, $the_year, $the_adv_type);
            $view_count[0] = 0;
            $view_count[1] = 0;
            $view_count[2] = 0;
            $view_count[3] = 0;
            $view_count[4] = 0;
            foreach ($view_result as $row)
            {
                $user_id = $row['many_child_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);

                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return < $age_group0)
                    {
                        $view_count[0] ++;
                    }
                    else if ($return < $age_group1)
                    {
                        $view_count[1] ++;
                    }
                    else if ($return < $age_group2)
                    {
                        $view_count[2] ++;
                    }
                    else if ($return < $age_group3)
                    {
                        $view_count[3] ++;
                    }
                    else
                    {
                        $view_count[4] ++;
                    }
                }
            }

            $like_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'like', $the_month, $the_year, $the_adv_type);
            $like_count[0] = 0;
            $like_count[1] = 0;
            $like_count[2] = 0;
            $like_count[3] = 0;
            $like_count[4] = 0;
            foreach ($like_result as $row)
            {
                $user_id = $row['act_by_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return < $age_group0)
                    {
                        $like_count[0] ++;
                    }
                    else if ($return < $age_group1)
                    {
                        $like_count[1] ++;
                    }
                    else if ($return < $age_group2)
                    {
                        $like_count[2] ++;
                    }
                    else if ($return < $age_group3)
                    {
                        $like_count[3] ++;
                    }
                    else
                    {
                        $like_count[4] ++;
                    }
                }
            }

            $rating_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'rating', $the_month, $the_year, $the_adv_type);
            $rating_count[0] = 0;
            $rating_count[1] = 0;
            $rating_count[2] = 0;
            $rating_count[3] = 0;
            $rating_count[4] = 0;
            foreach ($rating_result as $row)
            {
                $user_id = $row['act_by_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return < $age_group0)
                    {
                        $rating_count[0] ++;
                    }
                    else if ($return < $age_group1)
                    {
                        $rating_count[1] ++;
                    }
                    else if ($return < $age_group2)
                    {
                        $rating_count[2] ++;
                    }
                    else if ($return < $age_group3)
                    {
                        $rating_count[3] ++;
                    }
                    else
                    {
                        $rating_count[4] ++;
                    }
                }
            }

            $redeem_result = $this->m_merchant->getMerchantAnalysisReport($merchant_id, 'redeem', $the_month, $the_year, $the_adv_type);
            $redeem_count[0] = 0;
            $redeem_count[1] = 0;
            $redeem_count[2] = 0;
            $redeem_count[3] = 0;
            $redeem_count[4] = 0;
            foreach ($redeem_result as $row)
            {
                $user_id = $row['user_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return < $age_group0)
                    {
                        $redeem_count[0] ++;
                    }
                    else if ($return < $age_group1)
                    {
                        $redeem_count[1] ++;
                    }
                    else if ($return < $age_group2)
                    {
                        $redeem_count[2] ++;
                    }
                    else if ($return < $age_group3)
                    {
                        $redeem_count[3] ++;
                    }
                    else
                    {
                        $redeem_count[4] ++;
                    }
                }
            }

            $age_array0 = array();
            $age_array0['name'] = '< 15';
            $age_array0['data'][] = $view_count[0];
            $age_array0['data'][] = $like_count[0];
            $age_array0['data'][] = $rating_count[0];
            $age_array0['data'][] = $redeem_count[0];

            $age_array1 = array();
            $age_array1['name'] = '15 - 20';
            $age_array1['data'][] = $view_count[1];
            $age_array1['data'][] = $like_count[1];
            $age_array1['data'][] = $rating_count[1];
            $age_array1['data'][] = $redeem_count[1];

            $age_array2 = array();
            $age_array2['name'] = '21 - 30';
            $age_array2['data'][] = $view_count[2];
            $age_array2['data'][] = $like_count[2];
            $age_array2['data'][] = $rating_count[2];
            $age_array2['data'][] = $redeem_count[2];

            $age_array3 = array();
            $age_array3['name'] = '31 - 40';
            $age_array3['data'][] = $view_count[3];
            $age_array3['data'][] = $like_count[3];
            $age_array3['data'][] = $rating_count[3];
            $age_array3['data'][] = $redeem_count[3];

            $age_array4 = array();
            $age_array4['name'] = '> 40';
            $age_array4['data'][] = $view_count[4];
            $age_array4['data'][] = $like_count[4];
            $age_array4['data'][] = $rating_count[4];
            $age_array4['data'][] = $redeem_count[4];

            $result = array();
            array_push($result, $age_array4);
            array_push($result, $age_array3);
            array_push($result, $age_array2);
            array_push($result, $age_array1);
            array_push($result, $age_array0);

            echo json_encode($result);
        }
    }

    function getChart_redeem()
    {
        if (check_correct_login_type($this->main_group_id))
        {
            $merchant_id = $this->ion_auth->user()->row()->id;
            $group_by = 'gender';

            $the_year = $this->input->post("the_year", true);
            $the_month = $this->input->post("the_month", true);
            $the_new_user = $this->input->post("the_new_user", true) == '' ? 0 : $this->input->post("the_new_user", true);

            $active_result = $this->m_merchant->getMerchantAnalysisReportRedeem($merchant_id, $this->config->item('voucher_active'), $the_month, $the_year);
            $active_male_count = 0;
            $active_female_count = 0;
            foreach ($active_result as $row)
            {
                $user_id = $row['user_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $this->config->item('gender_id_male'))
                    {
                        $active_male_count++;
                    }
                    else
                    {
                        $active_female_count++;
                    }
                }
            }

            $used_result = $this->m_merchant->getMerchantAnalysisReportRedeem($merchant_id, $this->config->item('voucher_used'), $the_month, $the_year);
            $used_male_count = 0;
            $used_female_count = 0;
            foreach ($used_result as $row)
            {
                $user_id = $row['user_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $this->config->item('gender_id_male'))
                    {
                        $used_male_count++;
                    }
                    else
                    {
                        $used_female_count++;
                    }
                }
            }

            $expired_result = $this->m_merchant->getMerchantAnalysisReportRedeem($merchant_id, $this->config->item('voucher_expired'), $the_month, $the_year);
            $expired_male_count = 0;
            $expired_female_count = 0;
            foreach ($expired_result as $row)
            {
                $user_id = $row['user_id'];
                $return = $this->m_user->getUserAnalysisGroup($user_id, $group_by);
                if ($the_new_user == 0 || ($this->m_custom->check_is_new_user($user_id) && $the_new_user == 1))
                {
                    if ($return == $this->config->item('gender_id_male'))
                    {
                        $expired_male_count++;
                    }
                    else
                    {
                        $expired_female_count++;
                    }
                }
            }

            $active_array = array();
            $active_array['name'] = 'Non-Redeem';
            $active_array['data'][] = $active_male_count;
            $active_array['data'][] = $active_female_count;

            $used_array = array();
            $used_array['name'] = 'Redeem';
            $used_array['data'][] = $used_male_count;
            $used_array['data'][] = $used_female_count;

            $expired_array = array();
            $expired_array['name'] = 'Expired';
            $expired_array['data'][] = $expired_male_count;
            $expired_array['data'][] = $expired_female_count;

            $result = array();
            array_push($result, $expired_array);
            array_push($result, $used_array);
            array_push($result, $active_array);

            echo json_encode($result);
        }
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function _render_page($view, $data = null, $render = false)
    {

        $this->viewdata = (empty($data)) ? $this->data : $data;

        $view_html = $this->load->view($view, $this->viewdata, $render);

        if (!$render)
            return $view_html;
    }

}
