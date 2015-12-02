<!--CAMERA SLIDER-->
<link rel='stylesheet' id='camera-css'  href='<?php echo base_url('js/banner-row1-slider/camera.css') ?>' type='text/css' media='all'> 
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/jquery.mobile.customized.min.js') ?>'></script>
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/jquery.easing.1.3.js') ?>'></script> 
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/camera.min.js') ?>'></script> 

<!--SLICK SLIDER-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('js/slick-slider/slick.css') ?>"/>
<!--<link rel="stylesheet" type="text/css" href="js/slick-slider/slick-theme.css"/>-->

<!--RATING-->
<!--<script type="text/javascript" src="<?php echo base_url('js/star-rating/jquery.rating.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('js/star-rating/jquery.rating.css') ?>">-->

<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<!--<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>-->
<?php // echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<!--COUNTDOWN-->
<script type="text/javascript" src="<?php echo base_url('js/jquery.countdown.js') ?>"></script>

<script>
    $(function() {
        $('#camera_wrap_1').camera({
            skins: 'camera_violet_skin',
            loader: 'none',
            navigation: true,
            navigationHover: false,
            playPause: false,
            thumbnails: false,
            fx: 'scrollLeft'
        });
        
        $('#home-row3-column1-today-deal-box').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            prevArrow: '#home-row3-column1-today-deal-prev',
            nextArrow: '#home-row3-column1-today-deal-next',
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
        
        $('.home-row3-column1-today-deal-box-each-timer-box-relative').each(function(){
            var _this = $(this);
            var end_date = $(this).attr('end_date');
            _this.countdown(end_date, function(event) {
                //$(this).html(event.strftime('%D days %H:%M:%S'));
                $(this).children().find('.home-row3-column1-today-deal-box-each-timer-box1-time-day').html(event.strftime('%D'));
                $(this).children().find('.home-row3-column1-today-deal-box-each-timer-box2-time-hour').html(event.strftime('%H'));
                $(this).children().find('.home-row3-column1-today-deal-box-each-timer-box3-time-minute').html(event.strftime('%M'));
                $(this).children().find('.home-row3-column1-today-deal-box-each-timer-box4-time-second').html(event.strftime('%S'));
            });
        });
        
        $('#home-row3-column1-redemption-box').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            prevArrow: '#home-row3-column1-redemption-prev',
            nextArrow: '#home-row3-column1-redemption-next',
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
        
        $('.home-row3-column1-redemption-box-each-timer-box-relative').each(function(){
            var _this = $(this);
            var end_date = $(this).attr('end_date');
            _this.countdown(end_date, function(event) {
                //$(this).html(event.strftime('%D days %H:%M:%S'));
                $(this).children().find('.home-row3-column1-redemption-box-each-timer-box1-time-day').html(event.strftime('%D'));
                $(this).children().find('.home-row3-column1-redemption-box-each-timer-box2-time-hour').html(event.strftime('%H'));
                $(this).children().find('.home-row3-column1-redemption-box-each-timer-box3-time-minute').html(event.strftime('%M'));
                $(this).children().find('.home-row3-column1-redemption-box-each-timer-box4-time-second').html(event.strftime('%S'));
            });
        });
        
        $('#home-row4-logo-slider-box').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            prevArrow: '#home-row4-logo-slider-prev',
            nextArrow: '#home-row4-logo-slider-next',
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 1000,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 700,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>

<?php
//CONFIG DATA
$this->group_id_user = $this->config->item('group_id_user');
$this->album_merchant = $this->config->item('album_merchant');
$this->album_admin = $this->config->item('album_admin');
?>

<div id='wrapper'>
    <div id='home'>
        <div id='home-row1'>
            <div id='home-row1-categories-navigation'>
                <div id="home-row1-categories-navigation-main-category-title">
                    <span id="home-row1-categories-navigation-main-category-title-icon"><i class="fa fa-bars"></i></span>
                    <span id="home-row1-categories-navigation-main-category-title-label">Categories</span>
                </div>
                <ul>
                    <?php
                    $result_array_main_category = $query_main_category->result_array();
                    foreach($result_array_main_category as $main_category)
                    {
                        $main_category_id = $main_category['category_id'];
                        $main_category_label = $main_category['category_label'];
                        $where_sub_category = array('main_category_id' => $main_category_id);
                        $query_sub_category = $this->albert_model->read_sub_category_with_merchant($where_sub_category);
                        $num_rows_sub_category = $query_sub_category->num_rows();
                        ?>
                        <li <?php if($num_rows_sub_category != 0){ echo "class='has-sub'"; } ?>>
                            <a href="#">
                                <span id="home-row1-categories-navigation-main-category-icon">
                                    <i class="fa fa-crosshairs"></i>
                                </span>
                                <span id="home-row1-categories-navigation-main-category-label">
                                    <?php echo $main_category_label ?>
                                </span>
                            </a>
                            <?php
                            if ($num_rows_sub_category) 
                            {
                                $result_array_sub_category = $query_sub_category->result_array();
                                ?>
                                <ul>
                                    <li>
                                        <?php
                                        foreach($result_array_sub_category as $sub_category)
                                        {
                                            $sub_category_id = $sub_category['category_id'];
                                            $sub_category_label = $sub_category['category_label'];
                                            ?>
                                            <div id="home-row1-categories-navigation-box">
                                                <div id="home-row1-categories-navigation-box-title">
                                                    <?php echo $sub_category_label ?>
                                                </div>
                                                <?php
                                                $where_user = array('me_sub_category_id' => $sub_category_id);
                                                $query_user = $this->albert_model->read_user($where_user);
                                                $result_array_user = $query_user->result_array();
                                                ?>
                                                <div id="home-row1-categories-navigation-box-merchant">
                                                    <?php
                                                    foreach($result_array_user as $user)
                                                    {
                                                        $merchant_company = $user['company'];
                                                        $merchant_slug = $user['slug'];
                                                        ?>
                                                        <div id='home-row1-categories-navigation-box-merchant-each'>
                                                            <a href='<?php echo base_url() ?>all/merchant_dashboard/<?php echo $merchant_slug ?>'>
                                                                <?php echo $merchant_company ?>
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
                                    </li>
                                </ul>
                                <?php
                            }
                            ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <?php 
                for($i = 101; $i < 104; $i++){
                    ${'slider_info' . $i} = $this->m_admin->banner_select_one($i);
                    ${'slider_image_url' . $i} = ${'slider_info' . $i}['banner_image_url'];
                    ${'slider_website_ur' . $i} = ${'slider_info' . $i}['banner_website_url'];
                }
                ?>
            <div id='home-row1-banner-main'>
                <div class='fluid_container' style="width: 100.1%;">
                    <div class="camera_wrap camera_azure_skin" id="camera_wrap_1">
                        <?php 
                        for($i = 101; $i < 104; $i++){
                        echo "<div data-src=" . ${'slider_image_url' . $i} . " data-link=" . ${'slider_website_ur' . $i} . " data-target='_blank' ></div>";
                        }
                        ?>
<!--                        <div data-src="<?php //echo base_url('folder_upload/home_banner_row1/slide1.jpg') ?>"></div>
                        <div data-src="<?php //echo base_url('folder_upload/home_banner_row1/slide2.jpg') ?>"></div>
                        <div data-src="<?php //echo base_url('folder_upload/home_banner_row1/slide3.jpg') ?>"></div>-->
                    </div>
                </div>
            </div>
            <div id='float-fix'></div>
        </div>
        <div id='home-row2'>
            <div id='home-row2-block1'>
                <?php 
                $banner_info = $this->m_admin->banner_select_one(105);
                $banner_image_url = $banner_info['banner_image_url'];
                $banner_website_url = $banner_info['banner_website_url'];
                echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>";
                ?>
            </div>
            <div id='home-row2-block2'>
                <?php 
                $banner_info = $this->m_admin->banner_select_one(106);
                $banner_image_url = $banner_info['banner_image_url'];
                $banner_website_url = $banner_info['banner_website_url'];
                echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                ?>
            </div>
            <div id='home-row2-block3'>
                <?php 
                $banner_info = $this->m_admin->banner_select_one(107);
                $banner_image_url = $banner_info['banner_image_url'];
                $banner_website_url = $banner_info['banner_website_url'];
                echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                ?>
            </div>
            <div id='float-fix'></div>
        </div>        
        <div id='home-row3-column1'>
            <div id='home-row3-column1-today-deal'>
                <div id='home-row3-column1-today-deal-title'>Today's Deals</div>
                <div id='home-row3-column1-today-deal-navigation'>
                    <div id='home-row3-column1-today-deal-prev'> < </div>
                    <div id='home-row3-column1-today-deal-prev-next-separator'></div>
                    <div id='home-row3-column1-today-deal-next'> > </div>
                </div>
                <div id='float-fix'></div>
                <div id='home-row3-column1-today-deal-title-bottom-line'></div>
                <div id='home-row3-column1-today-deal-box'>
                    <?php
                    $hotdeal_list = $this->m_custom->getAdvertise('hot', NULL, NULL, 0, NULL, NULL, 1);
                    foreach ($hotdeal_list as $hotdeal)
                    {
                        $advertise_type = $hotdeal['advertise_type'];
                        $advertise_id = $hotdeal['advertise_id'];
                        $sub_category_id = $hotdeal['sub_category_id'];
                        $merchant_id = $hotdeal['merchant_id'];
                        $image = $hotdeal['image'];
                        $title = $hotdeal['title'];
                        $price_before_show = $hotdeal['price_before_show'];
                        $price_before = $hotdeal['price_before'];
                        $price_after_show = $hotdeal['price_after_show'];
                        $price_after = $hotdeal['price_after'];
                        $end_time = $hotdeal['end_time']; 
                        $post_hour = $hotdeal['post_hour'];
                        if ($advertise_type == 'adm')
                        {
                            $image_url = $this->album_admim . $image;
                        }
                        else 
                        {
                            $image_url = $this->album_merchant . $image;
                        }
                        ?>
                        <div class="home-row3-column1-today-deal-box-each">
                            <a href='<?php echo base_url("all/advertise/$advertise_id") ?>'>
                                <?php
                                if ($post_hour != 0)
                                { 
                                    ?>
                                    <div class="home-row3-column1-today-deal-box-each-timer-box-absolute">
                                        <div class="home-row3-column1-today-deal-box-each-timer-box-relative" end_date="<?php echo $end_time ?>">
                                            <div class="home-row3-column1-today-deal-box-each-timer-box1">
                                                <div class="home-row3-column1-today-deal-box-each-timer-box1-time-day">1</div>
                                                <div class="home-row3-column1-today-deal-box-each-timer-box1-label">Days</div>
                                            </div>
                                            <div class="home-row3-column1-today-deal-box-each-timer-box2">
                                                <div class="home-row3-column1-today-deal-box-each-timer-box2-time-hour">2</div>
                                                <div class="home-row3-column1-today-deal-box-each-timer-box2-label">Hours</div>
                                            </div>
                                            <div class="home-row3-column1-today-deal-box-each-timer-box3">
                                                <div class="home-row3-column1-today-deal-box-each-timer-box3-time-minute">3</div>
                                                <div class="home-row3-column1-today-deal-box-each-timer-box3-label">Mins</div>
                                            </div>
                                            <div class="home-row3-column1-today-deal-box-each-timer-box4">
                                                <div class="home-row3-column1-today-deal-box-each-timer-box4-time-second">4</div>
                                                <div class="home-row3-column1-today-deal-box-each-timer-box4-label">Secs</div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="home-row3-column1-today-deal-box-each-image">
                                    <?php echo img($image_url) ?>
                                </div>
                                <div class="home-row3-column1-today-deal-box-each-separator"></div>
                                <div class="home-row3-column1-today-deal-box-each-information">
                                    <div class="home-row3-column1-today-deal-box-each-information-title-rating">
                                        <div class="home-row3-column1-today-deal-box-each-information-title">
                                            <?php echo $title ?>
                                        </div>
                                        <div class="home-row3-column1-today-deal-box-each-information-rating">

                                        </div>
                                    </div>
                                    <div class="home-row3-column1-today-deal-box-each-information-price">
                                        <div class="home-row3-column1-today-deal-box-each-information-price-after">
                                            <?php
                                            if ($price_after_show)
                                            {
                                                echo 'RM ' . $price_after;
                                            }
                                            ?>
                                        </div>
                                        <div class="home-row3-column1-today-deal-box-each-information-price-before">
                                            <?php 
                                            if ($price_before_show) 
                                            {
                                                echo 'RM ' . $price_before;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="float-fix"></div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id='home-row3-column1-banner'>
                <div id='home-row3-column1-banner-block1'>
                    <?php 
                    $banner_info = $this->m_admin->banner_select_one(108);
                    $banner_image_url = $banner_info['banner_image_url'];
                    $banner_website_url = $banner_info['banner_website_url'];
                    echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                     ?>
                </div>
                <div id='home-row3-column1-banner-block2'>
                    <div id='home-row3-column1-banner-block2-row1'>
                        <?php 
                        $banner_info = $this->m_admin->banner_select_one(109);
                        $banner_image_url = $banner_info['banner_image_url'];
                        $banner_website_url = $banner_info['banner_website_url'];
                        echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                    ?>
                    </div>
                    <div id='home-row3-column1-banner-block2-row2'>
                        <?php 
                        $banner_info = $this->m_admin->banner_select_one(110);
                        $banner_image_url = $banner_info['banner_image_url'];
                        $banner_website_url = $banner_info['banner_website_url'];
                        echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                    ?>
                    </div>
                </div>
                <div id='home-row3-column1-banner-block3'>
                    <?php 
                    $banner_info = $this->m_admin->banner_select_one(111);
                    $banner_image_url = $banner_info['banner_image_url'];
                    $banner_website_url = $banner_info['banner_website_url'];
                    echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                    ?>
                </div>
                <div id='float-fix'></div>
            </div>
            <div id="home-row3-column1-redemption">
                <div id="home-row3-column1-redemption-title">Redemption</div>
                <div id='home-row3-column1-redemption-navigation'>
                    <div id='home-row3-column1-redemption-prev'> < </div>
                    <div id='home-row3-column1-redemption-prev-next-separator'></div>
                    <div id='home-row3-column1-redemption-next'> > </div>
                </div>
                <div id="float-fix"></div>
                <div id="home-row3-column1-redemption-title-bottom-line"></div>
                <div id="home-row3-column1-redemption-box">
                    <?php 
                    $redemption_list = $this->m_custom->getAdvertise('pro', NULL, NULL, 0, NULL, NULL, 1);
                    foreach ($redemption_list as $redemption)
                    {
                        $advertise_type = $redemption['advertise_type'];
                        $advertise_id = $redemption['advertise_id'];
                        $sub_category_id = $redemption['sub_category_id'];
                        $merchant_id = $redemption['merchant_id'];
                        $image = $redemption['image'];
                        $title = $redemption['title'];
                        $candie = $redemption['voucher_candie'];
                        $end_time = $redemption['end_time'];
                        if ($advertise_type == 'adm')
                        {
                            $image_url = $this->album_admim . $image;
                        }
                        else 
                        {
                            $image_url = $this->album_merchant . $image;
                        }
                        ?>
                        <div class="home-row3-column1-redemption-box-each">
                            <a href='<?php echo base_url("all/advertise/$advertise_id") ?>'>
                                <div class="home-row3-column1-redemption-box-each-image">
                                    <?php echo img($image_url) ?>
                                </div>
                                <div class="home-row3-column1-redemption-box-each-separator"></div>
                                <div class="home-row3-column1-redemption-box-each-information">
                                    <div class="home-row3-column1-redemption-box-each-information-title-rating">
                                        <div class="home-row3-column1-redemption-box-each-information-title">
                                            <?php echo $title ?>
                                        </div>
                                        <!--<div class="home-row3-column1-redemption-box-each-information-rating"></div>-->
                                    </div>
                                    <div class="home-row3-column1-redemption-box-each-information-candie">
                                        <?php echo $candie ?> Candies
                                    </div>
                                    <div class="float-fix"></div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id='home-row3-column2'>
            <div id='home-row3-column2-like'>
                <div id="home-row3-column2-like-title">Like</div>
                <div id="home-row3-column2-like-notification">
                    <table border="0px" cellpading="0px" cellspacing="0px">
                        <?php
                        $notification_list = $this->m_custom->notification_display(0, 1, 'like');
                        foreach($notification_list as $notification)
                        {
                            $notification_user_image = $notification['noti_user_image'];
                            $notification_user_name = $notification['noti_user_url'];
                            $notification_message = $notification['noti_message'];
                            $notification_url = $notification['noti_url'];
                            $notification_item_image = $notification['noti_image_url'];
                            ?>
                            <tr>
                                <td valign="top">
                                    <div id="home-row3-column2-like-notification-user-image">
                                        <?php echo $notification_user_image ?>
                                    </div>
                                </td>
                                <td style="width: 100%;">
                                    <div id="home-row3-column2-like-notification-description">
                                        <span id="home-row3-column2-like-notification-description-name">
                                            <?php echo $notification_user_name ?>
                                        </span>
                                        <?php
                                        if (empty($notification_url)) 
                                        { 
                                            echo $notification_message;
                                        }
                                        else
                                        {
                                            ?>
                                            <a href="<?php echo base_url($notification_url) ?>"><?php echo $notification_message ?></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td valign="top">
                                    <div id="home-row3-column2-like-notification-item-image">
                                        <a href="<?php echo base_url($notification_url) ?>">
                                            <?php echo img($notification_item_image) ?>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div id='home-row3-column2-user-picture'>
                <div id="home-row3-column2-user-picture-title">Users Pictures</div>
                <div id="home-row3-column2-user-picture-notification">
                    <table border="0px" cellpading="0px" cellspacing="0px">
                    <?php 
                    $notification_list = $this->m_custom->notification_display(0, 1, 'upload_image');
                    foreach($notification_list as $notification)
                    {
                        $notification_user_image = $notification['noti_user_image'];
                        $notification_user_name = $notification['noti_user_url'];
                        $notification_message = $notification['noti_message'];
                        $notification_url = $notification['noti_url'];
                        $notification_item_image = $notification['noti_image_url'];
                        ?>
                        <tr>
                            <td valign="top">
                                <div id="home-row3-column2-user-picture-notification-user-image">
                                    <?php echo $notification_user_image ?>
                                </div>
                            </td>
                            <td style="width: 100%;">
                                <div id="home-row3-column2-user-picture-notification-description">
                                    <span id="home-row3-column2-user-picture-notification-description-name">
                                        <?php echo $notification_user_name ?>
                                    </span>
                                    <?php
                                    if (empty($notification_url)) 
                                    { 
                                        echo $notification_message;
                                    }
                                    else
                                    {
                                        ?>
                                        <a href="<?php echo base_url($notification_url) ?>"><?php echo $notification_message ?></a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </td>
                            <td valign="top">
                                <div id="home-row3-column2-user-picture-notification-item-image">
                                    <a href="<?php echo base_url($notification_url) ?>">
                                        <?php echo img($notification_item_image) ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </table>
                </div>
            </div>
        </div>
        <div id="float-fix"></div>        
        <div id="home-row4-logo-slider">
            <div id="home-row4-logo-slider-prev">
                <div id="home-row4-logo-slider-prev-round">
                    <?php echo img('image/slider-prev.png') ?>
                </div>
            </div>
            <div id="home-row4-logo-slider-box">
                <div>
                    <img src="<?php echo base_url('folder_upload/home_banner_row4/1.png') ?>" style="height: 45px; margin-top: 19px;">
                </div>
                <div>
                    <img src="<?php echo base_url('folder_upload/home_banner_row4/2.png') ?>" style="height: 65px; margin-top: 10px;">
                </div>
                <div>
                    <img src="<?php echo base_url('folder_upload/home_banner_row4/3.png') ?>" style="height: 40px; margin-top: 19px;">
                </div>
                <div>
                    <img src="<?php echo base_url('folder_upload/home_banner_row4/4.png') ?>" style="height: 80px;">
                </div>
                <div>
                    <img src="<?php echo base_url('folder_upload/home_banner_row4/5.png') ?>" style="height: 40px; margin-top: 20px;">
                </div>
                <div>
                    <img src="<?php echo base_url('folder_upload/home_banner_row4/6.png') ?>" style="height: 80px;">
                </div>
                <div>
                    <img src="<?php echo base_url('folder_upload/home_banner_row4/7.png') ?>" style="height: 80px;">
                </div>
            </div>
            <div id="home-row4-logo-slider-next">
                <div id="home-row4-logo-slider-next-round">
                    <?php echo img('image/slider-next.png') ?>
                </div>
            </div>
            <div id="float-fix"></div>
        </div>
    </div>
</div>

<div id="home-bottom-background">
    <div id="home-bottom-background-text">
        JOIN THE KEPPO
    </div>
</div>
