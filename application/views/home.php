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
//            height: '480px'
        });
        
        $('#camera_wrap_2').camera({
            skins: 'camera_violet_skin',
            loader: 'none',
            navigation: true,
            navigationHover: false,
            playPause: false,
            thumbnails: false,
            fx: 'scrollLeft',
//            height: '280px'
        });
        
        $('#home-row3-logo-slider-box').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            prevArrow: '#home-row3-logo-slider-prev',
            nextArrow: '#home-row3-logo-slider-next',
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
            <?php 
            for($i = 101; $i <= 104; $i++)
            {
                ${'slider_info' . $i} = $this->m_admin->banner_select_one($i);
                ${'slider_image_url' . $i} = ${'slider_info' . $i}['banner_image_url'];
                ${'slider_website_url' . $i} = ${'slider_info' . $i}['banner_website_url'];
                ${'slider_no_image' . $i} = ${'slider_info' . $i}['no_image'];
            }
            ?>
            <div id='home-row1-banner-main'>
                <div class="camera_wrap camera_azure_skin" id="camera_wrap_1">
                    <?php 
                    for($i = 101; $i <= 104; $i++)
                    {
                        if(${'slider_no_image' . $i} == 0){
                            echo "<div data-src=" . ${'slider_image_url' . $i} . " data-link=" . ${'slider_website_url' . $i} . " data-target='_blank' ></div>";
                        }
                    }
                    ?>
                </div>
                <div class="float-fix"></div>
            </div>
            <div id='home-row1-banner-main2'>
                <div class="camera_wrap camera_azure_skin" id="camera_wrap_2">
                    <?php 
                    for($i = 101; $i <= 104; $i++)
                    {
                        if(${'slider_no_image' . $i} == 0){
                            echo "<div data-src=" . ${'slider_image_url' . $i} . " data-link=" . ${'slider_website_url' . $i} . " data-target='_blank' ></div>";
                        }
                    }
                    ?>
                </div>
                <div class="float-fix"></div>
            </div>
            <div id='home-row1-left-banner'>
                <div id='home-row1-left-banner-block1'>
                    <?php 
                    $banner_info = $this->m_admin->banner_select_one(105);
                    $banner_image_url = $banner_info['banner_image_url'];
                    $banner_website_url = $banner_info['banner_website_url'];
                    echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>";
                    ?>
                </div>
                <div id='home-row1-left-banner-block2'>
                    <?php 
                    $banner_info = $this->m_admin->banner_select_one(106);
                    $banner_image_url = $banner_info['banner_image_url'];
                    $banner_website_url = $banner_info['banner_website_url'];
                    echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                    ?>
                </div>
                <div id='home-row1-left-banner-block3'>
                    <?php 
                    $banner_info = $this->m_admin->banner_select_one(107);
                    $banner_image_url = $banner_info['banner_image_url'];
                    $banner_website_url = $banner_info['banner_website_url'];
                    echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                    ?>
                </div>
            </div>
            <div id='float-fix'></div>
        </div>
        <div id='home-row2-column1'>
            <div id='home-row2-column1-today-deal'>
                <?php 
                //TODAY DEAL
                $this->load->view('share/hot_deal_row_list4') 
                ?>
            </div>
            <div id='home-row2-column1-banner'>
                <div id='home-row2-column1-banner-block1'>
                    <?php 
                    $banner_info = $this->m_admin->banner_select_one(108);
                    $banner_image_url = $banner_info['banner_image_url'];
                    $banner_website_url = $banner_info['banner_website_url'];
                    echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                     ?>
                </div>
                <div id='home-row2-column1-banner-block2'>
                    <div id='home-row2-column1-banner-block2-row1'>
                        <?php 
                        $banner_info = $this->m_admin->banner_select_one(109);
                        $banner_image_url = $banner_info['banner_image_url'];
                        $banner_website_url = $banner_info['banner_website_url'];
                        echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                        ?>
                    </div>
                    <div id='home-row2-column1-banner-block2-row2'>
                        <?php 
                        $banner_info = $this->m_admin->banner_select_one(110);
                        $banner_image_url = $banner_info['banner_image_url'];
                        $banner_website_url = $banner_info['banner_website_url'];
                        echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                        ?>
                    </div>
                </div>
                <div id='home-row2-column1-banner-block3'>
                    <?php 
                    $banner_info = $this->m_admin->banner_select_one(111);
                    $banner_image_url = $banner_info['banner_image_url'];
                    $banner_website_url = $banner_info['banner_website_url'];
                    echo "<a href='" . $banner_website_url . "' target='_blank'>" . img($banner_image_url) . "</a>"; 
                    ?>
                </div>
                <div id='float-fix'></div>
            </div>
            <div id='home-row2-column1-redemption'>
                <?php
                //REDEMPTION
                $this->load->view('share/redemption_row_list4')
                ?>
            </div>
        </div>
        <div id='home-row2-column2'>
            <div id='home-row2-column2-like'>
                <div id='home-row2-column2-like-header'>
                    <div id='home-row2-column2-like-header-table'>
                        <div id='home-row2-column2-like-header-table-row'>
                            <div id='home-row2-column2-like-header-table-row-cell'>
                                <div id="home-row2-column2-like-header-title">Like</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="home-row2-column2-like-notification">
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
                                    <div id="home-row2-column2-like-notification-user-image">
                                        <?php echo $notification_user_image ?>
                                    </div>
                                </td>
                                <td style="width: 100%;">
                                    <div id="home-row2-column2-like-notification-description">
                                        <span id="home-row2-column2-like-notification-description-name">
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
                                    <div id="home-row2-column2-like-notification-item-image">
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
            <div id='home-row2-column2-user-picture'>
                <div id='home-row2-column2-user-picture-header'>
                    <div id='home-row2-column2-user-picture-header-table'>
                        <div id='home-row2-column2-user-picture-header-table-row'>
                            <div id='home-row2-column2-user-picture-header-table-row-cell'>
                                <div id="home-row2-column2-user-picture-header-title">Users Pictures</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="home-row2-column2-user-picture-notification">
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
                                <div id="home-row2-column2-user-picture-notification-user-image">
                                    <?php echo $notification_user_image ?>
                                </div>
                            </td>
                            <td style="width: 100%;">
                                <div id="home-row2-column2-user-picture-notification-description">
                                    <span id="home-row2-column2-user-picture-notification-description-name">
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
                                <div id="home-row2-column2-user-picture-notification-item-image">
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
        <div id="home-row3-logo-slider">
            <div id="home-row3-logo-slider-prev">
                <div id="home-row3-logo-slider-prev-round">
                    <?php echo img('image/slider-prev.png') ?>
                </div>
            </div>
            <?php 
            for($i = 131; $i <= 138; $i++)
            {
                ${'logo_info' . $i} = $this->m_admin->banner_select_one($i);
                ${'logo_image_url' . $i} = ${'logo_info' . $i}['banner_image_url'];
                ${'logo_website_url' . $i} = ${'logo_info' . $i}['banner_website_url'];
                ${'logo_no_image' . $i} = ${'logo_info' . $i}['no_image'];
            }
            ?>
            <div id="home-row3-logo-slider-box">   
                <?php 
                for($i = 131; $i <= 138; $i++)
                {
                    if(${'logo_no_image' . $i} == 0)
                    {
                        ?>
                        <div style='display: table'>
                            <div style='display: table-cell; vertical-align: middle; height: 80px;'>
                                <?php echo "<a href='" . ${'logo_website_url' . $i} . "' target='_blank'><img src='" . ${'logo_image_url' . $i} . "' style='max-width: 100%; max-height: 100%;'/></a>"; ?> 
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div id="home-row3-logo-slider-next">
                <div id="home-row3-logo-slider-next-round">
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