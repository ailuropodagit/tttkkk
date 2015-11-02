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
        $this->album_admin = $this->config->item('album_admin');
        $this->album_admin_profile = $this->config->item('album_admin_profile');
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
            $this->load->view('template/layout_right_menu', $this->data);
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
        if (!$this->m_custom->check_is_any_admin())
        {
            redirect('/', 'refresh');
        }

        $login_id = $this->ion_auth->user()->row()->id;

        $this->data['page_path_name'] = 'admin/admin_dashboard';
        $this->load->view('template/layout_right_menu', $this->data);
    }

    function profile()
    {
        if (!$this->m_custom->check_is_any_admin())
        {
            redirect('/', 'refresh');
        }
        $user_id = $this->ion_auth->user()->row()->id;
        $user = $this->ion_auth->user($user_id)->row();
        $tables = $this->config->item('tables', 'ion_auth');

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'));
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_phone_label'), 'required|valid_contact_number');
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
        );

        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['page_path_name'] = 'admin/profile';
        $this->load->view('template/layout_right_menu', $this->data);
    }

    function update_profile_image()
    {
        if (!$this->m_custom->check_is_any_admin())
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
        if (!$this->m_custom->check_is_any_admin())
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

        $this->data['main_category_list'] = $this->m_custom->getCategoryList('0', 'All Category');
        $this->data['main_category_id'] = array(
            'name' => 'main_category_id',
            'id' => 'main_category_id',
        );
        $this->data['main_category_selected'] = $search_category;

        $category_list = $this->m_custom->getCategory(0, 0, 1, 1, $search_category);  //0, 1, 1, 1 will show hide
        $this->data['the_result'] = $category_list;

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'admin/category_management';
        $this->load->view('template/layout_right_menu', $this->data);
    }

    function category_add()
    {
        if (!$this->m_custom->check_is_any_admin())
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
                        $message_info = add_message_info($message_info, $category_label . ' success insert.');
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
        $this->load->view('template/layout_right_menu', $this->data);
    }

    function category_edit($category_id)
    {
        if (!$this->m_custom->check_is_any_admin())
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->login_id;
        $login_type = $this->login_type;
        $result = $this->m_custom->get_one_table_record('category', 'category_id', $category_id, 1);

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
                        if($id == $main_category_id){
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
            if ($this->input->post('button_action') == "remove")
            {
                $count_check = count($this->m_custom->getSubCategory($category_id));
                if ($count_check > 0)
                {
                    $message_info = add_message_info($message_info, $category_label . ' cannot remove, because it still have Sub Category under it.');
                    $can_redirect_to = 1;
                }
                else
                {
                    $message_info = add_message_info($message_info, $category_label . ' success remove.');
                    $this->m_custom->update_hide_flag(1, 'category', $category_id);
                    $can_redirect_to = 2;
                }
            }
            if ($this->input->post('button_action') == "recover")
            {
                $message_info = add_message_info($message_info, $category_label . ' success recover.');
                $this->m_custom->update_hide_flag(0, 'category', $category_id);
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
            'value' => $this->form_validation->set_value('category_level', $category_id),
        );

        $this->data['main_category_list'] = $this->m_custom->getCategoryList('0', 'Please Select');
        $this->data['main_category_id'] = array(
            'name' => 'main_category_id',
            'id' => 'main_category_id',
        );
        $this->data['main_category_selected'] = $result['main_category_id'] == NULL ? '0' : $result['main_category_id'];

        $this->data['page_path_name'] = 'admin/category_edit';
        $this->load->view('template/layout_right_menu', $this->data);
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
