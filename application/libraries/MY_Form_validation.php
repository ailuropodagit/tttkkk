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
        $this->CI->form_validation->set_message('valid_contact_number','The Contact Number: Incorrect, please set a real contact number');
        if(preg_match("/^([\.\s-0-9_-])+$/i", $str))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}    
