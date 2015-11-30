<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{

    function __construct()
    {
        parent::__construct();
    }

    //VALID CONTACT NUMBER
    public function valid_contact_number($str)
    {
        $this->CI->form_validation->set_message('valid_contact_number', 'The %s field is incorrect, please set a real contact number');
        if (preg_match("/^([\.+\s-0-9_-]){9,12}+$/i", $str))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
   
    public function valid_date($str)
    {
        //date each
        $dob_day = substr($str, 0,2);
        $dob_month = substr($str, 3,3);
        $dob_year = substr($str, 7,4);
        //date format
        $dob_day_format = $dob_day;
        $dob_month_format = date('m',strtotime($dob_month));
        $dob_year_format = $dob_year;
        //date ori
        $date_ori = $dob_day_format .'-'. $dob_month_format .'-'. $dob_year_format;
        $date_format = date('d-m-Y',strtotime($str));
        //compare date
        if($date_ori != $date_format)
        {
            //invalid date
            $this->CI->form_validation->set_message('valid_date', 'The %s field is incorrect');
            return FALSE;
        }
        else
        {
            //valid date
            return TRUE;
        }
    }
    
    public function valid_facebook_email($input_email, $fb_email) 
    {
        if($input_email == $fb_email)
        {
            return TRUE;
        }
        else
        {
            $this->CI->form_validation->set_message('valid_facebook_email', 'The %s field is incorrect. Please enter your facebook E-mail Address');
            return FALSE; 
        }
    }
    
    public function required_dropdown($str)
    {
        if($str != 0)
        {
            return TRUE;
        }
        else
        {
            $this->CI->form_validation->set_message('required_dropdown', 'The %s field is required. Please select.');
            return FALSE; 
        }
    }
    
}
