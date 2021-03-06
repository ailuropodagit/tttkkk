<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'language'));
        $this->main_group_id = $this->config->item('group_id_admin');
        $this->group_id_admin = $this->config->item('group_id_admin');
        $this->group_id_worker = $this->config->item('group_id_worker');
        $this->group_id_merchant = $this->config->item('group_id_merchant');
        $this->group_id_user = $this->config->item('group_id_user');
        $this->album_admin = $this->config->item('album_admin');
        $this->album_admin_profile = $this->config->item('album_admin_profile');
        $this->album_banner = $this->config->item('album_banner');
        $this->album_user_profile = $this->config->item('album_user_profile');
        $this->folder_image = $this->config->item('folder_image');
        $this->temp_folder = $this->config->item('folder_image_temp');

        $this->login_id = 0;
        $this->login_type = 0;
        if ($this->ion_auth->logged_in())
        {
            $this->login_id = $this->session->userdata('user_id');
            $this->login_type = $this->session->userdata('user_group_id');
        }

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
    }

    // redirect if needed, otherwise display the user list
    function index()
    {

        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('admin/login', 'refresh');
        }
        elseif (!$this->ion_auth->is_admin())
        { // remove this elseif if you want to enable this for non-admins
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

            $this->_render_page('admin/index', $this->data);
        }
    }

    // log the user in
    function login()
    {
        $this->data['title'] = "Login";
        //validate form input
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true)
        {
            // check to see if the user is logging in
            // check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember, $this->main_group_id))
            {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('admin/admin_dashboard', 'refresh');
            }
            else if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember, $this->group_id_worker))
            {
                //$this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('admin/admin_dashboard', 'refresh');
            }
            else
            {
                // if the login was un-successful
                // redirect them back to the login page
                if ($this->ion_auth->errors() != "")
                {
                    $this->session->set_flashdata('message', $this->lang->line('login_unsuccessful'));
                }
                redirect('admin/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
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
            $this->data['page_path_name'] = 'admin/login';
            $this->load->view('template/layout', $this->data);
        }
    }

    // log the user out
    function logout()
    {
        $this->data['title'] = "Logout";

        // log the user out
        $logout = $this->ion_auth->logout();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('admin/login', 'refresh');
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
        $function_use_for = 'admin/change_password';

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
                set_simple_message('Thank you!', 'Your Password has been saved!', '', 'admin/admin_dashboard', 'Back to Dashboard', 'all/simple_message', 1, 3);
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect($function_use_for, 'refresh');
            }
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
            $this->_render_page('admin/forgot_password', $this->data);
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
                redirect("admin/forgot_password", 'refresh');
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten)
            {
                // if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("admin/login", 'refresh'); //we should display a confirmation page here instead of the login page
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("admin/forgot_password", 'refresh');
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
                $this->_render_page('admin/reset_password', $this->data);
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
                        redirect("admin/login", 'refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('admin/reset_password/' . $code, 'refresh');
                    }
                }
            }
        }
        else
        {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("admin/forgot_password", 'refresh');
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
            redirect("admin", 'refresh');
        }
        else
        {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("admin/forgot_password", 'refresh');
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
            $this->_render_page('admin/deactivate_user', $this->data);
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
            redirect('admin', 'refresh');
        }
    }

    // create a new user
    function create_user()
    {
        $this->data['title'] = "Create User";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('Admin', 'refresh');
        }

        $tables = $this->config->item('tables', 'ion_auth');
        $main_group_id = $this->group_id_worker;

        // validate form input
        $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_username_label'), 'required|is_unique[' . $tables['users'] . '.username]');
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            //$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                'username' => $username,
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
                'main_group_id' => $main_group_id,
                'password_visible' => $password
            );
        }

        $group_ids = array(
            $main_group_id
        );

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids))
        {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['username'] = array(
                'name' => 'username',
                'id' => 'username',
                'type' => 'text',
                'value' => $this->form_validation->set_value('username'),
            );
            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
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
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
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

            $this->_render_page('admin/create_user', $this->data);
        }
    }

    // edit a user, no use
    function edit_user($id)
    {
        $this->data['title'] = "Edit User";

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
        {
            redirect('admin', 'refresh');
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
                        redirect('Admin', 'refresh');
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
                        redirect('admin', 'refresh');
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

        $this->_render_page('admin/edit_user', $this->data);
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

            $this->data['page_path_name'] = 'admin/retrieve_password';
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
                redirect("admin/retrieve_password", 'refresh');
            }
            else
            {
                $this->session->set_flashdata('mail_info', $identity);
                redirect('admin/send_mail_process', 'refresh');
            }
        }
    }

    function send_mail_process()
    {
        $identity = $this->session->flashdata('mail_info');
        $get_status = send_mail_simple($identity->email, 'Your Keppo Admin Account Login Info', 'Username:' . $identity->username . '<br/>Email:' . $identity->email . '<br/>Password:' . $identity->password_visible, 'forgot_password_send_email_success');
        if ($get_status)
        {
            set_simple_message('Thank you!', 'An email will be sent to your registered email address.', "If you don't receive in the next 10 minutes, please check your spam folder and if you still haven't received it please try again...", 'admin/login', 'Go to Log In Page', 'all/simple_message');
        }
        else
        {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("admin/retrieve_password", 'refresh');
        }
    }

    function admin_dashboard()
    {
        if (!$this->m_admin->check_is_any_admin())
        {
            redirect('/', 'refresh');
        }

        $login_id = $this->ion_auth->user()->row()->id;

        $notification_list = $this->m_custom->notification_display(0, 1);
        $this->data['notification_list'] = $notification_list;

        $this->data['page_path_name'] = 'all/notification';
        $this->load->view('template/index', $this->data);

        if ($this->config->item('notification_auto_mark_as_read') == 1)
        {
            $this->m_custom->notification_admin_read();
        }
    }

    function profile()
    {
        if (!$this->m_admin->check_is_any_admin())
        {
            redirect('/', 'refresh');
        }
        $user_id = $this->ion_auth->user()->row()->id;
        $user = $this->ion_auth->user($user_id)->row();
        $tables = $this->config->item('tables', 'ion_auth');

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'));
        //$this->form_validation->set_rules('phone', $this->lang->line('create_user_phone_label'), 'required|valid_contact_number');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_phone_label'), 'required');
        $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_username_label'), 'trim|required|is_unique_edit[' . $tables['users'] . '.username.' . $user_id . ']');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique_edit[' . $tables['users'] . '.email.' . $user_id . ']');

        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "confirm")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $username = strtolower($this->input->post('username'));
                    $email = strtolower($this->input->post('email'));

                    $data = array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'phone' => $this->input->post('phone'),
                        'username' => $username,
                        'email' => $email,
                    );

                    // check to see if we are updating the user
                    if ($this->ion_auth->update($user->id, $data))
                    {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        $user = $this->ion_auth->user($user_id)->row();
                        redirect('admin/admin_dashboard', 'refresh');
                    }
                    else
                    {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }
            }
        }
        $this->data['image_path'] = $this->album_admin_profile;
        $this->data['image'] = $user->profile_image;
        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();
        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        // pass the user to the view
        $this->data['user'] = $user;

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
            'class' => 'phone_blur',
        );

        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['page_path_name'] = 'admin/profile';
        $this->load->view('template/index', $this->data);
    }

    function update_profile_image()
    {
        if (!$this->m_admin->check_is_any_admin())
        {
            redirect('/', 'refresh');
        }
        $user_id = $this->ion_auth->user()->row()->id;
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "change_image")
            {
                $upload_rule = array(
                    'upload_path' => $this->album_admin_profile,
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

                    if ($this->ion_auth->update($user_id, $data))
                    {
                        $this->session->set_flashdata('message', 'User profile image success update.');
                    }
                    else
                    {

                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }

                redirect('admin/admin_dashboard', 'refresh');
            }
        }
    }

    function category_management()
    {
        if (!$this->m_admin->check_is_any_admin(72))
        {
            redirect('/', 'refresh');
        }

        $search_category = 0;
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "filter_result")
            {
                $search_category = $this->input->post('main_category_id');
            }
        }

        $this->data['main_category_list'] = $this->m_custom->getCategoryList('0', 'All Category', 0, 1);
        $this->data['main_category_id'] = array(
            'name' => 'main_category_id',
            'id' => 'main_category_id',
        );
        $this->data['main_category_selected'] = $search_category;

        $category_list = $this->m_custom->getCategory(0, 1, 1, 1, $search_category);  //0, 1, 1, 1 will show hide
        $this->data['the_result'] = $category_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/category_management';
        $this->load->view('template/index', $this->data);
    }

    function category_add()
    {
        if (!$this->m_admin->check_is_any_admin(72))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $category_label = $this->input->post('category_label');
            $category_level = $this->input->post('category_level') == NULL ? 0 : 1;   //Check box special handling to know is checked or not
            $main_category_id = $this->input->post('main_category_id') == 0 ? NULL : $this->input->post('main_category_id');

            // validate form input
            $this->form_validation->set_rules('category_label', 'Category Name', 'required');
            if ($category_level == 1)
            {
                $this->form_validation->set_rules('main_category_id', 'Under Which Main Category', 'callback_check_main_category_id');
            }

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $data = array(
                        'category_label' => $category_label,
                        'category_level' => $category_level,
                        'main_category_id' => $main_category_id,
                    );

                    $new_id = $this->m_custom->get_id_after_insert('category', $data);
                    if ($new_id)
                    {
                        $this->m_custom->insert_row_log('category', $new_id, $login_id, $login_type);
                        $message_info = add_message_info($message_info, $category_label . ' success create.');
                        $can_redirect_to = 2;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/category_management', 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/category_edit/' . $new_id, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['category_label'] = array(
            'name' => 'category_label',
            'id' => 'category_label',
            'type' => 'text',
            'value' => $this->form_validation->set_value('category_label'),
        );

        $this->data['category_level'] = array(
            'name' => 'category_level',
            'id' => 'category_level',
            'checked' => TRUE,
            'onclick' => "checkbox_showhide('category_level','category-sub-div')",
            'value' => $login_id, //Just to have some value, checkbox have to have value
        );

        $this->data['main_category_list'] = $this->m_custom->getCategoryList('0', 'Please Select');
        $this->data['main_category_id'] = array(
            'name' => 'main_category_id',
            'id' => 'main_category_id',
        );

        $this->data['page_path_name'] = 'admin/category_add';
        $this->load->view('template/index', $this->data);
    }

    function category_edit($edit_id)
    {
        if (!$this->m_admin->check_is_any_admin(72))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $result = $this->m_custom->get_one_table_record('category', 'category_id', $edit_id, 1);

        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $id = $this->input->post('id');
            $category_label = $this->input->post('category_label');
            $category_level = $this->input->post('category_level') == NULL ? 0 : 1;   //Check box special handling to know is checked or not
            $main_category_id = $this->input->post('main_category_id') == 0 ? NULL : $this->input->post('main_category_id');

            // validate form input
            $this->form_validation->set_rules('category_label', 'Category Name', 'required');
            if ($category_level == 1)
            {
                $this->form_validation->set_rules('main_category_id', 'Under Which Main Category', 'callback_check_main_category_id');
            }

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    //Error checking and prevention
                    if ($category_level == 1)
                    {
                        $count_check = count($this->m_custom->getSubCategory($id));
                        if ($count_check > 0)
                        {
                            $message_info = add_message_info($message_info, $category_label . ' cannot become other Main Category Sub Category, because it still have Sub Category under it.');
                            $can_redirect_to = 1;
                            goto direct_go;
                        }
                        if ($id == $main_category_id)
                        {
                            $message_info = add_message_info($message_info, $category_label . ' cannot become it own Sub Category.');
                            $can_redirect_to = 1;
                            goto direct_go;
                        }
                    }

                    $data = array(
                        'category_label' => $category_label,
                        'category_level' => $category_level,
                        'main_category_id' => $main_category_id,
                    );

                    if ($this->m_custom->simple_update('category', $data, 'category_id', $id))
                    {
                        $this->m_custom->update_row_log('category', $id, $login_id, $login_type);
                        $message_info = add_message_info($message_info, $category_label . ' success update.');
                        $can_redirect_to = 1;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "frozen")
            {
                $count_check = count($this->m_custom->getSubCategory($edit_id));
                if ($count_check > 0)
                {
                    $message_info = add_message_info($message_info, $category_label . ' cannot hide, because it still have Sub Category under it.');
                    $can_redirect_to = 1;
                }
                else
                {
                    $message_info = add_message_info($message_info, $category_label . ' success hide.');
                    $this->m_custom->update_hide_flag(1, 'category', $edit_id);
                    $can_redirect_to = 2;
                }
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $category_label . ' success recover.');
                $this->m_custom->update_hide_flag(0, 'category', $edit_id);
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "remove_real")
            {
                $count_check = $this->m_custom->check_still_have_any_sub_category($edit_id);
                if ($count_check > 0)
                {
                    $message_info = add_message_info($message_info, $category_label . ' cannot remove, because it still have Sub Category under it not yet totally remove.');
                    $can_redirect_to = 1;
                }
                else
                {
                    $message_info = add_message_info($message_info, $category_label . ' success remove.');
                    $this->m_custom->update_remove_flag(1, 'category', $edit_id);
                    $can_redirect_to = 2;
                }
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/category_management', 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;

        $this->data['category_label'] = array(
            'name' => 'category_label',
            'id' => 'category_label',
            'type' => 'text',
            'value' => $this->form_validation->set_value('category_label', $result['category_label']),
        );

        $category_level_value = $result['category_level'];
        $this->data['category_level_value'] = $category_level_value;
        $this->data['category_level'] = array(
            'name' => 'category_level',
            'id' => 'category_level',
            'checked' => $category_level_value == "1" ? TRUE : FALSE,
            'onclick' => "checkbox_showhide('category_level','category-sub-div')",
            'value' => $this->form_validation->set_value('category_level', $edit_id),
        );

        $this->data['main_category_list'] = $this->m_custom->getCategoryList('0', 'Please Select', 0, 1);
        $this->data['main_category_id'] = array(
            'name' => 'main_category_id',
            'id' => 'main_category_id',
        );
        $this->data['main_category_selected'] = $result['main_category_id'] == NULL ? '0' : $result['main_category_id'];

        $this->data['page_path_name'] = 'admin/category_edit';
        $this->load->view('template/index', $this->data);
    }

    function check_main_category_id($dropdown_selection)
    {
        if ($dropdown_selection == 0)
        {
            $this->form_validation->set_message('check_main_category_id', 'The Main Category field is required if it is a Sub Category');
            return FALSE;
        }
        return TRUE;
    }

    function user_view($user_id)
    {
        if (!$this->m_admin->check_is_any_admin(86))
        {
            redirect('/', 'refresh');
        }

        $user = $this->m_custom->getUser($user_id, $this->group_id_user);

        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "back")
            {
                redirect('admin/user_management', 'refresh');
            }
        }

        $this->data['image_path'] = $this->album_user_profile;
        $this->data['image'] = $user['profile_image'];
        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();
        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        // pass the user to the view
        $this->data['user'] = $user;
        $this->data['title'] = "User View";
        $this->data['can_edit'] = 0;
        $this->data['user_id'] = $user_id;

        $the_date = explode('-', $user['us_birthday']);
        $this->data['b_year'] = $the_date[0];
        $this->data['b_month'] = $the_date[1];
        $this->data['b_day'] = $the_date[2];
        $this->data['username'] = array(
            'name' => 'username',
            'id' => 'username',
            'type' => 'text',
            'value' => $this->form_validation->set_value('username', $user['username']),
        );
        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user['first_name']),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user['last_name']),
        );
//        $promo_code = $this->m_custom->promo_code_get('user', $user->id, 1);
//        $this->data['promo_code_no'] = array(
//            'name' => 'promo_code_no',
//            'id' => 'promo_code_no',
//            'type' => 'text',
//            'readonly' => 'true',
//            'value' => $promo_code,
//        );
//        $this->data['promo_code_url'] = $this->m_custom->generate_promo_code_list_link($promo_code, 32);
        $this->data['description'] = array(
            'name' => 'description',
            'id' => 'description',
            'value' => $this->form_validation->set_value('description', $user['description']),
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $user['email']),
        );
        $this->data['day_list'] = generate_number_option(1, 31);
        $this->data['day'] = array(
            'name' => 'day',
            'id' => 'day',
        );
        $this->data['month_list'] = $this->ion_auth->get_static_option_list('month');
        $this->data['month'] = array(
            'name' => 'month',
            'id' => 'month',
        );
        $this->data['year_list'] = generate_number_option(1930, 2010);
        $this->data['year'] = array(
            'name' => 'year',
            'id' => 'year',
        );
        $this->data['age'] = array(
            'name' => 'age',
            'id' => 'age',
            'type' => 'text',
            'readonly' => 'true',
            'value' => age_count($user['us_birthday']),
        );
        $this->data['gender_list'] = $this->ion_auth->get_static_option_list('gender');
        $this->data['gender_id'] = array(
            'name' => 'gender_id',
            'id' => 'gender_id',
        );
        $this->data['us_gender_id'] = $user['us_gender_id'];

        $this->data['race_list'] = $this->ion_auth->get_static_option_list('race');
        $this->data['race_id'] = array(
            'name' => 'race_id',
            'id' => 'race_id',
            'onchange' => 'showraceother()',
        );
        $this->data['us_race_id'] = $user['us_race_id'];

        $this->data['race_other'] = array(
            'name' => 'race_other',
            'id' => 'race_other',
            'type' => 'text',
            'style' => $this->m_custom->display_static_option($user['us_race_id']) == 'Other' ? 'display:inline' : 'display:none',
            'value' => $this->form_validation->set_value('race_other', $user['us_race_other']),
        );
        $this->data['race_other_attributes'] = array(
            'id' => 'race_other_label',
            'style' => $this->m_custom->display_static_option($user['us_race_id']) == 'Other' ? 'display:inline' : 'display:none',
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $user['phone']),
            'class' => 'phone_blur',
        );

        $this->data['instagram_url'] = array(
            'name' => 'instagram_url',
            'id' => 'instagram_url',
            'type' => 'text',
            'value' => $this->form_validation->set_value('instagram_url', $user['us_instagram_url']),
        );
        $this->data['facebook_url'] = array(
            'name' => 'facebook_url',
            'id' => 'facebook_url',
            'type' => 'text',
            'value' => $this->form_validation->set_value('facebook_url', $user['us_facebook_url']),
        );

        //Blogger Function
        $us_is_blogger = $user['us_is_blogger'];
        $this->data['us_is_blogger'] = $us_is_blogger;
        $this->data['is_blogger'] = array(
            'name' => 'is_blogger',
            'id' => 'is_blogger',
            'checked' => $us_is_blogger == "1" ? TRUE : FALSE,
            'onclick' => "checkbox_showhide('is_blogger','profile-blogger-div')",
            'value' => $this->form_validation->set_value('is_blogger', $us_is_blogger),
        );

        $this->data['blog_url'] = array(
            'name' => 'blog_url',
            'id' => 'blog_url',
            'type' => 'text',
            'value' => $this->form_validation->set_value('blog_url', $user['us_blog_url']),
        );

        $this->data['blogger_list'] = $this->m_custom->get_dynamic_option_array('photography');
        $this->data['blogger_current'] = empty($user) ? array() : $this->m_custom->many_get_childlist('blogger', $user['id']);

        //Photographer Function
        $us_is_photographer = $user['us_is_photographer'];
        $this->data['us_is_photographer'] = $us_is_photographer;
        $this->data['is_photographer'] = array(
            'name' => 'is_photographer',
            'id' => 'is_photographer',
            'checked' => $us_is_photographer == "1" ? TRUE : FALSE,
            'onclick' => "checkbox_showhide('is_photographer','profile-photographer-div')",
            'value' => $this->form_validation->set_value('is_photographer', $us_is_photographer),
        );

        $this->data['photography_url'] = array(
            'name' => 'photography_url',
            'id' => 'photography_url',
            'type' => 'text',
            'value' => $this->form_validation->set_value('photography_url', $user['us_photography_url']),
        );

        $this->data['photography_list'] = $this->m_custom->get_dynamic_option_array('photography');
        $this->data['photography_current'] = empty($user) ? array() : $this->m_custom->many_get_childlist('photography', $user['id']);

        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['page_path_name'] = 'user/profile';
        $this->load->view('template/index', $this->data);
    }

    function user_management()
    {
        if (!$this->m_admin->check_is_any_admin(86))
        {
            redirect('/', 'refresh');
        }
        
        $this->m_custom->update_user_age();
        $user_list = $this->m_custom->getAllUser();
        $this->data['the_result'] = $user_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/user_management';
        $this->load->view('template/index', $this->data);
    }

    function user_special_action()
    {
        if (!$this->m_admin->check_is_any_admin(86))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 1;
            $login_id = $this->login_id;
            $id = $this->input->post('id');
            $user_login_info = $this->m_custom->getUserLoginInfo($id);
            $user_display_name = $this->m_custom->display_users($id);
            if ($this->input->post('button_action') == "log_in_as" && $this->m_admin->check_worker_role(61))
            {
                if ($user_login_info)
                {
                    if ($this->ion_auth->login($user_login_info['username'], $user_login_info['password_visible'], FALSE, $user_login_info['main_group_id'], $login_id, 1))
                    {
                        $this->m_custom->promo_code_insert_user($id);
                        redirect('all/user_dashboard/' . $id, 'refresh');
                    }
                    else
                    {
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "frozen" && $this->m_admin->check_worker_role(85))
            {
                $message_info = add_message_info($message_info, $user_display_name . ' success frozen.');
                $this->m_custom->update_hide_flag(1, 'users', $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $user_display_name . ' success unfrozen.');
                $this->m_custom->update_hide_flag(0, 'users', $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "remove" && $this->m_admin->check_worker_role(85))
            {
                $message_info = add_message_info($message_info, $user_display_name . ' success hide.');
                $this->m_custom->update_remove_flag(1, 'users', $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "recover_remove")
            {
                $message_info = add_message_info($message_info, $user_display_name . ' success unhide.');
                $this->m_custom->update_remove_flag(0, 'users', $id);
                $can_redirect_to = 1;
            }
            
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect('admin/user_management', 'refresh');
            }
        }
    }

    function user_bonus_candie($user_id)
    {
        if (!$this->m_admin->check_is_any_admin(74))
        {
            redirect('/', 'refresh');
        }

        $result = $this->m_custom->getUser($user_id, $this->group_id_user);

        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $user_id = $this->input->post('user_id');
            $amount_change = $this->input->post('amount_change');
            $trans_remark = $this->input->post('trans_remark');
            $user_name = $this->m_custom->display_users($user_id);

            // validate form input
            $this->form_validation->set_rules('amount_change', 'Bonus Candie Amount (RM)', 'required|integer');
            $this->form_validation->set_rules('trans_remark', 'Bonus Reason', 'required');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $new_id = $this->m_admin->trans_extra_bonus_candie($user_id, $amount_change, $trans_remark);
                    if ($new_id)
                    {
                        $message_info = add_message_info($message_info, 'Succes give ' . $user_name . ' ' . $amount_change . ' bonus candie.');
                        $can_redirect_to = 1;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/user_management', 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;

        $this->data['amount_change'] = array(
            'name' => 'amount_change',
            'id' => 'amount_change',
            'type' => 'text',
            'value' => $this->form_validation->set_value('amount_change'),
        );

        $this->data['trans_remark'] = array(
            'name' => 'trans_remark',
            'id' => 'trans_remark',
            'value' => $this->form_validation->set_value('trans_remark'),
        );

        $result_list = $this->m_custom->get_many_table_record('transaction_extra', 'trans_conf_id', '31', 1, 'user_id', $user_id, 'trans_time', 'desc');
        $this->data['the_result'] = $result_list;
        $this->data['page_path_name'] = 'admin/user_bonus_candie_change';
        $this->load->view('template/index', $this->data);
    }

    function user_balance_adjust($user_id, $request_msg_id = NULL)
    {
        if (!$this->m_admin->check_is_any_admin(75))
        {
            redirect('/', 'refresh');
        }

        $result = $this->m_custom->getUser($user_id, $this->group_id_user);

        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        if ($request_msg_id != NULL)  //to check the request message id is it correct for this user
        {
            $allowed_list = $this->m_custom->get_list_of_allow_id('user_message', 'msg_from_id', $user_id, 'msg_id', 'msg_type', 'withdraw');
            if (!check_allowed_list($allowed_list, $request_msg_id))
            {
                $request_msg_id = NULL;
            }
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $user_id = $this->input->post('user_id');
            $amount_change = $this->input->post('amount_change');
            $trans_remark = $this->input->post('trans_remark');
            $user_name = $this->m_custom->display_users($user_id);

            // validate form input
            $this->form_validation->set_rules('amount_change', 'Balance Adjust Amount (RM)', 'required|numeric');
            $this->form_validation->set_rules('trans_remark', 'Adjust Reason', 'required');

            if ($this->input->post('button_action') == "save")
            {
                if ($request_msg_id != NULL)  //if have link to user withdraw request then need check this
                {
                    $msg_reply = $this->input->post('msg_reply');
                    $this->form_validation->set_rules('msg_reply', 'Admin Reply', 'required');
                }

                if ($this->form_validation->run() === TRUE)
                {
                    $new_id = $this->m_admin->trans_extra_balance_adjust($user_id, $amount_change, $trans_remark);
                    if ($new_id)
                    {
                        if ($request_msg_id != NULL)
                        {
                            $data = array(
                                'msg_reply' => $msg_reply,
                            );
                            $this->m_custom->simple_update('user_message', $data, 'msg_id', $request_msg_id);  //To update also the message to reply to user
                        }

                        $message_info = add_message_info($message_info, 'Succes adjust ' . $user_name . ' user balance.');
                        $can_redirect_to = 1;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                if ($request_msg_id == NULL)  //either redirect back to withdraw request page or user page
                {
                    $can_redirect_to = 2;
                }
                else
                {
                    $can_redirect_to = 3;
                }
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/user_management', 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/user_withdraw', 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;

        $this->data['amount_change'] = array(
            'name' => 'amount_change',
            'id' => 'amount_change',
            'type' => 'text',
            'value' => $request_msg_id == NULL ? $this->form_validation->set_value('amount_change') : $this->form_validation->set_value('amount_change', '-50'),
            'placeholder' => '-50',
        );

        $this->data['trans_remark'] = array(
            'name' => 'trans_remark',
            'id' => 'trans_remark',
            'value' => $this->form_validation->set_value('trans_remark'),
        );

        $this->data['request_msg_id'] = $request_msg_id;
        $this->data['msg_reply'] = array(
            'name' => 'msg_reply',
            'id' => 'msg_reply',
            'value' => $this->form_validation->set_value('msg_reply'),
        );

        $result_list = $this->m_custom->get_many_table_record('transaction_extra', 'trans_conf_id', '23', 1, 'user_id', $user_id, 'trans_time', 'desc');
        $this->data['the_result'] = $result_list;
        $this->data['page_path_name'] = 'admin/user_balance_adjust';
        $this->load->view('template/index', $this->data);
    }

    function user_withdraw($view_status = 0)
    {
        if (!$this->m_admin->check_is_any_admin(75))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $main_table = 'user_message';
        $main_table_id_column = 'msg_id';

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $view_status = $this->input->post('view_status_id');
            $id = $this->input->post('id');
            $display_name = $this->m_custom->display_users($this->input->post('msg_from_id'));

            if ($this->input->post('button_action') == "success")
            {
                $data = array(
                    'msg_status' => 1,
                    'status_change_by' => $login_id,
                );

                if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $id))
                {
                    $message_info = add_message_info($message_info, ' success withdraw.', $display_name);
                    $can_redirect_to = 1;
                }
            }
            if ($this->input->post('button_action') == "fail")
            {
                $data = array(
                    'msg_status' => 2,
                    'status_change_by' => $login_id,
                );

                if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $id))
                {
                    $message_info = add_message_info($message_info, ' fail to withdraw.', $display_name);
                    $can_redirect_to = 1;
                }
            }

            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
        }

        $this->data['view_status_list'] = array('0' => 'Show New Only', '1' => 'Show Success Only', '2' => 'Show Fail Only', '3' => 'Show All');
        $this->data['view_status_id'] = array(
            'name' => 'view_status_id',
            'id' => 'view_status_id',
        );
        $this->data['view_status_selected'] = $view_status;

        $result_list = $this->m_admin->user_withdraw_request(0, $view_status);
        $this->data['the_result'] = $result_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/user_withdraw';
        $this->load->view('template/index', $this->data);
    }

    function user_withdraw_change($edit_id)
    {
        if (!$this->m_admin->check_is_any_admin(75))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $main_table = 'user_message';
        $main_table_id_column = 'msg_id';
        $result = $this->m_custom->get_one_table_record($main_table, $main_table_id_column, $edit_id, 1);

        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $id = $this->input->post('id');
            $msg_reply = $this->input->post('msg_reply');

            $this->form_validation->set_rules('msg_reply', 'Admin Reply', 'required');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $data = array(
                        'msg_reply' => $msg_reply,
                    );

                    if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $id))
                    {
                        $message_info = add_message_info($message_info, 'Record success update.');
                        $can_redirect_to = 2;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/user_withdraw', 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;
        $this->data['title'] = "Request Cash Back Withdraw Reply Edit";
        $this->data['can_edit'] = 1;

        $this->data['msg_reply'] = array(
            'name' => 'msg_reply',
            'id' => 'msg_reply',
            'value' => $result['msg_reply'],
        );      

        $this->data['page_path_name'] = 'admin/user_withdraw_change';
        $this->load->view('template/index', $this->data);
    }
    
    function merchant_management($low_balance_only = 0, $show_notyet_active = 0)
    {
        if (!$this->m_admin->check_is_any_admin(65))
        {
            redirect('/', 'refresh');
        }

        if ($low_balance_only == 1)
        {
            $user_list = $this->m_admin->merchant_low_balance_count();
        }
        else
        {
            $user_list = $this->m_custom->getAllMerchant($show_notyet_active);
        }
        $this->data['the_result'] = $user_list;
        $this->data['low_balance_only'] = $low_balance_only;
        $this->data['show_notyet_active'] = $show_notyet_active;
        
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/merchant_management';
        $this->load->view('template/index', $this->data);
    }

    function merchant_edit($edit_id, $low_balance_only = 0)
    {
        if (!$this->m_admin->check_is_any_admin(78))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $result = $this->m_custom->getUser($edit_id, $this->group_id_merchant);

        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $id = $this->input->post('id');
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $company_main = $this->input->post('company_main');
            $company = $this->input->post('company');
            $me_person_incharge = $this->input->post('me_person_incharge');
            $me_person_contact = $this->input->post('me_person_contact');
            $me_ssm = $this->input->post('me_ssm');
            $me_category_id = $this->input->post('me_category_id');
            $address = $this->input->post('address');
            $postcode = $this->input->post('postcode');
            $me_state_id = $this->input->post('me_state_id');
            $phone = $this->input->post('phone');
            //$me_is_halal = $this->input->post('me_is_halal') == NULL ? 0 : 1;
            $me_halal_way = $this->input->post('me_halal_way');
            
            // to generate company slug for check is it unique
            $_POST['slug'] = generate_slug($_POST['company']);
            $slug = $_POST['slug'];

            $tables = $this->config->item('tables', 'ion_auth');

            // validate form input
            $this->form_validation->set_rules('username', $this->lang->line('create_merchant_validation_username_label'), 'trim|required|is_unique_edit[' . $tables['users'] . '.username.' . $edit_id . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_merchant_validation_email_label'), 'trim|required|valid_email|is_unique_edit[' . $tables['users'] . '.email.' . $edit_id . ']');
            $this->form_validation->set_rules('company_main', $this->lang->line('create_merchant_validation_company_main_label'), "trim|required|min_length[3]");
            $this->form_validation->set_rules('company', $this->lang->line('create_merchant_validation_company_label'), "trim|required|min_length[3]");
            $this->form_validation->set_rules('slug', $this->lang->line('create_merchant_validation_company_label'), 'trim|is_unique_edit[' . $tables['users'] . '.slug.' . $edit_id . ']');
            $this->form_validation->set_rules('me_person_incharge', $this->lang->line('create_merchant_validation_person_incharge_label'));
            //$this->form_validation->set_rules('me_person_contact', $this->lang->line('create_merchant_validation_person_contact_label'), 'required|valid_contact_number');
            $this->form_validation->set_rules('me_person_contact', $this->lang->line('create_merchant_validation_person_contact_label'));
            $this->form_validation->set_rules('me_ssm', $this->lang->line('create_merchant_validation_companyssm_label'), 'required');
            $this->form_validation->set_rules('me_category_id', $this->lang->line('create_merchant_category_label'), 'callback_check_main_category');
            $this->form_validation->set_rules('address', $this->lang->line('create_merchant_validation_address_label'), 'required');
            $this->form_validation->set_rules('postcode', $this->lang->line('create_merchant_validation_postcode_label'), 'required|numeric');
            $this->form_validation->set_rules('me_state_id', $this->lang->line('create_merchant_validation_state_label'), 'callback_check_state_id');
            //$this->form_validation->set_rules('phone', $this->lang->line('create_merchant_validation_phone_label'), 'required|valid_contact_number');
            $this->form_validation->set_rules('phone', $this->lang->line('create_merchant_validation_phone_label'));
            
            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $data = array(
                        'username' => $username,
                        'email' => $email,
                        'company_main' => $company_main,
                        'company' => $company,
                        'slug' => $slug,
                        'me_person_incharge' => $me_person_incharge,
                        'me_person_contact' => $me_person_contact,
                        'me_ssm' => $me_ssm,
                        'me_category_id' => $me_category_id,
                        'address' => $address,
                        'postcode' => $postcode,
                        'me_state_id' => $me_state_id,
                        'phone' => $phone,
                        //'me_is_halal' => $me_is_halal,
                        'me_halal_way' => $me_halal_way,
                    );

                    //if ($this->m_admin->check_is_any_admin(87))
                    if (check_correct_login_type($this->group_id_admin))  //only admin can assign merchant to worker
                    {
                        $merchant_worker_selected = array();
                        $post_merchant_worker = $this->input->post('merchant_worker');
                        if (!empty($post_merchant_worker))
                        {
                            foreach ($post_merchant_worker as $key => $value)
                            {
                                $merchant_worker_selected[] = $value;
                            }
                        }
                    }
                    
                    if ($this->ion_auth->update($edit_id, $data))
                    {
                        $message_info = add_message_info($message_info, $company . ' success update.');
                        $this->m_custom->update_row_log('users', $edit_id, $login_id, $login_type);
                        if (check_correct_login_type($this->group_id_admin))  //only admin can assign merchant to worker
                        {
                            $this->m_custom->many_insert_or_remove('merchant_worker', $edit_id, $merchant_worker_selected);
                        }
                        $can_redirect_to = 1;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $company . ' success remove.');
                $this->m_custom->update_hide_flag(1, 'users', $edit_id);
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $company . ' success recover.');
                $this->m_custom->update_hide_flag(0, 'users', $edit_id);
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                if ($low_balance_only == 1)
                {
                    redirect('admin/merchant_management/1', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_management', 'refresh');
                }
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;
        $this->data['title'] = "Merchant Edit";
        $this->data['can_edit'] = 1;

        $this->data['username'] = array(
            'name' => 'username',
            'id' => 'username',
            'type' => 'text',
            'value' => $this->form_validation->set_value('username', $result['username']),
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $result['email']),
        );
        $this->data['company_main'] = array(
            'name' => 'company_main',
            'id' => 'company_main',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company_main', $result['company_main']),
        );
        $this->data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $result['company']),
        );

        $this->data['me_person_incharge'] = array(
            'name' => 'me_person_incharge',
            'id' => 'me_person_incharge',
            'type' => 'text',
            'value' => $this->form_validation->set_value('me_person_incharge', $result['me_person_incharge']),
        );
        $this->data['me_person_contact'] = array(
            'name' => 'me_person_contact',
            'id' => 'me_person_contact',
            'type' => 'text',
            'value' => $this->form_validation->set_value('me_person_contact', $result['me_person_contact']),
        );

        $this->data['me_ssm'] = array(
            'name' => 'me_ssm',
            'id' => 'me_ssm',
            'type' => 'text',
            'value' => $this->form_validation->set_value('me_ssm', $result['me_ssm']),
        );

        $this->data['category_selected'] = $result['me_category_id'];
        //$this->data['category_list'] = $this->ion_auth->get_main_category_list();
        $this->data['category_list'] = $this->m_custom->getCategoryList();
        $this->data['me_category_id'] = array(
            'name' => 'me_category_id',
            'id' => 'me_category_id',
            'value' => $this->form_validation->set_value('me_category_id'),
        );
        $this->data['address'] = array(
            'name' => 'address',
            'id' => 'address',
            'value' => $this->form_validation->set_value('address', $result['address']),
        );
        $this->data['postcode'] = array(
            'name' => 'postcode',
            'id' => 'postcode',
            'type' => 'text',
            'value' => $this->form_validation->set_value('postcode', $result['postcode']),
        );
        $this->data['state_selected'] = $result['me_state_id'];
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
            'value' => $this->form_validation->set_value('phone', $result['phone']),
        );
        
//        $me_is_halal = $result['me_is_halal'];
//        $this->data['me_is_halal'] = array(
//            'name' => 'me_is_halal',
//            'id' => 'me_is_halal',
//            'checked' => $me_is_halal == "1" ? TRUE : FALSE,
//            'value' => $result['me_is_halal'],
//        );
        
        $this->data['halal_way_selected'] = $result['me_halal_way'];
        $this->data['halal_way_list'] = $this->ion_auth->get_static_option_list('halal_way');
        $this->data['me_halal_way'] = array(
            'name' => 'me_halal_way',
            'id' => 'me_halal_way',
        );
        
        $this->data['merchant_worker_current'] = empty($result) ? array() : $this->m_custom->many_get_childlist('merchant_worker', $result['id']);
        $this->data['merchant_worker'] = $this->m_admin->getAllWorker();
        
        $this->data['page_path_name'] = 'admin/merchant_edit';
        $this->load->view('template/index', $this->data);
    }

    function merchant_view($edit_id, $low_balance_only = 0)
    {
        if (!$this->m_admin->check_is_any_admin(65))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $result = $this->m_custom->getUser($edit_id, $this->group_id_merchant);

        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                if ($low_balance_only == 1)
                {
                    redirect('admin/merchant_management/1', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_management', 'refresh');
                }
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;
        $this->data['title'] = "Merchant View";
        $this->data['can_edit'] = 0;

        $this->data['username'] = array(
            'name' => 'username',
            'id' => 'username',
            'type' => 'text',
            'value' => $this->form_validation->set_value('username', $result['username']),
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $result['email']),
        );
        $this->data['company_main'] = array(
            'name' => 'company_main',
            'id' => 'company_main',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company_main', $result['company_main']),
        );
        $this->data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $result['company']),
        );

        $this->data['me_person_incharge'] = array(
            'name' => 'me_person_incharge',
            'id' => 'me_person_incharge',
            'type' => 'text',
            'value' => $this->form_validation->set_value('me_person_incharge', $result['me_person_incharge']),
        );
        $this->data['me_person_contact'] = array(
            'name' => 'me_person_contact',
            'id' => 'me_person_contact',
            'type' => 'text',
            'value' => $this->form_validation->set_value('me_person_contact', $result['me_person_contact']),
        );

        $this->data['me_ssm'] = array(
            'name' => 'me_ssm',
            'id' => 'me_ssm',
            'type' => 'text',
            'value' => $this->form_validation->set_value('me_ssm', $result['me_ssm']),
        );

        $this->data['category_selected'] = $result['me_category_id'];
        $this->data['category_list'] = $this->m_custom->getCategoryList();
        //$this->data['category_list'] = $this->ion_auth->get_main_category_list();
        $this->data['me_category_id'] = array(
            'name' => 'me_category_id',
            'id' => 'me_category_id',
            'value' => $this->form_validation->set_value('me_category_id'),
        );
        $this->data['address'] = array(
            'name' => 'address',
            'id' => 'address',
            'value' => $this->form_validation->set_value('address', $result['address']),
        );
        $this->data['postcode'] = array(
            'name' => 'postcode',
            'id' => 'postcode',
            'type' => 'text',
            'value' => $this->form_validation->set_value('postcode', $result['postcode']),
        );
        $this->data['state_selected'] = $result['me_state_id'];
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
            'value' => $this->form_validation->set_value('phone', $result['phone']),
        );

//        $me_is_halal = $result['me_is_halal'];
//        $this->data['me_is_halal'] = array(
//            'name' => 'me_is_halal',
//            'id' => 'me_is_halal',
//            'checked' => $me_is_halal == "1" ? TRUE : FALSE,
//            'value' => $result['me_is_halal'],
//        );
        
        $this->data['halal_way_selected'] = $result['me_halal_way'];
        $this->data['halal_way_list'] = $this->ion_auth->get_static_option_list('halal_way');
        $this->data['me_halal_way'] = array(
            'name' => 'me_halal_way',
            'id' => 'me_halal_way',
        );
        
        $this->data['page_path_name'] = 'admin/merchant_edit';
        $this->load->view('template/index', $this->data);
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

    function merchant_special_action($low_balance_only = 0)
    {
        if (!$this->m_admin->check_is_any_admin(65))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 1;
            $login_id = $this->login_id;
            $id = $this->input->post('id');
            $user_login_info = $this->m_custom->getUserLoginInfo($id);
            $user_display_name = $this->m_custom->display_users($id);
            if ($this->input->post('button_action') == "log_in_as" && $this->m_admin->check_worker_role(60))
            {
                if ($user_login_info)
                {
                    if ($this->ion_auth->login($user_login_info['username'], $user_login_info['password_visible'], FALSE, $user_login_info['main_group_id'], $login_id, 1))
                    {
                        $this->m_custom->promo_code_insert_merchant($id);
                        redirect('all/merchant_dashboard/' . $user_login_info['slug'] . '//' . $id , 'refresh');
                    }
                    else
                    {
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "frozen" && $this->m_admin->check_worker_role(64))
            {
                $message_info = add_message_info($message_info, $user_display_name . ' success frozen.');
                $this->m_custom->update_hide_flag(1, 'users', $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $user_display_name . ' success unfrozen.');
                $this->m_custom->update_hide_flag(0, 'users', $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "remove" && $this->m_admin->check_worker_role(64))
            {
                $message_info = add_message_info($message_info, $user_display_name . ' success hide.');
                $this->m_custom->update_remove_flag(1, 'users', $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "recover_remove")
            {
                $message_info = add_message_info($message_info, $user_display_name . ' success unhide.');
                $this->m_custom->update_remove_flag(0, 'users', $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "active_merchant" && $this->m_admin->check_worker_role(87))
            {
                $message_info = add_message_info($message_info, $user_display_name . ' success active. Please remember change this merchant to real email and username.');
                $data = array(
                    'me_notyet_active' => 0,
                );
                $this->m_custom->simple_update('users', $data, 'id', $id);
                $can_redirect_to = 1;
            }

            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                if ($low_balance_only == 1)
                {
                    redirect('admin/merchant_management/1', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_management', 'refresh');
                }
            }
        }
    }

    function merchant_feecharge($merchant_id, $low_balance_only = 0)
    {
        if (!$this->m_admin->check_is_any_admin(67))
        {
            redirect('/', 'refresh');
        }

        $result = $this->m_custom->getUser($merchant_id, $this->group_id_merchant);
        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        $this->data['merchant_id'] = $merchant_id;
        $charge_list = $this->m_admin->getAllFeeCharge($merchant_id);
        $this->data['the_result'] = $charge_list;
        $this->data['low_balance_only'] = $low_balance_only;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/merchant_feecharge';
        $this->load->view('template/index', $this->data);
    }

    function merchant_feecharge_add($merchant_id, $low_balance_only = 0)
    {
        if (!$this->m_admin->check_is_any_admin(67))
        {
            redirect('/', 'refresh');
        }

        $result = $this->m_custom->getUser($merchant_id, $this->group_id_merchant);
        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $merchant_name = $this->m_custom->display_users($merchant_id);

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $feecharge_select = $this->input->post('feecharge_id');
            $feecharge_amount = $this->m_custom->display_dynamic_option($feecharge_select, NULL, NULL, 'option_value');
            $feecharge_remark = $this->input->post('feecharge_remark');

            // validate form input
            $this->form_validation->set_rules('feecharge_id', 'Fee Charge Type', 'callback_check_merchant_fee_id');
            $this->form_validation->set_rules('feecharge_remark', 'Transaction Remark');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $data = array(
                        'user_id' => $merchant_id,
                        'admin_id' => $login_id,
                        'trans_conf_id' => 20,
                        'amount_change' => $feecharge_amount * -1,
                        'refer_id' => $feecharge_select,
                        'trans_remark' => $feecharge_remark,
                    );

                    $new_id = $this->m_custom->get_id_after_insert('transaction_extra', $data);
                    if ($new_id)
                    {
                        $this->m_merchant->transaction_history_insert($merchant_id, 20, $new_id, 'transaction_extra', 0, $feecharge_amount);
                        $this->m_merchant->merchant_balance_update($merchant_id);
                        $this->m_custom->insert_row_log('transaction_extra', $new_id, $login_id, $login_type);
                        $message_info = add_message_info($message_info, $merchant_name . ' success add fee charge.');
                        $can_redirect_to = 2;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "back_list")
            {
                $can_redirect_to = 3;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                if ($low_balance_only == 1)
                {
                    redirect('admin/merchant_feecharge/' . $merchant_id . '/1', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_feecharge/' . $merchant_id, 'refresh');
                }
            }
            elseif ($can_redirect_to == 3)
            {
                if ($low_balance_only == 1)
                {
                    redirect('admin/merchant_management/1', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_management', 'refresh');
                }
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['feecharge_list'] = $this->m_custom->get_dynamic_option_array('merchant_fee', '0', 'Please Select', NULL, NULL, 0, 0, 1);
        $this->data['feecharge_id'] = array(
            'name' => 'feecharge_id',
            'id' => 'feecharge_id',
            'value' => $this->form_validation->set_value('feecharge_id'),
        );

        $this->data['feecharge_remark'] = array(
            'name' => 'feecharge_remark',
            'id' => 'feecharge_remark',
            'value' => $this->form_validation->set_value('feecharge_remark'),
        );

        $this->data['title'] = 'Merchant Fee Charge Add';
        $this->data['merchant_id'] = $merchant_id;

        $this->data['page_path_name'] = 'admin/merchant_feecharge_change';
        $this->load->view('template/index', $this->data);
    }

    function check_merchant_fee_id($dropdown_selection)
    {
        if ($dropdown_selection == 0)
        {
            $this->form_validation->set_message('check_merchant_fee_id', 'The Fee Charge Type is required');
            return FALSE;
        }
        return TRUE;
    }

    function merchant_topup($merchant_id, $low_balance_only = 0)
    {
        if (!$this->m_admin->check_is_any_admin(67))
        {
            redirect('/', 'refresh');
        }

        $result = $this->m_custom->getUser($merchant_id, $this->group_id_merchant);
        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        $this->data['merchant_id'] = $merchant_id;
        $topup_list = $this->m_admin->getAllTopup($merchant_id);
        $this->data['the_result'] = $topup_list;
        $this->data['low_balance_only'] = $low_balance_only;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/merchant_topup';
        $this->load->view('template/index', $this->data);
    }

    function merchant_topup_add($merchant_id, $low_balance_only = 0)
    {
        if (!$this->m_admin->check_is_any_admin(67))
        {
            redirect('/', 'refresh');
        }

        $result = $this->m_custom->getUser($merchant_id, $this->group_id_merchant);
        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $merchant_name = $this->m_custom->display_users($merchant_id);

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $topup_amount = $this->input->post('topup_amount');
            $topup_bank = $this->input->post('topup_bank');
            $topup_trans_date = validateDate($this->input->post('topup_trans_date'));
            $topup_trans_no = $this->input->post('topup_trans_no');
            $topup_remark = $this->input->post('topup_remark');

            // validate form input
            $this->form_validation->set_rules('topup_amount', 'Amount (RM)', 'required|numeric');
            $this->form_validation->set_rules('topup_bank', 'Transaction Bank');
            $this->form_validation->set_rules('topup_trans_date', 'Transaction Date');
            $this->form_validation->set_rules('topup_trans_no', 'Transaction No');
            $this->form_validation->set_rules('topup_remark', 'Transaction Remark');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $data = array(
                        'merchant_id' => $merchant_id,
                        'admin_id' => $login_id,
                        'topup_amount' => $topup_amount,
                        'topup_bank' => $topup_bank,
                        'topup_trans_date' => $topup_trans_date,
                        'topup_trans_no' => $topup_trans_no,
                        'topup_remark' => $topup_remark,
                    );

                    $new_id = $this->m_custom->get_id_after_insert('merchant_topup', $data);
                    if ($new_id)
                    {
                        $this->m_merchant->transaction_history_insert($merchant_id, 19, $new_id, 'merchant_topup', 0, $topup_amount);
                        $this->m_merchant->merchant_balance_update($merchant_id);
                        $this->m_custom->insert_row_log('merchant_topup', $new_id, $login_id, $login_type);
                        $message_info = add_message_info($message_info, $merchant_name . ' success topup.');
                        $can_redirect_to = 2;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "back_list")
            {
                $can_redirect_to = 3;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                if ($low_balance_only == 1)
                {
                    redirect('admin/merchant_topup/' . $merchant_id . '/1', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_topup/' . $merchant_id, 'refresh');
                }
            }
            elseif ($can_redirect_to == 3)
            {
                if ($low_balance_only == 1)
                {
                    redirect('admin/merchant_management/1', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_management', 'refresh');
                }
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['topup_amount'] = array(
            'name' => 'topup_amount',
            'id' => 'topup_amount',
            'type' => 'text',
            'value' => $this->form_validation->set_value('topup_amount'),
        );

        $this->data['topup_bank'] = array(
            'name' => 'topup_bank',
            'id' => 'topup_bank',
            'type' => 'text',
            'value' => $this->form_validation->set_value('topup_bank'),
        );

        $this->data['topup_trans_date'] = array(
            'name' => 'topup_trans_date',
            'id' => 'topup_trans_date',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $this->form_validation->set_value('topup_trans_date'),
        );

        $this->data['topup_trans_no'] = array(
            'name' => 'topup_trans_no',
            'id' => 'topup_trans_no',
            'type' => 'text',
            'value' => $this->form_validation->set_value('topup_trans_no'),
        );

        $this->data['topup_remark'] = array(
            'name' => 'topup_remark',
            'id' => 'topup_remark',
            'value' => $this->form_validation->set_value('topup_remark'),
        );

        $this->data['title'] = 'Merchant Top Up Add';
        $this->data['merchant_id'] = $merchant_id;

        $this->data['page_path_name'] = 'admin/merchant_topup_change';
        $this->load->view('template/index', $this->data);
    }

    function merchant_topup_edit($merchant_id, $edit_id, $low_balance_only = 0)
    {
        if (!$this->m_admin->check_is_any_admin(67))
        {
            redirect('/', 'refresh');
        }

        $result = $this->m_custom->getUser($merchant_id, $this->group_id_merchant);
        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $merchant_name = $this->m_custom->display_users($merchant_id);
        $result = $this->m_custom->get_one_table_record('merchant_topup', 'topup_id', $edit_id, 1);

        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $id = $this->input->post('id');
            $topup_amount = $this->input->post('topup_amount');
            $topup_bank = $this->input->post('topup_bank');
            $topup_trans_date = validateDate($this->input->post('topup_trans_date'));
            $topup_trans_no = $this->input->post('topup_trans_no');
            $topup_remark = $this->input->post('topup_remark');

            // validate form input
            $this->form_validation->set_rules('topup_amount', 'Amount (RM)', 'required|numeric');
            $this->form_validation->set_rules('topup_bank', 'Transaction Bank');
            $this->form_validation->set_rules('topup_trans_date', 'Transaction Date');
            $this->form_validation->set_rules('topup_trans_no', 'Transaction No');
            $this->form_validation->set_rules('topup_remark', 'Transaction Remark');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $data = array(
                        'admin_id' => $login_id,
                        //'topup_amount' => $topup_amount,
                        'topup_bank' => $topup_bank,
                        'topup_trans_date' => $topup_trans_date,
                        'topup_trans_no' => $topup_trans_no,
                        'topup_remark' => $topup_remark,
                    );

                    if ($this->m_custom->simple_update('merchant_topup', $data, 'topup_id', $id))
                    {
                        //$this->m_merchant->transaction_history_update($id, $topup_amount);
                        $this->m_merchant->merchant_balance_update($merchant_id);
                        $this->m_custom->update_row_log('merchant_topup', $id, $login_id, $login_type);
                        $message_info = add_message_info($message_info, $merchant_name . ' success update this topup record.');
                        $can_redirect_to = 2;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "back_list")
            {
                $can_redirect_to = 3;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                if ($low_balance_only == 1)
                {
                    redirect('admin/merchant_topup/' . $merchant_id . '/1', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_topup/' . $merchant_id, 'refresh');
                }
            }
            elseif ($can_redirect_to == 3)
            {
                if ($low_balance_only == 1)
                {
                    redirect('admin/merchant_management/1', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_management', 'refresh');
                }
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;

        $this->data['topup_amount'] = array(
            'name' => 'topup_amount',
            'id' => 'topup_amount',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $this->form_validation->set_value('topup_amount', $result['topup_amount']),
        );

        $this->data['topup_bank'] = array(
            'name' => 'topup_bank',
            'id' => 'topup_bank',
            'type' => 'text',
            'value' => $this->form_validation->set_value('topup_bank', $result['topup_bank']),
        );

        $this->data['topup_trans_date'] = array(
            'name' => 'topup_trans_date',
            'id' => 'topup_trans_date',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $this->form_validation->set_value('topup_trans_date', displayDate($result['topup_trans_date'])),
        );

        $this->data['topup_trans_no'] = array(
            'name' => 'topup_trans_no',
            'id' => 'topup_trans_no',
            'type' => 'text',
            'value' => $this->form_validation->set_value('topup_trans_no', $result['topup_trans_no']),
        );

        $this->data['topup_remark'] = array(
            'name' => 'topup_remark',
            'id' => 'topup_remark',
            'value' => $this->form_validation->set_value('topup_remark', $result['topup_remark']),
        );

        $this->data['title'] = 'Merchant Top Up Edit';
        $this->data['merchant_id'] = $merchant_id;
        $this->data['edit_id'] = $edit_id;

        $this->data['page_path_name'] = 'admin/merchant_topup_change';
        $this->load->view('template/index', $this->data);
    }

    function worker_management()
    {
        if (!$this->m_admin->check_is_any_admin(66))
        {
            redirect('/', 'refresh');
        }

        $user_list = $this->m_admin->getAllWorker();
        $this->data['the_result'] = $user_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/worker_management';
        $this->load->view('template/index', $this->data);
    }

    function worker_add()
    {
        if (!$this->m_admin->check_is_any_admin(66))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $us_ic = $this->input->post('us_ic');
            $wo_worker_id = $this->input->post('wo_worker_id');
            $wo_department = $this->input->post('wo_department');
            //$phone = '+60'.$this->input->post('phone');
            $phone = $this->input->post('phone');
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $wo_join_date = validateDate($this->input->post('wo_join_date'));
            $password = $this->input->post('password');

            // validate form input
            $tables = $this->config->item('tables', 'ion_auth');

            $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
            $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'));
            $this->form_validation->set_rules('us_ic', $this->lang->line('worker_ic_label'), 'required');
            $this->form_validation->set_rules('wo_worker_id', $this->lang->line('worker_id_label'), 'required');
            $this->form_validation->set_rules('wo_department', $this->lang->line('worker_department_label'));
            //$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required|valid_contact_number');
            $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
            $this->form_validation->set_rules('wo_join_date', $this->lang->line('worker_joindate_label'), 'trim|required');
            $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_username_label'), 'trim|required|is_unique[' . $tables['users'] . '.username]');
            $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
            //$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
            //$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $additional_data = array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'us_ic' => $us_ic,
                        'wo_worker_id' => $wo_worker_id,
                        'wo_department' => $wo_department,
                        'wo_join_date' => $wo_join_date,
                        'phone' => $phone,
                        'username' => $username,
                        'password_visible' => $password,
                        'main_group_id' => $this->group_id_worker,
                    );
                    $group_ids = array(
                        $this->group_id_worker
                    );

                    $new_id = $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids);
                    if ($new_id)
                    {
                        $message_info = add_message_info($message_info, $username . ' success create.');
                        $can_redirect_to = 3;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/worker_management', 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/worker_edit/' . $new_id, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['username'] = array(
            'name' => 'username',
            'id' => 'username',
            'type' => 'text',
            'value' => $this->form_validation->set_value('username'),
        );
        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name'),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name'),
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email'),
        );
        $this->data['us_ic'] = array(
            'name' => 'us_ic',
            'id' => 'us_ic',
            'type' => 'text',
            'value' => $this->form_validation->set_value('us_ic'),
        );
        $this->data['wo_worker_id'] = array(
            'name' => 'wo_worker_id',
            'id' => 'wo_worker_id',
            'type' => 'text',
            'value' => $this->form_validation->set_value('wo_worker_id'),
        );
        $this->data['wo_department'] = array(
            'name' => 'wo_department',
            'id' => 'wo_department',
            'type' => 'text',
            'value' => $this->form_validation->set_value('wo_department'),
        );
        $this->data['wo_join_date'] = array(
            'name' => 'wo_join_date',
            'id' => 'wo_join_date',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $this->form_validation->set_value('wo_join_date'),
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone'),
            'class' => 'phone_blur',
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            //'type' => 'password',
            'type' => 'text',
            'value' => $this->form_validation->set_value('password'),
        );
//        $this->data['password_confirm'] = array(
//            'name' => 'password_confirm',
//            'id' => 'password_confirm',
//            'type' => 'password',
//            'value' => $this->form_validation->set_value('password_confirm'),
//        );

        $this->data['page_path_name'] = 'admin/worker_add';
        $this->load->view('template/index', $this->data);
    }

    function worker_edit($edit_id)
    {
        if (!$this->m_admin->check_is_any_admin(66))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $result = $this->m_custom->getUser($edit_id, $this->group_id_worker);

        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $id = $this->input->post('id');
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $us_ic = $this->input->post('us_ic');
            $wo_worker_id = $this->input->post('wo_worker_id');
            $wo_department = $this->input->post('wo_department');
            $wo_join_date = validateDate($this->input->post('wo_join_date'));
            $phone = $this->input->post('phone');
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            //$category_level = $this->input->post('category_level') == NULL ? 0 : 1;   //Check box special handling to know is checked or not

            $tables = $this->config->item('tables', 'ion_auth');
            // validate form input
            $this->form_validation->set_rules('first_name', $this->lang->line('create_user_fname_label'), 'required');
            $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'));
            $this->form_validation->set_rules('us_ic', $this->lang->line('worker_ic_label'), 'required');
            $this->form_validation->set_rules('wo_worker_id', $this->lang->line('worker_id_label'), 'required');
            $this->form_validation->set_rules('wo_department', $this->lang->line('worker_department_label'));
            //$this->form_validation->set_rules('phone', $this->lang->line('create_user_phone_label'), 'required|valid_contact_number');
            $this->form_validation->set_rules('phone', $this->lang->line('create_user_phone_label'), 'required');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique_edit[' . $tables['users'] . '.email.' . $edit_id . ']');
            $this->form_validation->set_rules('wo_join_date', $this->lang->line('worker_joindate_label'), 'trim|required');
            $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_username_label'), 'trim|required|is_unique_edit[' . $tables['users'] . '.username.' . $edit_id . ']');
            $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $password_old = $result['password_visible'];
                    $data = array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'us_ic' => $us_ic,
                        'wo_worker_id' => $wo_worker_id,
                        'wo_department' => $wo_department,
                        'wo_join_date' => $wo_join_date,
                        'phone' => $phone,
                        'username' => $username,
                        'email' => $email,
                    );

                    $admin_role_selected = array();
                    $post_admin_role = $this->input->post('admin_role');
                    if (!empty($post_admin_role))
                    {
                        foreach ($post_admin_role as $key => $value)
                        {
                            $admin_role_selected[] = $value;
                        }
                    }

                    if ($this->ion_auth->update($edit_id, $data))
                    {
                        if ($password_old != $password)
                        {
                            if ($this->ion_auth->change_password_other_user($edit_id, $password_old, $password))
                            {
                                $message_info = add_message_info($message_info, $username . ' password success update.');
                            }
                            else
                            {
                                $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            }
                        }
                        $this->m_custom->many_insert_or_remove('admin_role', $edit_id, $admin_role_selected);
                        $message_info = add_message_info($message_info, $username . ' success update.');
                        $this->m_custom->update_row_log('users', $edit_id, $login_id, $login_type);
                        $can_redirect_to = 1;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $username . ' success remove.');
                $this->m_custom->update_hide_flag(1, 'users', $edit_id);
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $username . ' success recover.');
                $this->m_custom->update_hide_flag(0, 'users', $edit_id);
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/worker_management', 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;

        $this->data['username'] = array(
            'name' => 'username',
            'id' => 'username',
            'type' => 'text',
            'value' => $this->form_validation->set_value('username', $result['username']),
        );
        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $result['first_name']),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $result['last_name']),
        );
        $this->data['us_ic'] = array(
            'name' => 'us_ic',
            'id' => 'us_ic',
            'type' => 'text',
            'value' => $this->form_validation->set_value('us_ic', $result['us_ic']),
        );
        $this->data['wo_worker_id'] = array(
            'name' => 'wo_worker_id',
            'id' => 'wo_worker_id',
            'type' => 'text',
            'value' => $this->form_validation->set_value('wo_worker_id', $result['wo_worker_id']),
        );
        $this->data['wo_department'] = array(
            'name' => 'wo_department',
            'id' => 'wo_department',
            'type' => 'text',
            'value' => $this->form_validation->set_value('wo_department', $result['wo_department']),
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $result['email']),
        );
        $this->data['wo_join_date'] = array(
            'name' => 'wo_join_date',
            'id' => 'wo_join_date',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $this->form_validation->set_value('wo_join_date', displayDate($result['wo_join_date'])),
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $result['phone']),
            'class' => 'phone_blur',
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'type' => 'text',
            'value' => $this->form_validation->set_value('password', $result['password_visible']),
        );

        $this->data['admin_role_current'] = empty($result) ? array() : $this->m_custom->many_get_childlist('admin_role', $result['id']);
        $this->data['admin_role'] = $this->m_custom->get_static_option_array('admin_role', NULL, NULL, 1, NULL, 1);

        $this->data['page_path_name'] = 'admin/worker_edit';
        $this->load->view('template/index', $this->data);
    }

    function keppo_voucher_management()
    {
        if (!$this->m_admin->check_is_any_admin(70))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->ion_auth->user()->row()->id;
        $do_by_type = $this->main_group_id;
        $main_table = 'advertise';

        $search_category = 0;

        if (isset($_POST) && !empty($_POST))
        {
            $id = $this->input->post('id');
            $display_name = $this->input->post('title');
            if ($this->input->post('button_action') == "filter_result")
            {
                $search_category = $this->input->post('sub_category_id');
            }
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $display_name . ' success frozen.');
                $this->m_custom->update_frozen_flag(1, $main_table, $id);
                $this->m_custom->update_row_log('advertise', $id, $login_id, $do_by_type);
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $display_name . ' success unfrozen.');
                $this->m_custom->update_frozen_flag(0, $main_table, $id);
            }
            if ($this->input->post('button_action') == "remove")
            {
                $message_info = add_message_info($message_info, $display_name . ' success remove.');
                $this->m_custom->update_hide_flag(1, $main_table, $id);
                $this->m_custom->remove_row_log('advertise', $id, $login_id, $do_by_type);
            }
        }

        $this->data['sub_category_list'] = $this->m_custom->getSubCategoryList('0', 'All Keppo Voucher Category', $this->config->item('category_keppo_voucher'));
        $this->data['sub_category_id'] = array(
            'name' => 'sub_category_id',
            'id' => 'sub_category_id',
        );
        $this->data['sub_category_selected'] = $search_category;

        $advertise_list = $this->m_custom->getAdvertise('adm', $search_category, NULL, 1, NULL, NULL, 0, 1, 0);
        $this->data['the_result'] = $advertise_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/keppo_voucher_management';
        $this->load->view('template/index', $this->data);
    }

    function keppo_voucher_change($candie_id = NULL)
    {
        if (!$this->m_admin->check_is_any_admin(70))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $is_edit = 0;

        if ($candie_id != NULL)
        {
            $allowed_list = $this->m_custom->get_list_of_allow_id('advertise', 'advertise_type', 'adm', 'advertise_id');
            if (!check_allowed_list($allowed_list, $candie_id))
            {
                redirect('/', 'refresh');
            }
            $is_edit = 1;
        }

        $keppo_company_name = $this->m_custom->web_setting_get('keppo_company_name', 'set_desc');
        $candie_term = $this->m_custom->get_dynamic_option_array('candie_term', NULL, NULL, $keppo_company_name);

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;

            $upload_rule = array(
                'upload_path' => $this->album_admin,
                'allowed_types' => $this->config->item('allowed_types_image'),
                'max_size' => $this->config->item('max_size'),
                'max_width' => $this->config->item('max_width'),
                'max_height' => $this->config->item('max_height'),
            );

            $this->load->library('upload', $upload_rule);

            $candie_id = $this->input->post('candie_id');
            $title = $this->input->post('candie_title');
            $description = $this->input->post('candie_desc');
            $upload_file = "candie-file";
            $start_date = validateDate($this->input->post('start_date'));
            $end_date = validateDate($this->input->post('end_date'));
            $search_month = get_part_of_date('month');
            $search_year = get_part_of_date('year');
            $candie_point = check_is_positive_numeric($this->input->post('candie_point'));
            $candie_worth = check_is_positive_decimal($this->input->post('candie_worth'));
            $expire_date = validateDate($this->input->post('expire_date'));
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

            // validate form input
            $this->form_validation->set_rules('candie_title', $this->lang->line('candie_validation_title_label'), 'required');
            if ($is_edit == 0)
            {
                $this->form_validation->set_rules('candie_category', $this->lang->line('candie_validation_sub_category_label'), 'callback_check_keppo_voucher_category');
            }
            $this->form_validation->set_rules('candie_desc', $this->lang->line('candie_validation_description_label'));
            $this->form_validation->set_rules('candie_point', $this->lang->line('candie_validation_point_label'), 'required|integer');
            $this->form_validation->set_rules('candie_worth', $this->lang->line('candie_validation_worth_label'), 'required|numeric');
            $this->form_validation->set_rules('candie_extra_term', $this->lang->line('candie_validation_extra_term_label'));

            if ($candie_id == 0)
            {
                $is_edit = 0;
            }
            else
            {
                $is_edit = 1;
            }

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    if ($is_edit == 0)
                    {
                        $sub_category_id = $this->input->post('candie_category');

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
                            'advertise_type' => 'adm',
                            'merchant_id' => $login_id,
                            'sub_category_id' => $sub_category_id,
                            'title' => $title,
                            'description' => $description,
                            'image' => empty($image_data) ? '' : $image_data['upload_data']['file_name'],
                            'start_time' => $start_date,
                            'end_time' => $end_date,
                            'month_id' => $search_month,
                            'year' => $search_year,
                            'voucher_candie' => $candie_point,
                            'voucher_worth' => $candie_worth,
                            'voucher_expire_date' => $expire_date,
                            'extra_term' => $candie_extra_term,
                            'voucher_not_need' => $sub_category_id == $this->config->item('category_epay') ? 1 : 0,
                            'phone_required' => $sub_category_id == $this->config->item('category_epay') ? 1 : 0,
                        );

                        $new_id = $this->m_custom->get_id_after_insert('advertise', $data);
                        if ($new_id)
                        {
                            $this->m_custom->insert_row_log('advertise', $new_id, $login_id, $login_type);
                            $this->m_custom->many_insert_or_remove('candie_term', $new_id, $candie_term_selected);
                            $message_info = add_message_info($message_info, $title . ' success create.');
                            $candie_id = $new_id;
                            $can_redirect_to = 3;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 1;
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
                                    delete_file($this->album_admin . $previous_image_name);
                                }
                            }
                        }

                        //To update previous food & beverage
                        $data = array(
                            'title' => $title,
                            'description' => $description,
                            'image' => empty($image_data) ? $previous_image_name : $image_data['upload_data']['file_name'],
                            'start_time' => $start_date,
                            'end_time' => $end_date,
                            'voucher_candie' => $candie_point,
                            'voucher_worth' => $candie_worth,
                            'voucher_expire_date' => $expire_date,
                            'extra_term' => $candie_extra_term,
                        );

                        if ($this->m_custom->simple_update('advertise', $data, 'advertise_id', $candie_id))
                        {
                            $this->m_custom->update_row_log('advertise', $candie_id, $login_id, $login_type);
                            $this->m_custom->many_insert_or_remove('candie_term', $candie_id, $candie_term_selected);
                            $message_info = add_message_info($message_info, $title . ' success update.');
                            $can_redirect_to = 3;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 3;
                        }
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $title . ' success frozen.');
                $this->m_custom->update_hide_flag(1, 'advertise', $candie_id);
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $title . ' success recover.');
                $this->m_custom->update_hide_flag(0, 'advertise', $candie_id);
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/keppo_voucher_management', 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/keppo_voucher_change/' . $candie_id, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $result = $this->m_custom->getOneAdvertise($candie_id, 0, 1, 1);
        $this->data['candie_term_current'] = empty($result) ? array() : $this->m_custom->many_get_childlist('candie_term', $result['advertise_id']);
        $this->data['candie_term'] = $candie_term;
        $this->data['result'] = $result;

        $this->data['candie_id'] = array(
            'candie_id' => empty($result) ? '0' : $result['advertise_id'],
            'is_edit' => $is_edit,
        );

        $this->data['is_edit'] = $is_edit;

        if ($is_edit == 0)
        {
            $this->data['sub_category_list'] = $this->m_custom->getSubCategoryList('0', 'Select Category', $this->config->item('category_keppo_voucher'));
            $this->data['candie_category'] = array(
                'name' => 'candie_category',
                'id' => 'candie_category',
            );
            $this->data['candie_category_selected'] = empty($result) ? $this->config->item('category_epay') : $result['sub_category_id'];  //Preselect epay first
        }
        else
        {
            $this->data['candie_category'] = array(
                'name' => 'candie_category',
                'id' => 'candie_category',
                'readonly' => 'true',
                'value' => empty($result) ? '' : $this->m_custom->display_category($result['sub_category_id']),
            );
        }

        $this->data['candie_title'] = array(
            'name' => 'candie_title',
            'id' => 'candie_title',
            'value' => empty($result) ? $this->form_validation->set_value('candie_title') : $this->form_validation->set_value('candie_title', $result['title']),
        );

        $this->data['candie_desc'] = array(
            'name' => 'candie_desc',
            'id' => 'candie_desc',
            'value' => empty($result) ? $this->form_validation->set_value('candie_desc') : $this->form_validation->set_value('candie_desc', $result['description']),
        );

        $this->data['candie_image'] = empty($result) ? $this->config->item('empty_image') : $this->album_admin . $result['image'];

        $this->data['start_date'] = array(
            'name' => 'start_date',
            'id' => 'start_date',
            'readonly' => 'true',
            'value' => empty($result) ? '' : displayDate($result['start_time']),
        );

        $this->data['end_date'] = array(
            'name' => 'end_date',
            'id' => 'end_date',
            'readonly' => 'true',
            'value' => empty($result) ? '' : displayDate($result['end_time']),
        );

        $this->data['candie_point'] = array(
            'name' => 'candie_point',
            'id' => 'candie_point',
            'value' => empty($result) ? $this->form_validation->set_value('candie_point') : $this->form_validation->set_value('candie_point', $result['voucher_candie']),
        );

        $this->data['candie_worth'] = array(
            'name' => 'candie_worth',
            'id' => 'candie_worth',
            'value' => empty($result) ? $this->form_validation->set_value('candie_worth') : $this->form_validation->set_value('candie_worth', $result['voucher_worth']),
        );

        $this->data['expire_date'] = array(
            'name' => 'expire_date',
            'id' => 'expire_date',
            'readonly' => 'true',
            'value' => empty($result) ? '' : displayDate($result['voucher_expire_date']),
        );

        $this->data['extra_term'] = array(
            'name' => 'candie_extra_term',
            'id' => 'candie_extra_term',
            'value' => empty($result) ? $this->form_validation->set_value('candie_extra_term') : $this->form_validation->set_value('candie_extra_term', $result['extra_term']),
            'cols' => 90,
            'placeholder' => 'Add extra T&C seperate by Enter, one line one T&C',
        );

        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['page_path_name'] = 'admin/keppo_voucher_change';
        $this->load->view('template/index', $this->data);
    }

    function check_keppo_voucher_category($dropdown_selection)
    {
        if ($dropdown_selection == 0)
        {
            $this->form_validation->set_message('check_keppo_voucher_category', 'The Keppo Voucher Category field is required');
            return FALSE;
        }
        return TRUE;
    }

    function analysis_report($search_month = NULL, $search_year = NULL)
    {
        $message_info = '';
        if (!$this->m_admin->check_is_any_admin(63))
        {
            redirect('/', 'refresh');
        }

        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "search_history")
            {
                $search_month = $this->input->post('the_month');
                $search_year = $this->input->post('the_year');
            }
        }
        $year_list = generate_number_option(2015, get_part_of_date('year'));
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

        $first_day = displayDate(getFirstLastTime($selected_year, $selected_month));
        $last_day = displayDate(getFirstLastTime($selected_year, $selected_month, 1));
        $this->data['first_day'] = $first_day;
        $this->data['last_day'] = $last_day;
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'admin/analysis_report';
        $this->load->view('template/index', $this->data);
    }

    function getChart_Merchant()
    {
        if (!$this->m_admin->check_is_any_admin(63))
        {
            redirect('/', 'refresh');
        }

        $the_year = $this->input->post("the_year", true);
        $the_month = $this->input->post("the_month", true);

        $analysis = $this->m_admin->getAdminAnalysisReportMerchant($the_month, $the_year);
        $new_active_array = array();
        $new_active_array['name'] = 'New Merchant (Active)';
        $new_active_array['y'] = $analysis['new_count_active'];

        $new_frozen_array = array();
        $new_frozen_array['name'] = 'New Merchant (Frozen)';
        $new_frozen_array['y'] = $analysis['new_count_hide'];

        $old_active_array = array();
        $old_active_array['name'] = 'Old Merchant (Active)';
        $old_active_array['y'] = $analysis['old_count_active'];

        $old_frozen_array = array();
        $old_frozen_array['name'] = 'Old Merchant (Frozen)';
        $old_frozen_array['y'] = $analysis['old_count_hide'];

        $result = array();
        array_push($result, $new_active_array);
        array_push($result, $new_frozen_array);
        array_push($result, $old_active_array);
        array_push($result, $old_frozen_array);

        echo json_encode($result);
    }

    function analysis_report_user($search_month = NULL, $search_year = NULL)
    {
        $message_info = '';
        if (!$this->m_admin->check_is_any_admin(63))
        {
            redirect('/', 'refresh');
        }

        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "search_history")
            {
                $search_month = $this->input->post('the_month');
                $search_year = $this->input->post('the_year');
            }
        }
        $year_list = generate_number_option(2015, get_part_of_date('year'));
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

        $first_day = displayDate(getFirstLastTime($selected_year, $selected_month));
        $last_day = displayDate(getFirstLastTime($selected_year, $selected_month, 1));
        $this->data['first_day'] = $first_day;
        $this->data['last_day'] = $last_day;
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'admin/analysis_report_user';
        $this->load->view('template/index', $this->data);
    }

    function getChart_user()
    {
        if (!$this->m_admin->check_is_any_admin(63))
        {
            redirect('/', 'refresh');
        }

        $the_year = $this->input->post("the_year", true);
        $the_month = $this->input->post("the_month", true);
        $the_type = $this->input->post("the_type", true);

        $analysis = $this->m_admin->getAdminAnalysisReportUser($the_type, $the_month, $the_year);

        $old_total = 0;
        $old_category = array();
        $old_count = array();
        foreach ($analysis['old_list'] as $row)
        {
            $old_total += $row['option_desc'];
            $old_category[] = $row['option_text'];
            $old_count[] = $row['option_desc'];
        }

        $new_total = 0;
        $new_category = array();
        $new_count = array();
        foreach ($analysis['new_list'] as $row)
        {
            $new_total += $row['option_desc'];
            $new_category[] = $row['option_text'];
            $new_count[] = $row['option_desc'];
        }

        $cutoff_total = $old_total + $new_total;
        $result = array();
        array_push($result, 'Total user until this period: ' . $cutoff_total);
        array_push($result, $old_total);
        array_push($result, $old_category);
        array_push($result, $old_count);
        array_push($result, $new_total);
        array_push($result, $new_category);
        array_push($result, $new_count);

        echo json_encode($result);
    }

    function promo_code_management()
    {
        if (!$this->m_admin->check_is_any_admin(77))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        //$login_id = $this->login_id;
        //$login_type = $this->login_type;
        $main_table = 'promo_code';
        $main_table_id_column = 'code_id';
        $main_table_filter_column = 'code_type';
        $main_table_fiter_value = 'event';

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 1;
            $id = $this->input->post('id');
            $display_name = $this->m_custom->get_one_field_by_key($main_table, $main_table_id_column, $id, 'code_event_name');
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $display_name . ' success frozen.');
                $this->m_custom->update_hide_flag(1, $main_table, $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $display_name . ' success unfrozen.');
                $this->m_custom->update_hide_flag(0, $main_table, $id);
                $can_redirect_to = 1;
            }

            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
        }

        $result_list = $this->m_custom->get_many_table_record($main_table, $main_table_filter_column, $main_table_fiter_value, 1);
        $this->data['the_result'] = $result_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/promo_code_management';
        $this->load->view('template/index', $this->data);
    }

    function promo_code_management_user()
    {
        if (!$this->m_admin->check_is_any_admin(77))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
//        $login_id = $this->login_id;
//        $login_type = $this->login_type;
//        $main_table = 'promo_code';
//        $main_table_id_column = 'code_id';
//        $main_table_filter_column = 'code_type';
        $main_table_fiter_value = 'user';

        $result_list = $this->m_admin->promo_code_result_list($main_table_fiter_value);
        $this->data['code_type'] = $main_table_fiter_value;
        $this->data['the_result'] = $result_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/promo_code_management_user';
        $this->load->view('template/index', $this->data);
    }

    function promo_code_management_merchant()
    {
        if (!$this->m_admin->check_is_any_admin(77))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
//        $login_id = $this->login_id;
//        $login_type = $this->login_type;
//        $main_table = 'promo_code';
//        $main_table_id_column = 'code_id';
//        $main_table_filter_column = 'code_type';
        $main_table_fiter_value = 'merchant';

        $result_list = $this->m_admin->promo_code_result_list($main_table_fiter_value);
        $this->data['code_type'] = $main_table_fiter_value;
        $this->data['the_result'] = $result_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/promo_code_management_user';
        $this->load->view('template/index', $this->data);
    }

    function promo_code_change_event($edit_id = NULL)
    {
        if (!$this->m_admin->check_is_any_admin(77))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $is_edit = 0;
        $main_table = 'promo_code';
        $main_table_id_column = 'code_id';
        $main_table_filter_column = 'code_type';
        $main_table_fiter_value = 'event';

        if ($edit_id != NULL)
        {
            $allowed_list = $this->m_custom->get_list_of_allow_id($main_table, $main_table_filter_column, $main_table_fiter_value, $main_table_id_column);
            if (!check_allowed_list($allowed_list, $edit_id))
            {
                redirect('/', 'refresh');
            }
            $is_edit = 1;
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;

            $edit_id = $this->input->post('edit_id');
            $code_no = $this->input->post('code_no');
            $code_candie = $this->input->post('code_candie');
            $code_money = $this->input->post('code_money');
            $code_event_name = $this->input->post('code_event_name');

            if ($edit_id == 0)
            {
                $is_edit = 0;
            }
            else
            {
                $is_edit = 1;
            }

            if ($this->input->post('button_action') == "save")
            {
                // validate form input
                $this->form_validation->set_rules('code_no', $this->lang->line('promo_code_no'), 'trim|required');
                $this->form_validation->set_rules('code_candie', $this->lang->line('promo_code_candie'), 'trim|required|integer');
                $this->form_validation->set_rules('code_money', $this->lang->line('promo_code_money_event'), 'trim|required|numeric');
                $this->form_validation->set_rules('code_event_name', $this->lang->line('promo_code_event_name'), 'trim|required');

                if ($this->form_validation->run() === TRUE)
                {
                    if ($is_edit == 0)
                    {
                        $check_unique = $this->m_custom->check_is_value_unique($main_table, 'code_no', $code_no);
                        if (!$check_unique)
                        {
                            $message_info = add_message_info($message_info, $code_no . ' already used.');
                            $can_redirect_to = 1;
                            goto direct_go;
                        }

                        $new_id = $this->m_custom->promo_code_insert_event($code_no, $code_candie, $code_money, $code_event_name);
                        if ($new_id)
                        {
                            $message_info = add_message_info($message_info, $code_event_name . ' success create.');
                            $edit_id = $new_id;
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 1;
                        }
                    }
                    else
                    {
                        $check_unique = $this->m_custom->check_is_value_unique($main_table, 'code_no', $code_no, $main_table_id_column, $edit_id);
                        if (!$check_unique)
                        {
                            $message_info = add_message_info($message_info, $code_no . ' already used.');
                            $can_redirect_to = 1;
                            goto direct_go;
                        }

                        $data = array(
                            'code_no' => $code_no,
                            'code_candie' => $code_candie,
                            'code_money' => $code_money,
                            'code_event_name' => $code_event_name,
                            'last_modify_by' => $login_id,
                        );

                        if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $edit_id))
                        {
                            $message_info = add_message_info($message_info, $code_event_name . ' success update.');
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 3;
                        }
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/promo_code_management', 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/promo_code_change_event/' . $edit_id, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $result = $this->m_custom->get_one_table_record($main_table, $main_table_id_column, $edit_id, 1);
        $this->data['result'] = $result;

        $this->data['edit_id'] = array(
            'edit_id' => empty($result) ? '0' : $result[$main_table_id_column],
            'is_edit' => $is_edit,
        );

        $this->data['is_edit'] = $is_edit;
        $this->data['code_type'] = $main_table_fiter_value;

        $this->data['code_no'] = array(
            'name' => 'code_no',
            'id' => 'code_no',
            'value' => empty($result) ? $this->form_validation->set_value('code_no') : $this->form_validation->set_value('code_no', $result['code_no']),
        );
        $this->data['promo_code_url'] = $this->m_custom->generate_promo_code_list_link($result['code_no'], 34);

        $this->data['code_candie'] = array(
            'name' => 'code_candie',
            'id' => 'code_candie',
            'value' => empty($result) ? $this->form_validation->set_value('code_candie') : $this->form_validation->set_value('code_candie', $result['code_candie']),
        );

        $this->data['code_money'] = array(
            'name' => 'code_money',
            'id' => 'code_money',
            'value' => empty($result) ? $this->form_validation->set_value('code_money') : $this->form_validation->set_value('code_money', $result['code_money']),
        );
                
        $this->data['code_event_name'] = array(
            'name' => 'code_event_name',
            'id' => 'code_event_name',
            'value' => empty($result) ? $this->form_validation->set_value('code_event_name') : $this->form_validation->set_value('code_event_name', $result['code_event_name']),
        );

        $this->data['page_path_name'] = 'admin/promo_code_change';
        $this->load->view('template/index', $this->data);
    }

    function promo_code_change_merchant($user_id = NULL, $come_from = 0)
    {
        if (!$this->m_admin->check_is_any_admin(77))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $is_edit = 1;
        $main_table = 'promo_code';
        $main_table_id_column = 'code_id';
        $main_table_filter_column = 'code_type';
        $main_table_fiter_value = 'merchant';

        $result = $this->m_custom->promo_code_get($main_table_fiter_value, $user_id);
        if (!$result)
        {
            $message_info = add_message_info($message_info, 'Cannot find promo code of this merchant. Maybe merchant never login after register account.');
            $can_redirect_to = 2;
            goto direct_go;
        }
        $edit_id = $result[$main_table_id_column];

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;

            $edit_id = $this->input->post('edit_id');
            $code_candie_overwrite = $this->input->post('code_candie_overwrite') == NULL ? 0 : 1;   //Check box special handling to know is checked or not
            $code_candie = $this->input->post('code_candie');

            if ($this->input->post('button_action') == "save")
            {
                // validate form input
                $this->form_validation->set_rules('code_candie', $this->lang->line('promo_code_candie'), 'trim|required|integer');

                if ($this->form_validation->run() === TRUE)
                {
                    $data = array(
                        'code_candie' => $code_candie,
                        'code_candie_overwrite' => $code_candie_overwrite,
                        'last_modify_by' => $login_id,
                    );

                    if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $edit_id))
                    {
                        $message_info = add_message_info($message_info, $result['code_no'] . ' success update.');
                        $can_redirect_to = 3;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 3;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                if ($come_from == 1)
                {
                    redirect('admin/promo_code_management_merchant', 'refresh');
                }
                else
                {
                    redirect('admin/merchant_management', 'refresh');
                }
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/promo_code_change_merchant/' . $user_id . '/' . $come_from, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;

        $this->data['edit_id'] = array(
            'edit_id' => empty($result) ? '0' : $result[$main_table_id_column],
            'is_edit' => $is_edit,
        );

        $this->data['is_edit'] = $is_edit;
        $this->data['code_type'] = $main_table_fiter_value;

        $this->data['code_no'] = array(
            'name' => 'code_no',
            'id' => 'code_no',
            'readonly' => 'true',
            'value' => empty($result) ? $this->form_validation->set_value('code_no') : $this->form_validation->set_value('code_no', $result['code_no']),
        );
        $this->data['promo_code_url'] = $this->m_custom->generate_promo_code_list_link($result['code_no'], 33);

        $code_candie_overwrite_value = $result['code_candie_overwrite'];
        $this->data['code_candie_overwrite_value'] = $code_candie_overwrite_value;
        $this->data['code_candie_overwrite'] = array(
            'name' => 'code_candie_overwrite',
            'id' => 'code_candie_overwrite',
            'checked' => $code_candie_overwrite_value == "1" ? TRUE : FALSE,
            'onclick' => "checkbox_showhide('code_candie_overwrite','code-candie-div')",
            'value' => $this->form_validation->set_value('code_candie_overwrite', $edit_id),
        );

        $this->data['code_candie'] = array(
            'name' => 'code_candie',
            'id' => 'code_candie',
            'value' => empty($result) ? $this->form_validation->set_value('code_candie') : $this->form_validation->set_value('code_candie', $result['code_candie']),
        );

        $this->data['page_path_name'] = 'admin/promo_code_change';
        $this->load->view('template/index', $this->data);
    }

    function promo_code_change_user($user_id = NULL, $come_from = 0)
    {
        if (!$this->m_admin->check_is_any_admin(77))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $is_edit = 1;
        $main_table = 'promo_code';
        $main_table_id_column = 'code_id';
        $main_table_filter_column = 'code_type';
        $main_table_fiter_value = 'user';

        $result = $this->m_custom->promo_code_get($main_table_fiter_value, $user_id);
        if (!$result)
        {
            $message_info = add_message_info($message_info, 'Cannot find promo code of this user. Maybe user never login after register account.');
            $can_redirect_to = 2;
            goto direct_go;
        }
        $edit_id = $result[$main_table_id_column];

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;

            $edit_id = $this->input->post('edit_id');
            $code_candie_overwrite = $this->input->post('code_candie_overwrite') == NULL ? 0 : 1;   //Check box special handling to know is checked or not
            $code_candie = $this->input->post('code_candie');
            $code_money_overwrite = $this->input->post('code_money_overwrite') == NULL ? 0 : 1;   //Check box special handling to know is checked or not
            $code_money = $this->input->post('code_money');

            if ($this->input->post('button_action') == "save")
            {
                // validate form input
                $this->form_validation->set_rules('code_candie', $this->lang->line('promo_code_candie'), 'trim|required|integer');
                $this->form_validation->set_rules('code_money', $this->lang->line('promo_code_money'), 'trim|required|numeric');

                if ($this->form_validation->run() === TRUE)
                {
                    $data = array(
                        'code_candie' => $code_candie,
                        'code_candie_overwrite' => $code_candie_overwrite,
                        'code_money' => $code_money,
                        'code_money_overwrite' => $code_money_overwrite,
                        'last_modify_by' => $login_id,
                    );

                    if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $edit_id))
                    {
                        $message_info = add_message_info($message_info, $result['code_no'] . ' success update.');
                        $can_redirect_to = 3;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 3;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                if ($come_from == 1)
                {
                    redirect('admin/promo_code_management_user', 'refresh');
                }
                else
                {
                    redirect('admin/user_management', 'refresh');
                }
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/promo_code_change_user/' . $user_id . '/' . $come_from, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;

        $this->data['edit_id'] = array(
            'edit_id' => empty($result) ? '0' : $result[$main_table_id_column],
            'is_edit' => $is_edit,
        );

        $this->data['is_edit'] = $is_edit;
        $this->data['code_type'] = $main_table_fiter_value;

        $this->data['code_no'] = array(
            'name' => 'code_no',
            'id' => 'code_no',
            'readonly' => 'true',
            'value' => empty($result) ? $this->form_validation->set_value('code_no') : $this->form_validation->set_value('code_no', $result['code_no']),
        );
        $this->data['promo_code_url'] = $this->m_custom->generate_promo_code_list_link($result['code_no'], 32);

        $code_candie_overwrite_value = $result['code_candie_overwrite'];
        $this->data['code_candie_overwrite_value'] = $code_candie_overwrite_value;
        $this->data['code_candie_overwrite'] = array(
            'name' => 'code_candie_overwrite',
            'id' => 'code_candie_overwrite',
            'checked' => $code_candie_overwrite_value == "1" ? TRUE : FALSE,
            'onclick' => "checkbox_showhide('code_candie_overwrite','code-candie-div')",
            'value' => $this->form_validation->set_value('code_candie_overwrite', $edit_id),
        );

        $this->data['code_candie'] = array(
            'name' => 'code_candie',
            'id' => 'code_candie',
            'value' => empty($result) ? $this->form_validation->set_value('code_candie') : $this->form_validation->set_value('code_candie', $result['code_candie']),
        );

        $code_money_overwrite_value = $result['code_money_overwrite'];
        $this->data['code_money_overwrite_value'] = $code_money_overwrite_value;
        $this->data['code_money_overwrite'] = array(
            'name' => 'code_money_overwrite',
            'id' => 'code_money_overwrite',
            'checked' => $code_money_overwrite_value == "1" ? TRUE : FALSE,
            'onclick' => "checkbox_showhide('code_money_overwrite','code-money-div')",
            'value' => $this->form_validation->set_value('code_money_overwrite', $edit_id),
        );

        $this->data['code_money'] = array(
            'name' => 'code_money',
            'id' => 'code_money',
            'value' => empty($result) ? $this->form_validation->set_value('code_money') : $this->form_validation->set_value('code_money', $result['code_money']),
        );

        $this->data['page_path_name'] = 'admin/promo_code_change';
        $this->load->view('template/index', $this->data);
    }

    function banner_management($view_status = 0)
    {
        if (!$this->m_admin->check_is_any_admin(69))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        //$login_type = $this->login_type;
        $main_table = 'banner';
        $main_table_id_column = 'banner_id';

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 1;
            $id = $this->input->post('id');
            $position_id = $this->input->post('position_id');
            $view_status = $this->input->post('view_status_id');

            $display_name = $this->m_custom->display_static_option($position_id);
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $display_name . ' success hide.');
                $this->m_custom->update_hide_flag(1, $main_table, $id, $login_id);
            }
            if ($this->input->post('button_action') == "recover")
            {
                $status = $this->m_admin->banner_recover($id);
                if ($status)
                {
                    $message_info = add_message_info($message_info, $display_name . ' success recover.');
                }
                else
                {
                    $message_info = add_message_info($message_info, $display_name . ' fail to recover. Because already have other active banner in the same banner position');
                }
            }
            if ($this->input->post('button_action') == "filter_result")
            {
                $view_status = $this->input->post('view_status_id');
            }

            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect('admin/banner_management/' . $view_status, 'refresh');
            }
        }

        $this->data['view_status_list'] = array('0' => 'Show Active Only', '1' => 'Show Reach Expire Only', '2' => 'Show Expired Only', '3' => 'Show All');
        $this->data['view_status_id'] = array(
            'name' => 'view_status_id',
            'id' => 'view_status_id',
        );
        $this->data['view_status_selected'] = $view_status;

        $result_list = $this->m_admin->banner_select($view_status);
        $this->data['the_result'] = $result_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/banner_management';
        $this->load->view('template/index', $this->data);
    }

    function banner_change($edit_id = NULL, $view_status = NULL)
    {
        if (!$this->m_admin->check_is_any_admin(69))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $is_edit = 0;
        $main_table = 'banner';
        $main_table_id_column = 'banner_id';

        if ($edit_id != NULL)
        {
            $is_edit = 1;
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;

            //$edit_id = $this->input->post('edit_id');
            $merchant_id = $this->input->post('merchant_id');
            $banner_position_id = $this->input->post('banner_position_id');
            $banner_start_time = validateDate($this->input->post('banner_start_time'));
            $banner_end_time = validateDate($this->input->post('banner_end_time'));
            $banner_url = $this->input->post('banner_url');
            $banner_position = $this->m_custom->display_static_option($banner_position_id);
            $view_status = $this->input->post('view_status');

            $upload_rule = array(
                'upload_path' => $this->album_banner,
                'allowed_types' => $this->config->item('allowed_types_image'),
                'max_size' => $this->config->item('max_size'),
                'max_width' => $this->config->item('max_width'),
                'max_height' => $this->config->item('max_height'),
            );

            $this->load->library('upload', $upload_rule);
            $upload_file = "image-file-name";

            if ($edit_id == 0)
            {
                $is_edit = 0;
            }
            else
            {
                $is_edit = 1;
            }

            if ($this->input->post('button_action') == "save")
            {
                // validate form input                          
                $this->form_validation->set_rules('banner_position_id', $this->lang->line('banner_position'), 'callback_check_banner_position_id');
                $this->form_validation->set_rules('merchant_id', $this->lang->line('banner_merchant'));
                $this->form_validation->set_rules('banner_start_time', $this->lang->line('banner_start_time'), 'trim|required');
                $this->form_validation->set_rules('banner_end_time', $this->lang->line('banner_end_time'), 'trim|required');
                $this->form_validation->set_rules('banner_url', $this->lang->line('banner_url'), 'trim|required');

                if ($this->form_validation->run() === TRUE)
                {
                    if ($is_edit == 0)
                    {
                        if (!empty($_FILES[$upload_file]['name']))
                        {
                            if (!$this->upload->do_upload($upload_file))
                            {
                                $message_info = add_message_info($message_info, $this->upload->display_errors());
                            }
                            else
                            {
                                $image_data = array('upload_data' => $this->upload->data());
                            }
                        }
                        $banner_image = empty($image_data) ? '' : $image_data['upload_data']['file_name'];

                        $new_id = $this->m_admin->banner_insert($merchant_id, NULL, $banner_start_time, $banner_end_time, $banner_image, $banner_url, $banner_position_id);
                        if ($new_id)
                        {
                            $message_info = add_message_info($message_info, 'Success create a banner on this banner position ' . $banner_position);
                            $edit_id = $new_id;
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, 'Fail to create banner on this banner position ' . $banner_position . ' because it still have another banner occupy, please select an empty banner position');
                            $can_redirect_to = 1;
                        }
                    }
                    else
                    {
                        $result_update = $this->m_custom->get_one_table_record($main_table, $main_table_id_column, $edit_id, 1);

                        $previous_image_name = $result_update['banner_image'];
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
                                    delete_file($this->album_banner . $previous_image_name);
                                }
                            }
                        }
                        $banner_image = empty($image_data) ? $previous_image_name : $image_data['upload_data']['file_name'];

                        if ($this->m_admin->banner_update($merchant_id, NULL, $banner_start_time, $banner_end_time, $banner_image, $banner_url, $banner_position_id, $edit_id, $result_update['hide_flag']))
                        {
                            $message_info = add_message_info($message_info, 'Success update the banner on banner position ' . $banner_position);
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, 'Fail to update banner on this banner position ' . $banner_position . ' because it still have another banner occupy, please select an empty banner position');
                            $can_redirect_to = 3;
                        }
                    }
                    $this->m_custom->remove_image_temp();
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $banner_position . ' success hide.');
                $this->m_custom->update_hide_flag(1, $main_table, $edit_id, $login_id);
            }
            if ($this->input->post('button_action') == "recover")
            {
                $status = $this->m_admin->banner_recover($edit_id);
                if ($status)
                {
                    $message_info = add_message_info($message_info, $banner_position . ' success recover.');
                }
                else
                {
                    $message_info = add_message_info($message_info, $banner_position . ' fail to recover. Because already have other active banner in the same banner position');
                }
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/banner_management/' . $view_status, 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/banner_change/' . $edit_id . '/' . $view_status, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $result = $this->m_custom->get_one_table_record($main_table, $main_table_id_column, $edit_id, 1);
        $this->data['result'] = $result;

        $this->data['edit_id'] = array(
            'edit_id' => empty($result) ? '0' : $result[$main_table_id_column],
            'is_edit' => $is_edit,
            'view_status' => $view_status,
        );

        $this->data['is_edit'] = $is_edit;

        $this->data['image_item'] = empty($result) ? $this->config->item('empty_image') : $this->album_banner . $result['banner_image'];

        $this->data['banner_position_list'] = $this->m_custom->get_static_option_array('banner_position', '0', 'Please Select', 0, 'option_value');
        $this->data['banner_position_id'] = array(
            'name' => 'banner_position_id',
            'id' => 'banner_position_id',
        );
        $this->data['banner_position_selected'] = $result['banner_position'] == NULL ? $this->form_validation->set_value('banner_position_id', '0') : $result['banner_position'];

        $this->data['merchant_list'] = $this->m_merchant->getMerchantList('0', 'Please Select');
        $this->data['merchant_id'] = array(
            'name' => 'merchant_id',
            'id' => 'merchant_id',
            'class' => 'chosen-select',
        );
        $this->data['merchant_selected'] = $result['merchant_id'] == NULL ? $this->form_validation->set_value('merchant_id', '0') : $result['merchant_id'];

        $this->data['banner_start_time'] = array(
            'name' => 'banner_start_time',
            'id' => 'banner_start_time',
            'type' => 'text',
            'readonly' => 'true',
            'value' => empty($result) ? $this->form_validation->set_value('banner_start_time') : $this->form_validation->set_value('banner_start_time', displayDate($result['start_time'])),
        );

        $this->data['banner_end_time'] = array(
            'name' => 'banner_end_time',
            'id' => 'banner_end_time',
            'type' => 'text',
            'readonly' => 'true',
            'value' => empty($result) ? $this->form_validation->set_value('banner_end_time') : $this->form_validation->set_value('banner_end_time', displayDate($result['end_time'])),
        );

        $this->data['banner_url'] = array(
            'name' => 'banner_url',
            'id' => 'banner_url',
            'value' => empty($result) ? $this->form_validation->set_value('banner_url') : $this->form_validation->set_value('banner_url', $result['banner_url']),
        );

        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['page_path_name'] = 'admin/banner_change';
        $this->load->view('template/index', $this->data);
    }

    function check_banner_position_id($dropdown_selection)
    {
        if ($dropdown_selection == 0)
        {
            $this->form_validation->set_message('check_banner_position_id', 'The Banner Position Field is required');
            return FALSE;
        }
        return TRUE;
    }

    //to do, add admin redemption page
    public function keppo_voucher_redemption_page($show_used = 0)
    {
        if (!$this->m_admin->check_is_any_admin(71))
        {
            redirect('/', 'refresh');
        }

        $login_id = $this->ion_auth->user()->row()->id;

        $search_word = '';
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "search")
            {
                $search_word = $this->input->post('search_word2');
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
        $this->data['promotion_list'] = $this->m_custom->getPromotionAdmin($this->config->item('category_epay'));

        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'admin/keppo_voucher_redeem_done';
        $this->load->view('template/index', $this->data);
    }

    public function keppo_voucher_redeem_done()
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
                if ($this->m_admin->promotion_admin_redemption_done($redeem_id))
                {
                    $this->session->set_flashdata('message', 'You mark ' . $voucher . ' voucher for ' . $user_name . ' as used');
                }
                else
                {
                    $this->session->set_flashdata('message', 'Sorry, redeem fail. Please check with admin...');
                }
            }
            else if ($this->input->post('button_action') == "submit_expired")
            {
                if ($this->m_admin->promotion_admin_redemption_done($redeem_id, 1))
                {
                    $this->session->set_flashdata('message', 'You mark ' . $voucher . ' voucher for ' . $user_name . ' as expired');
                }
            }
        }
        redirect($current_url, 'refresh');
    }

    function keppo_voucher_redeem_change($edit_id)
    {
        if (!$this->m_admin->check_is_any_admin(71))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $main_table = 'user_redemption';
        $main_table_id_column = 'redeem_id';
        $result = $this->m_custom->get_one_table_record($main_table, $main_table_id_column, $edit_id, 1);
        $status_id_active = $this->config->item('voucher_active');
        $status_id_used = $this->config->item('voucher_used');

        if (empty($result))
        {
            redirect('/', 'refresh');
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $id = $this->input->post('id');
            $top_up_serial_code = $this->input->post('top_up_serial_code');
            $top_up_already = $this->input->post('top_up_already') == NULL ? $status_id_active : $status_id_used;
            $top_up_date = validateDate($this->input->post('top_up_date'));
            $top_up_time = $this->input->post('top_up_time') == NULL ? NULL : $this->input->post('top_up_time');

            $this->form_validation->set_rules('top_up_serial_code', 'Serial Code');
            $this->form_validation->set_rules('top_up_date', 'Top Up Date');
            $this->form_validation->set_rules('top_up_time', 'Top Up Time');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $data = array(
                        'top_up_serial_code' => $top_up_serial_code,
                        'top_up_date' => $top_up_date,
                        'top_up_time' => $top_up_time,
                    );

                    if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $id))
                    {
                        if ($top_up_already == $status_id_used)  //If user tick the Top Up Used Already? check box
                        {
                            $this->m_admin->promotion_admin_redemption_done($id);
                        }
                        else if ($top_up_already == $status_id_active)  //If user un-tick the Top Up Used Already? check box
                        {
                            $this->m_admin->promotion_admin_redemption_done($id, 0, 1);
                        }

                        $message_info = add_message_info($message_info, 'Record success update.');
                        $can_redirect_to = 2;
                    }
                    else
                    {
                        $message_info = add_message_info($message_info, $this->ion_auth->errors());
                        $can_redirect_to = 1;
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/keppo_voucher_redemption_page', 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['result'] = $result;
        $this->data['title'] = "User Redemption Edit";
        $this->data['can_edit'] = 1;

        $this->data['top_up_serial_code'] = array(
            'name' => 'top_up_serial_code',
            'id' => 'top_up_serial_code',
            'type' => 'text',
            'value' => $result['top_up_serial_code'],
        );

        $top_up_already_value = $result['status_id'];
        $this->data['top_up_already_value'] = $top_up_already_value;
        $this->data['top_up_already'] = array(
            'name' => 'top_up_already',
            'id' => 'top_up_already',
            'checked' => $top_up_already_value == $status_id_used ? TRUE : FALSE,
            'value' => $result['status_id'],
        );

        $this->data['top_up_date'] = array(
            'name' => 'top_up_date',
            'id' => 'top_up_date',
            'readonly' => 'true',
            'value' => empty($result) ? '' : displayDate($result['top_up_date']),
        );

        $this->data['top_up_time'] = array(
            'name' => 'top_up_time',
            'id' => 'top_up_time',
            'value' => empty($result) ? '' : $result['top_up_time'],
        );

        $this->data['page_path_name'] = 'admin/keppo_voucher_redeem_change';
        $this->load->view('template/index', $this->data);
    }

    function manage_web_setting()
    {
        if (!$this->m_admin->check_is_any_admin(73))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $keppo_company_name = $this->input->post('keppo_company_name');
            $keppo_admin_email = strtolower($this->input->post('keppo_admin_email'));
            $keppo_company_address = $this->input->post('keppo_company_address');
            $keppo_company_phone = $this->input->post('keppo_company_phone');
            $keppo_company_fax = $this->input->post('keppo_company_fax');
            $keppo_company_bank = $this->input->post('keppo_company_bank');
            $merchant_minimum_balance = check_is_positive_decimal($this->input->post('merchant_minimum_balance'));
            $merchant_max_hotdeal_per_day = check_is_positive_numeric($this->input->post('merchant_max_hotdeal_per_day'));
            $user_max_picture_per_day = check_is_positive_numeric($this->input->post('user_max_picture_per_day'));
            $friend_success_register_get_money = check_is_positive_decimal($this->input->post('friend_success_register_get_money'));
            $register_promo_code_get_candie = check_is_positive_numeric($this->input->post('register_promo_code_get_candie'));
            $merchant_promo_code_get_candie = check_is_positive_numeric($this->input->post('merchant_promo_code_get_candie'));
            $popular_hotdeal_number = check_is_positive_numeric($this->input->post('popular_hotdeal_number'));
            $popular_redemption_number = check_is_positive_numeric($this->input->post('popular_redemption_number'));
            $min_rating_get_for_sort_list = check_is_positive_numeric($this->input->post('min_rating_get_for_sort_list'));

            // validate form input
            $this->form_validation->set_rules('keppo_company_name', $this->lang->line('web_setting_keppo_company_name'), 'trim|required');
            $this->form_validation->set_rules('keppo_admin_email', $this->lang->line('web_setting_keppo_admin_email'), 'trim|required|valid_emails');
            $this->form_validation->set_rules('keppo_company_address', $this->lang->line('web_setting_keppo_company_address'), 'trim|required');
            $this->form_validation->set_rules('keppo_company_phone', $this->lang->line('web_setting_keppo_company_phone'), 'trim|required');
            $this->form_validation->set_rules('keppo_company_fax', $this->lang->line('web_setting_keppo_company_fax'), 'trim|required');
            $this->form_validation->set_rules('keppo_company_bank', $this->lang->line('web_setting_keppo_company_bank'), 'required');
            $this->form_validation->set_rules('merchant_minimum_balance', $this->lang->line('web_setting_merchant_minimum_balance'), 'trim|required|numeric');
            $this->form_validation->set_rules('merchant_max_hotdeal_per_day', $this->lang->line('web_setting_merchant_max_hotdeal_per_day'), 'trim|required|integer');
            $this->form_validation->set_rules('user_max_picture_per_day', $this->lang->line('web_setting_user_max_picture_per_day'), 'trim|required|integer');
            $this->form_validation->set_rules('friend_success_register_get_money', $this->lang->line('web_setting_friend_success_register_get_money'), 'trim|required|numeric');
            $this->form_validation->set_rules('register_promo_code_get_candie', $this->lang->line('web_setting_register_promo_code_get_candie'), 'trim|required|integer');
            $this->form_validation->set_rules('merchant_promo_code_get_candie', $this->lang->line('web_setting_merchant_promo_code_get_candie'), 'trim|required|integer');
            $this->form_validation->set_rules('popular_hotdeal_number', $this->lang->line('web_setting_popular_hotdeal_number'), 'trim|required|integer');
            $this->form_validation->set_rules('popular_redemption_number', $this->lang->line('web_setting_popular_redemption_number'), 'trim|required|integer');
            $this->form_validation->set_rules('min_rating_get_for_sort_list', $this->lang->line('web_setting_min_rating_get_for_sort_list'), 'trim|required|integer');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $this->m_custom->web_setting_set('keppo_company_name', $keppo_company_name, 'set_desc');
                    $this->m_custom->web_setting_set('keppo_admin_email', $keppo_admin_email, 'set_desc');
                    $this->m_custom->web_setting_set('keppo_company_address', $keppo_company_address, 'set_desc');
                    $this->m_custom->web_setting_set('keppo_company_phone', $keppo_company_phone, 'set_desc');
                    $this->m_custom->web_setting_set('keppo_company_fax', $keppo_company_fax, 'set_desc');
                    $this->m_custom->web_setting_set('keppo_company_bank', $keppo_company_bank, 'set_desc');
                    $this->m_custom->web_setting_set('merchant_minimum_balance', $merchant_minimum_balance, 'set_decimal');
                    $this->m_custom->web_setting_set('merchant_max_hotdeal_per_day', $merchant_max_hotdeal_per_day);
                    $this->m_custom->web_setting_set('user_max_picture_per_day', $user_max_picture_per_day);
                    $this->m_custom->web_setting_set('friend_success_register_get_money', $friend_success_register_get_money, 'set_decimal');
                    $this->m_custom->web_setting_set('register_promo_code_get_candie', $register_promo_code_get_candie);
                    $this->m_custom->web_setting_set('merchant_promo_code_get_candie', $merchant_promo_code_get_candie);
                    $this->m_custom->web_setting_set('popular_hotdeal_number', $popular_hotdeal_number);
                    $this->m_custom->web_setting_set('popular_redemption_number', $popular_redemption_number);
                    $this->m_custom->web_setting_set('min_rating_get_for_sort_list', $min_rating_get_for_sort_list);

                    $message_info = add_message_info($message_info, 'Web Setting success update.');
                    $can_redirect_to = 1;
                }
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['keppo_company_name'] = array(
            'name' => 'keppo_company_name',
            'id' => 'keppo_company_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('keppo_company_name', $this->m_custom->web_setting_get('keppo_company_name', 'set_desc')),
        );
        $this->data['keppo_admin_email'] = array(
            'name' => 'keppo_admin_email',
            'id' => 'keppo_admin_email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('keppo_admin_email', $this->m_custom->web_setting_get('keppo_admin_email', 'set_desc')),
        );
        $this->data['keppo_company_address'] = array(
            'name' => 'keppo_company_address',
            'id' => 'keppo_company_address',
            'type' => 'text',
            'value' => $this->form_validation->set_value('keppo_company_address', $this->m_custom->web_setting_get('keppo_company_address', 'set_desc')),
        );
        $this->data['keppo_company_phone'] = array(
            'name' => 'keppo_company_phone',
            'id' => 'keppo_company_phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('keppo_company_phone', $this->m_custom->web_setting_get('keppo_company_phone', 'set_desc')),
        );
        $this->data['keppo_company_fax'] = array(
            'name' => 'keppo_company_fax',
            'id' => 'keppo_company_fax',
            'type' => 'text',
            'value' => $this->form_validation->set_value('keppo_company_fax', $this->m_custom->web_setting_get('keppo_company_fax', 'set_desc')),
        );
        $this->data['keppo_company_bank'] = array(
            'name' => 'keppo_company_bank',
            'id' => 'keppo_company_bank',
            'value' => $this->form_validation->set_value('keppo_company_bank', $this->m_custom->web_setting_get('keppo_company_bank', 'set_desc')),
        );
        $this->data['merchant_minimum_balance'] = array(
            'name' => 'merchant_minimum_balance',
            'id' => 'merchant_minimum_balance',
            'type' => 'text',
            'value' => $this->form_validation->set_value('merchant_minimum_balance', $this->m_custom->web_setting_get('merchant_minimum_balance', 'set_decimal')),
        );
        $this->data['merchant_max_hotdeal_per_day'] = array(
            'name' => 'merchant_max_hotdeal_per_day',
            'id' => 'merchant_max_hotdeal_per_day',
            'type' => 'text',
            'value' => $this->form_validation->set_value('merchant_max_hotdeal_per_day', $this->m_custom->web_setting_get('merchant_max_hotdeal_per_day')),
        );
        $this->data['user_max_picture_per_day'] = array(
            'name' => 'user_max_picture_per_day',
            'id' => 'user_max_picture_per_day',
            'type' => 'text',
            'value' => $this->form_validation->set_value('user_max_picture_per_day', $this->m_custom->web_setting_get('user_max_picture_per_day')),
        );
        $this->data['friend_success_register_get_money'] = array(
            'name' => 'friend_success_register_get_money',
            'id' => 'friend_success_register_get_money',
            'type' => 'text',
            'value' => $this->form_validation->set_value('friend_success_register_get_money', $this->m_custom->web_setting_get('friend_success_register_get_money', 'set_decimal')),
        );
        $this->data['register_promo_code_get_candie'] = array(
            'name' => 'register_promo_code_get_candie',
            'id' => 'register_promo_code_get_candie',
            'type' => 'text',
            'value' => $this->form_validation->set_value('register_promo_code_get_candie', $this->m_custom->web_setting_get('register_promo_code_get_candie')),
        );
        $this->data['merchant_promo_code_get_candie'] = array(
            'name' => 'merchant_promo_code_get_candie',
            'id' => 'merchant_promo_code_get_candie',
            'type' => 'text',
            'value' => $this->form_validation->set_value('merchant_promo_code_get_candie', $this->m_custom->web_setting_get('merchant_promo_code_get_candie')),
        );
        $this->data['popular_hotdeal_number'] = array(
            'name' => 'popular_hotdeal_number',
            'id' => 'popular_hotdeal_number',
            'type' => 'text',
            'value' => $this->form_validation->set_value('popular_hotdeal_number', $this->m_custom->web_setting_get('popular_hotdeal_number')),
        );
        $this->data['popular_redemption_number'] = array(
            'name' => 'popular_redemption_number',
            'id' => 'popular_redemption_number',
            'type' => 'text',
            'value' => $this->form_validation->set_value('popular_redemption_number', $this->m_custom->web_setting_get('popular_redemption_number')),
        );
        $this->data['min_rating_get_for_sort_list'] = array(
            'name' => 'min_rating_get_for_sort_list',
            'id' => 'min_rating_get_for_sort_list',
            'type' => 'text',
            'value' => $this->form_validation->set_value('min_rating_get_for_sort_list', $this->m_custom->web_setting_get('min_rating_get_for_sort_list')),
        );

        $this->data['page_path_name'] = 'admin/manage_web_setting';
        $this->load->view('template/index', $this->data);
    }

    function manage_photography()
    {
        if (!$this->m_admin->check_is_any_admin(73))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        //$login_id = $this->login_id;
        //$login_type = $this->login_type;
        $main_table = 'dynamic_option';
        $main_table_id_column = 'option_id';
        $main_table_filter_column = 'option_type';
        $main_table_fiter_value = 'photography';

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 1;
            $id = $this->input->post('id');
            $display_name = $this->m_custom->display_dynamic_option($id);
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $display_name . ' success hide.');
                $this->m_custom->update_hide_flag(1, $main_table, $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $display_name . ' success unhide.');
                $this->m_custom->update_hide_flag(0, $main_table, $id);
                $can_redirect_to = 1;
            }

            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
        }

        $result_list = $this->m_custom->get_many_table_record($main_table, $main_table_filter_column, $main_table_fiter_value, 1);
        $this->data['the_result'] = $result_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/manage_photography';
        $this->load->view('template/index', $this->data);
    }

    function manage_photography_change($edit_id = NULL)
    {
        if (!$this->m_admin->check_is_any_admin(73))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $is_edit = 0;
        $main_table = 'dynamic_option';
        $main_table_id_column = 'option_id';
        $main_table_filter_column = 'option_type';
        $main_table_fiter_value = 'photography';

        if ($edit_id != NULL)
        {
            $allowed_list = $this->m_custom->get_list_of_allow_id($main_table, $main_table_filter_column, $main_table_fiter_value, $main_table_id_column);
            if (!check_allowed_list($allowed_list, $edit_id))
            {
                redirect('/', 'refresh');
            }
            $is_edit = 1;
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;

            $edit_id = $this->input->post('edit_id');
            $option_desc = $this->input->post('option_desc');

            // validate form input
            $this->form_validation->set_rules('option_desc', 'Photography Type', 'required');

            if ($edit_id == 0)
            {
                $is_edit = 0;
            }
            else
            {
                $is_edit = 1;
            }

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    if ($is_edit == 0)
                    {
                        $data = array(
                            'option_desc' => $option_desc,
                            'option_type' => $main_table_fiter_value,
                        );

                        $new_id = $this->m_custom->get_id_after_insert($main_table, $data);
                        if ($new_id)
                        {
                            $this->m_custom->insert_row_log($main_table, $new_id, $login_id, $login_type);
                            $message_info = add_message_info($message_info, $option_desc . ' success create.');
                            $edit_id = $new_id;
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 1;
                        }
                    }
                    else
                    {
                        $data = array(
                            'option_desc' => $option_desc,
                        );

                        if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $edit_id))
                        {
                            $this->m_custom->update_row_log($main_table, $edit_id, $login_id, $login_type);
                            $message_info = add_message_info($message_info, $option_desc . ' success update.');
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 3;
                        }
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/manage_photography', 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/manage_photography_change/' . $edit_id, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $result = $this->m_custom->get_one_table_record($main_table, $main_table_id_column, $edit_id, 1);
        $this->data['result'] = $result;

        $this->data['edit_id'] = array(
            'edit_id' => empty($result) ? '0' : $result[$main_table_id_column],
            'is_edit' => $is_edit,
        );

        $this->data['is_edit'] = $is_edit;

        $this->data['option_desc'] = array(
            'name' => 'option_desc',
            'id' => 'option_desc',
            'value' => empty($result) ? $this->form_validation->set_value('option_desc') : $this->form_validation->set_value('option_desc', $result['option_desc']),
        );

        $this->data['page_path_name'] = 'admin/manage_photography_change';
        $this->load->view('template/index', $this->data);
    }

    function manage_candie_term()
    {
        if (!$this->m_admin->check_is_any_admin(73))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        //$login_id = $this->login_id;
        //$login_type = $this->login_type;
        $main_table = 'dynamic_option';
        $main_table_id_column = 'option_id';
        $main_table_filter_column = 'option_type';
        $main_table_fiter_value = 'candie_term';

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 1;
            $id = $this->input->post('id');
            $display_name = $this->m_custom->display_dynamic_option($id);
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $display_name . ' success hide.');
                $this->m_custom->update_hide_flag(1, $main_table, $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $display_name . ' success unhide.');
                $this->m_custom->update_hide_flag(0, $main_table, $id);
                $can_redirect_to = 1;
            }

            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
        }

        $result_list = $this->m_custom->get_many_table_record($main_table, $main_table_filter_column, $main_table_fiter_value, 1);
        $this->data['the_result'] = $result_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/manage_candie_term';
        $this->load->view('template/index', $this->data);
    }

    function manage_candie_term_change($edit_id = NULL)
    {
        if (!$this->m_admin->check_is_any_admin(73))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $is_edit = 0;
        $main_table = 'dynamic_option';
        $main_table_id_column = 'option_id';
        $main_table_filter_column = 'option_type';
        $main_table_fiter_value = 'candie_term';

        if ($edit_id != NULL)
        {
            $allowed_list = $this->m_custom->get_list_of_allow_id($main_table, $main_table_filter_column, $main_table_fiter_value, $main_table_id_column);
            if (!check_allowed_list($allowed_list, $edit_id))
            {
                redirect('/', 'refresh');
            }
            $is_edit = 1;
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;

            $edit_id = $this->input->post('edit_id');
            $option_desc = $this->input->post('option_desc');
            $option_special = $this->input->post('option_special') == NULL ? 0 : 1;   //Check box special handling to know is checked or not
            $option_level = $this->input->post('option_level') == NULL ? 0 : 1;   //Check box special handling to know is checked or not
            // validate form input
            $this->form_validation->set_rules('option_desc', 'Term & Condition', 'required');

            if ($edit_id == 0)
            {
                $is_edit = 0;
            }
            else
            {
                $is_edit = 1;
            }

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    if ($is_edit == 0)
                    {
                        $data = array(
                            'option_desc' => $option_desc,
                            'option_special' => $option_special,
                            'option_level' => $option_level,
                            'option_type' => $main_table_fiter_value,
                        );

                        $new_id = $this->m_custom->get_id_after_insert($main_table, $data);
                        if ($new_id)
                        {
                            $this->m_custom->insert_row_log($main_table, $new_id, $login_id, $login_type);
                            $message_info = add_message_info($message_info, $option_desc . ' success create.');
                            $edit_id = $new_id;
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 1;
                        }
                    }
                    else
                    {
                        $data = array(
                            'option_desc' => $option_desc,
                            'option_special' => $option_special,
                            'option_level' => $option_level,
                        );

                        if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $edit_id))
                        {
                            $this->m_custom->update_row_log($main_table, $edit_id, $login_id, $login_type);
                            $message_info = add_message_info($message_info, $option_desc . ' success update.');
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 3;
                        }
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/manage_candie_term', 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/manage_candie_term_change/' . $edit_id, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $result = $this->m_custom->get_one_table_record($main_table, $main_table_id_column, $edit_id, 1);
        $this->data['result'] = $result;

        $this->data['edit_id'] = array(
            'edit_id' => empty($result) ? '0' : $result[$main_table_id_column],
            'is_edit' => $is_edit,
        );

        $this->data['is_edit'] = $is_edit;

        $this->data['option_desc'] = array(
            'name' => 'option_desc',
            'id' => 'option_desc',
            'style' => 'width:500px',
            'value' => empty($result) ? $this->form_validation->set_value('option_desc') : $this->form_validation->set_value('option_desc', $result['option_desc']),
        );

        $option_special_value = $result['option_special'];
        $this->data['option_special_value'] = $option_special_value;
        $this->data['option_special'] = array(
            'name' => 'option_special',
            'id' => 'option_special',
            'checked' => $option_special_value == "1" ? TRUE : FALSE,
            'value' => $this->form_validation->set_value('option_special', $edit_id),
        );

        $option_level_value = $result['option_level'];
        $this->data['option_level_value'] = $option_level_value;
        $this->data['option_level'] = array(
            'name' => 'option_level',
            'id' => 'option_level',
            'checked' => $option_level_value == "1" ? TRUE : FALSE,
            'value' => $this->form_validation->set_value('option_level', $edit_id),
        );

        $this->data['page_path_name'] = 'admin/manage_candie_term_change';
        $this->load->view('template/index', $this->data);
    }

    function manage_merchant_fee()
    {
        if (!$this->m_admin->check_is_any_admin(76))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        //$login_id = $this->login_id;
        //$login_type = $this->login_type;
        $main_table = 'dynamic_option';
        $main_table_id_column = 'option_id';
        $main_table_filter_column = 'option_type';
        $main_table_fiter_value = 'merchant_fee';

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 1;
            $id = $this->input->post('id');
            $display_name = $this->m_custom->display_dynamic_option($id);
            if ($this->input->post('button_action') == "frozen")
            {
                $message_info = add_message_info($message_info, $display_name . ' success hide.');
                $this->m_custom->update_hide_flag(1, $main_table, $id);
                $can_redirect_to = 1;
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $display_name . ' success unhide.');
                $this->m_custom->update_hide_flag(0, $main_table, $id);
                $can_redirect_to = 1;
            }

            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
        }

        $result_list = $this->m_custom->get_many_table_record($main_table, $main_table_filter_column, $main_table_fiter_value, 1);
        $this->data['the_result'] = $result_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/manage_merchant_fee';
        $this->load->view('template/index', $this->data);
    }

    function manage_merchant_fee_change($edit_id = NULL)
    {
        if (!$this->m_admin->check_is_any_admin(76))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $is_edit = 0;
        $main_table = 'dynamic_option';
        $main_table_id_column = 'option_id';
        $main_table_filter_column = 'option_type';
        $main_table_fiter_value = 'merchant_fee';

        if ($edit_id != NULL)
        {
            $allowed_list = $this->m_custom->get_list_of_allow_id($main_table, $main_table_filter_column, $main_table_fiter_value, $main_table_id_column);
            if (!check_allowed_list($allowed_list, $edit_id))
            {
                redirect('/', 'refresh');
            }
            $is_edit = 1;
        }

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;

            $edit_id = $this->input->post('edit_id');
            $option_desc = $this->input->post('option_desc');
            $option_value = check_is_positive_decimal($this->input->post('option_value'));

            // validate form input
            $this->form_validation->set_rules('option_desc', 'Merchant Fee Charge Type Name', 'required');
            $this->form_validation->set_rules('option_value', 'Amount (RM)', 'required|numeric');

            if ($edit_id == 0)
            {
                $is_edit = 0;
            }
            else
            {
                $is_edit = 1;
            }

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    if ($is_edit == 0)
                    {
                        $data = array(
                            'option_desc' => $option_desc,
                            'option_value' => $option_value,
                            'option_type' => $main_table_fiter_value,
                        );

                        $new_id = $this->m_custom->get_id_after_insert($main_table, $data);
                        if ($new_id)
                        {
                            $this->m_custom->insert_row_log($main_table, $new_id, $login_id, $login_type);
                            $message_info = add_message_info($message_info, $option_desc . ' success create.');
                            $edit_id = $new_id;
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 1;
                        }
                    }
                    else
                    {
                        $data = array(
                            'option_desc' => $option_desc,
                            'option_value' => $option_value,
                        );

                        if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $edit_id))
                        {
                            $this->m_custom->update_row_log($main_table, $edit_id, $login_id, $login_type);
                            $message_info = add_message_info($message_info, $option_desc . ' success update.');
                            $can_redirect_to = 2;
                        }
                        else
                        {
                            $message_info = add_message_info($message_info, $this->ion_auth->errors());
                            $can_redirect_to = 3;
                        }
                    }
                }
            }
            if ($this->input->post('button_action') == "back")
            {
                $can_redirect_to = 2;
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
            elseif ($can_redirect_to == 2)
            {
                redirect('admin/manage_merchant_fee', 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('admin/manage_merchant_fee_change/' . $edit_id, 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $result = $this->m_custom->get_one_table_record($main_table, $main_table_id_column, $edit_id, 1);
        $this->data['result'] = $result;

        $this->data['edit_id'] = array(
            'edit_id' => empty($result) ? '0' : $result[$main_table_id_column],
            'is_edit' => $is_edit,
        );

        $this->data['is_edit'] = $is_edit;

        $this->data['option_desc'] = array(
            'name' => 'option_desc',
            'id' => 'option_desc',
            'style' => 'width:500px',
            'value' => empty($result) ? $this->form_validation->set_value('option_desc') : $this->form_validation->set_value('option_desc', $result['option_desc']),
        );

        $this->data['option_value'] = array(
            'name' => 'option_value',
            'id' => 'option_value',
            'type' => 'text',
            'value' => empty($result) ? $this->form_validation->set_value('option_value') : $this->form_validation->set_value('option_value', $result['option_value']),
        );

        $this->data['page_path_name'] = 'admin/manage_merchant_fee_change';
        $this->load->view('template/index', $this->data);
    }

    function manage_trans_config()
    {
        if (!$this->m_admin->check_is_any_admin(76))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;

        $editable_list = $this->m_admin->trans_config_get_all('editable', 1);

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;

            foreach ($editable_list as $row)
            {
                $conf_type = $row['conf_type'];
                $conf_slug = generate_label_name($row['conf_name']) . $row['trans_conf_id'];
                $post_data = 'post_data' . $row['trans_conf_id'];
                $field_desc = $row['conf_name'] . ' (' . $row['trans_conf_desc'] . ')';
                if ($conf_type == 'can')
                {
                    $post_list[$post_data] = check_is_positive_numeric($this->input->post($conf_slug));
                    $this->form_validation->set_rules($conf_slug, $field_desc, 'trim|required|integer');
                }
                else
                {
                    $post_list[$post_data] = check_is_positive_decimal($this->input->post($conf_slug));
                    $this->form_validation->set_rules($conf_slug, $field_desc, 'trim|required|numeric');
                }
            }

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    foreach ($editable_list as $row)
                    {
                        $post_data = 'post_data' . $row['trans_conf_id'];
                        $update_value = format_decimal($post_list[$post_data]);
                        $this->m_admin->trans_config_set($row['trans_conf_id'], $update_value);
                    }

                    $message_info = add_message_info($message_info, 'Transaction Config success update.');
                    $can_redirect_to = 1;
                }
            }

            direct_go:
            if ($message_info != NULL)
            {
                $this->session->set_flashdata('message', $message_info);
            }
            if ($can_redirect_to == 1)
            {
                redirect(uri_string(), 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        foreach ($editable_list as $row)
        {
            $conf_slug = generate_label_name($row['conf_name']) . $row['trans_conf_id'];
            $conf_value = $this->m_admin->trans_config_get($row['trans_conf_id']);
            $this->data[$conf_slug] = array(
                'name' => $conf_slug,
                'id' => $conf_slug,
                'type' => 'text',
                'style' => 'text-align:right;width:50px',
                'value' => $this->form_validation->set_value($conf_slug, $conf_value),
            );
        }

        $this->data['editable_list'] = $editable_list;
        $this->data['page_path_name'] = 'admin/manage_trans_config';
        $this->load->view('template/index', $this->data);
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
