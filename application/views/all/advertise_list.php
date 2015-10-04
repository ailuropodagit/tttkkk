<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.countdown.js"></script>
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
            })
            .on('finish.countdown', function (event) {
                $this.html('Expired!');
                $this.parent().css({color: 'red'});
            });
        });
    });
</script>

<?php
$this->album_merchant = $this->config->item('album_merchant');
$this->album_admin = $this->config->item('album_admin');
?>

<div id="advertise-list">
    <h1><?php echo $title; ?></h1>
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
        //UPLOAD BUTTON
        $fetch_method = $this->router->fetch_method();
        if ($this->ion_auth->logged_in())
        {
            if ($fetch_method == 'album_merchant')
            {
                ?>            
                <div id='advertise-list-upload-button'>
                    <a href='<?php echo base_url() ?>merchant/upload_hotdeal' class='ahref-button'>Upload</a>
                </div>
                <?php
            }
        }
        ?>

        <?php 
        if (empty($hotdeal_list)) 
        {
            //SHARE PAGE
            if ($fetch_method == 'hotdeal_list')
            {
                $empty_data_message = 'No Hot Deal';
            }
            else if ($fetch_method == 'merchant_dashboard')
            {
                $empty_data_message = 'No Offer Deal';
            }
            else if ($fetch_method == 'promotion_list' || $fetch_method == 'redemption_list')
            {
                $empty_data_message = 'No Redemption';
            }
            else if ($fetch_method == 'album_merchant')
            {
                $empty_data_message = 'No Picture';
            }
            //EMPTY
            ?><div id='empty-message'><?php echo $empty_data_message ?></div><?php
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
                if ($advertise_type == 'adm') { 
                    $image_url = base_url($this->album_admin . $row['image']);
                }else{
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
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/all/0/" . $merchant_id;
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
                    <?php if($fetch_method != 'merchant_dashboard') { ?>
                    <div id="advertise-list-title1">
                        <?php echo $merchant_dashboard_url ?>
                    </div>
                    <?php } ?>
                    <div id="advertise-list-photo">
                        <div id="advertise-list-photo-box">
                            <a href='<?php echo $advertise_detail_url ?>'><img src='<?php echo $image_url ?>'></a>
                        </div>
                    </div>
                    <div id="advertise-list-title2">
                        <a href='<?php echo $advertise_detail_url ?>'><?php echo $row['title'] ?></a>
                    </div>
                    <?php if ($advertise_type == 'hot') { ?>
                        <div id="advertise-list-dynamic-time">
                            <i class="fa fa-clock-o"></i><span id="advertise-list-dynamic-time-label" data-countdown='<?php echo $row['end_time'] ?>'></span>
                        </div>
                    <?php } ?>
                    <?php if ($advertise_type == 'pro' || $advertise_type == 'adm') { ?>
                        <div id="advertise-list-dynamic-time">
                            <i class="fa fa-bullseye"></i><span id="advertise-list-dynamic-time-label"><?php echo $row['voucher_candie'] ?> candies</span>
                        </div>
                    <?php } ?>
                    <div id="advertise-list-info">
                        <table border="0" cellpadding="4px" cellspacing="0px">
                            <?php if (($advertise_type == 'pro' || $advertise_type == 'adm') && !empty($row['voucher_worth'])) { ?>
                            <tr>
                                <td>Worth</td>
                                <td>:</td>
                                <td>
                                    <div id="advertise-list-voucher-worth"><?php echo "RM " . $row['voucher_worth']; ?></div>
                                </td>
                            </tr>    
                            <?php } ?>
                            <tr>
                                <td>Category</td>
                                <td>:</td>
                                <td>
                                    <div id="advertise-list-info-category"><?php echo $this->m_custom->display_category($row['sub_category_id']) ?></div>
                                </td>
                            </tr>
                            <?php 
                            if ($advertise_type != 'adm'){
                            ?>
                            <tr>
                                <td>Like</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->generate_like_list_link($row['advertise_id'], 'adv'); ?></td>
                            </tr>
                            <tr>
                                <td>Comment</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->activity_comment_count($row['advertise_id'], 'adv'); ?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <?php
            }
            ?>
            
            <div id='float-fix'></div>
            <div id='advertise-list-bottom-empty-fix'>&nbsp;</div>
            
            <?php
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
