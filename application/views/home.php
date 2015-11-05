<link rel='stylesheet' id='camera-css'  href='<?php echo base_url('js/banner-row1-slider/camera.css') ?>' type='text/css' media='all'> 
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/jquery.min.js') ?>'></script>
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/jquery.mobile.customized.min.js') ?>'></script>
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/jquery.easing.1.3.js') ?>'></script> 
<script type='text/javascript' src='<?php echo base_url('js/banner-row1-slider/camera.min.js') ?>'></script> 

<script>
    $(function () {
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
                            <a href="#"><?php echo $main_category_label ?></a>
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
                Today Deal
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
            <div id="home-row3-column1-redemption">
                Redemption
            </div>
        </div>
        <div id='home-row3-column2'>
            <div id='home-row3-column2-like-rating'>
                Like Rating
            </div>
            <div id='home-row3-column2-users-pictures'>
                Users Pictures
            </div>
        </div>
        <div id="float-fix"></div>
    </div>
</div>

<div id="home-bottom-background">
    <div id="home-bottom-background-text">
        JOIN THE KEPPO
    </div>
</div>