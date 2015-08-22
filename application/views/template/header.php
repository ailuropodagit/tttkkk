<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Keppo</title>
        <?php echo link_tag('css/main.css') ?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    </head>
    <body>
        <!--HEADER-->
        <div id='header'>
            <div id='wrapper'>
                <!--HEADER LOGO-->
                <div id='header-logo'>
                    <a href='<?php echo base_url(); ?>home'><img src="<?php echo base_url(); ?>image/header-logo.png" id='header-logo-img'></a>
                </div>
                <!--HEADER MENU-->
                <div id="header-menu">
                    <ul>
                        <li><a href='<?php echo base_url(); ?>home'><i class="fa fa-home header-menu-icon header-menu-icon-home"></i>Home</a></li>
                        <li><a href='#'><i class="fa fa-th-large header-menu-icon"></i>Categories</a></li>
                        <li><a href='#'><i class="fa fa-fire header-menu-icon"></i>Hot Deal</a></li>
                        <li><a href='#'><i class="fa fa-diamond header-menu-icon"></i>Redemption</a></li>
                        <li><a href='<?php echo base_url(); ?>user/login'><i class="fa fa-user header-menu-icon"></i>Login</a></li>
                        <li><a href='<?php echo base_url(); ?>user/register'><i class="fa fa-user-plus header-menu-icon"></i>Register</a></li>
                    </ul>
                </div>
                <div id="float-fix"></div>
            </div>
        </div>
        <!--SEARCH BAR-->
        <div id="wrapper">
            <div id="search">
                <div id="search-content">
                    <div id="search-content-box">
                        <div id="search-content-box-content">
                            <div id="search-box-block1">
                                <style>
                                    #filtersubmit {
                                        position: absolute;
                                        left: 23px;
                                        top: 23px;
                                        color: #7B7B7B;
                                    }
                                </style>
                                <input type="text" placeholder="Search: Tony Roma's, Vans, ChatTime">
                                <i id="filtersubmit" class="fa fa-search"></i>
                            </div>
                            <div id="search-box-block2">
                                    <?php
                                $state_list = $this->m_custom->get_static_option_array('state', '0', 'All');

                                $me_state_id = array(
                                    'name' => 'me_state_id',
                                    'id' => 'me_state_id',
                                );
                                echo form_dropdown($me_state_id, $state_list);
                                ?>
                            </div>
                            <div id="search-box-block3">
                                <input type='submit' value='Search'>
                            </div>
                            <div id="float-fix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
//        if($this->ion_auth->logged_in()){
//            echo 'logged';
//        }else{
//            echo 'logout';
//        }