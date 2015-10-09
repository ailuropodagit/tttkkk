<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_us extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        $message_info = '';
        if (isset($_POST) && !empty($_POST))
        {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $phone = $this->input->post('phone');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');
            if (IsNullOrEmptyString($name) || IsNullOrEmptyString($email) || IsNullOrEmptyString($subject))
            {
                $message_info = add_message_info($message_info, 'Name, Email & Subject cannot be empty.');
            }
            else
            {
                $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
                if ($this->form_validation->run() == true)
                {
                    $email_data = array(
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'subject' => $subject,
                        'message' => $message,
                    );
                    $this->session->set_flashdata('mail_info', $email_data);
                    redirect('contact_us/send_mail_process', 'refresh');
                }
                else
                {
                    $message_info = add_message_info($message_info, 'E-mail not valid. Please use a valid email.');
                }
            }
            $this->session->set_flashdata('message', $message_info);
        }
        $this->data['message'] = $this->session->flashdata('message');

        $this->data['page_path_name'] = 'page/contact_us';
        $this->load->view('template/layout', $this->data);
    }

    function send_mail_process()
    {
        $message_info = '';
        $email_data = $this->session->flashdata('mail_info');
        $get_status = send_mail_simple($this->config->item('keppo_admin_email'), 'Keppo Inquiry : ' . $email_data['subject'], '<br/>Name : ' . $email_data['name'] .
                '<br/>Email : ' . $email_data['email'] .
                '<br/>Phone : ' . $email_data['phone'] .
                '<br/>Message : ' . $email_data['message'], 'keppo_contact_us_send_email_success', 0);
        if ($get_status)
        {
            $message_info = add_message_info($message_info, 'Thank you! Success send your email inquiry to Keppo admin.');
        }
        else
        {
            $message_info = add_message_info($message_info, 'Some error happen, system send email fail, sorry for this, please call us or manually send to ' . $this->config->item('keppo_admin_email'));
        }
        $this->session->set_flashdata('message', $message_info);
        redirect('contact_us', 'refresh');
    }

}
