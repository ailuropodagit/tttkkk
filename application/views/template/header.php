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
        <script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
        <?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>
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
                        
            //FB LOGOUT
            function fbLogout() {
                ////log out both facebook and app
                //FB.logout();
                //logout fb only but required permission pop up
                FB.api('/me/permissions', 'delete', function(response) {
                   console.log(response.status); // true for successful logout.
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
                FB.getLoginStatus();
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

            $(function(){
                //AUTO COMPLETE
                $("#search_word").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "/keppo/home/get_merchant_list/",
                            data: { term: $("#search_word").val()},
                            dataType: "json",
                            type: "POST",
                            success: function(data){
                                var resp = $.map(data,function(obj){                     
                                    return obj.tag;                  
                                });
                                response(data);
                            }
                        });
                    }
                });
            });
        </script>
    </head>
    <body>        
        <!--HEADER-->
        <div id='header'>
            <?php
            //URI
            $header_fetch_class = $this->router->fetch_class();
            $header_fetch_method = $this->router->fetch_method();
            $header_uri_segment4 = $this->uri->segment(4);
            ?>
            <div id='wrapper'>
                <!--LOGO-->
                <div id='header-logo'>
                    <a href='<?php echo base_url('home') ?>'>
                        <img src='<?php echo base_url('image/header-logo-red.png') ?>' id='header-logo-img'>
                    </a>
                </div>
                <!--NORMAL MENU-->
                <div id="header-menu">
                    <ul>
                        <!--NORMAL MENU HOME-->
                        <li <?php if($header_fetch_class == 'home'){ echo "class='header-menu-active'"; } ?>>
                            <a href='<?php echo base_url('home') ?>'>
                                <i class="fa fa-home header-menu-icon header-menu-icon-home"></i>Home
                            </a>
                        </li>
                        <?php 
                        if($this->router->fetch_class() != 'home')
                        {
                            ?>
                            <!--NORMAL MENU CATEGORIES-->
                            <li <?php if($header_fetch_class == 'categories'){ echo "class='header-menu-active'"; } ?>>
                                <a href='<?php echo base_url('categories') ?>'>
                                    <i class="fa fa-th-large header-menu-icon"></i>Categories
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                        <!--NORMAL MENU HOT DEAL-->     
                        <li <?php if($header_fetch_method == 'hotdeal_list' || $header_uri_segment4 == 'hot'){ echo "class='header-menu-active'"; } ?>>
                            <a href='<?php echo base_url('all/hotdeal-list/26') ?>'>
                                <i class="fa fa-fire header-menu-icon"></i>Hot Deal
                            </a>
                        </li>
                        <!--NORMAL MENU REDEMPTION-->
                        <li <?php if($header_fetch_method == 'promotion_list' || $header_uri_segment4 == 'pro'){ echo "class='header-menu-active'"; } ?>>
                            <a href="<?php echo base_url('all/promotion-list/26') ?>">
                                <i class="fa fa-gift header-menu-icon"></i>Redemption
                            </a>
                        </li>
                        <!--NORMAL MENU BLOGGER-->
                        <li <?php if($header_fetch_class == 'blogger'){ echo "class='header-menu-active'"; } ?>>
                            <a href='<?php echo base_url('blogger') ?>'>
                                <i class="fa fa-pencil header-menu-icon"></i>Blogger
                            </a>
                        </li>
                        <?php
                        if (check_is_login())
                        {
                            if (check_correct_login_type($this->config->item('group_id_user'))) 
                            {
                                ?>
                                <!--NORMAL MENU USER PROFILE-->
                                <li <?php if($header_fetch_method == 'profile'){ echo "class='header-menu-active'"; } ?>>
                                    <a href='<?php echo base_url('user/profile') ?>'>
                                        <i class='fa fa-user header-menu-icon'></i>Profile
                                    </a>
                                </li>
                                <!--NORMAL MENU USER LOGOUT-->
                                <li>
                                    <a href='<?php echo base_url('user/logout') ?>' onclick="fbLogout()">
                                        <i class='fa fa-sign-out header-menu-icon'></i>Logout
                                    </a>
                                </li>
                                <?php
                            }
                            else 
                            {
                                ?>
                                <!--NORMAL MENU MERCHANT PROFILE-->
                                <li <?php if($header_fetch_method == 'profile'){ echo "class='header-menu-active'"; } ?>>
                                    <a href='<?php echo base_url('merchant/profile') ?>'>
                                        <i class='fa fa-user header-menu-icon'></i>Profile
                                    </a>
                                </li>
                                <!--NORMAL MENU MERCHANT LOGOUT-->
                                <li>
                                    <a href='<?php echo base_url('merchant/logout') ?>'>
                                        <i class='fa fa-sign-out header-menu-icon'></i>Logout
                                    </a>
                                </li>
                                <?php
                            }
                        }
                        else
                        { 
                            ?>
                            <!--NORMAL MENU USER LOGIN-->
                            <li>
                                <a href='<?php echo base_url('user/login') ?>'>
                                    <i class="fa fa-user header-menu-icon"></i>Login
                                </a>
                            </li>
                            <!--NORMAL MENU USER REGISTER-->
                            <li>
                                <a href='<?php echo base_url('user/register') ?>'>
                                    <i class="fa fa-user-plus header-menu-icon"></i>Register
                                </a>
                            </li>
                            <?php
                        } 
                        ?>                            
                    </ul>
                </div>
                <div id='float-fix'></div>
                <!--MOBILE MENU ICON-->
                <div id="header-menu-mobile-icon"><i class="fa fa-bars"></i></div>
            </div>
        </div>
        <!-- MENU MOBILE-->
        <div id="header-menu-mobile">
            <div id="wrapper">
                <ul>
                    <!--MOBILE MENU HOME-->
                    <li>
                        <a href='<?php echo base_url('home') ?>'>
                            <i class="fa fa-home header-menu-icon header-menu-icon-home"></i>Home
                        </a>
                    </li>
                    <?php
                    if($header_fetch_class != 'home')
                    {
                        ?>
                        <!--MOBILE MENU CATEGORIES-->
                        <li>
                            <a href='<?php echo base_url('categories') ?>'>
                                <i class="fa fa-th-large header-menu-icon"></i>Categories
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <!--MOBILE MENU HOT DEAL-->
                    <li>
                        <a href='<?php echo base_url('all/hotdeal-list/26') ?>'>
                            <i class="fa fa-fire header-menu-icon"></i>Hot Deal
                        </a>
                    </li>
                    <!--MOBILE MENU REDEMPTION-->
                    <li>
                        <a href='<?php echo base_url('all/promotion-list/26') ?>'>
                            <i class="fa fa-diamond header-menu-icon"></i>Redemption
                        </a>
                    </li>
                    <?php
                    if(check_is_login())
                    {
                        if (check_correct_login_type($this->config->item('group_id_user'))) 
                        {
                            ?>
                            <!--MOBILE MENU USER PROFILE-->
                            <li>
                                <a href='<?php echo base_url('user/profile') ?>'>
                                    <i class='fa fa-user header-menu-icon'></i>Profile
                                </a>
                            </li>
                            <!--MOBILE MENU USER LOGOUT-->
                            <li>
                                <a href='<?php echo base_url('user/logout') ?>' onclick="fbLogout()">
                                    <i class='fa fa-sign-out header-menu-icon'></i>Logout
                                </a>
                            </li>
                            <?php
                        } 
                        else 
                        {
                            ?>
                            <!--MOBILE MENU MERCHANT PROFILE-->
                            <li>
                                <a href='<?php echo base_url('merchant/profile') ?>'>
                                    <i class='fa fa-user header-menu-icon'></i>Profile
                                </a>
                            </li>
                            <!--MOBILE MENU MERCHANT LOGOUT-->
                            <li>
                                <a href='<?php echo base_url('merchant/logout') ?>'>
                                    <i class='fa fa-sign-out header-menu-icon'></i>Logout
                                </a>
                            </li>
                            <?php
                        }
                    } 
                    else
                    { 
                        ?>
                        <!--MOBILE MENU USER LOGIN-->
                        <li>
                            <a href='<?php echo base_url('user/login') ?>'>
                                <i class="fa fa-user header-menu-icon"></i>Login
                            </a>
                        </li>
                        <!--MOBILE MENU USER REGISTER-->
                        <li>
                            <a href='<?php echo base_url('user/login'); ?>'>
                                <i class="fa fa-user-plus header-menu-icon"></i>Register
                            </a>
                        </li>
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
                                <input type="text" placeholder="Search: Merchant, Hot Deal, Promotion" name="search_word" id="search_word">
                                <span id="search-icon"><i class="fa fa-search"></i></span>
                            </div>
                            <div id="search-box-block2">
                                <?php
                                $state_list = $this->m_custom->get_static_option_array('state', '0', 'All');
                                $me_state_id = array(
                                    'name' => 'me_state_id',
                                    'id' => 'me_state_id',
                                );
                                $selected_state = $this->uri->segment(4);
                                if(!empty($selected_state) && $this->router->fetch_method() == 'home_search'){
                                    echo form_dropdown($me_state_id, $state_list, $selected_state);
                                }else{
                                echo form_dropdown($me_state_id, $state_list);
                                }
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
