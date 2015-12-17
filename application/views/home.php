<!--CAMERA SLIDER-->
<link rel='stylesheet' id='camera-css'  href='<?php echo base_url('js/banner-row1-slider/camera.css') ?>' type='text/css' media='all'> 
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/jquery.mobile.customized.min.js') ?>'></script>
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/jquery.easing.1.3.js') ?>'></script> 
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/camera.min.js') ?>'></script> 

<!--SLICK SLIDER-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('js/slick-slider/slick.css') ?>"/>
<!--<link rel="stylesheet" type="text/css" href="js/slick-slider/slick-theme.css"/>-->

<!--RATING-->
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
            fx: 'scrollLeft',
            height: '450px'
        });
        
        $('#camera_wrap_2').camera({
            skins: 'camera_violet_skin',
            loader: 'none',
            navigation: true,
            navigationHover: false,
            playPause: false,
            thumbnails: false,
            fx: 'scrollLeft'
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
            for($i = 101; $i < 104; $i++)
            {
                ${'slider_info' . $i} = $this->m_admin->banner_select_one($i);
                ${'slider_image_url' . $i} = ${'slider_info' . $i}['banner_image_url'];
                ${'slider_website_ur' . $i} = ${'slider_info' . $i}['banner_website_url'];
            }
            ?>
            <div id='home-row1-banner-main'>
                <div class="camera_wrap camera_azure_skin" id="camera_wrap_1">
                    <?php 
                    for($i = 101; $i < 104; $i++)
                    {
                        echo "<div data-src=" . ${'slider_image_url' . $i} . " data-link=" . ${'slider_website_ur' . $i} . " data-target='_blank' ></div>";
                    }
                    ?>
                </div>
            </div>
            <div id='home-row1-banner-main2'>
                <div class="camera_wrap camera_azure_skin" id="camera_wrap_2">
                    <?php 
                    for($i = 101; $i < 104; $i++)
                    {
                        echo "<div data-src=" . ${'slider_image_url' . $i} . " data-link=" . ${'slider_website_ur' . $i} . " data-target='_blank' ></div>";
                    }
                    ?>
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
                <?php 
                //TODAY DEAL
                $this->load->view('share/hot_deal_row_list4') 
                ?>
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
            <div id='home-row3-column1-redemption'>
                <?php
                //REDEMPTION
                $this->load->view('share/redemption_row_list4')
                ?>
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
                                        <b><?php echo $notification_user_name ?></b>
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
