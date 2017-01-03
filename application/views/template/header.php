<!DOCTYPE html>
<html>
    <html xmlns="https://www.w3.org/1999/xhtml" xmlns:og="https://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
    <head>
        <meta charset="UTF-8">
        <meta property="fb:app_id" content="1682555468669559" />
        <title>
            <?php           
            if (isset($this->data['title']))   //First Level To Set Tab Title
            {
                tab_title($this->data['title']);
            }
            else if (isset($this->data['sub_title']))
            {
                tab_title($this->data['sub_title']);
            }
            else if (isset($this->data['page_title']))
            {
                tab_title($this->data['page_title']);
            }
            else
            {
                echo 'Keppo';
            }
            ?>
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" href="<?php echo base_url('image/favicon.ico') ?>">
        <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,700,900' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo base_url('css/all.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/main.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('css/main-1200.css') ?>" media="screen and (max-width: 1200px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-1100.css') ?>" media="screen and (max-width: 1100px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-1000.css') ?>" media="screen and (max-width: 1000px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0900.css') ?>" media="screen and (max-width: 0900px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0800.css') ?>" media="screen and (max-width: 0800px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0700.css') ?>" media="screen and (max-width: 0700px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0600.css') ?>" media="screen and (max-width: 0600px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0500.css') ?>" media="screen and (max-width: 0500px)">
        <link rel="stylesheet" href="<?php echo base_url('css/main-0400.css') ?>" media="screen and (max-width: 0400px)">
        <link rel="stylesheet" href="<?php echo base_url('library/font-awesome/font-awesome-4.4.0.css') ?>">
        <script type="text/javascript" src="<?php echo base_url('js/jquery/jquery-2.1.4.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('js/jquery-ui-1.11.4.custom/jquery-ui.js') ?>"></script>
        <link rel="stylesheet" href="<?php echo base_url('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('js/bootstrap-3.3.5/dist/css/custom-bootstrap.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('js/bootstrap-3.3.5/dist/css/custom-bootstrap-modal.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('js/bootstrap-3.3.5/dist/css/custom-bootstrap-modal-center.css') ?>">
        <script type="text/javascript" src="<?php echo base_url('js/bootstrap-3.3.5/dist/js/bootstrap.min.js') ?>"></script>
        
        <!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">-->
        <link href="<?php echo base_url('js/bootstrap-photo-gallery/jquery.bsPhotoGallery.css') ?>" rel="stylesheet">
        <!--<script src="<?php echo base_url('js/bootstrap-photo-gallery/jquery.bsPhotoGallery.js') ?>"></script>-->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('js/bootstrap-photo-gallery/jquery.bsPhotoGallery.js') ?>"></script>

        <script>
          $(document).ready(function(){
            $('ul.first').bsPhotoGallery({
              "classes" : "col-lg-2 col-md-4 col-sm-3 col-xs-4 col-xxs-12",
              "hasModal" : true,
              // "fullHeight" : false
            });
            $("#pop-up-slide-1").trigger('click'); 
          });
        </script>

        <script>
            $(function(){     
                
                setTimeout(function(){
                    //BOOSTRAP MODAL
                    $('#visit-first-time-modal').modal('show');
                }, 10000);

                //MOBILE MENU
                var mobile_menu_show = 0;
                $('#header-logo-bar-mobile-navigation-icon').click(function(){
                    if(mobile_menu_show == 0){
                        mobile_menu_show = 1;
                        $("#header-mobile-navigation").css({display: 'inline'});                        
                        $("#header-mobile-navigation-block").css({display: 'inline'});
                        $("body").css({'overflow': 'hidden'});
                    }else{
                        mobile_menu_show = 0;
                        $("#header-mobile-navigation").css({display: 'none'});                        
                        $("#header-mobile-navigation-block").css({display: 'none'});
                        $("body").css({'overflow': 'scroll'});
                    }
                });
                
                //HIDE SUB MENU
                var sub_menu_status = 0;
                //$(".header-mobile-navigation-bar-box-each-merchant").css({'display':'none'});
                $(".header-mobile-navigation-food-n-beverage").click(function(){                    
                    if(sub_menu_status == 0){
                        $(".header-mobile-navigation-food-n-beverage").next().css({'display':'block'});
                        sub_menu_status = 1;
                    }else{
                        $(".header-mobile-navigation-food-n-beverage").next().css({'display':'none'});
                        sub_menu_status = 0;
                    }
                });
                $(".header-mobile-navigation-keppo-voucher").click(function(){                    
                    if(sub_menu_status == 0){
                        $(".header-mobile-navigation-keppo-voucher").next().css({'display':'block'});
                        sub_menu_status = 1;
                    }else{
                        $(".header-mobile-navigation-keppo-voucher").next().css({'display':'none'});
                        sub_menu_status = 0;
                    }
                });
                $(".header-mobile-navigation-others").click(function(){
                    if(sub_menu_status == 0){
                        $(".header-mobile-navigation-others").next().css({'display':'block'});
                        sub_menu_status = 1;
                    }else{
                        $(".header-mobile-navigation-others").next().css({'display':'none'});
                        sub_menu_status = 0;
                    }
                });
                
                //CLOSE MOBILE MENU
                $("#header-mobile-navigation-block, #header-mobile-navigation-close-icon").click(function(){
                    mobile_menu_show = 0;
                    $("#header-mobile-navigation").css({display: 'none'});                        
                    $("#header-mobile-navigation-block").css({display: 'none'});
                    $("body").css({'overflow': 'auto'});
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
                    appId: '<?php echo fb_appID(); ?>',
                    cookie: true,
                    xfbml: true,
                    version: 'v2.5'
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
            
            function myfunction(submit_to_where) {
                document.getElementById("general_form_login").action = submit_to_where;
                document.getElementById("general_form_login").submit();
            }
            
            //Third Level To Set Tab Title
            $(document).ready(function () {
            if(document.title == 'Keppo'){
            var h1s = document.getElementsByTagName("h1");
                for (var i = 0; i < h1s.length; i++) {
                    document.title = h1s[i].textContent;
                }   
            }
            });
        </script>
        <?php
        if (isset($meta_fb))
        {
            echo $meta_fb;
        }
        ?>
    </head>
    <style type="text/css">
        #ui-id-1{
            max-height:300px;
            overflow-y:auto;
        }
        @font-face {
            font-family: 'Glyphicons Halflings';

            src: url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/fonts/glyphicons-halflings-regular.eot');
            src: url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'), url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/fonts/glyphicons-halflings-regular.woff2') format('woff2'), url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/fonts/glyphicons-halflings-regular.woff') format('woff'), url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/fonts/glyphicons-halflings-regular.ttf') format('truetype'), url('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular') format('svg');
        }
        .glyphicon {
            position: relative;
            top: 1px;
            display: inline-block;
            font-family: 'Glyphicons Halflings';
            font-style: normal;
            font-weight: normal;
            line-height: 1;

            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .glyphicon-chevron-left:before {
            content: "\e079";
        }
        .glyphicon-chevron-right:before {
            content: "\e080";
        }        
        .glyphicon-remove-circle:before {
            content: "\e088";
        }
        #bsPhotoGalleryModal .modal-dialog{
            position:relative;
            width:99%;
            margin:0px;
        }
        .glyphicon-chevron-right{
            right:80%;
        }
        .glyphicon-chevron-left{
            left:80%;
        }
        #bsPhotoGalleryModal .bsp-close{
            right:-5px;
            top:0px;
        }
    </style>
    <body>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-76880426-1', 'auto');
        ga('send', 'pageview');
    </script>
        <?php
        $fetch_method = $this->router->fetch_method();
        if (!isset($_COOKIE['visit_first_time']) && $fetch_method != 'login')
        {
            //COOKIE NO EXPIRE
            setcookie('visit_first_time', 'no');
            //BOOSTRAP MODAL
            if (!$this->ion_auth->logged_in())
            {
                ?>
                <div class="modal fade" id="visit-first-time-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="bootstrap-close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <div id="visit-first-time-modal-title">
                                    Welcome to keppo.my
                                </div>
                                <div id='visit-first-time-modal-title-sub'>
                                    Get access to restaurants, great offer and much more promotion...
                                </div>
                                <div id='visit-first-time-modal-horizontal-line'></div>
                                <div id="visit-first-time-modal-left">
                                    <div id='visit-first-time-modal-left-explore'>
                                        Explore Keppo for <span style="color: #0185c6">FREE!</span>
                                    </div>
                                    <div id='visit-first-time-modal-left-login-form-register'>
                                        <?php
                                        $user_register = base_url() . 'user/register';
                                        $merchant_register = base_url() . 'merchant/register';
                                        ?>
                                        <div id='visit-first-time-modal-left-login-form-register-user'>
                                            Already Register? <a href="<?php echo $user_register; ?>">User Register</a>
                                        </div>
                                        <!--<div id='visit-first-time-modal-left-login-form-register-merchant'>
                                            <a href="<?php //echo $merchant_register; ?>">Merchant Register</a>
                                        </div>-->
                                    </div>
                                    <form action="<?php echo base_url() . 'user/login'; ?>" method="post" accept-charset="utf-8" id="general_form_login">
                                        <?php
                                        $user_login = base_url() . 'user/login';
                                        $user_retrieve_pass = base_url() . 'user/retrieve-password';
                                        $merchant_login = base_url() . 'merchant/login';
                                        $merchant_retrieve_pass = base_url() . 'merchant/retrieve-password';
                                        $identity = array('name' => 'identity',
                                            'id' => 'identity',
                                            'type' => 'text',
                                        );
                                        $password = array('name' => 'password',
                                            'id' => 'password',
                                            'type' => 'password',
                                        );
                                        ?>
                                        <div id='visit-first-time-modal-left-login-form'>
                                            <div id='visit-first-time-modal-left-login-form-email'>
                                                <div id='visit-first-time-modal-left-login-form-email-label'>E-mail / Username</div>
                                                <div id='visit-first-time-modal-left-login-form-email-input'><?php echo form_input($identity); ?></div>
                                            </div>
                                            <div id='visit-first-time-modal-left-login-form-password'>
                                                <div id='visit-first-time-modal-left-login-form-password-label'>Password</div>
                                                <div id='visit-first-time-modal-left-login-form-password-input'><?php echo form_input($password); ?></div>
                                            </div>
                                            <div id='visit-first-time-modal-left-login-form-submit'>
                                                <div id='visit-first-time-modal-left-login-form-submit-button'>
                                                    <input type="submit" value="User Login" onclick="myfunction('<?php echo $user_login; ?>')"/>
                                                </div>
                                                <div id='visit-first-time-modal-left-login-form-submit-login-with-facebook'>
                                                    <a href='<?php echo base_url() ?>user/login'><img style='width:140px' src='<?php echo base_url('image/social-media-facebook-login.png') ?>'></a>
  <!--                                                  <input type="submit" value="Merchant Login" onclick="myfunction('<?php //echo $merchant_login; ?>')"/> -->
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="visit-first-time-modal-right">
                                    <div id="visit-first-time-modal-right-image">
                                        <div id="visit-first-time-modal-right-image-each">
                                            <?php
                                            $banner_info = $this->m_admin->banner_select_one(112);
                                            $banner_image_url = $banner_info['banner_image_url'];
                                            $banner_website_url = $banner_info['banner_website_url'];
                                            echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>";
                                            ?>
                                            <?php //echo img('folder_upload/first_time_visit_modal/1.jpg') ?>
                                        </div>
                                        <div id="visit-first-time-modal-right-image-each">
                                            <?php
                                            $banner_info = $this->m_admin->banner_select_one(113);
                                            $banner_image_url = $banner_info['banner_image_url'];
                                            $banner_website_url = $banner_info['banner_website_url'];
                                            echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>";
                                            ?>
                                            <?php //echo img('folder_upload/first_time_visit_modal/2.jpg') ?>
                                        </div>
                                        <div id="visit-first-time-modal-right-image-each">
                                            <?php
                                            $banner_info = $this->m_admin->banner_select_one(114);
                                            $banner_image_url = $banner_info['banner_image_url'];
                                            $banner_website_url = $banner_info['banner_website_url'];
                                            echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>";
                                            ?>
                                            <?php //echo img('folder_upload/first_time_visit_modal/3.jpg') ?>
                                        </div>
                                        <div id="visit-first-time-modal-right-image-each">
                                            <?php
                                            $banner_info = $this->m_admin->banner_select_one(115);
                                            $banner_image_url = $banner_info['banner_image_url'];
                                            $banner_website_url = $banner_info['banner_website_url'];
                                            echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>";
                                            ?>
                                            <?php //echo img('folder_upload/first_time_visit_modal/4.jpg') ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="float-fix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    
    <?php 

            if ($this->agent->is_mobile() && $this->agent->is_tablet() === FALSE)
            { 
                    if (!isset($_COOKIE['visit_first_time_mobile']))
                    {
                         
            ?>
            <ul id="pop-up-slide" class="row first" style="display:none">
                    <li>
                        <img src="<?php echo base_url('image/help_guide/mobile/mobile_slide_1.PNG') ?>" id="pop-up-slide-1">
                    </li>
                    <li>
                        <img src="<?php echo base_url('image/help_guide/mobile/mobile_slide_2.PNG') ?>">
                    </li>
                    <li>
                        <img src="<?php echo base_url('image/help_guide/mobile/mobile_slide_3.PNG') ?>">
                    </li>
                    <li>
                        <img src="<?php echo base_url('image/help_guide/mobile/mobile_slide_4.PNG') ?>">
                    </li>
                    <li>
                        <img src="<?php echo base_url('image/help_guide/mobile/mobile_slide_5.PNG') ?>">
                    </li>
                    <li>
                        <img src="<?php echo base_url('image/help_guide/mobile/mobile_slide_6.PNG') ?>">
                    </li>
            </ul>
            <?php }          
                    } 
                      setcookie('visit_first_time_mobile', 'no');
                    ?>
        <!--HEADER-->
        <div id='header'>
            <?php
            //CONFIG DATA
            $header_album_user_profile_path = $this->config->item('album_user_profile');
            $header_album_merchant_profile_path = $this->config->item('album_merchant_profile');
            $header_empty_image = $this->config->item('empty_image');
            $header_voucher_active = $this->config->item('voucher_active');       
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
                    $header_profile_login_us_gender_id = $header_row_read_user->us_gender_id;
                    $header_profile_login_user_name = $header_row_read_user->first_name . ' ' . $header_row_read_user->last_name;
                }
                else if($this->m_custom->check_is_any_merchant())
                {
                    //PROFILE DSIPLAY MERCHANT
                    $header_where = array('id'=>$login_user_id);
                    $header_query_read_merchant_superviosr_as_merchant = $this->albert_model->read_merchant_supervisor_as_merchant($header_where);
                    $header_row_read_merchant_superviosr_as_merchant = $header_query_read_merchant_superviosr_as_merchant->row();
                    $header_profile_login_slug = $header_row_read_merchant_superviosr_as_merchant->slug;
                    $header_profile_login_profile_image = $header_row_read_merchant_superviosr_as_merchant->profile_image;
                    $header_profile_login_company_name =  $header_row_read_merchant_superviosr_as_merchant->company;
                    $the_merchant_id = $header_row_read_merchant_superviosr_as_merchant->id;
                }
                else if($this->m_admin->check_is_any_admin())
                {
                    $header_profile_login_user_name = "";
                    $header_album_user_profile_path = $this->config->item('album_admin_profile');
                    $header_profile_login_profile_image = $this->m_custom->get_one_field_by_key('users', 'id', $login_user_id, 'profile_image');
                    $header_profile_login_user_name = $this->m_custom->display_users($login_user_id);
                    $header_profile_login_company_name = "";
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
                            <a href='https://www.facebook.com/keppo.my/' target='_blank'><i class="fa fa-facebook"></i></a>
                        </div>
                        <div id='header-top-bar-social-media-each'>
                            <a href='https://twitter.com/keppom' target='_blank'><i class="fa fa-twitter"></i></a>
                        </div>
                        <div id='header-top-bar-social-media-each'>
                            <a href='https://plus.google.com' target='_blank'><i class="fa fa-google-plus"></i></a>
                        </div>
                        <div id='header-top-bar-social-media-each'>
                            <a href='https://www.instagram.com/keppomy/' target='_blank'><i class="fa fa-instagram"></i></a>
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
                                <li>
                                    <a href='<?php echo base_url('user/login') ?>'>
                                        <i class="fa fa-user header-top-bar-navigation-icon"></i> Login
                                    </a>
                                </li>
                                <li>
                                    <a href='<?php echo base_url('user/register') ?>'>
                                        <i class="fa fa-user-plus header-top-bar-navigation-icon"></i> Register
                                    </a>
                                </li>
                                <?php
                            }
                            else
                            {                     
                                if (check_correct_login_type($this->config->item('group_id_user')))
                                {
                                    $follower_count = $this->albert_model->follower_count($login_user_id);
                                    $notification_count = $this->m_custom->notification_count($login_user_id);
                                    ?>
                                    <li>
                                        <a>My Account</a>                                      
                                        <ul>
                                            <li><a href='<?php echo base_url("all/user_dashboard/$login_user_id") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'user_dashboard'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard</a></li>
                                            <li><a href='<?php echo base_url('user/profile') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'profile'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
                                            <li><a href='<?php echo base_url('user/change_password') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'change_password'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Change Password</a></li>
                                            <li><a href='<?php echo base_url('all/notification') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'notification'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Notification (<?php echo $notification_count; ?> new)</a></li>
                                            <li><a href='<?php echo base_url("user/follower/user/$login_user_id") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'follower' || $fetch_method == 'following'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Follower (<?php echo $follower_count ?>)</a></li>
                                            <li><a href='<?php echo base_url('user/review_merchant') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'review_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Reviews</a></li>
                                            <li><a href='<?php echo base_url("user/main_album/$login_user_id") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_user' || $fetch_method == 'upload_image' || $fetch_method == 'upload_for_merchant' || $fetch_method == 'album_user_merchant' || $fetch_method == 'user_picture' || $fetch_method == 'merchant_user_picture' || $fetch_method == 'main_album'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Album</a></li>
                                            <li><a href='<?php echo base_url('user/candie_page') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'candie_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candies (<?php echo $this->m_user->candie_check_balance($login_user_id); ?>) <span class="layout-inner-right-menu-bar-click">Click</span></a></li>
                                            <li><a href='<?php echo base_url("user/redemption/$header_voucher_active") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'redemption'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Vouchers <span class="layout-inner-right-menu-bar-click">Click</span></a></li>
                                            <!--<li><a href='<?php //echo base_url('user/invite_friend') ?>' class='layout-inner-right-menu-bar <?php //if ($fetch_method == 'invite_friend'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Invite Friend</a></li>-->
                                            <li><a href='<?php echo base_url('user/promo_code') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'promo_code'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Promo Code <span class="layout-inner-right-menu-bar-click">Click</span></a></li>
                                            <li><a href='<?php echo base_url('user/balance_page') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'balance_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Keppo Wallet (<?php echo 'RM '.$this->m_user->user_check_balance($login_user_id); ?>)</a></li>
                                            <li><a href='<?php echo base_url('user/add_merchant') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'add_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Add Shop</a></li>
                                            <li><a href='<?php echo base_url('user/logout') ?>'>Logout</a>
                                        </ul>
                                    </li>
                                    <li style="display:none">
                                         <a href='<?php echo base_url('user/logout') ?>'  onclick="fbLogout()">
                                             <i class='fa fa-sign-out header-top-bar-navigation-icon'></i> Logout
                                         </a>
                                    </li>
                                    <?php
                                }
                                else if (check_correct_login_type($this->config->item('group_id_merchant')))
                                {
                                    $header_merchant_slug = generate_slug($this->session->userdata('company_name'));
                                    $merchant_balance_text = $this->m_merchant->merchant_balance_color($login_user_id);
                                    $follower_count = $this->albert_model->follower_count($login_user_id);
                                    $notification_count = $this->m_custom->notification_count($login_user_id) + $this->m_custom->display_row_monitor(1) + $this->m_custom->getAdvertise_expired($login_user_id, 1);
                                    ?>
                                    <li>
                                        <a>My Account</a>
                                        <ul>
                                            <li><a href='<?php echo base_url("all/merchant_dashboard/$header_merchant_slug//$login_user_id") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_dashboard' || $fetch_method == 'merchant_outlet' || $fetch_method == 'map'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard</a></li>
                                            <li><a href='<?php echo base_url('merchant/profile') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'profile' || $fetch_method == 'upload_ssm' || $fetch_method == 'branch' || $fetch_method == 'supervisor'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
                                            <li><a href='<?php echo base_url('merchant/change_password') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'change_password'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Change Password</a></li>
                                            <li><a href='<?php echo base_url('all/notification') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'notification'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Notification (<?php echo $notification_count; ?> new)</a></li>
                                            <li><a href='<?php echo base_url("merchant/follower/user/$login_user_id") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'follower' || $fetch_method == 'following'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Follower (<?php echo $follower_count ?>)</a></li>
                                            <li><a href='<?php echo base_url('merchant/upload_hotdeal') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'upload_hotdeal' || $fetch_method == 'edit_hotdeal'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Create Food & Beverage <span class="layout-inner-right-menu-bar-click">Click</span></a></li>
                                            <li><a href='<?php echo base_url("all/album_merchant/$header_merchant_slug") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Food & Beverage's Album</a></li>
                                            <li><a href='<?php echo base_url('merchant/candie_promotion') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'candie_promotion'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Create Candie Voucher <span class="layout-inner-right-menu-bar-click">Click</span></a></li>
                                            <li><a href='<?php echo base_url("all/album_redemption/$header_merchant_slug") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_redemption'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candie Voucher's Album</a></li>
                                            <li><a href='<?php echo base_url('merchant/merchant_redemption_page') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_redemption_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>User's Redemption</a></li>
                                            <li><a href='<?php echo base_url('merchant/analysis_report') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'analysis_report'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Insights <span class="layout-inner-right-menu-bar-click">Click</span></a></li>
                                            <li><a href='<?php echo base_url('merchant/promo_code') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'promo_code'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Promo Code</a></li>
                                            <li style="display:none"><a href='<?php echo base_url('merchant/payment_page') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'payment_page' || $fetch_method == 'payment_charge_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Payment <?php echo $merchant_balance_text; ?></a></li>
                                            <li><a href='<?php echo base_url('merchant/logout') ?>'>Logout</a>
                                        </ul>
                                    </li>
                                    <li style="display:none">
                                        <a href='<?php echo base_url('merchant/logout') ?>'>
                                            <i class='fa fa-sign-out header-top-bar-navigation-icon'></i> Logout
                                        </a>
                                    </li>
                                    <?php
                                }
                                else if (check_correct_login_type($this->config->item('group_id_supervisor')))
                                {
                                    $the_row = $this->m_custom->get_parent_table_record('users', 'id', $login_user_id, 'su_merchant_id', 'users', 'id');
                                    $header_merchant_slug = generate_slug($the_row->company);
                                    $where_read_user = array('id'=>$login_user_id);
                                    $login_main_merchant_id = $this->albert_model->read_user($where_read_user)->row()->su_merchant_id;
                                    $follower_count = $this->albert_model->follower_count($login_main_merchant_id);
                                    $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
                                    $notification_count = $this->m_custom->notification_count($merchant_id) + $this->m_custom->getAdvertise_expired($merchant_id, 1); 
                                    ?>
                                    <li>
                                        <a>My Account</a>
                                        <ul>
                                            <li><a href='<?php echo base_url("all/merchant_dashboard/$header_merchant_slug//$login_main_merchant_id") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_dashboard' || $fetch_method == 'merchant_outlet' || $fetch_method == 'map'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard</a></li>
                                            <li><a href='<?php echo base_url('merchant/profile') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'profile'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
                                            <li><a href='<?php echo base_url('all/notification') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'notification'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Notification (<?php echo $notification_count; ?> new)</a></li>
                                            <li><a href='<?php echo base_url("merchant/follower/user/$login_user_id") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'follower' || $fetch_method == 'following'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Follower (<?php echo $follower_count ?>)</a></li>
                                            <li><a href='<?php echo base_url('merchant/upload_hotdeal') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'upload_hotdeal' || $fetch_method == 'edit_hotdeal'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Create Food & Beverage <span class="layout-inner-right-menu-bar-click">Click</span></a></li>
                                            <li><a href='<?php echo base_url("all/album_merchant/$header_merchant_slug") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Food & Beverage's Album</a></li>
                                            <li><a href='<?php echo base_url('merchant/candie_promotion') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'candie_promotion'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Create Candie Voucher <span class="layout-inner-right-menu-bar-click">Click</span></a></li>
                                            <li><a href='<?php echo base_url("all/album_redemption/$header_merchant_slug") ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'album_redemption'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Candie Voucher's Album</a></li>
                                            <li><a href='<?php echo base_url('merchant/merchant_redemption_page') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_redemption_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>User's Redemption</a></li>
                                            <li><a href='<?php echo base_url('merchant/logout') ?>'>Logout</a>
                                        </ul>
                                    </li>
                                    <li style="display:none">
                                        <a href='<?php echo base_url('merchant/logout') ?>'>
                                            <i class='fa fa-sign-out header-top-bar-navigation-icon'></i> Logout
                                        </a>
                                    </li>
                                    <?php
                                }
                                else if (check_correct_login_type($this->config->item('group_id_admin')))
                                {
                                    $redeem_count = $this->m_custom->getPromotionAdminRedeemCount($this->config->item('category_epay'));
                                    ?>
                                    <li>
                                        <a>My Account</a>
                                        <ul>
                                            <li><a href='<?php echo base_url('admin/admin_dashboard') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'admin_dashboard' || $fetch_method == 'monitor_remove' || $fetch_method == 'user_withdraw'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard Notification</a></li>
                                            <li><a href='<?php echo base_url('admin/profile') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'profile'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
                                            <li><a href='<?php echo base_url('admin/change_password') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'change_password'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Change Password</a></li>
                                            <li><a href='<?php echo base_url('admin/user_management') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'user_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage User</a></li>
                                            <li><a href='<?php echo base_url('admin/merchant_management') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Merchant</a></li>
                                            <li><a href='<?php echo base_url('admin/worker_management') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'worker_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Worker</a></li>
                                            <li><a href='<?php echo base_url('admin/category_management') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'category_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Category</a></li>
                                            <li><a href='<?php echo base_url('admin/banner_management') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'banner_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Banner</a></li>
                                            <li><a href='<?php echo base_url('admin/promo_code_management') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'promo_code_management' || $fetch_method == 'promo_code_management_user' || $fetch_method == 'promo_code_management_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Promo Codes</a></li>
                                            <li><a href='<?php echo base_url('admin/keppo_voucher_management') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'keppo_voucher_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Keppo Voucher</a></li>
                                            <li><a href='<?php echo base_url('admin/keppo_voucher_redemption_page') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'keppo_voucher_redemption_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>User's Redeem Keppo Voucher (<?php echo $redeem_count ?>)</a></li>
                                            <li><a href='<?php echo base_url('admin/analysis_report') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'analysis_report' || $fetch_method == 'analysis_report_user'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Insights</a></li>
                                            <li><a href='<?php echo base_url('admin/manage_web_setting') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'manage_web_setting' || $fetch_method == 'manage_candie_term' || $fetch_method == 'manage_photography' || $fetch_method == 'manage_trans_config' || $fetch_method == 'manage_merchant_fee'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Web Setting</a></li>
                                            <li><a href='<?php echo base_url('admin/logout') ?>'>Logout</a></li>
                                        </ul>
                                    </li>
                                    <li style="display:none">
                                        <a href='<?php echo base_url('admin/logout') ?>'>
                                            <i class='fa fa-sign-out header-top-bar-navigation-icon'></i> Logout
                                        </a>
                                    </li>
                                    <?php
                                }
                                else if (check_correct_login_type($this->config->item('group_id_worker')))
                                {
                                    $redeem_count = $this->m_custom->getPromotionAdminRedeemCount($this->config->item('category_epay'));
                                    ?>
                                    <li>
                                        <a>My Account worker</a>
                                        <ul>
                                            <?php 
                                            if($this->m_admin->check_worker_role(68) && !$this->m_admin->check_worker_role(79)) 
                                            {
                                                ?>
                                            <li><a href='<?php echo base_url('all/monitor-remove') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'monitor_remove' || $fetch_method == 'user_withdraw'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard Notification</a></li>
                                            <?php }elseif($this->m_admin->check_worker_role(79)){ ?>
                                            <li><a href='<?php echo base_url('admin/admin_dashboard') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'admin_dashboard' || $fetch_method == 'monitor_remove' || $fetch_method == 'user_withdraw'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Dashboard Notification</a></li>
                                            <?php } ?>
                                            <li><a href='<?php echo base_url('admin/profile') ?>' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'profile'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Profile</a></li>
                                            <?php 
                                            if($this->m_admin->check_worker_role(86)) 
                                            {
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/user_management' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'user_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage User</a></li>
                                                <?php
                                            }
                                            if($this->m_admin->check_worker_role(65)) 
                                            {
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/merchant_management' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'merchant_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Merchant</a></li>
                                                <?php
                                            }
                                            if($this->m_admin->check_worker_role(66)) 
                                            {
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/worker_management' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'worker_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Worker</a></li>
                                                <?php
                                            }
                                            if($this->m_admin->check_worker_role(72))
                                            { 
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/category_management' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'category_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Category</a></li>
                                                <?php
                                            }
                                            if($this->m_admin->check_worker_role(69))
                                            { 
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/banner_management' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'banner_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Banner</a></li>
                                                <?php
                                            }
                                            if($this->m_admin->check_worker_role(77))
                                            { 
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/promo_code_management' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'promo_code_management' || $fetch_method == 'promo_code_management_user' || $fetch_method == 'promo_code_management_merchant'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Promo Code</a></li>
                                                <?php
                                            }
                                            if($this->m_admin->check_worker_role(70))
                                            { 
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/keppo_voucher_management' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'keppo_voucher_management'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Manage Keppo Voucher</a></li>
                                                <?php
                                            }
                                            if($this->m_admin->check_worker_role(71))
                                            { 
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/keppo_voucher_redemption_page' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'keppo_voucher_redemption_page'){ echo "layout-inner-right-menu-bar-active"; } ?>'>User's Redeem Keppo Voucher (<?php echo $redeem_count ?>)</a></li>
                                                <?php
                                            }
                                            if($this->m_admin->check_worker_role(63))
                                            { 
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/analysis_report' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'analysis_report' || $fetch_method == 'analysis_report_user'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Insights</a></li>
                                                <?php
                                            }
                                            if($this->m_admin->check_worker_role(73))
                                            { 
                                                ?>
                                                <li><a href='<?php echo base_url(); ?>admin/manage_web_setting' class='layout-inner-right-menu-bar <?php if ($fetch_method == 'manage_web_setting' || $fetch_method == 'manage_candie_term' || $fetch_method == 'manage_photography' || $fetch_method == 'manage_trans_config' || $fetch_method == 'manage_merchant_fee'){ echo "layout-inner-right-menu-bar-active"; } ?>'>Web Setting</a></li>
                                                <?php
                                            }                                          
                                            ?>
                                                <li><a href='<?php echo base_url('admin/logout') ?>'>Logout</a></li>
                                        </ul>
                                    </li>
                                    <li style="display:none">
                                        <a href='<?php echo base_url('admin/logout') ?>'>
                                            <i class='fa fa-sign-out header-top-bar-navigation-icon'></i> Logout
                                        </a>
                                    </li>
                                    <?php
                                }
                                else
                                {

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
                    <div id='header-logo-bar-table'>
                        <div id='header-logo-bar-table-row'>
                            <div id='header-logo-bar-table-row-cell' class="header-logo-bar-table-row-cell-mobile-logo">
                                <div id='header-logo-bar-logo'>
                                    <a href='<?php echo base_url('home') ?>'>
                                        <img src='<?php echo base_url('image/logo-red.png') ?>'>
                                    </a>
                                </div>
                            </div>
                            <div id='header-logo-bar-table-row-cell' class="header-logo-bar-table-row-cell-mobile-search">
                                <div id="header-logo-bar-search">
                                    <div id="header-logo-bar-search-content">
                                        <?php $this->load->view('home_search_box') ?>
                                    </div>
                                </div>
                            </div>
                            <div id='header-logo-bar-table-row-cell' class="header-logo-bar-table-row-cell-mobile-navigation">
                                <div id="header-logo-bar-mobile-navigation-icon">
                                    <i class="fa fa-bars"></i>
                                </div>
                            </div>
                            <?php
                            if (check_is_login())
                            {
                                ?>
                                <div id='header-logo-bar-table-row-cell' class="header-logo-bar-table-row-cell-profile-display">
                                    <div id="header-logo-bar-profile-display">
                                        <?php
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
                                                            if($header_profile_login_us_gender_id == '13')
                                                            {
                                                                ?><img src="<?php echo base_url('image/default-image-user-gender-male.png') ?>" id="userimage"><?php
                                                            }
                                                            if($header_profile_login_us_gender_id == '14')
                                                            {
                                                                ?><img src="<?php echo base_url('image/default-image-user-gender-female.png') ?>" id="userimage"><?php
                                                            }
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
                                        else if ($this->m_admin->check_is_any_admin()) 
                                        {
                                            ?>
                                            <a href='<?php echo base_url("admin/admin_dashboard/$login_user_id") ?>'>
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
                                            <a href='<?php echo base_url("all/merchant_dashboard/$header_profile_login_slug//$the_merchant_id") ?>'>
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
                                        ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div id="header-logo-bar-search2">
                        <div id="header-logo-bar-search2-content">
                            <?php $this->load->view('home_search_box') ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--HEADER NAVIGATION-->
            <div id='wrapper'>
                <div id='header-navigation-bar'>
                    <div id='header-navigation-bar-left'>
                        <ul>
                            <li <?php if($header_fetch_class == 'home'){ echo "class='header-navigation-bar-active'"; } ?>>
                                <a href='<?php echo base_url('home') ?>'>
                                    <i class="fa fa-home header-navigation-bar-left-icon"></i> Home
                                </a>
                            </li>
                            <li <?php if($header_fetch_class == 'categories'){ echo "class='header-navigation-bar-active'"; } ?>>
                                <a href="#">
                                    <i class="fa fa-th-large header-navigation-bar-left-icon"></i> Categories
                                </a>
                                <ul>
                                    <li>
                                        <div id="header-navigation-bar-box">
                                            <?php
                                            $main_category_object = $this->m_custom->getCategory();
                                            foreach ($main_category_object as $main_category) 
                                            {
                                                $main_category_id = $main_category->category_id;
                                                $main_category_label = $main_category->category_label;
                                                ?>
                                                <div id="header-navigation-bar-box-each">
                                                    <div id="header-navigation-bar-box-each-title"><?php echo $main_category_label ?></div>
                                                    <div id="header-navigation-bar-box-each-merchant">
                                                        <?php
                                                        $sub_category_object = $this->m_custom->getSubCategory($main_category_id); 
                                                        foreach ($sub_category_object as $sub_category)
                                                        {
                                                            $sub_category_id = $sub_category->category_id;
                                                            $sub_category_label = $sub_category->category_label;
                                                            ?>
                                                            <div id="header-navigation-bar-box-each-merchant-each">
                                                                <a href="<?php echo base_url() ?>all/merchant-category/<?php echo $sub_category_id ?>">
                                                                    <span id="header-navigation-bar-box-each-merchant-each-icon"><i class="fa fa-caret-right"></i></span>
                                                                    <span id="header-navigation-bar-box-each-merchant-each-label">
                                                                        <?php echo $sub_category_label ?>
                                                                        (<?php echo $this->m_merchant->getMerchantCount_by_subcategory($sub_category_id); ?>)
                                                                    </span>
                                                                </a> 
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li <?php if($header_fetch_method == 'hotdeal_list' || $header_uri_segment4 == 'hot'){ echo "class='header-navigation-bar-active'"; } ?>>
                                <a href='<?php echo base_url('all/hotdeal-list/1') ?>'>
                                    <i class="fa fa-fire header-navigation-bar-left-icon"></i> Food & Beverage
                                </a>
                            </li>
                            <li <?php if($header_fetch_method == 'promotion_list' || $header_uri_segment4 == 'pro'){ echo "class='header-navigation-bar-active'"; } ?>>
                                <a href="<?php echo base_url('all/promotion-list/1') ?>">
                                    <i class="fa fa-gift header-navigation-bar-left-icon"></i> Redemption
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id='header-navigation-bar-right'>
                        <ul>
                            <li <?php if($header_fetch_class == 'blogger'){ echo "class='header-navigation-bar-active'"; } ?>>
                                <a href='<?php echo base_url('blogger') ?>'>
                                    <i class="fa fa-pencil header-navigation-bar-left-icon"></i> Blogger
                                </a>
                            </li>
                            <li <?php if($header_fetch_class == 'photographer'){ echo "class='header-navigation-bar-active'"; } ?>>
                                <a href='<?php echo base_url('photographer') ?>'>
                                    <i class="fa fa-camera header-navigation-bar-left-icon"></i> Photographer
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div id="float-fix"></div>
                </div>
            </div>            
            <!--MOBILE NAVIGATION-->
            <div id="header-mobile-navigation">
                <div id="header-mobile-navigation-close-icon">x</div>
                <div id="header-mobile-navigation-content">
                    <ul>
                        <li>
                            <a href='<?php echo base_url('home') ?>'>Home</a>
                        </li>
                        <li>
                            <a href='#'>Categories</a>
                            <ul>
                                <li>
                                    <div id="header-mobile-navigation-bar-box">
                                        <?php
                                        $main_category_object = $this->m_custom->getCategory();
                                        foreach ($main_category_object as $main_category) 
                                        {
                                            $main_category_id = $main_category->category_id;
                                            $main_category_label = $main_category->category_label;
                                            ?>
                                            <div id="header-mobile-navigation-bar-box-each">
                                                <div id="header-mobile-navigation-bar-box-each-title"
                                                <?php if(strtolower($main_category_label) == 'food & beverage'){ echo 'class="header-mobile-navigation-food-n-beverage"'; } ?>
                                                <?php if(strtolower($main_category_label) == 'others'){ echo 'class="header-mobile-navigation-others"'; } ?>
                                                >
                                                    <?php echo $main_category_label ?> 
                                                    <span style="font-size: 17px; margin-left: 3px;">+</span>
                                                </div>
                                                <div class="header-mobile-navigation-bar-box-each-merchant">
                                                    <?php
                                                    $sub_category_object = $this->m_custom->getSubCategory($main_category_id); 
                                                    foreach ($sub_category_object as $sub_category)
                                                    {
                                                        $sub_category_id = $sub_category->category_id;
                                                        $sub_category_label = $sub_category->category_label;
                                                        ?>
                                                        <div id="header-mobile-navigation-bar-box-each-merchant-each">
                                                            <a href="<?php echo base_url() ?>all/merchant-category/<?php echo $sub_category_id ?>">
                                                                <span id="header-mobile-navigation-bar-box-each-merchant-each-icon"><i class="fa fa-caret-right"></i></span>
                                                                <span id="header-mobile-navigation-bar-box-each-merchant-each-label">
                                                                    <?php echo $sub_category_label ?>
                                                                    (<?php echo $this->m_merchant->getMerchantCount_by_subcategory($sub_category_id); ?>)
                                                                </span>
                                                            </a> 
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href='<?php echo base_url('all/hotdeal-list/1') ?>'>Food & Beverage</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('all/promotion-list/1') ?>">Redemption</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('blogger') ?>">Blogger</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('photographer') ?>">Photographer</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="header-mobile-navigation-block"></div>
        </div>
        <!--MOBILE SCALE NONE-->
        <script>
             var meta = document.createElement("meta");
             meta.setAttribute('name','viewport');
             meta.setAttribute('content','width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no');
             document.getElementsByTagName('head')[0].appendChild(meta);
        </script>
