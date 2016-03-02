<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('url', 'language', 'albert'));
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->main_group_id = $this->config->item('group_id_user');
        $this->album_user_profile = $this->config->item('album_user_profile');
        $this->album_user = $this->config->item('album_user');
        $this->album_user_merchant = $this->config->item('album_user_merchant');
        $this->folder_image = $this->config->item('folder_image');
        $this->box_number = $this->config->item('user_upload_box_per_page');
        $this->temp_folder = $this->config->item('folder_image_temp');    
        $this->temp_folder_cut = $this->config->item('folder_image_temp_cut');
        $this->strong_password = $this->config->item('strong_password');
    }

    // redirect if needed, otherwise display the user list
    function index()
    {
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('user/login', 'refresh');
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

            $this->_render_page('user/index', $this->data);
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
                $this->m_user->check_birthday_candie();
                $this->m_custom->promo_code_insert_user($user_id);
                redirect('all/user_dashboard/'.$user_id, 'refresh');
            }
            else
            {
                // if the login was un-successful
                // redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('user/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
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
            $this->data['page_path_name'] = 'user/login';
            $this->load->view('template/layout', $this->data);
        }
    }
        
    //LOGIN FACEBOOK CHECK
    function login_facebook_check()
    {        
        //CONFIG VALUE
        $group_id_merchant = $this->config->item('group_id_merchant');
        //POST VALUE
        $fb_id = $this->input->post('fb_id');
        $fb_email = $this->input->post('fb_email');
        $fb_first_name = $this->input->post('fb_first_name');
        $fb_last_name = $this->input->post('fb_last_name');
        
        //READ USERS
        $where_user = array('email'=>$fb_email, 'main_group_id'=>$group_id_merchant);
        $query_user = $this->albert_model->read_user($where_user);
        $num_rows_user = $query_user->num_rows();
        if ($num_rows_user)
        {
            //FB LOGIN MERCHANT EMAIL
            ?>
            <div id="login-fb-merchant-email">1</div>
            <?php
        }
        else
        {
            //FB LOGIN NOT MERCHANT EMAIL
            //READ USERS
            $where_user = array('us_fb_id' => $fb_id, 'us_register_type' => 'fbr');
            $query_user = $this->albert_model->read_user($where_user);        
            $num_rows_user = $query_user->num_rows();
            if($num_rows_user)
            {
                $hide_flag = $query_user->row()->hide_flag;
                if ($hide_flag)
                {
                    //USER ALREADY FROZEN
                    ?>
                    <div id="login-fb-user-frozen">1</div>
                    <?php
                }
                else
                {
                    $username = $query_user->row()->username;
                    $password = $query_user->row()->password_visible;
                    $remember = 0;
                    if ($this->ion_auth->login($username, $password, $remember, $this->main_group_id))
                    {
                    ?>
                    <!--LOGIN USER ID-->
                    <div id="login-user-id"><?php echo $this->session->userdata('user_id') ?></div>
                    <!--LOGIN SUCCESS-->
                    <div id="login-fb-id-success">1</div>
                    <?php
                }
                }
            }
            else
            {
                //FB ID NOT EXISTS
                $post_value_array = array(
                    'fb_id'=>$fb_id, 
                    'fb_email'=>$fb_email,
                    'fb_first_name'=>$fb_first_name,
                    'fb_last_name'=>$fb_last_name
                );
                $this->session->set_flashdata('post_value_array', $post_value_array);
                //RESPONSE
                ?><div id="login-fb-id-not-exists">1</div><?php
            }
        }
    }
    
    // login facebook first time
    function login_facebook_first_time()
    {
        $post_value_array_temp = $this->session->flashdata('post_value_array');
        if(empty($post_value_array_temp))
        {
            redirect('./', 'refresh');
        }
        //preserve value
        $data['email'] = '';
        $data['contact_number'] = '';
        $data['dob_day'] = '';
        $data['dob_month'] = '';
        $data['dob_year'] = '';
        $data['race'] = '';
        $data['race_other'] = '';
        $data['gender'] = '';
        //dob
        $data['dob_day_array'] = range_two_digit_associative_array(1, 31);
        $data['dob_month_associative_array'] = $this->albert_model->read_static_option_month_associative_array();
        $data['dob_year_array'] = range_two_digit_associative_array(1930, 2010);
        $data['race_associative_array'] = $this->albert_model->read_static_option_race_associative_array();
        $data['gender_associative_array'] = $this->albert_model->read_static_option_gender_associative_array(); 
        //flash data
        $this->session->keep_flashdata('post_value_array');
        $post_value_array = $this->session->flashdata('post_value_array');
        $fb_id = $post_value_array['fb_id'];
        $fb_email = $post_value_array['fb_email'];
        $fb_first_name = $post_value_array['fb_first_name'];
        $fb_last_name = $post_value_array['fb_last_name']; 
        //post
        if ($this->input->post())
        {
            //post value
            $email = $this->input->post('email');
            $contact_number = $this->input->post('contact_number');
            $dob_day = $this->input->post('dob_day');
            $dob_month = $this->input->post('dob_month');
            $dob_year = $this->input->post('dob_year');
            $dob = $dob_day.'-'.$dob_month.'-'.$dob_year;
            $_POST['dob'] = $dob;
            $race = $this->input->post('race');
            if($race == '19') //19 = other
            {
                $race_other = $this->input->post('race_other');
            }
            else
            {
                $race_other = '';
            }
            $gender = $this->input->post('gender');
            //preserve value
            $data['email'] = $email;
            $data['contact_number'] = $contact_number;
            $data['dob_day'] = $dob_day;
            $data['dob_month'] = $dob_month;
            $data['dob_year'] = $dob_year;
            $data['race'] = $race;
            $data['race_other'] = $race_other;
            $data['gender'] = $gender;
            //validation
            //$this->form_validation->set_rules('email', 'E-mail address:', 'required|valid_email|valid_facebook_email['.$fb_email.']'); 
            $tables = $this->config->item('tables', 'ion_auth');
            $this->form_validation->set_rules('email', 'Active E-mail:', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]'); 
            //$this->form_validation->set_rules('email', 'Active E-mail:', 'required|valid_email'); 
            //$this->form_validation->set_rules('contact_number', 'Contact Number:', 'required|valid_contact_number'); 
            $this->form_validation->set_rules('contact_number', 'Contact Number:', 'required'); 
            $this->form_validation->set_rules('dob', 'Date of Birth:', 'valid_date');
            $this->form_validation->set_rules('race', 'Race:', 'required_dropdown');
            if($race == '19') { $this->form_validation->set_rules('race_other', 'Race Other:', 'required'); } //19 = other
            $this->form_validation->set_rules('gender', 'Gender:', 'required_dropdown');     
            $this->form_validation->set_rules('accept_terms', '...', 'callback_accept_terms');
            
            if ($this->form_validation->run() == TRUE) 
            {
                //FORM VALIDATION TRUE
                //READ USERS
                //$where_read_user = array('email'=>$fb_email);  //If put like this will have error, user normal register with a email first, then fb email is the same, will have error only 1 user is created
                $where_read_user = array('email'=>$email);
                $query_read_user = $this->albert_model->read_user($where_read_user);               
                $num_rows_read_user = $query_read_user->num_rows();
                if($num_rows_read_user)
                {                    
                    $password_visible = $this->albert_model->read_user($where_read_user)->row()->password_visible;
                    //UPDATE USER
                    //$where_update_user = array('email'=>$fb_email);   //If put like this will have error, user normal register with a email first, then fb email is the same, will have error only 1 user is created
                    $where_update_user = array('email'=>$email);
                    $data_update_user = array('us_fb_id'=>$fb_id);
                    $this->albert_model->update_user($where_update_user, $data_update_user);     
                    //LOG USER IN
                    //$email = $fb_email;  //If put like this will have error, user normal register with a email first, then fb email is the same, will have error only 1 user is created
                    $remember = 0;                        
                    if ($this->ion_auth->login($email, $password_visible, $remember, $this->main_group_id))
                    {
                        $user_id = $this->session->userdata('user_id');
                        redirect("all/user_dashboard/$user_id", 'refresh');
                    }
                    else
                    {
                        $data['message'] = $this->ion_auth->errors();
                    }
                }
                else
                {
                    //DATA
                    $ip_address = $this->input->ip_address();
                    $main_group_id = $this->config->item('group_id_user');
                    $dob = $dob_year.'-'.date('m',strtotime($dob_month)).'-'.$dob_day;
                    $password = $this->config->item('password_example_encrypt');
                    $password_visible = $this->config->item('password_example');
                    //CREATE USER
                    $data_update_user = array(
                        'ip_address'=>$ip_address, 
                        'username'=>$email, 
                        'password'=>$password,
                        'password_visible'=>$password_visible,
                        'main_group_id'=>$main_group_id, 
                        'email'=>$email, 
                        'created_on'=>time(),
                        'active'=>'1',
                        'first_name'=>$fb_first_name,
                        'last_name'=>$fb_last_name,
                        'phone'=>$contact_number,
                        'us_birthday'=>$dob, 
                        'us_race_id'=>$race, 
                        'us_race_other'=>$race_other, 
                        'us_gender_id'=>$gender, 
                        'us_register_type'=>'fbr', 
                        'us_fb_id'=>$fb_id
                    );
                    $this->albert_model->create_user($data_update_user);
                    if($this->db->affected_rows() > 0)
                    {
                        $remember = 0;                        
                        if ($this->ion_auth->login($email, $password_visible, $remember, $this->main_group_id))
                        {
                            $user_id = $this->session->userdata('user_id');
                            $this->m_custom->promo_code_insert_user($user_id);
                            $get_status = send_mail_simple($email, 'Your Keppo User Account Success Created', 'Name : ' . $fb_first_name . ' ' . $fb_last_name .
                                '<br/>Contact Number : ' . $contact_number .
                                '<br/>Username : ' . $email .
                                '<br/>E-mail : ' . $email .
                                '<br/>Temporary Password : ' . $password_visible .
                                '<br/><br/>Please change this temporary password to your own password after login.', 'create_user_send_email_success', 0);
                                if ($get_status)
                                {
                                     redirect("all/user_dashboard/$user_id", 'refresh');
                                }
                                else
                                {
                                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                                    redirect("user/register", 'refresh');
                                }                          
                        }
                        else
                        {
                            $data['message'] = $this->ion_auth->errors();
                        }
                    }
                }                
//                //valid
//                echo $email;
//                echo "<br/>";
//                echo $contact_number;
//                echo "<br/>";
//                echo $dob_day;
//                echo "<br/>";
//                echo $dob_month;
//                echo "<br/>";
//                echo $dob_year;
//                echo "<br/>";
//                echo $race;
//                echo "<br/>";
//                echo $gender;
            }
            else
            {
                //FORM VALIDATION ERROR
                $data['message'] = validation_errors();
            }
        }
        //template
        $data['page_path_name'] = 'user/login_facebook_first_time';
        $this->load->view('template/layout', $data);
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
                    redirect('admin/user_management', 'refresh');
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
        redirect('user/login', 'refresh');
    }

    // change password
    function change_password()
    {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        if ($this->strong_password == 1)
        {
            $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|matches[new_confirm]|min_length[8]|callback_password_check');
        }
        else
        {
            $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        }
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }

        $user = $this->ion_auth->user()->row();
        $function_use_for = 'user/change_password';

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
                set_simple_message('Thank you!', 'Your Password has been saved!', '', 'all/user_dashboard/'.$user->id, 'Back to Dashboard', 'all/simple_message', 1, 3);
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect($function_use_for, 'refresh');
            }
        }
    }
    
    function password_check($str)
    {
        //if (preg_match('#[0-9]#', $str) && preg_match('#[a-z]#', $str) && preg_match('#[A-Z]#', $str))
        if (preg_match('#[0-9]#', $str) && preg_match('#[a-z]#', $str))        
        {
            return TRUE;
        }
        $this->form_validation->set_message('password_check', $this->lang->line('strong_password_rule'));
        return FALSE;
    }

    //FOLLOWER
    function follower($user_type, $user_id)
    {
        //CONFIG DATA
        $group_id_user = $this->config->item('group_id_user');
        //POST
        if($this->input->post('search'))
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
            'name'=>'keyword',
            'placeholder'=>'Search',
            'value'=>$keyword
        );
        //READ USER
        $where_read_user = array('id'=>$user_id);
        $query_read_user = $this->albert_model->read_user($where_read_user);
        $user_name = $query_read_user->row()->first_name . ' ' . $query_read_user->row()->last_name;
        //USER
        if ($user_type == 'user')
        {
            //DATA
            $data['page_title'] = $user_name . ' User Followers';
            //QUERY USER FOLLOWER
            $where_user_follower = array('following_id' => $user_id, 'main_group_id' => $group_id_user);
            $data['query_follow'] = $this->albert_model->read_follower($where_user_follower, $keyword);
        }
        //MERCHANT
        if ($user_type == 'merchant')
        {
            //DATA
            $data['page_title'] = $user_name . ' Merchant Followers';
            //QUERY MERCHANT FOLLOWER
            $where_merchant_follower = array('following_id' => $user_id);
            $data['query_follow'] = $this->albert_model->read_follower_merchant($where_merchant_follower, $keyword);
        }
        //COUNT 
        $data['user_follower_count'] = $this->albert_model->user_follower_count($user_id);
        $data['user_following_count'] = $this->albert_model->user_following_count($user_id);
        $data['merchant_follower_count'] = $this->albert_model->merchant_follower_count($user_id);
        $data['merchant_following_count'] = $this->albert_model->merchant_following_count($user_id);        
        //DATA
        $data['user_id'] = $user_id;
        //TEMPLATE
        $data['page_path_name'] = 'user/follow';
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
        $group_id_user = $this->config->item('group_id_user');
        //POST
        if($this->input->post('search'))
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
            'name'=>'keyword',
            'placeholder'=>'Search',
            'value'=>$keyword
        );
        //READ USER
        $where_read_user = array('id'=>$user_id);
        $query_read_user = $this->albert_model->read_user($where_read_user);
        $user_name = $query_read_user->row()->first_name . ' ' . $query_read_user->row()->last_name;
        //USER
        if ($user_type == 'user')
        {
            //DATA
            $data['page_title'] = $user_name . ' User Following';
            //QUERY USER FOLLOWING
            $where_user_following = array('follower_id' => $user_id, 'main_group_id' => $group_id_user);
            $data['query_follow'] = $this->albert_model->read_following($where_user_following, $keyword);
        }
        //MERCHANT
        if ($user_type == 'merchant')
        {
            //DATA
            $data['page_title'] = $user_name . ' Merchant Following';
            //QUERY MERCHANT FOLLOWING
            $where_merchant_following = array('follower_id' => $user_id);
            $data['query_follow'] = $this->albert_model->read_following_merchant($where_merchant_following, $keyword);
        }
        //COUNT 
        $data['user_follower_count'] = $this->albert_model->user_follower_count($user_id);
        $data['user_following_count'] = $this->albert_model->user_following_count($user_id);
        $data['merchant_follower_count'] = $this->albert_model->merchant_follower_count($user_id);
        $data['merchant_following_count'] = $this->albert_model->merchant_following_count($user_id);
        //DATA
        $data['user_id'] = $user_id;
        //TEMPLATE
        $data['page_path_name'] = 'user/follow';
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
            $user_id = $this->ion_auth->user()->row()->id;
            for ($i = 1; $i < 13; $i++)
            {
                $this->m_user->user_balance_update($user_id, $i);
                $this->m_user->candie_balance_update($user_id, $i);
            }
        }
    }
    
    function retrieve_password()
    {
        $this->form_validation->set_rules('username_email', $this->lang->line('forgot_password_username_email_label'), 'required');
        if ($this->form_validation->run() == false)
        {
            // setup the input
            $this->data['username_email'] = array('name' => 'username_email', 'id' => 'username_email');
            $this->data['identity_label'] = $this->lang->line('forgot_password_username_email_label');
            // set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['page_path_name'] = 'user/retrieve_password';
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
                redirect("user/retrieve-password", 'refresh');
            }
            else
            {
                $this->session->set_flashdata('mail_info', $identity);
                redirect('user/send_mail_process', 'refresh');
            }
        }
    }

    function send_mail_process()
    {
        $identity = $this->session->flashdata('mail_info');
        $get_status = send_mail_simple($identity->email, 'Your Keppo Account Login Info', 'First Name:' . $identity->first_name . '<br/>Last Name:' . $identity->last_name . '<br/>username:' . $identity->username . '<br/>Email:' . $identity->email . '<br/>Password:' . $identity->password_visible, 'forgot_password_send_email_success');
        if ($get_status)
        {
            set_simple_message('Thank you!', 'An email will be sent to your registered email address.', "If you don't receive in the next 10 minutes, please check your spam folder and if you still haven't received it please try again...", 'user/login', 'Go to Log In Page', 'all/simple_message');
        }
        else
        {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("user/retrieve-password", 'refresh');
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
            $this->_render_page('user/forgot_password', $this->data);
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
                redirect("user/forgot_password", 'refresh');
            }
            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
            if ($forgotten)
            {
                // if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("user/login", 'refresh'); //we should display a confirmation page here instead of the login page
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("user/forgot_password", 'refresh');
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
                $this->_render_page('user/reset_password', $this->data);
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
                        redirect("user/login", 'refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('user/reset_password/' . $code, 'refresh');
                    }
                }
            }
        }
        else
        {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("user/forgot_password", 'refresh');
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
            redirect("user", 'refresh');
        }
        else
        {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("user/forgot_password", 'refresh');
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

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() == FALSE)
        {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->_render_page('user/deactivate_user', $this->data);
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
            redirect('user', 'refresh');
        }
    }

    // create a new user
    function create_user()
    {
        $controller = $this->uri->segment(2);
        $function_use_for = 'user/create_user';
        if ($controller == 'create_user')
        {
            $this->data['title'] = "Create User";
            if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
            {
                redirect('user', 'refresh');
            }
        }
        else
        {
            $this->data['title'] = "User Sign Up";
            $function_use_for = 'user/register';
        }
        $this->data['function_use_for'] = $function_use_for;
        $tables = $this->config->item('tables', 'ion_auth');
        if (isset($_POST) && !empty($_POST))
        {
            $this->d_year = $_POST['year'];
            $this->d_month = $_POST['month'];
            $this->d_day = $_POST['day'];
            $_POST['dob'] = $this->d_year . '-' . $this->d_month . '-' . $this->d_day;
        }
        // validate form input        
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'));
        //$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required|valid_contact_number');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required');
        $this->form_validation->set_rules('dob', $this->lang->line('create_user_validation_dob_label'), 'callback_date_check');
        $this->form_validation->set_rules('gender_id', $this->lang->line('create_user_validation_gender_label'), 'callback_check_gender_id');
        $this->form_validation->set_rules('race_id', $this->lang->line('create_user_validation_race_label'), 'callback_check_race_id');
        $this->form_validation->set_rules('race_other', $this->lang->line('create_user_race_other_label'));
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_username_label'), 'trim|required|is_unique[' . $tables['users'] . '.username]');       
        if ($this->strong_password == 1)
        {
            $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|matches[password_confirm]|min_length[8]|callback_password_check');
        }
        else
        {
            $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        }
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
        $this->form_validation->set_rules('promo_code', $this->lang->line('create_user_promo_code_label2'));
        $this->form_validation->set_rules('accept_terms', '...', 'callback_accept_terms');
        
        if ($this->form_validation->run() == true)
        {
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            //$phone = '+60'.$this->input->post('phone');
            $phone = $this->input->post('phone');
            $username = strtolower($this->input->post('username'));
            $email = strtolower($this->input->post('email'));
            $password = $this->input->post('password');
            $race_other = $this->input->post('race_other');
            $additional_data = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $phone,
                'us_birthday' => $this->input->post('dob'),
                'us_age' => age_count($this->input->post('dob')),
                'us_gender_id' => $this->input->post('gender_id'),
                'us_race_id' => $this->input->post('race_id'),
                'us_race_other' => $race_other,
                'username' => $username,
                'password_visible' => $password,
                'main_group_id' => $this->main_group_id,
                'us_promo_code_temp' => $this->input->post('promo_code'),
                    //'profile_image' => $this->config->item('user_default_image'),
            );
        }
        $group_ids = array(
            $this->main_group_id
        );
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $group_ids))
        {
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            $get_status = send_mail_simple($email, 'Your Keppo User Account Success Created', 'Name : ' . $first_name . ' ' . $last_name .
                    '<br/>Contact Number : ' . $phone .
                    '<br/>Username : ' . $username .
                    '<br/>E-mail : ' . $email .
                    '<br/>Password : ' . $password, 'create_user_send_email_success');
            if ($get_status)
            {
                redirect("user/login", 'refresh');
            }
            else
            {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("user/register", 'refresh');
            }
        }
        else
        {
            $this->data['message'] = (
                    validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message'))
                    );
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
            //$this->data['gender_list'] = $this->ion_auth->get_static_option_list('gender');
            $this->data['gender_list'] = $this->m_custom->get_static_option_array('gender', '0', 'Please Select');
            $this->data['gender_id'] = array(
                'name' => 'gender_id',
                'id' => 'gender_id',
                'value' => $this->form_validation->set_value('gender_id'),
            );
            //$this->data['race_list'] = $this->ion_auth->get_static_option_list('race');
            $this->data['race_list'] = $this->m_custom->get_static_option_array('race', '0', 'Please Select');
            $this->data['race_id'] = array(
                'name' => 'race_id',
                'id' => 'race_id',
                'onchange' => 'showraceother()',
                'value' => $this->form_validation->set_value('race_id'),
            );
            $this->data['race_other'] = array(
                'name' => 'race_other',
                'id' => 'race_other',
                'type' => 'text',
                'style' => 'display:none',
                'value' => $this->form_validation->set_value('race_other'),
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
                'type' => 'password',
                'placeholder' => $this->config->item('password_example'),
                'value' => $this->form_validation->set_value('password'),               
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'placeholder' => $this->config->item('password_example'),
                'value' => $this->form_validation->set_value('password_confirm'),
            );
            $this->data['promo_code'] = array(
                'name' => 'promo_code',
                'id' => 'promo_code',
                'type' => 'text',
                'value' => $this->form_validation->set_value('promo_code'),
            );
            $this->data['page_path_name'] = 'user/create_user';
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
    
    //validate is the date is correct
    public function date_check()
    {
        if (checkdate($this->d_month, $this->d_day, $this->d_year))
        {
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('date_check', 'Date of Birth: Incorrect date, please set a real date.');
            return FALSE;
        }
    }

    function check_gender_id($dropdown_selection)
    {
        if ($dropdown_selection == 0)
        {
            $this->form_validation->set_message('check_gender_id', 'The Gender field is required');
            return FALSE;
        }
        return TRUE;
    }
    
    function check_race_id($dropdown_selection)
    {
        if ($dropdown_selection == 0)
        {
            $this->form_validation->set_message('check_race_id', 'The Race field is required');
            return FALSE;
        }
        return TRUE;
    }
    
    //user profile view and edit page
    function profile()
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $user_id = $this->ion_auth->user()->row()->id;
        $user = $this->ion_auth->user($user_id)->row();
        $this->m_custom->promo_code_insert_user($user_id);
        $this->m_custom->promo_code_temp_register($user_id);
        
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "confirm")
            {
                $this->d_year = $_POST['year'];
                $this->d_month = $_POST['month'];
                $this->d_day = $_POST['day'];
                $_POST['dob'] = $this->d_year . '-' . $this->d_month . '-' . $this->d_day;
            }
        }
        $tables = $this->config->item('tables', 'ion_auth');

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'));
        $this->form_validation->set_rules('description', $this->lang->line('create_user_validation_description_label'));
        //$this->form_validation->set_rules('phone', $this->lang->line('create_user_phone_label'), 'required|valid_contact_number');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_phone_label'), 'required');
        $this->form_validation->set_rules('dob', $this->lang->line('create_user_dob_label'), 'callback_date_check');
        $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_username_label'), 'trim|required|is_unique_edit[' . $tables['users'] . '.username.' . $user_id . ']');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique_edit[' . $tables['users'] . '.email.' . $user_id . ']');
        $this->form_validation->set_rules('race_other', $this->lang->line('create_user_race_other_label'));
        $this->form_validation->set_rules('instagram_url', $this->lang->line('create_user_validation_instagram_label'));
        $this->form_validation->set_rules('facebook_url', $this->lang->line('create_user_validation_facebook_label'));
        $this->form_validation->set_rules('blog_url', $this->lang->line('create_user_validation_blogger_url_label'));
        $this->form_validation->set_rules('photography_url', $this->lang->line('create_user_validation_photography_url_label'));
        
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "confirm")
            {
                // do we have a valid request?
//                if ($this->_valid_csrf_nonce() === FALSE || $user_id != $this->input->post('id'))
//                {
//                    show_error($this->lang->line('error_csrf'));
//                }
                if ($this->form_validation->run() === TRUE)
                {
                    $first_name = $this->input->post('first_name');
                    $last_name = $this->input->post('last_name');
                    $username = strtolower($this->input->post('username'));
                    $email = strtolower($this->input->post('email'));
                    $race_other = $this->input->post('race_other');
                    $is_blogger = $this->input->post('is_blogger');
                    $blog_url = $this->input->post('blog_url');
                    $instagram_url = $this->input->post('instagram_url');
                    $facebook_url = $this->input->post('facebook_url');
                    $is_photographer = $this->input->post('is_photographer');
                    $photography_url = $this->input->post('photography_url');
                    $age = age_count($this->input->post('dob'));
                    
                    $blogger_list_selected = array();
                    $post_blogger_list = $this->input->post('blogger_list');
                    if (!empty($post_blogger_list))
                    {
                        foreach ($post_blogger_list as $key => $value)
                        {
                            $blogger_list_selected[] = $value;
                        }
                    }
                    
                    $photography_list_selected = array();
                    $post_photography_list = $this->input->post('photography_list');
                    if (!empty($post_photography_list))
                    {
                        foreach ($post_photography_list as $key => $value)
                        {
                            $photography_list_selected[] = $value;
                        }
                    }

                    $data = array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'description' => $this->input->post('description'),
                        'phone' => $this->input->post('phone'),
                        'us_birthday' => $this->input->post('dob'),
                        'us_age' => $age,
                        'us_gender_id' => $this->input->post('gender_id'),
                        'us_race_id' => $this->input->post('race_id'),
                        'us_race_other' => $race_other,
                        'username' => $username,
                        'email' => $email,
                        'us_is_blogger' => $is_blogger,
                        'us_blog_url' => $blog_url,
                        'us_instagram_url' => $instagram_url,
                        'us_facebook_url' => $facebook_url,
                        'us_is_photographer' => $is_photographer,
                        'us_photography_url' => $photography_url,
                    );

                    // check to see if we are updating the user
                    if ($this->ion_auth->update($user->id, $data))
                    {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        $this->m_custom->many_insert_or_remove('blogger', $user_id, $blogger_list_selected);
                        $this->m_custom->many_insert_or_remove('photography', $user_id, $photography_list_selected);
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        $user = $this->ion_auth->user($user_id)->row();
                        redirect('all/user_dashboard/'.$user_id, 'refresh');
                    }
                    else
                    {
                        // redirect them back to the admin page if admin, or to the base url if non admin
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                    }
                }
            }
        }
        $this->data['image_path'] = $this->album_user_profile;
        $this->data['image'] = $user->profile_image;
        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();
        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        // pass the user to the view
        $this->data['user'] = $user;
        $this->data['title'] = "Profile";
        $this->data['can_edit'] = 1;
        $this->data['user_id'] = $user_id;
        
        $the_date = explode('-', $user->us_birthday);
        $this->data['b_year'] = $the_date[0];
        $this->data['b_month'] = $the_date[1];
        $this->data['b_day'] = $the_date[2];
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
        $promo_code = $this->m_custom->promo_code_get('user', $user->id, 1);
        $this->data['promo_code_no'] = array(
            'name' => 'promo_code_no',
            'id' => 'promo_code_no',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $promo_code,
        );
        $this->data['promo_code_url'] = $this->m_custom->generate_promo_code_list_link($promo_code, 32);
        $this->data['description'] = array(
                'name' => 'description',
                'id' => 'description',
                'value' => $this->form_validation->set_value('description', $user->description),
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $user->email),
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
            'value' => age_count($user->us_birthday),
        );
        $this->data['gender_list'] = $this->ion_auth->get_static_option_list('gender');
        $this->data['gender_id'] = array(
            'name' => 'gender_id',
            'id' => 'gender_id',
        );
        $this->data['us_gender_id'] = $user->us_gender_id;
        
        $this->data['race_list'] = $this->ion_auth->get_static_option_list('race');
        $this->data['race_id'] = array(
            'name' => 'race_id',
            'id' => 'race_id',
            'onchange' => 'showraceother()',
        );
        $this->data['us_race_id'] = $user->us_race_id;
        
        $this->data['race_other'] = array(
            'name' => 'race_other',
            'id' => 'race_other',
            'type' => 'text',
            'style' => $this->m_custom->display_static_option($user->us_race_id) == 'Other' ? 'display:inline' : 'display:none',
            'value' => $this->form_validation->set_value('race_other', $user->us_race_other),
        );
        $this->data['race_other_attributes'] = array(
            'id' => 'race_other_label',
            'style' => $this->m_custom->display_static_option($user->us_race_id) == 'Other' ? 'display:inline' : 'display:none',
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone),
            'class' => 'phone_blur',
        );     
        
        $this->data['instagram_url'] = array(
            'name' => 'instagram_url',
            'id' => 'instagram_url',
            'type' => 'text',
            'value' => $this->form_validation->set_value('instagram_url', $user->us_instagram_url),
        );
        $this->data['facebook_url'] = array(
            'name' => 'facebook_url',
            'id' => 'facebook_url',
            'type' => 'text',
            'value' => $this->form_validation->set_value('facebook_url', $user->us_facebook_url),
        );
        
        //Blogger Function
        $us_is_blogger = $user->us_is_blogger;
        $this->data['us_is_blogger'] = $us_is_blogger;
        $this->data['is_blogger'] = array(
            'name' => 'is_blogger',
            'id' => 'is_blogger',
            'checked' => $us_is_blogger == "1"? TRUE : FALSE,
            'onclick' => "checkbox_showhide('is_blogger','profile-blogger-div')",
            'value' => $this->form_validation->set_value('is_blogger', $us_is_blogger),           
        );
        
        $this->data['blog_url'] = array(
            'name' => 'blog_url',
            'id' => 'blog_url',
            'type' => 'text',
            'value' => $this->form_validation->set_value('blog_url', $user->us_blog_url),
        );
        
        $this->data['blogger_list'] = $this->m_custom->get_dynamic_option_array('photography');
        $this->data['blogger_current'] = empty($user) ? array() : $this->m_custom->many_get_childlist('blogger', $user->id);
        
        //Photographer Function
        $us_is_photographer = $user->us_is_photographer;
        $this->data['us_is_photographer'] = $us_is_photographer;
        $this->data['is_photographer'] = array(
            'name' => 'is_photographer',
            'id' => 'is_photographer',
            'checked' => $us_is_photographer == "1"? TRUE : FALSE,
            'onclick' => "checkbox_showhide('is_photographer','profile-photographer-div')",
            'value' => $this->form_validation->set_value('is_photographer', $us_is_photographer),           
        );
        
        $this->data['photography_url'] = array(
            'name' => 'photography_url',
            'id' => 'photography_url',
            'type' => 'text',
            'value' => $this->form_validation->set_value('photography_url', $user->us_photography_url),
        );
        
        $this->data['photography_list'] = $this->m_custom->get_dynamic_option_array('photography');
        $this->data['photography_current'] = empty($user) ? array() : $this->m_custom->many_get_childlist('photography', $user->id);
        
        $this->data['temp_folder'] = $this->temp_folder;  
        $this->data['page_path_name'] = 'user/profile';
        $this->load->view('template/index', $this->data);
    }

    function update_profile_image()
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $user_id = $this->ion_auth->user()->row()->id;
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "change_image")
            {
                $upload_rule = array(
                    'upload_path' => $this->album_user_profile,
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

                redirect('all/user_dashboard/' . $user_id, 'refresh');
            }
        }
    }

    function edit_merchant_picture($picture_id = NULL)
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $message_info = '';
        $user_id = $this->ion_auth->user()->row()->id;
        $do_by_type = $this->main_group_id;
        $do_by_id = $user_id;

        $allowed_list = $this->m_custom->get_list_of_allow_id('merchant_user_album', 'user_id', $user_id, 'merchant_user_album_id', 'post_type', 'mer');
        if (!check_allowed_list($allowed_list, $picture_id))
        {
            redirect('/', 'refresh');
        }

        //$user_data = $this->m_custom->getUser($user_id);

        if (isset($_POST) && !empty($_POST))
        {
            if (IsNullOrEmptyString($picture_id))
            {
                redirect('user/upload_image', 'refresh');
            }
            $upload_rule = array(
                'upload_path' => $this->album_user_merchant,
                'allowed_types' => $this->config->item('allowed_types_image'),
                'max_size' => $this->config->item('max_size'),
                'max_width' => $this->config->item('max_width'),
                'max_height' => $this->config->item('max_height'),
            );

            $this->load->library('upload', $upload_rule);

            $picture_id = $this->input->post('picture_id');
            $post_file = "post-file";
            $post_title = $this->input->post('picture-title');
            $post_merchant_id = $this->input->post('picture-merchant');
            $post_desc = $this->input->post('picture-desc');
            if ($this->input->post('button_action') == "edit_picture")
            {
                $image_data = NULL;
                $previous_image_name = $this->m_custom->get_one_table_record('merchant_user_album', 'merchant_user_album_id', $picture_id)->image;

                //To check old deal got change image or not, if got then upload the new one and delete previous image
                if (!empty($_FILES[$post_file]['name']))
                {
                    if (!$this->upload->do_upload($post_file))
                    {
                        //$message_info = add_message_info($message_info, $this->upload->display_errors(), $post_title);
                        $message_info = add_message_info($message_info, $this->upload->display_errors(), $post_desc);
                    }
                    else
                    {
                        $image_data = array('upload_data' => $this->upload->data());
                        if (!IsNullOrEmptyString($previous_image_name))
                        {
                            delete_file($this->album_user_merchant . $previous_image_name);
                        }
                    }
                }

                $data = array(
                    'merchant_id' => $post_merchant_id,
                    'post_id' => $post_merchant_id,
                    //'title' => $post_title,
                    'title' => '',
                    'description' => $post_desc,
                    'image' => empty($image_data) ? $previous_image_name : $image_data['upload_data']['file_name'],
                );

                if ($this->m_custom->simple_update('merchant_user_album', $data, 'merchant_user_album_id', $picture_id))
                {
                    $this->m_custom->update_row_log('merchant_user_album', $picture_id, $do_by_id, $do_by_type);
                    //$message_info = add_message_info($message_info, 'Picture for merchant success update.', $post_title);
                    $message_info = add_message_info($message_info, 'Picture for merchant success update.', $post_desc);
                }
                else
                {
                    //$message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_title);
                    $message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_desc);
                }

                $this->session->set_flashdata('message', $message_info);
                redirect('user/edit_merchant_picture/' . $picture_id, 'refresh');
            }
            if ($this->input->post('button_action') == "remove_picture")
            {
                $data = array(
                    'hide_flag' => 1,
                );
                if ($this->m_custom->simple_update('merchant_user_album', $data, 'merchant_user_album_id', $picture_id))
                {
                    $this->m_custom->remove_row_log('merchant_user_album', $picture_id, $do_by_id, $do_by_type);
                    //$message_info = add_message_info($message_info, 'Picture for merchant success remove.', $post_title);
                    $message_info = add_message_info($message_info, 'Picture for merchant success remove.', $post_desc);
                    redirect('all/album_user_merchant/' . $user_id, 'refresh');
                }
                else
                {
                    //$message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_title);
                    $message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_desc);
                }
            }
            if ($this->input->post('button_action') == "back_picture")
            {
                redirect('all/merchant_user_picture/' . $picture_id, 'refresh');
            }
        }

        $picture_result = $this->m_custom->getOneMUA($picture_id);

        $this->data['picture_date'] = empty($picture_result) ? '' : displayDate($picture_result['create_date']);

        $this->data['picture_title'] = array(
            'name' => 'picture-title',
            'id' => 'picture-title',
            'value' => empty($picture_result) ? '' : $picture_result['title'],
        );

        $this->data['picture_image'] = empty($picture_result) ? $this->config->item('empty_image') : $this->album_user_merchant . $picture_result['image'];

        $this->data['picture_merchant'] = array(
            'name' => 'picture-merchant',
            'id' => 'picture-merchant',
        );
        $this->data['merchant_list'] = $this->m_merchant->getMerchantList();
        $this->data['picture_merchant_selected'] = empty($picture_result) ? '' : $picture_result['merchant_id'];

        $this->data['picture_desc'] = array(
            'name' => 'picture-desc',
            'id' => 'picture-desc',
            'value' => empty($picture_result) ? '' : $picture_result['description'],
        );

        $mua_id = empty($picture_result) ? '0' : $picture_result['merchant_user_album_id'];
        $this->data['mua_id_value'] = $mua_id;

        $this->data['picture_id'] = array(
            'picture_id' => $mua_id,
        );

        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'user/edit_merchant_picture';
        $this->load->view('template/index', $this->data);
    }

    function edit_user_picture($picture_id = NULL)
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $message_info = '';
        $user_id = $this->ion_auth->user()->row()->id;
        $do_by_type = $this->main_group_id;
        $do_by_id = $user_id;

        $allowed_list = $this->m_custom->get_list_of_allow_id('user_album', 'user_id', $user_id, 'user_album_id');
        if (!check_allowed_list($allowed_list, $picture_id))
        {
            redirect('/', 'refresh');
        }

        //$user_data = $this->m_custom->getUser($user_id);

        if (isset($_POST) && !empty($_POST))
        {
            if (IsNullOrEmptyString($picture_id))
            {
                redirect('user/upload_image', 'refresh');
            }
            $upload_rule = array(
                'upload_path' => $this->album_user,
                'allowed_types' => $this->config->item('allowed_types_image'),
                'max_size' => $this->config->item('max_size'),
                'max_width' => $this->config->item('max_width'),
                'max_height' => $this->config->item('max_height'),
            );

            $this->load->library('upload', $upload_rule);

            $picture_id = $this->input->post('picture_id');
            $post_file = "post-file";
            $post_title = $this->input->post('picture-title');
            $post_desc = $this->input->post('picture-desc');
            $post_album_id = $this->input->post('picture-main-album');
             
            if ($this->input->post('button_action') == "edit_picture")
            {
                $image_data = NULL;
                $previous_image_name = $this->m_custom->get_one_table_record('user_album', 'user_album_id', $picture_id)->image;

                //To check old deal got change image or not, if got then upload the new one and delete previous image
                if (!empty($_FILES[$post_file]['name']))
                {
                    if (!$this->upload->do_upload($post_file))
                    {
                        //$message_info = add_message_info($message_info, $this->upload->display_errors(), $post_title);
                        $message_info = add_message_info($message_info, $this->upload->display_errors(), $post_desc);
                    }
                    else
                    {
                        $image_data = array('upload_data' => $this->upload->data());
                        if (!IsNullOrEmptyString($previous_image_name))
                        {
                            delete_file($this->album_user . $previous_image_name);
                        }
                    }
                }

                $data = array(
                    //'title' => $post_title,
                    'title' => '',
                    'description' => $post_desc,
                    'album_id' => $post_album_id,
                    'image' => empty($image_data) ? $previous_image_name : $image_data['upload_data']['file_name'],
                );

                if ($this->m_custom->simple_update('user_album', $data, 'user_album_id', $picture_id))
                {
                    $this->m_custom->update_row_log('user_album', $picture_id, $do_by_id, $do_by_type);
                    //$message_info = add_message_info($message_info, 'Picture for user success update.', $post_title);
                    $message_info = add_message_info($message_info, 'Picture for user success update.', $post_desc);
                }
                else
                {
                    //$message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_title);
                    $message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_desc);
                }

                $this->session->set_flashdata('message', $message_info);
                redirect('user/edit_user_picture/' . $picture_id, 'refresh');
            }
            if ($this->input->post('button_action') == "remove_picture")
            {
                $data = array(
                    'hide_flag' => 1,
                );
                if ($this->m_custom->simple_update('user_album', $data, 'user_album_id', $picture_id))
                {
                    $this->m_custom->remove_row_log('user_album', $picture_id, $do_by_id, $do_by_type);
                    //$message_info = add_message_info($message_info, 'Picture for user success remove.', $post_title);
                    $message_info = add_message_info($message_info, 'Picture for user success remove.', $post_desc);
                    redirect('all/album_user/' . $user_id . '/' . $post_album_id, 'refresh');
                }
                else
                {
                    //$message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_title);
                    $message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_desc);
                }
            }
            if ($this->input->post('button_action') == "back_picture")
            {
                redirect('all/user_picture/' . $picture_id, 'refresh');
            }
        }

        $picture_result = $this->m_custom->getOneUserPicture($picture_id);

        $this->data['picture_date'] = empty($picture_result) ? '' : displayDate($picture_result['create_date']);

        $this->data['picture_title'] = array(
            'name' => 'picture-title',
            'id' => 'picture-title',
            'value' => empty($picture_result) ? '' : $picture_result['title'],
        );

        $this->data['picture_image'] = empty($picture_result) ? $this->config->item('empty_image') : $this->album_user . $picture_result['image'];

        $this->data['picture_desc'] = array(
            'name' => 'picture-desc',
            'id' => 'picture-desc',
            'value' => empty($picture_result) ? '' : $picture_result['description'],
        );

        $this->data['picture_main_album'] = array(
            'name' => 'picture-main-album',
            'id' => 'picture-main-album',
        );
        $this->data['main_album_list'] = $this->m_custom->getMainAlbum($user_id, NULL, 1);
        $this->data['picture_main_album_selected'] = empty($picture_result) ? '' : $picture_result['album_id'];
        
        $usa_id = empty($picture_result) ? '0' : $picture_result['user_album_id'];
        //$this->data['usa_id_value'] = $usa_id;

        $this->data['picture_id'] = array(
            'picture_id' => $usa_id,
        );

        $this->data['temp_folder'] = $this->temp_folder;
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'user/edit_user_picture';
        $this->load->view('template/index', $this->data);
    }

    function main_album($user_id = NULL)
    {
        $album_list = '';
        if ($user_id != NULL)
        {
            $album_list = $this->m_custom->getMainAlbum($user_id);
        }
        else
        {
            $album_list = $this->m_custom->getMainAlbum();
        }
        $this->data['album_list'] = $album_list;
        $this->data['title'] = "My Album";
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'user/main_album';
        $this->load->view('template/index_background_blank', $this->data);
    }
    
    function main_album_change($edit_id = NULL)
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->ion_auth->user()->row()->id;
        $login_type = $this->session->userdata('user_group_id');
        $is_edit = 0;
        $main_table = 'main_album';
        $main_table_id_column = 'album_id';
        $main_table_filter_column = 'user_id';
        $main_table_fiter_value = $login_id;

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
            $album_title = $this->input->post('album_title');

            // validate form input
            $this->form_validation->set_rules('album_title', $this->lang->line('main_album_title_label'), 'required');

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
                            'album_title' => $album_title,
                            'user_id' => $login_id,
                            'user_type' => $login_type,
                        );

                        $new_id = $this->m_custom->get_id_after_insert($main_table, $data);
                        if ($new_id)
                        {
                            $message_info = add_message_info($message_info, $album_title . ' success create.');
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
                            'album_title' => $album_title,
                        );

                        if ($this->m_custom->simple_update($main_table, $data, $main_table_id_column, $edit_id))
                        {
                            $message_info = add_message_info($message_info, $album_title . ' success update.');
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
            if ($this->input->post('button_action') == "remove_real")
            {
                $message_info = add_message_info($message_info, $album_title . ' success remove. All image in this album also removed.');
                $this->m_custom->update_hide_flag(1, $main_table, $edit_id);
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
                redirect('user/main_album/' . $login_id, 'refresh');
            }
            elseif ($can_redirect_to == 3)
            {
                redirect('user/main_album_change/' . $edit_id, 'refresh');
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

        $this->data['album_title'] = array(
            'name' => 'album_title',
            'id' => 'album_title',
            'value' => empty($result) ? $this->form_validation->set_value('album_title') : $this->form_validation->set_value('album_title', $result['album_title']),
        );

        $this->data['page_path_name'] = 'user/main_album_change';
        $this->load->view('template/index_background_blank', $this->data);
    }
    
    function upload_for_merchant($merchant_id_pass = NULL)
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $message_info = '';
        $user_id = $this->ion_auth->user()->row()->id;
        $login_type = $this->session->userdata('user_group_id');
        $user_data = $this->m_custom->get_one_table_record('users', 'id', $user_id);
        if (!IsNullOrEmptyString($merchant_id_pass))
        {
            $merchant_id = $merchant_id_pass;
        }
        $this->data['box_number'] = $this->box_number;
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "upload_image")
            {
                $can_redirect = 0;
                $upload_rule = array(
                    'upload_path' => $this->album_user_merchant,
                    'allowed_types' => $this->config->item('allowed_types_image'),
                    'max_size' => $this->config->item('max_size'),
                    'max_width' => $this->config->item('max_width'),
                    'max_height' => $this->config->item('max_height'),
                );

                $this->load->library('upload', $upload_rule);

                $validate_fail = 0;
                for ($i = 0; $i < $this->box_number; $i++)
                {
                    $user_today_upload_count = $this->m_user->get_user_today_upload_count($user_id);
                    $user_max_picture_per_day = $this->m_custom->web_setting_get('user_max_picture_per_day');

                    if ($user_today_upload_count >= $user_max_picture_per_day)
                    {
                        $message_info = add_message_info($message_info, 'You already reach max ' . $user_max_picture_per_day . ' picture upload per day. Please upload again after today.');
                        $this->session->set_flashdata('message', $message_info);
                        redirect('user/upload_for_merchant', 'refresh');
                    }

                    $post_file = "image-file-" . $i;
                    $post_title = $this->input->post('image-title-' . $i);
                    $post_merchant_id = $this->input->post('image-merchant-' . $i);
                    $post_desc = $this->input->post('image-desc-' . $i);

                    //For Multiple Image Upload
                    $have_hidden_image = 0;
                    $post_hidden_image = $this->input->post('hideimage-' . $i);
                    if (!empty($post_hidden_image))
                    {
                        $have_hidden_image = 1;
                        goto HiddenImageSkip;
                    }
                    
                    if (!empty($_FILES[$post_file]['name']))
                    {
                        //to do todo if want to add auto populate back
//                        $this->form_validation->set_rules('image-title-' . $i, $this->lang->line('album_title_label'), 'required');
//                        $this->form_validation->set_rules('image-merchant-' . $i, $this->lang->line('album_merchant_label'), 'required');
//                        $this->form_validation->set_rules('image-desc-' . $i, $this->lang->line('album_description_label'));
                        //if ($this->form_validation->run() == false)
                        if ($post_merchant_id == null)
                        {
                            $validate_fail = 1;
                            //$message_info = add_message_info($message_info, 'Merchant cannot be empty.', $post_title);
                            $message_info = add_message_info($message_info, 'Merchant cannot be empty.', $post_desc);
                            goto ValidateFail;
                        }
                        if (!$this->upload->do_upload($post_file))
                        {
                            $validate_fail = 1;
                            //$message_info = add_message_info($message_info, $this->upload->display_errors(), $post_title);
                            $message_info = add_message_info($message_info, $this->upload->display_errors(), $post_desc);
                        }
                        else
                        {
                            HiddenImageSkip:
                            $image_file_name = '';
                            if ($have_hidden_image == 0)
                            {
                                $image_data = array('upload_data' => $this->upload->data());
                                $image_file_name = $image_data['upload_data']['file_name'];
                            }
                            else   //For Multiple Image Upload
                            {
                                $from_path = $this->temp_folder_cut . $post_hidden_image;
                                $to_path = $this->album_user_merchant . $post_hidden_image;   
                                rename($from_path, $to_path);
                                $image_file_name = $post_hidden_image;
                            }
                            
                            $data = array(
                                'post_type' => 'mer',
                                'user_id' => $user_id,
                                'merchant_id' => $post_merchant_id,
                                'post_id' => $post_merchant_id,
                                //'title' => $post_title,
                                'title' => '',
                                'description' => $post_desc,
                                'image' => $image_file_name,
                            );

                            $new_id = $this->m_custom->get_id_after_insert('merchant_user_album', $data);
                            if ($new_id)
                            {
                                $this->m_custom->insert_row_log('merchant_user_album', $new_id, $user_id, $login_type);
                                $this->m_user->candie_history_insert(4, $new_id, 'merchant_user_album');
                                //$this->m_merchant->transaction_history_insert($post_merchant_id, 14, $new_id, 'merchant_user_album');
                                //$this->m_user->user_trans_history_insert($user_id, 21, $new_id);   //Temporary comment this because user upload image for merchant cannot get cash back already 
                                $this->m_custom->notification_process('merchant_user_album', $new_id);
                                //$message_info = add_message_info($message_info, 'Image for merchant ' . $this->m_custom->display_users($post_merchant_id) . ' success create.', $post_title);
                                $message_info = add_message_info($message_info, 'Image for merchant ' . $this->m_custom->display_users($post_merchant_id) . ' success create.', $post_desc);
                            }
                            else
                            {
                                //$message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_title);
                                $message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_desc);
                            }
                        }
                    }
                    ValidateFail:
                }
                $this->session->set_flashdata('message', $message_info);
                if ($validate_fail == 0)
                {
                    $this->m_custom->remove_image_temp();
                    redirect('all/album_user_merchant/' . $user_id, 'refresh');
                }
            }
        }

        $this->data['category_list'] = $this->m_custom->getCategoryList('0', '');
        $this->data['merchant_list'] = $this->m_merchant->getMerchantList();

        for ($i = 0; $i < $this->box_number; $i++)
        {
            $image_title = 'image_title' . $i;
            $this->data[$image_title] = array(
                'name' => 'image-title-' . $i,
                'id' => 'image-title-' . $i,
                'value' => $this->form_validation->set_value('image-title-' . $i),
            );

            $image_url = 'image_url' . $i;
            $this->data[$image_url] = $this->config->item('empty_image');

            $image_category = 'image_category' . $i;
            $this->data[$image_category] = array(
                'name' => 'image-category-' . $i,
                'id' => 'image-category-' . $i,
                'value' => $this->form_validation->set_value('image-category-' . $i),
                'onChange' => "get_Merchant(" . $i . ")",
            );

            $image_merchant = 'image_merchant' . $i;
            $this->data[$image_merchant] = array(
                'name' => 'image-merchant-' . $i,
                'id' => 'image-merchant-' . $i,
                'class' => 'chosen-select',
            );

            $image_merchant_selected = 'image_merchant_selected' . $i;
            $this->data[$image_merchant_selected] = empty($merchant_id) ? '' : $merchant_id;

            $image_desc = 'image_desc' . $i;
            $this->data[$image_desc] = array(
                'name' => 'image-desc-' . $i,
                'id' => 'image-desc-' . $i,
                'value' => $this->form_validation->set_value('image-desc-' . $i),
            );
        }

        $this->data['temp_folder'] = $this->temp_folder;  
        $this->data['temp_folder_cut'] = $this->temp_folder_cut;     
        $this->data['empty_image'] = $this->config->item('empty_image');
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'user/upload_for_merchant';
        $this->load->view('template/index_background_blank', $this->data);
    }

    public function get_merchant_by_category($i, $selected_category = NULL)
    {
        $merchant_list = array();
        if ($selected_category != '0')
        {
            $query = $this->m_merchant->getMerchantList_by_category($selected_category, 0, 1);

            foreach ($query as $item)
            {
                $merchant_list[$item->id] = $item->company;
            }
        }

        $image_merchant = array(
            'name' => 'image-merchant-' . $i,
            'id' => 'image-merchant-' . $i,
            'class' => 'chosen-select',
        );
        $output = form_dropdown($image_merchant, $merchant_list);
        echo $output;
    }

    function candie_page($search_month = NULL)
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
            
            $month_list = limited_month_select(2,1);
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
            
            $user_id = $this->ion_auth->user()->row()->id;
            $this->m_user->candie_balance_update($user_id);
            $this->data['previous_end_month_balance'] = $this->m_user->candie_check_balance($user_id, 1, month_previous($month_last_date));
            $this->data['end_month_balance'] = $this->m_user->candie_check_balance($user_id, 1, $month_last_date);
            $this->data['current_balance'] = $this->m_user->candie_check_balance($user_id, 0, $month_last_date);
            $this->data['this_month_redemption'] = $this->m_user->user_this_month_redemption($user_id, $selected_month);
            $this->data['this_month_candie_gain'] = $this->m_user->user_this_month_candie_gain($user_id, $selected_month);
            $this->data['this_month_candie'] = $this->m_user->user_this_month_candie($user_id, $selected_month);          
            
            $this->data['voucher_active_count'] = 'Active Voucher ('.count($this->m_user->user_redemption($user_id, $this->config->item('voucher_active'))).')';
            $this->data['voucher_used_count'] = 'Used Voucher ('.count($this->m_user->user_redemption($user_id, $this->config->item('voucher_used'))).')';
            $this->data['voucher_expired_count'] = 'Expired Voucher ('.count($this->m_user->user_redemption($user_id, $this->config->item('voucher_expired'))).')';
            
            $this->data['candie_url'] = base_url() . "user/candie_page";
            $this->data['voucher_active_url'] = base_url() . "user/redemption/" . $this->config->item('voucher_active');
            $this->data['voucher_used_url'] = base_url() . "user/redemption/" . $this->config->item('voucher_used');
            $this->data['voucher_expired_url'] = base_url() . "user/redemption/" . $this->config->item('voucher_expired');

            $this->data['message'] = $this->session->flashdata('message');
            $this->data['page_path_name'] = 'user/candie';
            $this->load->view('template/index', $this->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }

    function balance_page(){
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

            $user_id = $this->ion_auth->user()->row()->id;
            $this->m_user->user_balance_update($user_id);
            $this->data['previous_end_month_balance'] = $this->m_user->user_check_balance($user_id, 1, month_previous($month_last_date));
            $this->data['end_month_balance'] = $this->m_user->user_check_balance($user_id, 1, $month_last_date);
            $this->data['current_balance'] = $this->m_user->user_check_balance($user_id, 0, $month_last_date);
            $this->data['this_month_transaction'] = $this->m_user->user_this_month_transaction($user_id, $selected_month);

            $this->data['this_month_transaction_user_balance'] = $this->m_user->get_transaction_extra($user_id, 23, $selected_month);
            
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['page_path_name'] = 'user/balance_page';
            $this->load->view('template/index', $this->data);
        }
        else
        {
            redirect('/', 'refresh');
        }
    }
    
    function redemption($status_id = NULL, $sub_category = NULL, $merchant = NULL)
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $user_id = $this->ion_auth->user()->row()->id;

        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "search_by_subcategory")
            {
                $sub_category = $this->input->post('sub_category');
                $merchant = $this->input->post('merchant');
                $redeem_search_month = $this->input->post('redeem_the_month');
                $expire_search_month = $this->input->post('expire_the_month');
            }
        }
        
        $redeem_month_list = limited_month_select(5, 1, '0', 'All Redemption Month');
        $this->data['redeem_month_list'] = $redeem_month_list;
        $this->data['redeem_the_month'] = array(
            'name' => 'redeem_the_month',
            'id' => 'redeem_the_month',
        );
        $redeem_selected_month = empty($redeem_search_month) ? NULL : $redeem_search_month;
        $this->data['redeem_the_month_selected'] = $redeem_selected_month;

        $expire_month_list = limited_month_select(5, 1, '0', 'All Expire Month', 3);
        $this->data['expire_month_list'] = $expire_month_list;
        $this->data['expire_the_month'] = array(
            'name' => 'expire_the_month',
            'id' => 'expire_the_month',
        );
        $expire_selected_month = empty($expire_search_month) ? NULL : $expire_search_month;
        $this->data['expire_the_month_selected'] = $expire_selected_month;

        $this->data['redemption'] = $this->m_user->user_redemption($user_id, $status_id, $sub_category, $merchant, $redeem_selected_month, $expire_selected_month);
        if ($status_id != NULL)
        {
            $this->data['title'] = "Redemption : " . $this->m_custom->display_static_option($status_id);
        }
        else
        {
            $this->data['title'] = "Redemption";
        }

        $sub_category_list = $this->m_user->user_redemption_sub_category_list($user_id, $status_id);
        $this->data['sub_category_list'] = $sub_category_list;
        $this->data['sub_category'] = array(
            'name' => 'sub_category',
            'id' => 'sub_category',
        );
        $this->data['sub_category_selected'] = empty($sub_category) ? "" : $sub_category;

        $merchant_list = $this->m_user->user_redemption_merchant_list($user_id, $status_id);       
        $this->data['merchant_list'] = $merchant_list;
        $this->data['merchant'] = array(
            'name' => 'merchant',
            'id' => 'merchant',
        );
        $this->data['merchant_selected'] = empty($merchant) ? "" : $merchant;
        
        $this->data['voucher_active_count'] = 'Active Voucher ('.count($this->m_user->user_redemption($user_id, $this->config->item('voucher_active'), $sub_category, $merchant, $redeem_selected_month, $expire_selected_month)).')';
        $this->data['voucher_used_count'] = 'Used Voucher ('.count($this->m_user->user_redemption($user_id, $this->config->item('voucher_used'), $sub_category, $merchant, $redeem_selected_month, $expire_selected_month)).')';
        $this->data['voucher_expired_count'] = 'Expired Voucher ('.count($this->m_user->user_redemption($user_id, $this->config->item('voucher_expired'), $sub_category, $merchant, $redeem_selected_month, $expire_selected_month)).')';
        
        $this->data['candie_url'] = base_url() . "user/candie_page";
        $this->data['voucher_active_url'] = base_url() . "user/redemption/" . $this->config->item('voucher_active');
        $this->data['voucher_used_url'] = base_url() . "user/redemption/" . $this->config->item('voucher_used');
        $this->data['voucher_expired_url'] = base_url() . "user/redemption/" . $this->config->item('voucher_expired');

        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'user/redemption';
        $this->load->view('template/index', $this->data);
    }

    function review_adv($act_type = NULL, $users_id = NULL, $category = NULL)
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $user_id = $users_id == NULL ? $this->ion_auth->user()->row()->id : $users_id;
        $act_type = $act_type == NULL ? $this->config->item('user_activity_comment') : $act_type;

        //PAGE PATH NAME
        $data['page_path_name'] = 'user/review_adv';
        $data['message'] = $this->session->flashdata('message');
        $data['title'] = "Review (" . $this->m_custom->display_static_option($act_type) . ") ";

        $data['user_review_like'] = base_url() . "user/review_adv/" . $this->config->item('user_activity_like');
        $data['user_review_rating'] = base_url() . "user/review_adv/" . $this->config->item('user_activity_rating');
        $data['user_review_comment'] = base_url() . "user/review_adv/" . $this->config->item('user_activity_comment');
        $data['review_list'] = $this->m_user->user_review_list($act_type, $user_id, $category);
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

    function review_merchant($act_type = NULL, $users_id = NULL, $category = NULL)
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $user_id = $users_id == NULL ? $this->ion_auth->user()->row()->id : $users_id;
        $act_type = $act_type == NULL ? $this->config->item('user_activity_comment') : $act_type;

        //PAGE PATH NAME
        $data['page_path_name'] = 'share/merchant_grid_list5';
        $data['message'] = $this->session->flashdata('message');
        $data['title'] = "Review (" . $this->m_custom->display_static_option($act_type) . ") ";
        $data['user_review_like'] = base_url() . "user/review_merchant/" . $this->config->item('user_activity_like');
        $data['user_review_rating'] = base_url() . "user/review_merchant/" . $this->config->item('user_activity_rating');
        $data['user_review_comment'] = base_url() . "user/review_merchant/" . $this->config->item('user_activity_comment');
        $review_list = $this->m_user->user_review_merchant_list($act_type, $user_id, $category);
        $data['review_list'] = $review_list;
        $review_list_for_know_category = $this->m_user->user_review_merchant_list($act_type, $user_id);
        $category_list = array();
        $category_array = array();
        foreach ($review_list_for_know_category as $row)
        {
            if (!in_array($row['me_category_id'], $category_array))
            {
                $category_array[] = $row['me_category_id'];
                $temp_url = base_url() . "user/review_merchant/" . $act_type . "/" . $user_id . "/" . $row['me_category_id'];
                $category_list[] = "<a href=" . $temp_url . " >" . $row['me_category_name'] . "</a>";
            }
        }
        $data['category_list'] = $category_list;
        $this->load->view('template/index_background_blank', $data);
    }
    
    function upload_image($album_id = NULL)
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }
        $message_info = '';
        $user_id = $this->ion_auth->user()->row()->id;
        //$user_data = $this->m_custom->getUser($user_id);
        $this->data['box_number'] = $this->box_number;
        if (isset($_POST) && !empty($_POST))
        {
            if ($this->input->post('button_action') == "upload_image")
            {
                $can_redirect = 0;
                $upload_rule = array(
                    'upload_path' => $this->album_user,
                    'allowed_types' => $this->config->item('allowed_types_image'),
                    'max_size' => $this->config->item('max_size'),
                    'max_width' => $this->config->item('max_width'),
                    'max_height' => $this->config->item('max_height'),
                );
                $this->load->library('upload', $upload_rule);
                $validate_fail = 0;
                for ($i = 0; $i < $this->box_number; $i++)
                {
                    $user_today_upload_count = $this->m_user->get_user_today_upload_count($user_id);
                    $user_max_picture_per_day = $this->m_custom->web_setting_get('user_max_picture_per_day');
                    if ($user_today_upload_count >= $user_max_picture_per_day)
                    {
                        $message_info = add_message_info($message_info, 'You already reach max ' . $user_max_picture_per_day . ' picture upload per day. Please upload again after today.');
                        $this->session->set_flashdata('message', $message_info);
                        redirect('user/upload_image', 'refresh');
                    }
                    $post_file = "image-file-" . $i;
                    $post_title = $this->input->post('image-title-' . $i);
                    $post_desc = $this->input->post('image-desc-' . $i);
                    $post_album_id = $this->input->post('image-main-album-' . $i);
                    
                    //For Multiple Image Upload
                    $have_hidden_image = 0;
                    $post_hidden_image = $this->input->post('hideimage-' . $i);
                    if (!empty($post_hidden_image))
                    {
                        $have_hidden_image = 1;
                        goto HiddenImageSkip;
                    }

                    if (!empty($_FILES[$post_file]['name']))
                    {
                        if ($post_album_id == '0')
                        {
                            $validate_fail = 1;
                            //$message_info = add_message_info($message_info, 'Main Album cannot be empty.', $post_title);
                            $message_info = add_message_info($message_info, 'Main Album cannot be empty.', $post_desc);
                            goto ValidateFail;
                        }
                        
                        if (!$this->upload->do_upload($post_file))
                        {
                            $validate_fail = 1;
                            //$message_info = add_message_info($message_info, $this->upload->display_errors(), $post_title);
                            $message_info = add_message_info($message_info, $this->upload->display_errors(), $post_desc);
                        }
                        else
                        {
                            HiddenImageSkip:
                            $image_file_name = '';
                            if ($have_hidden_image == 0)
                            {
                                $image_data = array('upload_data' => $this->upload->data());
                                $image_file_name = $image_data['upload_data']['file_name'];
                            }
                            else  //For Multiple Image Upload
                            {
                                $from_path = $this->temp_folder_cut . $post_hidden_image;
                                $to_path = $this->album_user . $post_hidden_image;   
                                rename($from_path, $to_path);
                                $image_file_name = $post_hidden_image;
                            }

                            $data = array(
                                'user_id' => $user_id,
                                //'title' => $post_title,
                                'title' => '',
                                'description' => $post_desc,
                                'album_id' => $post_album_id,
                                'image' => $image_file_name,
                            );

                            $new_id = $this->m_custom->get_id_after_insert('user_album', $data);
                            if ($new_id)
                            {
                                //$this->m_user->candie_history_insert(5, $new_id, 'user_album');  //Upload self image not need give candie
                                //$message_info = add_message_info($message_info, 'Image for user ' . $this->m_custom->display_users($user_id) . ' success create.', $post_title);
                                $message_info = add_message_info($message_info, 'Image for user ' . $this->m_custom->display_users($user_id) . ' success create.', $post_desc);
                            }
                            else
                            {
                                //$message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_title);
                                $message_info = add_message_info($message_info, $this->ion_auth->errors(), $post_desc);
                            }
                        }
                    }
                    ValidateFail:
                }
                $this->session->set_flashdata('message', $message_info);
                if ($validate_fail == 0)
                {
                    $this->m_custom->remove_image_temp();
                    redirect('all/album_user/' . $user_id . '/' . $album_id, 'refresh');
                }
            }
        }
        $this->data['main_album_list'] = $this->m_custom->getMainAlbum($user_id, NULL, 1, '0', 'Please Select');
        for ($i = 0; $i < $this->box_number; $i++)
        {
            $image_title = 'image_title' . $i;
            $this->data[$image_title] = array(
                'name' => 'image-title-' . $i,
                'id' => 'image-title-' . $i,
                'value' => $this->form_validation->set_value('image-title-' . $i),
            );
            
            $image_url = 'image_url' . $i;
            $this->data[$image_url] = $this->config->item('empty_image');
            $image_desc = 'image_desc' . $i;
            $this->data[$image_desc] = array(
                'name' => 'image-desc-' . $i,
                'id' => 'image-desc-' . $i,
                'value' => $this->form_validation->set_value('image-desc-' . $i),
            );
            
            $image_main_album = 'image_main_album' . $i;
            $this->data[$image_main_album] = array(
                'name' => 'image-main-album-' . $i,
                'id' => 'image-main-album-' . $i,
                'value' => $this->form_validation->set_value('image-main-album-' . $i),
            );
            $image_main_album_selected = 'image_main_album_selected' . $i;
            $this->data[$image_main_album_selected] = empty($album_id) ? '' : $album_id;
        }
        $this->data['temp_folder'] = $this->temp_folder;   
        $this->data['temp_folder_cut'] = $this->temp_folder_cut;     
        $this->data['empty_image'] = $this->config->item('empty_image');
        $this->data['message'] = $this->session->flashdata('message');
        $this->data['page_path_name'] = 'user/upload_image';
        $this->load->view('template/index_background_blank', $this->data);
    }

    // edit a user, no use
    function edit_user($id)
    {
        $this->data['title'] = "Edit user";

        if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
        {
            redirect('user', 'refresh');
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
                        redirect('user', 'refresh');
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
                        redirect('user', 'refresh');
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

        $this->_render_page('user/edit_user', $this->data);
    }
    
    function invite_friend()
    {
        //SESSION LOGGED DATA
        $logged_main_group_id = $this->session->userdata('user_group_id');
        //CONFIG ITEM
        $group_id_user = $this->config->item('group_id_user');
        if($logged_main_group_id == $group_id_user)
        {
            //LOGGED CORRECT
            $data['message'] = $this->session->flashdata('message');
            //POST SUBMIT
            if($this->input->post())
            {
                //LOGGED
                $logged_user_id = $this->session->userdata('user_id');
                $where_read_user = array('id' => $logged_user_id);
                $query_read_user = $this->albert_model->read_user($where_read_user);
                $logged_user_email = $query_read_user->row()->email;
                //INPUT POST
                $input_email = $this->input->post('email');
                //VALIDATION
                $this->form_validation->set_rules('email', 'E-mail Address', 'required|valid_email');
                if ($this->form_validation->run() == true)
                {
                    //VALIDATION SUCCESS
                    $current_month = date("n");
                    $current_year = date("Y");
                    //READ USER_INVITE_FRIEND
                    $where_user_invite_friend = array('user_id' => $logged_user_id, 'friend_email' => $input_email);
                    $query_user_invite_friend = $this->albert_model->read_user_invite_friend($where_user_invite_friend);
                    $num_rows_user_invite_friend = $query_user_invite_friend->num_rows();
                    if ($num_rows_user_invite_friend > 0)
                    {
                        //DUPLICATED DATA
                        $this->session->set_flashdata('message', "You had already invited '$input_email'");
                    }
                    else
                    {
                        //QUERY CANDIE BALANCE
                        $where_candie_balance = array('user_id' => $logged_user_id, 'month_id' => $current_month, 'year' => $current_year);
                        $query_candie_balance = $this->albert_model->read_candie_balance($where_candie_balance);
                        $num_rows_candie_balance = $query_candie_balance->num_rows();

                        if ($this->invite_friend_send_email($input_email, $logged_user_email))
                        {
                            if ($num_rows_candie_balance > 0)
                            {
                                //UPDATE CANDIE BALANCE
                                $where_candie_balance_invite_friend_count_increment = array('user_id' => $logged_user_id, 'month_id' => $current_month, 'year' => $current_year);
                                $this->albert_model->update_candie_balance_invite_friend_count_increment($where_candie_balance_invite_friend_count_increment);
                                if ($this->db->affected_rows() == 0)
                                {
                                    //INSERT FAIL
                                    $this->session->set_flashdata('message', 'Update fail for candie');
                                }
                            }
                            else
                            {
                                $data_candie_balance = array('user_id' => $logged_user_id, 'month_id' => $current_month, 'year' => $current_year, 'month_last_date' => displayDate(displayLastDay($current_year, $current_month), 0, 1));
                                $this->albert_model->insert_candie_balance($data_candie_balance);
                                if ($this->db->affected_rows() == 0)
                                {
                                    //INSERT FAIL
                                    $this->session->set_flashdata('message', 'Insert fail for candie');
                                }
                                $query_candie_balance = $this->albert_model->read_candie_balance($where_candie_balance);
                            }
                            //INSERT USER_INVITE_FRIEND
                            $data_user_invite_friend = array('user_id' => $logged_user_id, 'friend_email' => $input_email);
                            $this->albert_model->insert_user_invite_friend($data_user_invite_friend);
                            if ($this->db->affected_rows() == 0)
                            {
                                //INSERT FAIL
                                $this->session->set_flashdata('message', 'Insert fail for invite friend');
                            }
                            //INSERT SUCCESS
                            $this->session->set_flashdata('message', 'Invitation email sent');

                            //GIVE USER CANDIE FOR THE FIRST 5 INVITATION MONTHLY
                            $invitation_send_current_month = $query_candie_balance->row_array();
                            $user_max_invitation_get_candie_per_month = $this->m_custom->web_setting_get('user_max_invitation_get_candie_per_month');
                            if ($invitation_send_current_month['invite_friend_count'] <= $user_max_invitation_get_candie_per_month)
                            {
                                $this->m_user->candie_history_insert(6, $invitation_send_current_month['balance_id'], 'candie_balance', 1);
                            }
                        }
                    }
                    redirect('user/invite_friend', 'refresh');
                }
                else
                {
                    //VALIDATION FAIL            
                    $this->session->set_flashdata('message', validation_errors());
                    redirect('user/invite_friend', 'refresh');
                }
            }
        }
        else
        {
            //LOGGED INCORRECT
            redirect('/', 'refresh');
        }
        $data['page_path_name'] = 'user/invite_friend';
        $this->load->view('template/index', $data);
    }
    
    //SEND INVITATION EMAIL 
    function invite_friend_send_email($input_email, $logged_user_email)
    {
        //LOAD LIBRARY
        $this->load->library('email');
        $this->email->from('info@keppo.my', 'Info Keppo');
        $this->email->to($input_email);
        $this->email->bcc('weechiat.teo@hotmail.com');
        $this->email->subject('Email Test');
        $this->email->message("
            You has been invited by $logged_user_email to join <a href='http://www.keppo.my/'>www.keppo.my</a> <br/><br/>
            <a href='http://www.keppo.my/user/register'>Sign Up</a> Now
        ");
        return $this->email->send();
    }

    function contact_admin()
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->ion_auth->user()->row()->id;
        //$login_type = $this->session->userdata('user_group_id');
        $main_table = 'user_message';
        $main_table_id_column = 'msg_id';
        $main_table_filter_column = 'msg_type';
        $main_table_fiter_value = 'withdraw';

        $result_list = $this->m_admin->user_withdraw_request(0, 3, $login_id);     
        $this->data['the_result'] = $result_list;
        $this->data['current_balance'] = $this->m_user->user_check_balance($login_id);
        
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['page_path_name'] = 'user/contact_admin';
        $this->load->view('template/index', $this->data);
    }
    
    function contact_admin_change()
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->ion_auth->user()->row()->id;

        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $msg_content = $this->input->post('msg_content');
            $msg_desc = $this->input->post('msg_desc');
            $msg_remark = $this->input->post('msg_remark');

            // validate form input
            $this->form_validation->set_rules('msg_content', 'Bank Name', 'required');
            $this->form_validation->set_rules('msg_desc', 'Bank Account No', 'required');
            $this->form_validation->set_rules('msg_remark', 'Extra Info');
            
            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $this->m_custom->user_message_insert_withdraw_request($msg_content, $msg_desc, $msg_remark);
                    $message_info = add_message_info($message_info, 'Withdraw request send.');
                    $can_redirect_to = 2;
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
                redirect('user/contact_admin', 'refresh');
            }       
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        
        $this->data['msg_content'] = array(
            'name' => 'msg_content',
            'id' => 'msg_content',
            'value' => $this->form_validation->set_value('msg_content'),
        );
        
        $this->data['msg_desc'] = array(
            'name' => 'msg_desc',
            'id' => 'msg_desc',
            'value' => $this->form_validation->set_value('msg_desc'),
        );
        
        $this->data['msg_remark'] = array(
            'name' => 'msg_remark',
            'id' => 'msg_remark',
            'value' => $this->form_validation->set_value('msg_remark', 'I want to withdraw RM50.'),
        );

        $this->data['page_path_name'] = 'user/contact_admin_change';
        $this->load->view('template/index', $this->data);
    }
    
    function promo_code()
    {
        if (!check_correct_login_type($this->main_group_id))
        {
            redirect('/', 'refresh');
        }

        $message_info = '';
        $login_id = $this->ion_auth->user()->row()->id;
        $this->m_custom->promo_code_insert_user($login_id);
        
        if (isset($_POST) && !empty($_POST))
        {
            $can_redirect_to = 0;
            $promo_code = $this->input->post('promo_code');

            // validate form input
            $this->form_validation->set_rules('promo_code', 'Promo Code', 'required');

            if ($this->input->post('button_action') == "save")
            {
                if ($this->form_validation->run() === TRUE)
                {
                    $message_info = $this->m_custom->promo_code_history_insert($promo_code);
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

        $promo_code = $this->m_custom->promo_code_get('user', $login_id, 1);
        $this->data['promo_code_no'] = array(
            'name' => 'promo_code_no',
            'id' => 'promo_code_no',
            'type' => 'text',
            'readonly' => 'true',
            'value' => $promo_code,
        );
        $this->data['promo_code_url'] = $this->m_custom->generate_promo_code_list_link($promo_code, 32);
        
        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['page_path_name'] = 'user/promo_code';
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
