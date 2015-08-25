<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_User_agent extends CI_User_agent {

    public function __construct()
    {
        parent::__construct();
    }

    public function is_tablet()
    {
        //echo $_SERVER['HTTP_USER_AGENT'].'<br/>';
        if(strpos($_SERVER['HTTP_USER_AGENT'],'iPad')||stripos($_SERVER['HTTP_USER_AGENT'],"Tablet")||stripos($_SERVER['HTTP_USER_AGENT'],"Nexus 10")||stripos($_SERVER['HTTP_USER_AGENT'],"Nexus 7")){
            return TRUE;
        }
        return False;

    }
}