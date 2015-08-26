<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('set_simple_message')) {

    function set_simple_message($title = '', $sentence1 = '', $sentence2 = '', $back_page_url = '', $back_page = '', $maintain_page = '') {
        $ci = & get_instance();
        $simple_info = array(
            'title' => $title,
            'sentence1' => $sentence1 . "</br>",
            'sentence2' => $sentence2 . "</br>",
            'back_page_url' => $back_page_url,
            'back_page' => $back_page,
            'maintain_page' => $maintain_page,
        );

        $ci->session->set_flashdata('simple_info', $simple_info);
        redirect($maintain_page, 'refresh');
    }

}

if (!function_exists('display_simple_message')) {

    function display_simple_message() {
        $ci = & get_instance();
        $simple_info = $ci->session->flashdata('simple_info');
        if (!empty($simple_info)) {
            $ci->data['simple_info'] = $simple_info;
            $ci->data['page_path_name'] = 'simple_message';          
            $ci->load->view('template/layout', $ci->data);
        } else {
            redirect('/', 'refresh');
        }
    }

}

if (!function_exists('check_correct_login_type')) {
function check_correct_login_type($desired_group_id = NULL){
        $ci = & get_instance();
        
        //Check is it login
        if (!$ci->ion_auth->logged_in()) {
            return FALSE;
        }
        
        $id = $ci->ion_auth->user()->row()->id;
        //Check is the url id is same with login session id
        if (!($ci->ion_auth->user()->row()->id == $id)) {
            return FALSE;
        }
        
        $user = $ci->ion_auth->user($id)->row();
        //Check is this user type can go in this page or not
        if ($user->main_group_id != $desired_group_id) {
            return FALSE;
        }
        return TRUE;
    }
}

if (!function_exists('check_is_login')) {
function check_is_login(){
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        
        //Check is it login
        if (!$ci->ion_auth->logged_in()) {
            return FALSE;
        }
        return TRUE;
    }
}

if (!function_exists('send_mail_simple')) {

    function send_mail_simple($to_email = '', $to_subject = '', $to_message = '', $success_message = '') {
        $ci = & get_instance();
        $ci->load->library('email'); // Note: no $config param needed
        $ci->email->from($ci->config->item('smtp_user'), $ci->config->item('from_name'));
        $ci->email->to($to_email);
        $ci->email->subject($to_subject);
        $ci->email->message($to_message);
        if ($ci->email->send()) {
            $ci->ion_auth->set_message($success_message);
            return TRUE;
        } else {
            //show_error($this->email->print_debugger());
            $ci->ion_auth->set_error('fail_to_send_email');
            return False;
        }
    }

}

if (!function_exists('generate_options')) {

    function generate_slug($value='') {
        return url_title($value, 'dash', TRUE);
    }
}
if (!function_exists('generate_options')) {

    function generate_options($from, $to, $callback = false) {
        $reverse = false;
        if ($from > $to) {
            $tmp = $from;
            $from = $to;
            $to = $tmp;
            $reverse = true;
        }
        $return_string = array();
        for ($i = $from; $i <= $to; $i++) {
            $return_string[] = '<option value="' . $i . '">' . ($callback ? $callback($i) : $i) . '</option>';
        }

        if ($reverse) {
            $return_string = array_reverse($return_string);
        }
        return join('', $return_string);
    }

}
if (!function_exists('generate_number_option')) {

    function generate_number_option($from, $to) {
        return array_combine(range($from, $to), range($from, $to));
    }

}
if (!function_exists('callback_month')) {

    function callback_month($month) {
        return date('F', mktime(0, 0, 0, $month, 1));
    }

}

if (!function_exists('format_date')) {

    function format_date($date) {
        $parts = explode('-', $date);
        return date('F j, Y', mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]));
    }

}

if (!function_exists('IsNullOrEmptyString')) {

    function IsNullOrEmptyString($value) {
        return (!isset($value) || trim($value) === '' || empty($value));
    }

}