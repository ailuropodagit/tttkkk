<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Keppo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <?php echo link_tag('css/main.css') ?>
        <link rel="stylesheet" href="<?php echo base_url() ?>css/main-1100.css" media="screen and (max-width: 1100px)">
        <link rel="stylesheet" href="<?php echo base_url() ?>css/main-0900.css" media="screen and (max-width: 0900px)">
        <link rel="stylesheet" href="<?php echo base_url() ?>css/main-0700.css" media="screen and (max-width: 0700px)">
        <link rel="stylesheet" href="<?php echo base_url() ?>css/main-0500.css" media="screen and (max-width: 0500px)">
        <link rel="stylesheet" href="<?php echo base_url() ?>css/main-0400.css" media="screen and (max-width: 0400px)">
        <link rel="stylesheet" href="<?php echo base_url() ?>library/font-awesome/font-awesome-4.4.0.css">
        <script type="text/javascript" src='<?php echo base_url() ?>js/jquery/jquery-2.1.4.min.js'></script>
        <script>
            $(function(){
                //BROSWER RESIZE
                $(window).resize(function(){
                    var window_width = $(window).width();
                    if(window_width >= 1100){
                        $('#header-menu-mobile').hide();
                    }
                });   
                //HEADER MENU MOBILE 
                $('#header-menu-mobile-icon').click(function(){
                    $('#header-menu-mobile').toggle();
                });
            });
        </script>
    </head>
    <body>
<!--if(!$this->ion_auth->logged_in())-->

            <script>
                // Call from FB.getLoginStatus().
                function statusChangeCallback(response) {
                    //console.log(response);
                    // Login status
                    if (response.status === 'connected') {
                        // connected
                        FB.api('/me/permissions/public_profile', function (response) {
                            if (response.data[0].status === 'granted') {
                                //public profile granted
                                FB.api('/me/permissions/email', function (response) {
                                    if (response.data[0].status === 'granted') {
                                        //email granted
                                        FB.api('/me', {fields: 'id,first_name,last_name,email,gender'}, function (response) {
                                            document.getElementById('login-facebook-label').innerHTML = "Logged in";
                                            var fb_id = response.id;
                                            var fb_email = response.email;
                                            var fb_first_name = response.first_name;
                                            var fb_last_name = response.last_name;
                                            var fb_gender = response.gender;
                                            $.ajax({
                                                type: "POST",
                                                url: "<?php echo base_url() ?>user/login_facebook",
                                                data: {fb_id:fb_id, fb_email:fb_email, fb_first_name:fb_first_name, fb_last_name:fb_last_name, fb_gender:fb_gender},
                                                success: function(data) {
                                                    document.getElementById('login-facebook-label').innerHTML = data;
                                                    
                                                }
                                            });
                                            //window.location.replace("<?php echo base_url() ?>user/login_facebook");
                                        });
                                    } else {
                                        //email declined
                                        document.getElementById('login-facebook-label').innerHTML = "Email Declined";
                                        FB.login(function(response) {
                                            //console.log(response);
                                        }, {
                                            scope: 'email',
                                            auth_type: 'rerequest'
                                        });
                                    }
                                });
                            } else {
                                //public profile declined
                                document.getElementById('login-facebook-label').innerHTML = "Public Profile Declined";
                            }
                        });
                    } else if (response.status === 'not_authorized') {
                        // not_authorized
                    } else {
                        // unknown
                    }
                }

                // Check Login Status
                function checkLoginState() {
                    FB.getLoginStatus(function (response) {
                        statusChangeCallback(response);
                    });
                }
                
                // Logout
                function fbLogout() {
                    FB.logout(function(response) {
                        console.log(response);
                        document.getElementById('login-facebook-label').innerHTML = "Log In with facebook";
                    });
                }

                window.fbAsyncInit = function () {
                    FB.init({
                        appId: '1636247466623391',
                        cookie: true,
                        xfbml: true,
                        version: 'v2.2'
                    });
                    //Get if logged in
                    FB.getLoginStatus(function (response) {
                        statusChangeCallback(response);
                    });
                };

                // Load the SDK asynchronously
                (function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {
                        return;
                    }
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            </script>

        <!--HEADER-->
        <div id='header'>
            <div id='wrapper'>
                <!--HEADER LOGO-->
                <div id='header-logo'>
                    <a href='<?php echo base_url(); ?>home'><img src="<?php echo base_url(); ?>image/header-logo-red.png" id='header-logo-img'></a>
                </div>
                <!--HEADER MENU-->
                <div id="header-menu">
                    <ul>
                        <li <?php if($this->router->fetch_class() == 'home'){ echo "class='header-menu-active'"; } ?>>
                            <a href='<?php echo base_url(); ?>home'><i class="fa fa-home header-menu-icon header-menu-icon-home"></i>Home</a>
                        </li>
                        <?php 
                        if($this->router->fetch_class() != 'home')
                        {
                            ?>
                            <li <?php if($this->router->fetch_class() == 'categories'){ echo "class='header-menu-active'"; } ?>>
                                <a href='<?php echo base_url(); ?>categories'><i class="fa fa-th-large header-menu-icon"></i>Categories</a>
                            </li>
                            <?php
                        }
                        ?>
                        <li <?php if($this->router->fetch_method() == 'hotdeal_list' || $this->uri->segment(4) == 'hot'){ echo "class='header-menu-active'"; } ?>>
                            <a href='<?php echo base_url(); ?>all/hotdeal-list/26/0'><i class="fa fa-fire header-menu-icon"></i>Hot Deal</a>
                        </li>
                        <li <?php if($this->router->fetch_method() == 'promotion_list' || $this->uri->segment(4) == 'pro'){ echo "class='header-menu-active'"; } ?>>
                            <a href='<?php echo base_url(); ?>all/promotion-list/26/0'><i class="fa fa-gift header-menu-icon"></i>Redemption</a>
                        </li>
                        <?php
                        if (check_is_login())
                        {                            
                            if (check_correct_login_type($this->config->item('group_id_user'))) 
                            {
                                ?>
                                <li <?php if($this->router->fetch_method() == 'profile'){ echo "class='header-menu-active'"; } ?>>
                                    <a href='<?php echo base_url() ?>user/profile'><i class='fa fa-user header-menu-icon'></i>Profile</a>
                                </li>
                                <li>
                                    <a href='<?php echo base_url() ?>user/logout' onclick="fbLogout()"><i class='fa fa-sign-out header-menu-icon'></i>Logout</a>
                                </li>
                                <?php
                            } 
                            else 
                            {
                                ?>
                                <li <?php if($this->router->fetch_method() == 'profile'){ echo "class='header-menu-active'"; } ?>>
                                    <a href='<?php echo base_url() ?>merchant/profile'><i class='fa fa-user header-menu-icon'></i>Profile</a>
                                </li>
                                <li>
                                    <a href='<?php echo base_url() ?>merchant/logout' onclick="fbLogout()"><i class='fa fa-sign-out header-menu-icon'></i>Logout</a>
                                </li>
                                <?php
                            }
                        } 
                        else
                        { 
                            ?>
                            <li><a href='<?php echo base_url(); ?>user/login'><i class="fa fa-user header-menu-icon"></i>Login</a></li>
                            <li><a href='<?php echo base_url(); ?>user/register'><i class="fa fa-user-plus header-menu-icon"></i>Register</a></li>
                            <?php
                        } 
                        ?>
                    </ul>
                </div>
                <!--HEADER MENU MOBILE ICON-->
                <div id="header-menu-mobile-icon">
                    <i class="fa fa-bars"></i>
                </div>
                <div id="float-fix"></div>
            </div>
        </div>
        <!--HEADER MENU MOBILE-->
        <div id="header-menu-mobile">
            <div id="wrapper">
                <ul>
                    <li><a href='<?php echo base_url(); ?>home'><i class="fa fa-home header-menu-icon header-menu-icon-home"></i>Home</a></li>
                    <?php 
                    if($this->router->fetch_class() != 'home')
                    {
                        ?>
                        <li><a href='<?php echo base_url(); ?>categories'><i class="fa fa-th-large header-menu-icon"></i>Categories</a></li>
                        <?php
                    }
                    ?>
                    <li><a href='<?php echo base_url(); ?>all/hotdeal-list/26/0'><i class="fa fa-fire header-menu-icon"></i>Hot Deal</a></li>
                    <li><a href='<?php echo base_url(); ?>all/promotion-list/26/0'><i class="fa fa-diamond header-menu-icon"></i>Redemption</a></li>
                    <?php 
                    if(check_is_login())
                    {
                        if (check_correct_login_type($this->config->item('group_id_user'))) 
                        {
                            ?>
                            <li><a href='<?php echo base_url() ?>user/profile'><i class='fa fa-user header-menu-icon'></i>Profile</a></li>
                            <li><a href='<?php echo base_url() ?>user/logout' onclick="fbLogout()"><i class='fa fa-sign-out header-menu-icon'></i>Logout</a></li>
                            <?php
                        } 
                        else 
                        {
                            ?>
                            <li><a href='<?php echo base_url() ?>merchant/profile'><i class='fa fa-user header-menu-icon'></i>Profile</a></li>
                            <li><a href='<?php echo base_url() ?>merchant/logout' onclick="fbLogout()"><i class='fa fa-sign-out header-menu-icon'></i>Logout</a></li>
                            <?php
                        }
                    } 
                    else
                    { 
                        ?>
                        <li><a href='<?php echo base_url(); ?>user/login'><i class="fa fa-user header-menu-icon"></i>Login</a></li>
                        <li><a href='<?php echo base_url(); ?>user/register'><i class="fa fa-user-plus header-menu-icon"></i>Register</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
        <!--SEARCH BAR-->
        <div id="wrapper">
            <div id="search">
                <div id="search-content">
                    <div id="search-content-box">
                        <div id="search-content-box-content">
                            <?php echo form_open('all/home_search');?>
                            <div id="search-box-block1">
                                <input type="text" placeholder="Search: Merchant, Hot Deal, Promotion" name="search_word">
                                <span id="search-icon"><i class="fa fa-search"></i></span>
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
                                <button name="button_action" type="submit" value="search">Search</button>
                            </div>
                            <?php echo form_close(); ?>
                            <div id="float-fix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>