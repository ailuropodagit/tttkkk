<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.countdown.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('[data-countdown]').each(function () {
            var $this = $(this), finalDate = $(this).data('countdown');
            $this.countdown(finalDate).on('update.countdown', function (event) {
                var format = '%H:%M:%S';
                if (event.offset.days > 0) {
                    format = '%-d day%!d ' + format;
                }
                if (event.offset.weeks > 0) {
                    format = '%-w week%!w ' + format;
                }
                $this.html(event.strftime(format));
            }).on('finish.countdown', function (event) {
                $this.html('Expired!');
                $this.parent().css({color: 'red'});
            });
        });
    });
</script>

<?php
//CONFIG DATA
$this->album_merchant = $this->config->item('album_merchant');
$this->album_admin = $this->config->item('album_admin');

//URI
$fetch_method = $this->router->fetch_method();
$uri_segment_4 = $this->uri->segment(4);
?>

<div id="advertise-list">
    <div id="advertise-list-title"><?php echo $title ?></div>
    <?php
    //UPLOAD BUTTON
    if ($this->ion_auth->logged_in())
    {
        //To check is supervisor have role to upload hot deal
        $have_role = $this->m_custom->check_role_su_can_uploadhotdeal();
        if ($have_role == 1)
        {
            $upload_picture_url = '';
            $second_parameter = $this->uri->segment(4);
            if ($fetch_method == 'album_redemption' || ($fetch_method == 'merchant_dashboard' && $second_parameter == 'promotion'))
            {
                $upload_picture_url = 'merchant/candie_promotion';
            }
            elseif ($fetch_method == 'album_merchant' || $fetch_method == 'merchant_dashboard')
            {
                $upload_picture_url = 'merchant/upload_hotdeal';
            }
            if (check_correct_login_type($this->config->item('group_id_user'))){
                $upload_picture_url = 'user/upload_for_merchant/' . $this->uri->segment(3);
            }
            if (!empty($upload_picture_url))
            {
                ?>     
                <div id='advertise-list-title-upload'>
                    <a href='<?php echo base_url($upload_picture_url) ?>'><i class="fa fa-upload advertise-list-title-upload-icon"></i>Upload Picture</a>
                </div>
                <?php
            }
        }
    }
    ?>
    <div id="float-fix"></div>
    <div id='advertise-list-title-bottom-line'></div>
    <div id="advertise-list-content">
        <?php
        //CATEGORY BREADCRUMB
        if (!empty($sub_category))
        {
            ?>
            <div id='advertise-list-category-breadcrumb'>
                <?php echo $main_category; ?>
                &nbsp; > &nbsp;
                <?php echo $sub_category; ?>
            </div>
            <?php
        }
        ?>
        
        <?php
        $bottom_empty_message = '';
        if (empty($hotdeal_list))
        {          
            //SHARE PAGE
            $empty_message = 'No Picture';
            if ($fetch_method == 'merchant_dashboard')
            {
                if ($uri_segment_4 == '')
                {
                    $empty_message = 'No Hot Deal';
                    $bottom_empty_message = 'No Hot Deal Suggestion';
                }
                else
                {
                    $empty_message = 'No Redemption';
                    $bottom_empty_message = 'No Redemption Suggestion';
                }
            }
            //EMPTY
            ?><div id='advertise-list-empty'><?php echo $empty_message ?></div><?php            
        }
        else
        {            
            //NOT EMPTY
            foreach ($hotdeal_list as $row)
            {
                $advertise_id = $row['advertise_id'];                
                $sub_category_id = $row['sub_category_id'];
                $merchant_id = $row['merchant_id'];
                $merchant_name = $this->m_custom->display_users($merchant_id);
                $merchant_dashboard_url = $this->m_custom->generate_merchant_link($merchant_id);
                $advertise_type = $row['advertise_type'];
                $average_rating = $this->m_custom->activity_rating_average($advertise_id, 'adv');
                $show_extra_info = $row['show_extra_info'];
                $price_before = $row['price_before'];
                $price_before_show = $row['price_before_show'];
                $price_after = $row['price_after'];
                $price_after_show = $row['price_after_show'];
                $voucher_worth = $row['voucher_worth'];
                $get_off_percent = $row['get_off_percent'];
                $how_many_buy = $row['how_many_buy'];
                $how_many_get = $row['how_many_get'];
                $second_parameter = $this->uri->segment(4);
                if ($advertise_type == 'adm')
                {
                    $image_url = base_url($this->album_admin . $row['image']);
                }
                else
                {
                    $image_url = base_url($this->album_merchant . $row['image']);
                }
                //SHARE PAGE
                if ($fetch_method == 'hotdeal_list')
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/hot/" . $sub_category_id;
                }
                else if ($fetch_method == 'album_merchant')
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $row['advertise_id'] . "/all/0/" . $row['merchant_id'] . '/1';
                }
                else if ($fetch_method == 'merchant_dashboard')
                {
                    if($second_parameter == 'promotion'){
                        $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/pro/0/" . $merchant_id;
                    }
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/hot/0/" . $merchant_id;
                }
                else if ($fetch_method == 'promotion_list')
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/pro/" . $sub_category_id;
                }
                else if ($fetch_method == 'redemption_list')
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/adm/" . $sub_category_id;
                }
                else
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id;
                }
                ?>
                <div id='advertise-list-box'>
                    <?php
                    if ($fetch_method != 'merchant_dashboard')
                    {
                        ?>
                        <div id="advertise-list-title1">
                            <?php echo $merchant_dashboard_url ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div id="advertise-list-photo">
                        <div id="advertise-list-photo-box">
                            <a href='<?php echo $advertise_detail_url ?>'><img src='<?php echo $image_url ?>'></a>
                        </div>
                    </div>
                    <div id="advertise-list-title2">
                        <a href='<?php echo $advertise_detail_url ?>'><?php echo $row['title'] ?>&nbsp;</a>
                    </div>
                    <div id="advertise-list-dynamic-time">
                        <?php
                        if ($advertise_type == 'hot')
                        { 
                            if ($row['post_hour'] != 0)
                            { 
                                ?>
                                <i class="fa fa-clock-o"></i><span id="advertise-list-dynamic-time-label" data-countdown='<?php echo $row['end_time'] ?>'></span>
                                <?php
                            } 
                        }
                        if ($advertise_type == 'pro' || $advertise_type == 'adm')
                        {
                            ?>
                            <i class="fa fa-bullseye"></i><span id="advertise-list-dynamic-time-label"><?php echo $row['voucher_candie'] ?> candy</span>
                            <?php 
                        }
                        ?>
                    </div>
                    <div id="advertise-list-info">
                        <table border="0" cellpadding="4px" cellspacing="0px">
                            <?php
                            if (($advertise_type == 'adm' && !empty($row['voucher_worth'])) || ($advertise_type == 'pro'  && $show_extra_info == 122))
                            { 
                                ?>
                                <tr valign='top'>
                                    <td>Worth</td>
                                    <td>:</td>
                                    <td>
                                        <div id="advertise-list-voucher-worth"><?php echo "RM " . $row['voucher_worth']; ?></div>
                                    </td>
                                </tr>    
                                <?php
                            } 
                            ?>
                            <tr valign='top'>
                                <td>Rating</td>
                                <td>:</td>
                                <td>
                                    <div>
                                        <?php
                                        for ($i = 1; $i <= 5; $i++)
                                        {
                                            if ($i == round($average_rating))
                                            {
                                                echo "<input class='star' type='radio' name='a-rating-$advertise_id' disabled='disabled' value='" . $i . "' checked='checked'/>";
                                            }
                                            else
                                            {
                                                echo "<input class='star' type='radio' name='a-rating-$advertise_id' disabled='disabled' value='" . $i . "'/>";
                                            }
                                        }
                                        ?>
                                        <div class="float-fix"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            if ($advertise_type != 'adm')
                            {
                                ?>
                                <tr valign='top'>
                                    <td>Like</td>
                                    <td>:</td>
                                    <td><?php echo $this->m_custom->generate_like_list_link($row['advertise_id'], 'adv'); ?></td>
                                </tr>
                                <tr valign='top'>
                                    <td>Comment</td>
                                    <td>:</td>
                                    <td><?php echo $this->m_custom->activity_comment_count($row['advertise_id'], 'adv'); ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <?php
            }
            //PAGINATION
            if (!empty($paging_links))
            {
                ?>
                <div id='advertise-list-pagination'>
                    <?php echo $paging_links; ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
