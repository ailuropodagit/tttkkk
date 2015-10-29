<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Keppo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo base_url('css/main.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/main-1100.css') ?>" media="screen and (max-width: 1100px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0900.css') ?>" media="screen and (max-width: 0900px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0700.css') ?>" media="screen and (max-width: 0700px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0500.css') ?>" media="screen and (max-width: 0500px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0400.css') ?>" media="screen and (max-width: 0400px)">
        <link rel="stylesheet" href="<?php echo base_url('library/font-awesome/font-awesome-4.4.0.css') ?>">
        <script type="text/javascript" src='<?php echo base_url() ?>js/jquery/jquery-2.1.4.min.js'></script>
        <script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
        <link rel="stylesheet" href="<?php echo base_url('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('js/bootstrap-3.3.5/dist/css/custom-bootstrap.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('js/bootstrap-3.3.5/dist/css/custom-bootstrap-modal.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('js/bootstrap-3.3.5/dist/css/custom-bootstrap-modal-center.css') ?>">
        <script type="text/javascript" src="<?php echo base_url('js/bootstrap-3.3.5/dist/js/bootstrap.min.js') ?>"></script>       
        <script>
            $(function(){
                //BOOSTRAP MODAL
                //$('#visit-first-time').modal('show');
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
        
        <?php
        if (!isset($_COOKIE['visit_first_time']))
        {
            //COOKIE NO EXPIRE
            setcookie('visit_first_time', 'no');
            //BOOSTRAP MODAL
            ?>
            <div class="modal fade" id="visit-first-time" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="bootstrap-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <center>
                                <div style='font-size: 30px; margin: 20px 0px 20px 0px;'>Welcome to keppo.my</div>
                                <div style='font-size: 20px; margin: 0px 0px 20px 0px;'>Pop up only first time visit</div>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
              
        <!--HEADER-->
        <div id='header'>
            <?php
            //CONFIG DATA
            $header_album_user_profile_path = $this->config->item('album_user_profile');
            $header_album_merchant_profile_path = $this->config->item('album_merchant_profile');
            $header_empty_image = $this->config->item('empty_image');
            
            //URI
            $header_fetch_class = $this->router->fetch_class();
            $header_fetch_method = $this->router->fetch_method();
            $header_uri_segment4 = $this->uri->segment(4);
            
            if (check_is_login())
            {
                $login_user_id = $this->session->userdata('user_id');                            
                if (check_correct_login_type($this->config->item('group_id_user'))) 
                {
                    //PROFILE DISPLAY USER
                    $header_where_read_user = array('id'=>$login_user_id);
                    $header_query_read_user = $this->albert_model->read_user($header_where_read_user);
                    $header_row_read_user = $header_query_read_user->row();
                    $header_profile_login_profile_image = $header_row_read_user->profile_image;
                    $header_profile_login_user_name = $header_row_read_user->first_name . ' ' . $header_row_read_user->last_name;
                }
                else
                {
                    //PROFILE DSIPLAY MERCHANT
                    $header_where = array('id'=>$login_user_id);
                    $header_query_read_merchant_superviosr_as_merchant = $this->albert_model->read_merchant_supervisor_as_merchant($header_where);
                    $header_row_read_merchant_superviosr_as_merchant = $header_query_read_merchant_superviosr_as_merchant->row();
                    $header_profile_login_slug = $header_row_read_merchant_superviosr_as_merchant->slug;
                    $header_profile_login_profile_image = $header_row_read_merchant_superviosr_as_merchant->profile_image;
                    $header_profile_login_company_name =  $header_row_read_merchant_superviosr_as_merchant->company;
                }
            }
            ?>
            <!--HEADER BACKGROUND-->
            <div id="header-background">
                <div class="header-background-skew"></div>
            </div>
            <!--HEADER TOP BAR-->
            <div id="header-top-bar">
                <div id='wrapper'>
                    <div id='header-top-bar-social-media'>
                        <div id='header-top-bar-social-media-each'>
                            <a href='https://www.facebook.com' target='_blank'><i class="fa fa-facebook"></i></a>
                        </div>
                        <div id='header-top-bar-social-media-each'>
                            <a href='https://twitter.com/' target='_blank'><i class="fa fa-twitter"></i></a>
                        </div>
                        <div id='header-top-bar-social-media-each'>
                            <a href='https://plus.google.com' target='_blank'><i class="fa fa-google-plus"></i></a>
                        </div>
                        <div id='header-top-bar-social-media-each'>
                            <a href='https://www.linkedin.com/' target='_blank'><i class="fa fa-linkedin"></i></a>
                        </div>
                        <div id='header-top-bar-social-media-each'>
                            <a href='https://www.pinterest.com/' target='_blank'><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                    <div id='header-top-bar-navigation'>
                        <ul>
                            <?php
                            if (!check_is_login())
                            {
                                ?>
                                <li <?php if($header_fetch_method == 'login'){ echo "class='header-top-bar-navigation-active'"; } ?>>
                                    <a href='<?php echo base_url('user/login') ?>'>Login</a>
                                </li>
                                <li <?php if($header_fetch_method == 'create_user'){ echo "class='header-top-bar-navigation-active'"; } ?>>
                                    <a href='<?php echo base_url('user/register') ?>'>Register</a>
                                </li>
                                <?php
                            }
                            else
                            {                     
                                if (check_correct_login_type($this->config->item('group_id_user'))) 
                                {
                                    ?>
                                    <ul>
                                        <li <?php if($header_fetch_method == 'user_dashboard'){ echo "class='header-top-bar-navigation-active'"; } ?>>
                                            <a href='<?php echo base_url("all/user_dashboard/$login_user_id") ?>'>
                                                Dashboard
                                            </a>
                                        </li>
                                        <li>
                                             <a href='<?php echo base_url('merchant/logout') ?>'>
                                                 <i class='fa fa-sign-out header-top-bar-navigation-icon'></i> Logout
                                             </a>
                                        </li>
                                    </ul>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <li <?php if($header_fetch_method == 'merchant_dashboard'){ echo "class='header-top-bar-navigation-active'"; } ?>>
                                        <a href='<?php echo base_url("all/merchant_dashboard/$header_profile_login_slug") ?>'>
                                            Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a href='<?php echo base_url('user/logout') ?>' onclick="fbLogout()">
                                            <i class='fa fa-sign-out header-menu-icon'></i>Logout
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div id='float-fix'></div>
                </div>
            </div>
            <!--HEADER LOGO BAR-->
            <div id='header-logo-bar'>
                <div id='wrapper'>
                    <div id='header-logo-bar-logo'>
                        <a href='<?php echo base_url('home') ?>'>
                            <img src='<?php echo base_url('image/header-logo-red.png') ?>'>
                        </a>
                    </div>
                    <div id="header-logo-bar-profile-display">
                        <?php
                        if (check_is_login())
                        {                          
                            if (check_correct_login_type($this->config->item('group_id_user'))) 
                            {
                                ?>
                                <a href='<?php echo base_url("all/user_dashboard/$login_user_id") ?>'>
                                    <div id="header-logo-bar-profile-display-photo">
                                        <div id="header-logo-bar-profile-display-photo-box">
                                            <?php 
                                            if($header_profile_login_profile_image)
                                            {
                                                echo img("$header_album_user_profile_path/$header_profile_login_profile_image");
                                            }
                                            else
                                            {
                                                echo img($header_empty_image);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div id="header-logo-bar-profile-display-name">
                                        <?php echo $header_profile_login_user_name; ?>
                                    </div>
                                </a>
                                <?php
                            }
                            else 
                            {
                                ?>
                                <a href='<?php echo base_url("all/merchant_dashboard/$header_profile_login_slug") ?>'>
                                    <div id="header-logo-bar-profile-display-photo">
                                        <div id="header-logo-bar-profile-display-photo-box">
                                            <?php
                                            if($header_profile_login_profile_image)
                                            {
                                                echo img("$header_album_merchant_profile_path/$header_profile_login_profile_image");
                                            }
                                            else
                                            {
                                                echo img($header_empty_image);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div id="header-logo-bar-profile-display-name">
                                        <?php echo $header_profile_login_company_name; ?>
                                    </div>
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div id='float-fix'></div>
                </div>
            </div>
            <!--HEADER NAVIGATION-->
            <div id='wrapper'>
                <div id='header-navigation-bar'>
                    <div id='header-navigation-bar-left'>
                        <ul>
                            <li <?php if($header_fetch_class == 'home'){ echo "class='header-menu-active'"; } ?>>
                                <a href='<?php echo base_url('home') ?>'>Home</a>
                            </li>
                            <li <?php if($header_fetch_class == 'categories'){ echo "class='header-menu-active'"; } ?>>
                                <a href='<?php echo base_url('categories') ?>'>Categories</a>
                            </li>
                            <li <?php if($header_fetch_method == 'hotdeal_list' || $header_uri_segment4 == 'hot'){ echo "class='header-menu-active'"; } ?>>
                                <a href='<?php echo base_url('all/hotdeal-list/26') ?>'>Hot Deal</a>
                            </li>
                            <li <?php if($header_fetch_method == 'promotion_list' || $header_uri_segment4 == 'pro'){ echo "class='header-menu-active'"; } ?>>
                                <a href="<?php echo base_url('all/promotion-list/26') ?>">Redemption</a>
                            </li>
                        </ul>
                    </div>
                    <div id='header-navigation-bar-right'>
                        <ul>
                            <li <?php if($header_fetch_class == 'blogger'){ echo "class='header-menu-active'"; } ?>>
                                <a href='<?php echo base_url('blogger') ?>'>Blogger</a>
                            </li>
                            <li <?php if($header_fetch_class == 'photographer'){ echo "class='header-menu-active'"; } ?>>
                                <a href='<?php echo base_url('photographer') ?>'>Photographer</a>
                            </li>
                        </ul>
                    </div>
                    <div id="float-fix"></div>
                </div>
            </div>
            

            <div  style="display: none"><i class="fa fa-bars"></i></div>

                    
        <!--SEARCH BAR-->
<!--        <div id="wrapper">
            <div id="search">
                <div id="search-content">
                    <div id="search-content-box">
                        <div id="search-content-box-content">
                            <?php echo form_open('all/home_search');?>
                            <div id="search-box-block1">
                                <input type="text" placeholder="Search: Merchant, Hot Deal, Promotion" name="search_word" id="search_word">
                            </div>
                            <div id="search-box-block2">
                                <?php
                                $state_list = $this->m_custom->get_static_option_array('state', '0', 'All');
                                $me_state_id = array(
                                    'name' => 'me_state_id',
                                    'id' => 'me_state_id',
                                );
                                $selected_state = $this->uri->segment(4);
                                if (!empty($selected_state) && $this->router->fetch_method() == 'home_search')
                                {
                                    echo form_dropdown($me_state_id, $state_list, $selected_state);
                                }
                                else
                                {
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
        </div>-->