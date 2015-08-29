<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library(array('ion_auth', 'form_validation'));
        $this->load->helper(array('url', 'language'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->main_group_id = $this->config->item('group_id_merchant');
        $this->supervisor_group_id = $this->config->item('group_id_supervisor');
        $this->album_merchant = $this->config->item('album_merchant');
        $this->folder_merchant_ssm = $this->config->item('folder_merchant_ssm');
    }

    // redirect if needed, otherwise display the user list
    function index() {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('merchant/login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        } else {
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            //list the users
            $this->data['users'] = $this->ion_auth->users()->result();
            foreach ($this->data['users'] as $k => $user) {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }
            $this->_render_page('merchant/index', $this->data);
        }
    }

    // log the user in
    function login() {
        $this->data['title'] = "Log In";

        //validate form input
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember, $this->main_group_id)) {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('merchant/dashboard/' . generate_slug($this->session->userdata('company_name')), 'refresh');
            } else if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember, $this->supervisor_group_id)) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('/', 'refresh');
            } else {
                // if the login was un-successful
                // redirect them back to the login page
                if ($this->ion_auth->errors() != "") {
                    $this->session->set_flashdata('message', $this->lang->line('login_unsuccessful'));
                }
                //$this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('merchant/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {
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
    function logout() {
        $this->data['title'] = "Logout";

        // log the user out
        $logout = $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('merchant/login', 'refresh');
    }

    // change password
    function change_password() {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!check_correct_login_type($this->main_group_id)) {
            redirect('/', 'refresh');
        }

        $user = $this->ion_auth->user()->row();
        $function_use_for = 'merchant/change_password';

        if ($this->form_validation->run() == false) {
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

            $this->data['page_path_name'] = 'auth/change_password';
            $this->load->view('template/layout_right_menu', $this->data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                //$this->logout();
                set_simple_message('Thank you!', 'Your Password has been saved!', '', 'merchant/change_password', 'Back', 'merchant/simple_message');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect($function_use_for, 'refresh');
            }
        }
    }

    function simple_message() {
        display_simple_message();
    }

    function retrieve_password() {
        $this->form_validation->set_rules('username_email', $this->lang->line('forgot_password_username_email_label'), 'required');
        if ($this->form_validation->run() == false) {
            // setup the input
            $this->data['username_email'] = array('name' => 'username_email',
                'id' => 'username_email',
            );
            $this->data['identity_label'] = $this->lang->line('forgot_password_username_email_label');
            // set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['page_path_name'] = 'merchant/retrieve_password';
            $this->load->view('template/layout', $this->data);
        } else {
            $the_input = $this->input->post('username_email');
            $the_id = $this->ion_auth->get_id_by_email_or_username($the_input);

            $identity = $this->ion_auth->where('id', $the_id)->where('main_group_id', $this->main_group_id)->users()->row();
            if (empty($identity)) {
                $this->ion_auth->set_error('forgot_password_username_email_not_found');
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("merchant/retrieve_password", 'refresh');
            } else {
                $this->session->set_flashdata('mail_info', $identity);
                redirect('merchant/send_mail_process', 'refresh');
            }
        }
    }

    function send_mail_process() {
        $identity = $this->session->flashdata('mail_info');
        $get_status = send_mail_simple($identity->email, 'Your Keppo Account Login Info', 'Company Name:' . $identity->company . '<br/>Username:' . $identity->username . '<br/>Email:' . $identity->email . '<br/>Password:' . $identity->password_visible, 'forgot_password_send_email_success');
        if ($get_status) {
            set_simple_message('Thank you!', 'An email will be sent to your registered email address.', "If you don't receive in the next 10 minutes, please check your spam folder and if you still haven't received it please try again...", 'merchant/login', 'Go to Log In Page', 'merchant/simple_message');
        } else {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("merchant/retrieve_password", 'refresh');
        }
    }

    // forgot password
    function forgot_password() {
        // setting validation rules by checking wheather identity is username or email
        if ($this->config->item('identity', 'ion_auth') != 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        }


        if ($this->form_validation->run() == false) {
            // setup the input
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
            );

            if ($this->config->item('identity', 'ion_auth') != 'email') {
                $this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
            } else {
                $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            }

            // set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->_render_page('merchant/forgot_password', $this->data);
        } else {
            $identity_column = $this->config->item('identity', 'ion_auth');
            $identity = $this->ion_auth->where($identity_column, $this->input->post('email'))->users()->row();

            if (empty($identity)) {

                if ($this->config->item('identity', 'ion_auth') != 'email') {
                    $this->ion_auth->set_error('forgot_password_identity_not_found');
                } else {
                    $this->ion_auth->set_error('forgot_password_email_not_found');
                }

                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("merchant/forgot_password", 'refresh');
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten) {
                // if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("merchant/login", 'refresh'); //we should display a confirmation page here instead of the login page
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("merchant/forgot_password", 'refresh');
            }
        }
    }

    // reset password - final step for forgotten password
    public function reset_password($code = NULL) {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {
            // if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false) {
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
            } else {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    // something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));
                } else {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("merchant/login", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('merchant/reset_password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("merchant/forgot_password", 'refresh');
        }
    }

    // activate the user
    function activate($id, $code = false) {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("merchant", 'refresh');
        } else {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("merchant/forgot_password", 'refresh');
        }
    }

    // deactivate the user
    function deactivate($id = NULL) {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            // redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        }

        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() == FALSE) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();
            $this->_render_page('merchant/deactivate_user', $this->data);
        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->ion_auth->deactivate($id);
                }
            }

            // redirect them back to the auth page
            redirect('merchant', 'refresh');
        }
    }

    // create a new user
    function create_user() {
        $controller = $this->uri->segment(2);
        $function_use_for = 'merchant/create_user';

        //To set this function is use by create merchant and register merchant
        if ($controller == 'create_user') {
            $this->data['title'] = "Create Merchant";
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
                redirect('merchant', 'refresh');
            }
        } else {
            $this->data['title'] = "Merchant Sign Up";
            $function_use_for = 'merchant/register';
        }
        $this->data['function_use_for'] = $function_use_for;

        $tables = $this->config->item('tables', 'ion_auth');

        if (isset($_POST) && !empty($_POST)) {
            $_POST['slug'] = generate_slug($_POST['company']);
            $slug = $_POST['slug'];
        }

        // validate form input
        $this->form_validation->set_rules('company', $this->lang->line('create_merchant_validation_company_label'), 'required');
        $this->form_validation->set_rules('slug', $this->lang->line('create_merchant_validation_company_label'), 'is_unique[' . $tables['users'] . '.slug]');
        $this->form_validation->set_rules('me_ssm', $this->lang->line('create_merchant_validation_companyssm_label'), 'required');
        $this->form_validation->set_rules('address', $this->lang->line('create_merchant_validation_address_label'), 'required');
        $this->form_validation->set_rules('phone', $this->lang->line('create_merchant_validation_phone_label'), 'required');
        $this->form_validation->set_rules('username', $this->lang->line('create_merchant_validation_username_label'), 'required|is_unique[' . $tables['users'] . '.username]');
        $this->form_validation->set_rules('email', $this->lang->line('create_merchant_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('password', $this->lang->line('create_merchant_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_merchant_validation_password_confirm_label'), 'required');
        //$this->form_validation->set_rules('first_name', $this->lang->line('create_merchant_fname_label'), 'required');
        //$this->form_validation->set_rules('website', $this->lang->line('create_user_validation_website_label'));
        //$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'));

        if ($this->form_validation->run() == true) {
            //$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $company = $this->input->post('company');
            $me_ssm = $this->input->post('me_ssm');
            $address = $this->input->post('address');
            $phone = $this->input->post('phone');

//            if(!$this->m_custom->check_is_value_unique('users','slug',$slug)){               
//                $this->ion_auth->set_error('account_creation_duplicate_company_name');
//                redirect("merchant/register", 'refresh');
//            }

            $additional_data = array(
                'username' => $username,
                //'first_name' => $this->input->post('first_name'),
                //'last_name' => $this->input->post('last_name'),
                'company' => $company,
                'slug' => $slug,
                'address' => $address,
                'me_category_id' => $this->input->post('me_category_id'),
                'me_state_id' => $this->input->post('me_state_id'),
                'phone' => $phone,
                'me_ssm' => $me_ssm,
                'profile_image' => $this->config->item('merchant_default_image'),
                //'me_website_url' => $this->input->post('website'),
                'main_group_id' => $this->main_group_id,
                'password_visible' => $password
            );
        }

        $group_ids = array(
            $this->main_group_id
        );

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids)) {
            // check to see if we are creating the user
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            $get_status = send_mail_simple($email, 'Your Keppo Merchant Account Success Created', 'Company Name : ' . $company .
                    '<br/>Register No(SSM) : ' . $me_ssm .
                    '<br/>Company Address : ' . $address .
                    '<br/>Contact Number : ' . $phone .
                    '<br/>Username : ' . $username .
                    '<br/>E-mail : ' . $email .
                    '<br/>Password : ' . $password, 'create_user_send_email_success');
            if ($get_status) {
                // if there were no errors
                redirect("merchant/login", 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("merchant/register", 'refresh');
            }
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['username'] = array(
                'name' => 'username',
                'id' => 'username',
                'type' => 'text',
                'value' => $this->form_validation->set_value('username'),
            );
//            $this->data['first_name'] = array(
//                'name' => 'first_name',
//                'id' => 'first_name',
//                'type' => 'text',
//                'value' => $this->form_validation->set_value('first_name'),
//            );
//            $this->data['last_name'] = array(
//                'name' => 'last_name',
//                'id' => 'last_name',
//                'type' => 'text',
//                'value' => $this->form_validation->set_value('last_name'),
//            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
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

            $this->data['category_list'] = $this->ion_auth->get_main_category_list();
            $this->data['me_category_id'] = array(
                'name' => 'me_category_id',
                'id' => 'me_category_id',
                'value' => $this->form_validation->set_value('me_category_id'),
            );

            $this->data['state_list'] = $this->ion_auth->get_static_option_list('state');
            $this->data['me_state_id'] = array(
                'name' => 'me_state_id',
                'id' => 'me_state_id',
                'value' => $this->form_validation->set_value('me_state_id'),
            );

            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
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

    //View the merchant dashboard upper part
    function dashboard($slug) {
        $the_row = $this->m_custom->get_one_table_record('users', 'slug', $slug);
        if ($the_row) {
            $this->data['logo_url'] = $this->album_merchant . $the_row->profile_image;
            $this->data['company_name'] = $the_row->company;
            $this->data['address'] = $the_row->address;
            $this->data['phone'] = $the_row->phone;
            $this->data['show_outlet'] = base_url() . 'merchant/outlet/' . $slug;
            $this->data['website_url'] = $the_row->me_website_url;
            $this->data['facebook_url'] = $the_row->me_facebook_url;
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['page_path_name'] = 'merchant/dashboard';
            $this->load->view('template/layout_right_menu', $this->data);
        } else {
            redirect('/', 'refresh');
        }
    }

    //View the merchant dashboard upper part
    function outlet($slug) {
        $the_row = $this->m_custom->get_one_table_record('users', 'slug', $slug);
        if ($the_row) {
            $this->data['logo_url'] = $this->album_merchant . $the_row->profile_image;
            $this->data['company_name'] = $the_row->company;
            $this->data['address'] = $the_row->address;
            $this->data['phone'] = $the_row->phone;
            $this->data['show_outlet'] = '';
            $this->data['website_url'] = $the_row->me_website_url;
            $this->data['facebook_url'] = $the_row->me_facebook_url;
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['page_path_name'] = 'merchant/outlet';

            if (isset($_POST) && !empty($_POST)) {
                $search_word = $this->input->post('search_word');
                $this->data['branch_list'] = $this->m_custom->getBranchList_with_search($the_row->id, $search_word);
            } else {
                $this->data['branch_list'] = $this->m_custom->getBranchList($the_row->id);
            }

            $this->data['page_path_name'] = 'merchant/outlet';

            $this->load->view('template/layout_right_menu', $this->data);
        } else {
            redirect('/', 'refresh');
        }
    }

    //merchant profile view and edit page
    function profile() {

        if (!check_correct_login_type($this->main_group_id) && !check_correct_login_type($this->supervisor_group_id)) {
            redirect('/', 'refresh');
        }

        $merchant_id = $this->ion_auth->user()->row()->id;

        $is_supervisor = 0;
        $branch = FALSE;
        //for supervisor view merchant profile because supervisor don't have own profile
        if (check_correct_login_type($this->supervisor_group_id)) {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
            $is_supervisor = 1;
            $supervisor = $this->ion_auth->user()->row();
            $branch = $this->m_custom->get_one_table_record('merchant_branch', 'branch_id', $supervisor->su_branch_id);
        }

        $user = $this->ion_auth->user($merchant_id)->row();

        $this->form_validation->set_rules('phone', $this->lang->line('create_merchant_validation_phone_label'), 'required');
        $this->form_validation->set_rules('website', $this->lang->line('create_merchant_validation_website_label'));
        $this->form_validation->set_rules('facebook_url', $this->lang->line('create_merchant_validation_facebook_url_label'));

        if (isset($_POST) && !empty($_POST)) {
            if ($this->input->post('button_action') == "confirm") {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $merchant_id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }
                if ($this->form_validation->run() === TRUE) {

                    $data = array(
                        'phone' => $this->input->post('phone'),
                        //'slug' => generate_slug($this->input->post('company')),
                        //'me_category_id' => $this->input->post('me_category_id'),
                        'me_website_url' => $this->input->post('website'),
                        'me_facebook_url' => $this->input->post('facebook_url'),
                    );

                    // check to see if we are updating the user
                    if ($this->ion_auth->update($user->id, $data)) {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        $user = $this->ion_auth->user($merchant_id)->row();
                    } else {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }
            } else if ($this->input->post('button_action') == "change_image") {
                $upload_rule = array(
                    'upload_path' => $this->album_merchant,
                    'allowed_types' => $this->config->item('allowed_types_image'),
                    'max_size' => $this->config->item('max_size'),
                    'max_width' => $this->config->item('max_width'),
                    'max_height' => $this->config->item('max_height'),
                );

                $this->load->library('upload', $upload_rule);

                if (!$this->upload->do_upload()) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('message', $this->upload->display_errors());
                } else {
                    $image_data = array('upload_data' => $this->upload->data());
                    //$this->ion_auth->set_message('image_upload_successful');

                    $data = array(
                        'profile_image' => $this->upload->data('file_name'),
                    );

                    if ($this->ion_auth->update($merchant_id, $data)) {
                        $this->session->set_flashdata('message', 'Merchant logo success update.');
                        redirect('merchant/profile', 'refresh');
                    } else {

                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }

                redirect('merchant/profile', 'refresh');
            } else if ($this->input->post('button_action') == "view_branch") {
                redirect('merchant/branch', 'refresh');
            } else if ($this->input->post('button_action') == "add_branch") {
                redirect('merchant/branch/add', 'refresh');
            } else if ($this->input->post('button_action') == "view_supervisor") {
                redirect('merchant/supervisor', 'refresh');
            } else if ($this->input->post('button_action') == "add_supervisor") {
                redirect('merchant/supervisor/add', 'refresh');
            } else {
                
            }
        }

        $this->data['logo_url'] = $this->album_merchant . $user->profile_image;

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;
        $this->data['is_supervisor'] = $is_supervisor;

        $this->data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'readonly ' => 'true',
            'value' => $this->form_validation->set_value('company', $user->company),
        );
        $this->data['me_ssm'] = array(
            'name' => 'me_ssm',
            'id' => 'me_ssm',
            'type' => 'text',
            'readonly ' => 'true',
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
            'readonly ' => 'true',
            'value' => $this->m_custom->get_one_table_record('category', 'category_id', $user->me_category_id)->category_label,
        );

        $this->data['address'] = array(
            'name' => 'address',
            'id' => 'address',
            'readonly ' => 'true',
            'value' => $this->form_validation->set_value('address', $user->address),
        );

        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
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
            'readonly ' => 'true',
            'value' => ($branch) ? $branch->name : '',
        );

        $this->data['branch_address'] = array(
            'name' => 'branch_address',
            'id' => 'branch_address',
            'readonly ' => 'true',
            'value' => ($branch) ? $branch->address : '',
        );

        $this->data['branch_phone'] = array(
            'name' => 'branch_phone',
            'id' => 'branch_phone',
            'readonly ' => 'true',
            'value' => ($branch) ? $branch->phone : '',
        );

        $this->data['branch_state'] = array(
            'name' => 'branch_state',
            'id' => 'branch_state',
            'readonly ' => 'true',
            'value' => ($branch) ? $this->m_custom->get_one_static_option_text($branch->state_id) : '',
        );

        $this->data['supervisor_username'] = array(
            'name' => 'supervisor_username',
            'id' => 'supervisor_username',
            'readonly ' => 'true',
            'value' => $is_supervisor == 1 ? $supervisor->username : $user->username,
        );

        $this->data['supervisor_password'] = array(
            'name' => 'supervisor_password',
            'id' => 'supervisor_password',
            'readonly ' => 'true',
            'value' => $is_supervisor == 1 ? $supervisor->password_visible : $user->password_visible,
        );


        $this->data['page_path_name'] = 'merchant/profile';
        $this->load->view('template/layout_right_menu', $this->data);
//        $this->load->view('template/header');
//        $this->_render_page('merchant/profile', $this->data);
//        $this->load->view('template/layout_management', $this->branch_management());
//        $this->load->view('template/footer');
    }

    function branch() {
        if (!check_correct_login_type($this->main_group_id)) {
            redirect('/', 'refresh');
        }
        $this->load->view('template/layout_management', $this->branch_management());
    }

    function branch_management() {
        $merchant_id = $this->ion_auth->user()->row()->id;

        //for supervisor view the branch of merchant
        if (check_correct_login_type($this->supervisor_group_id)) {
            $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
        }

        $this->load->library('grocery_CRUD');
        try {
            $crud = new grocery_CRUD();

            $this->load->library('user_agent');
            if ($this->agent->is_mobile() && $this->agent->is_tablet() === FALSE) {
                $crud->set_theme('bootstrap');
                $crud->unset_search();
            } else {
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
            $crud->callback_column('address', array($this, '_full_text'));
            $crud->callback_column('supervisor', array($this, '_branch_supervisor'));
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

            if ($state == 'read') {
                $crud->set_relation('state_id', 'static_option', '{option_text}');
            }
            $output = $crud->render();
            return $output;
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function _full_text($value, $row) {
        return wordwrap($row->address);
    }

    function _branch_supervisor($value, $row) {
        return $this->ion_auth->get_branch_supervisor_list($row->branch_id);
    }

    function branch_insert_callback($post_array, $primary_key) {
        $post_array['merchant_id'] = $this->ion_auth->user()->row()->id;
        if (check_correct_login_type($this->supervisor_group_id)) {
            $post_array['merchant_id'] = $this->ion_auth->user()->row()->su_merchant_id;
        }
        return $this->db->insert('merchant_branch', $post_array);
    }

    function map($branch_id = NULL) {
        if (!empty($branch_id)) {
            $the_branch = $this->m_custom->get_one_table_record('merchant_branch', 'branch_id', $branch_id);
            if ($the_branch) {
                $the_merchant = $this->m_custom->get_one_table_record('users', 'id', $the_branch->merchant_id);
                $this->data['logo_url'] = $this->album_merchant . $the_merchant->profile_image;
                $this->data['company_name'] = $the_merchant->company;
                $this->data['phone'] = $the_branch->phone;

                $this->data['address'] = $the_branch->address;
                $this->data['googlemap_url'] = 'https://www.google.com/maps/place/' . $the_branch->google_map_url;
                $this->load->library('googlemaps');

                $location = $the_branch->google_map_url;
                if (IsNullOrEmptyString($location)) {
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
                $this->load->view('template/layout', $this->data);
            }
        } else {
            redirect('/', 'refresh');
        }
    }

    function supervisor() {
        if (!check_correct_login_type($this->main_group_id)) {
            redirect('/', 'refresh');
        }
        $this->load->view('template/layout_management', $this->supervisor_management());
    }

    function supervisor_management() {
        $id = $this->ion_auth->user()->row()->id;
        $this->load->library('grocery_CRUD');
        try {
            $crud = new grocery_CRUD();

            $this->load->library('user_agent');
            if ($this->agent->is_mobile() && $this->agent->is_tablet() === FALSE) {
                $crud->set_theme('bootstrap');
                $crud->unset_search();
            } else {
                $crud->set_theme('datatables');    //datatables, flexigrid, bootstrap             
            }

            $crud->set_table('users');
            $crud->set_subject('Supervisor');
            $crud->columns('username', 'password_visible', 'su_branch_id');
            $crud->required_fields('username', 'password_visible', 'su_branch_id');
            $crud->fields('username', 'password_visible', 'su_branch_id');
            $crud->display_as('password_visible', 'Password');
            $crud->display_as('su_branch_id', 'Branch');
            $crud->callback_add_field('su_branch_id', array($this, '_selected_branch_callback'));   //For add page set pre-selected value if got pass in brach id
            $crud->field_type('su_branch_id', 'dropdown', $this->ion_auth->get_merchant_branch_list($id));  //For view show the branch list text
            $crud->callback_insert(array($this, 'supervisor_insert_callback'));
            $crud->callback_update(array($this, 'supervisor_update_callback'));
            $crud->set_rules('username', 'Username', 'trim|required|callback_supervisor_username_check');
            $crud->unset_export();
            $crud->unset_print();
            $crud->unset_read();

            $state = $crud->getState();

            //filter that this is supervisor type user and it is under this merchant
            $crud->where('su_merchant_id', $id);
            $crud->where('main_group_id', $this->supervisor_group_id);

            $output = $crud->render();
            return $output;
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function _selected_branch_callback($post_array) {
        $id = $this->ion_auth->user()->row()->id;
        $selected = $this->uri->segment(4);
        return form_dropdown('su_branch_id', $this->ion_auth->get_merchant_branch_list($id), $selected);
    }

    function supervisor_insert_callback($post_array, $primary_key) {

//        if(!$this->m_custom->check_is_value_unique('users','username',$post_array['username'])){
//            return FALSE;
//        }

        $additional_data = array(
            'username' => $post_array['username'],
            'su_merchant_id' => $this->ion_auth->user()->row()->id,
            'su_branch_id' => $post_array['su_branch_id'],
            'main_group_id' => $this->supervisor_group_id,
            'password_visible' => $post_array['password_visible'],
        );

        return $this->ion_auth->register($post_array['username'], $post_array['password_visible'], $post_array['username'] . $this->config->item('keppo_email_domain'), $additional_data, $this->supervisor_group_id);
    }

    function supervisor_update_callback($post_array, $primary_key) {

//        if(!$this->m_custom->check_is_value_unique('users','username',$post_array['username'],'id',$primary_key)){
//            return FALSE;
//        }

        $additional_data = array(
            'username' => $post_array['username'],
            'email' => $post_array['username'] . $this->config->item('keppo_email_domain'),
            'password' => $post_array['password_visible'],
            'password_visible' => $post_array['password_visible'],
            'su_branch_id' => $post_array['su_branch_id'],
        );

        return $this->ion_auth->update($primary_key, $additional_data);
    }

    public function supervisor_username_check($str) {
        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id)) {
            $username_old = $this->db->where("id", $id)->get('users')->row()->username;
            $this->db->where("username !=", $username_old);
        }

        $num_row = $this->db->where('username', $str)->get('users')->num_rows();
        if ($num_row >= 1) {
            $this->form_validation->set_message('supervisor_username_check', 'The username already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function upload_ssm() {
        if (!check_correct_login_type($this->main_group_id)) {
            redirect('/', 'refresh');
        }
        $merchant_id = $this->ion_auth->user()->row()->id;
        $me_ssm_file = $this->ion_auth->user()->row()->me_ssm_file;
        $this->data['me_ssm_file'] = $me_ssm_file;
        if (isset($_POST) && !empty($_POST)) {
             if ($this->input->post('button_action') == "upload_ssm") {           
            $upload_rule = array(
                'upload_path' => $this->folder_merchant_ssm,
                'allowed_types' => $this->config->item('allowed_types_file'),
                'max_size' => $this->config->item('max_size'),
            );

            $this->load->library('upload', $upload_rule);

            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('message', $this->upload->display_errors());
            } else {
                $image_data = array('upload_data' => $this->upload->data());
                //$this->ion_auth->set_message('image_upload_successful');

                $data = array(
                    'me_ssm_file' => $this->upload->data('file_name'),
                );

                if ($this->ion_auth->update($merchant_id, $data)) {
                    $this->session->set_flashdata('message', 'Merchant SSM success update.');
                    redirect('merchant/profile', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
            }
            }else if ($this->input->post('button_action') == "download_ssm")  {
                $this->load->helper('download');
                $file_path = $this->folder_merchant_ssm.$me_ssm_file;
                force_download($file_path, NULL);
            }else{
                
            }
        }
        $this->data['page_path_name'] = 'merchant/upload_ssm';
        $this->load->view('template/layout_right_menu', $this->data);
    }

//    function upload_image() {
//
//        redirect('/','refresh'); //no use currently, disable this function first
//        if (!$this->ion_auth->logged_in()) {
//            redirect('merchant/login', 'refresh');
//        }
//
//        $id = $this->ion_auth->user()->row()->id;
//
//        if (isset($_POST) && !empty($_POST)) {
//            $upload_rule = array(
//                'upload_path' => $this->album_merchant,
//                'allowed_types' => $this->config->item('allowed_types_image'),
//                'max_size' => $this->config->item('max_size'),
//            );
//
//            $this->load->library('upload', $upload_rule);
//
//            if (!$this->upload->do_upload()) {
//                $error = array('error' => $this->upload->display_errors());
//                $this->session->set_flashdata('message', $this->upload->display_errors());
//            } else {
//                $image_data = array('upload_data' => $this->upload->data());
//                //$this->ion_auth->set_message('image_upload_successful');
//
//                $data = array(
//                    'profile_image' => $this->upload->data('file_name'),
//                );
//
//                if ($this->ion_auth->update($id, $data)) {
//                    $this->session->set_flashdata('message', 'Merchant logo success update.');
//                    redirect('merchant/profile', 'refresh');
//                } else {
//
//                    $this->session->set_flashdata('message', $this->ion_auth->errors());
//                }
//            }
//        }
//
//        $user = $this->ion_auth->user($id)->row();
//        $this->data['logo_url'] = $this->album_merchant . $user->profile_image;
//
//        // set the flash data error message if there is one
//        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
//
//        $this->data['page_path_name'] = 'merchant/upload_image';
//        $this->load->view('template/layout', $this->data);
//    }
    // edit a user
    function edit_user($id) {
        $this->data['title'] = "Edit User";

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id))) {
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

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE) {
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
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                    $data['password_visible'] = $this->input->post('password');
                }



                // Only allow updating groups if user is admin
                if ($this->ion_auth->is_admin()) {
                    //Update the groups user belongs to
                    $groupData = $this->input->post('groups');

                    if (isset($groupData) && !empty($groupData)) {

                        $this->ion_auth->remove_from_group('', $id);

                        foreach ($groupData as $grp) {
                            $this->ion_auth->add_to_group($grp, $id);
                        }
                    }
                }

                // check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data)) {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    if ($this->ion_auth->is_admin()) {
                        redirect('merchant', 'refresh');
                    } else {
                        redirect('/', 'refresh');
                    }
                } else {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    if ($this->ion_auth->is_admin()) {
                        redirect('merchant', 'refresh');
                    } else {
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

    function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce() {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function _render_page($view, $data = null, $render = false) {

        $this->viewdata = (empty($data)) ? $this->data : $data;

        $view_html = $this->load->view($view, $this->viewdata, $render);

        if (!$render)
            return $view_html;
    }

}
