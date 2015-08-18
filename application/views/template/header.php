<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Keppo</title>
        <?php echo link_tag('css/main.css') ?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    </head>
    <body>
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
                        <li><a href='#'><i class="fa fa-user-plus header-menu-icon"></i>Register</a></li>
                    </ul>
                </div>
                <div id="float-fix"></div>
            </div>
        </div>