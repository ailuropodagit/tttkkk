<link rel='stylesheet' id='camera-css'  href='<?php echo base_url('js/banner-row1-slider/camera.css') ?>' type='text/css' media='all'> 
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/jquery.mobile.customized.min.js') ?>'></script>
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/jquery.easing.1.3.js') ?>'></script> 
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/camera.min.js') ?>'></script> 

<!--RATING-->
<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>

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
            height: '480px'
        });
        
        $('#home-row3-today-deal-box').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            prevArrow: '#home-row3-today-deal-prev',
            nextArrow: '#home-row3-today-deal-next'
        });
        
        $('#home-row4-logo-slider-box').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            prevArrow: '#home-row4-logo-slider-prev',
            nextArrow: '#home-row4-logo-slider-next'
        });
    });
</script>
<style>
    .fluid_container {
        width: 100.1%;
    }
</style>

<div id='wrapper'>
    <div id='home'>
        <div id='home-row1'>
            <div id='home-row1-categories-navigation'>
                <div id="home-row1-categories-navigation-main-category-title">
                    <span id="home-row1-categories-navigation-main-category-title-icon">
                        <i class="fa fa-bars"></i>
                    </span>
                    <span id="home-row1-categories-navigation-main-category-title-label">
                        Categories
                    </span>
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
            <div id='home-row1-banner-main'>
                <div class='fluid_container'>
                    <div class="camera_wrap camera_azure_skin" id="camera_wrap_1">
                        <div data-src="<?php echo base_url('folder_upload/home_banner_row1/slide1.jpg') ?>"></div>
                        <div data-src="<?php echo base_url('folder_upload/home_banner_row1/slide2.jpg') ?>"></div>
                        <div data-src="<?php echo base_url('folder_upload/home_banner_row1/slide3.jpg') ?>"></div>
                    </div>
                </div>
            </div>
            <div id='float-fix'></div>
        </div>
        <div id='home-row2'>
            <div id='home-row2-block1'>
                <?php echo img('folder_upload/home_banner_row2/home-banner-row2-banner1.png') ?>
            </div>
            <div id='home-row2-block2'>
                <?php echo img('folder_upload/home_banner_row2/home-banner-row2-banner2.png') ?>
            </div>
            <div id='home-row2-block3'>
                <?php echo img('folder_upload/home_banner_row2/home-banner-row2-banner3.png') ?>
            </div>
            <div id='float-fix'></div>
        </div>
        <div id='home-row3-column1'>
            <div id='home-row3-column1-today-deal'>
                <div id='home-row3-column1-today-deal-title'>Today's Deals</div>
                <div id='home-row3-column1-today-deal-navigation'>
                    <div id='home-row3-today-deal-prev'> < </div>
                    <div id='home-row3-today-deal-prev-next-separator'></div>
                    <div id='home-row3-today-deal-next'> > </div>
                </div>
                <div id='float-fix'></div>
                <div id='home-row3-column1-today-deal-title-bottom-line'></div>
                <div id='home-row3-today-deal-box'>
                    <?php 
                    //CONFIG DATA
                    $this->group_id_user = $this->config->item('group_id_user');
                    $this->album_merchant = $this->config->item('album_merchant');
                    $this->album_admin = $this->config->item('album_admin');
                    
                    $hotdeal_list = $this->m_custom->getAdvertise('hot', NULL, NULL, 0, NULL, NULL, 1);
                    foreach ($hotdeal_list as $hotdeal)
                    {
                        $advertise_type = $hotdeal['advertise_type'];
                        $advertise_id = $hotdeal['advertise_id'];
                        $sub_category_id = $hotdeal['sub_category_id'];
                        $merchant_id = $hotdeal['merchant_id'];
                        $image = $hotdeal['image'];
                        $title = $hotdeal['title'];
                        $price_before = $hotdeal['price_before'];
                        $price_after = $hotdeal['price_after'];
                        if ($advertise_type == 'adm')
                        {
                            $image_url = $this->album_admim . $image;
                        }
                        else 
                        {
                            $image_url = $this->album_merchant . $image;
                        }
                        ?>
                        <div class="home-row3-today-deal-box-each">
                            <div class="home-row3-today-deal-box-each-timer-box1">
                                <div class="home-row3-today-deal-box-each-timer-box1-time">257</div>
                                <div class="home-row3-today-deal-box-each-timer-box1-label">Days</div>
                            </div>
                            <div class="home-row3-today-deal-box-each-timer-box2">
                                <div class="home-row3-today-deal-box-each-timer-box1-time">2</div>
                                <div class="home-row3-today-deal-box-each-timer-box1-label">Hours</div>
                            </div>
                            <div class="home-row3-today-deal-box-each-timer-box3">
                                <div class="home-row3-today-deal-box-each-timer-box1-time">3</div>
                                <div class="home-row3-today-deal-box-each-timer-box1-label">Mins</div>
                            </div>
                            <div class="home-row3-today-deal-box-each-timer-box4">
                                <div class="home-row3-today-deal-box-each-timer-box1-time">4</div>
                                <div class="home-row3-today-deal-box-each-timer-box1-label">Secs</div>
                            </div>
                            <div class="home-row3-today-deal-box-each-image">
                                <?php echo img($image_url) ?>
                            </div>
                            <div class="home-row3-today-deal-box-each-separator"></div>
                            <div class="home-row3-today-deal-box-each-information">
                                <div class="home-row3-today-deal-box-each-information-title-rating">
                                    <div class="home-row3-today-deal-box-each-information-title">
                                        <?php echo $title ?>
                                    </div>
                                    <div class="home-row3-today-deal-box-each-information-rating">
                                        <?php
                                        $average_rating = $this->m_custom->activity_rating_average($advertise_id, 'adv');
                                        if (check_correct_login_type($this->group_id_user)) //Check if user logged in
                                        {
                                            $radio_level = " ";
                                        }
                                        else
                                        {
                                            $radio_level = "disabled";
                                        }
                                        for ($i = 1; $i <= 5; $i++)
                                        {
                                            if ($i == round($average_rating))
                                            {
                                                echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "' checked='checked'/>";
                                            }
                                            else
                                            {
                                                echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "'/>";
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="home-row3-today-deal-box-each-information-price">
                                    <div class="home-row3-today-deal-box-each-information-price-after">
                                        <?php
                                        if ($price_after)
                                        {
                                            echo 'RM ' . $price_after;
                                        }
                                        ?>
                                    </div>
                                    <div class="home-row3-today-deal-box-each-information-price-before">
                                        <?php 
                                        if ($price_before) 
                                        {
                                            echo 'RM ' . $price_before;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="float-fix"></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div id='home-row3-column1-banner'>
                <div id='home-row3-column1-banner-block1'>
                    <?php echo img('folder_upload/home_banner_row3/home-banner-row3-banner1.png') ?>
                </div>
                <div id='home-row3-column1-banner-block2'>
                    <div id='home-row3-column1-banner-block2-row1'>
                        <?php echo img('folder_upload/home_banner_row3/home-banner-row3-banner2.png') ?>
                    </div>
                    <div id='home-row3-column1-banner-block2-row2'>
                        <?php echo img('folder_upload/home_banner_row3/home-banner-row3-banner3.png') ?>
                    </div>
                </div>
                <div id='home-row3-column1-banner-block3'>
                    <?php echo img('folder_upload/home_banner_row3/home-banner-row3-banner4.png') ?>
                </div>
                <div id='float-fix'></div>
            </div>
            <div id="home-row3-column1-redemption" style="overflow: scroll;">
                Redemption
                <?php 
                $data['hotdeal_list'] = $this->m_custom->getAdvertise('pro', NULL, NULL, 0, NULL, NULL, 1);
                $this->load->view('all/advertise_home', $data);
                ?>
            </div>
        </div>
        <div id='home-row3-column2'>
            <div id='home-row3-column2-like-rating' style="overflow: scroll;">
                Like
                <?php 
                $data['notification_list'] = $this->m_custom->notification_display(0, 1, 'like');
                $this->load->view('all/notification_home', $data);
                ?>
            </div>
            <div id='home-row3-column2-users-pictures' style="overflow: scroll;">
                Users Pictures
                <?php 
                $data['notification_list'] = $this->m_custom->notification_display(0, 1, 'upload_image');
                $this->load->view('all/notification_home', $data);
                ?>
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