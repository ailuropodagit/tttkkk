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

            });
        });
    });
</script>

<?php
$this->album_merchant = $this->config->item('album_merchant');
?>

<div id="advertise-list">
    
    <h1><?php echo $title; ?></h1>
    <?php
    if (!empty($upload_hotdeal_button))
    {
        echo $upload_hotdeal_button;
    }
    ?>
    <?php
    if (!empty($paging_links))
    {
        echo "<p>" . $paging_links . "<p>";
    }
    ?>
    
    <div id="advertise-list-content">
        
        <!--CATEGORY BREADCRUMB-->
        <?php if (!empty($sub_category)) { ?>
            <div id='advertise-list-category-breadcrumb'>
                <?php echo $main_category; ?>
                &nbsp; > &nbsp;
                <?php echo $sub_category; ?>
            </div>
        <?php } ?>

        <?php 
        if (empty($hotdeal_list)) 
        {
            //EMPTY
            ?><div id=''>No Offer Deal</div><?php
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
                $merchant_dashboard_url = base_url() . "all/merchant-dashboard/" . generate_slug($merchant_name);
                
                //SHARE PAGE
                if ($this->router->fetch_method() == 'hotdeal_list')
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/hot/" . $sub_category_id;
                }
                else if ($this->router->fetch_method() == 'album_merchant')
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $row['advertise_id'] . "/all/0/" . $row['merchant_id'] . '/1';
                }
                else if ($this->router->fetch_method() == 'merchant_dashboard')
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/all/0/" . $merchant_id;
                }
                else if ($this->router->fetch_method() == 'promotion_list')
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/pro/" . $sub_category_id;
                }
                else
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id;
                }
                ?>
                <div id='advertise-list-box'>
                    <?php if($this->router->fetch_method() != 'merchant_dashboard') { ?>
                    <div id="advertise-list-title1">
                        <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                    </div>
                    <?php } ?>
                    <div id="advertise-list-photo">
                        <div id="advertise-list-photo-box">
                            <a href='<?php echo $advertise_detail_url ?>'><img src='<?php echo base_url($this->album_merchant . $row['image']) ?>'></a>
                        </div>
                    </div>
                    <div id="advertise-list-title2">
                        <a href='" . $advertise_detail_url . "'><?php echo $row['title'] ?></a>
                    </div>
                    <div id="advertise-list-info">
                        <table border="0" cellpadding="4px" cellspacing="0px">
                            <tr>
                                <td>Category</td>
                                <td>:</td>
                                <td>
                                    <div id="advertise-list-info-category"><?php echo $this->m_custom->display_category($row['sub_category_id']) ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Like</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->activity_like_count($row['advertise_id'], 'adv'); ?></td>
                            </tr>
                            <tr>
                                <td>Comment</td>
                                <td>:</td>
                                <td>10</td>
                            </tr>
                        </table>
                    </div>
                    <?php if ($row['advertise_type'] == 'hot') { ?>
                        <div id="advertise-list-dynamic-time">
                            <i class="fa fa-clock-o"></i><span id="advertise-list-dynamic-time-label" data-countdown='<?php echo $row['end_time'] ?>'></span>
                        </div>
                    <?php } ?>
                    <?php if ($row['advertise_type'] == 'pro') { ?>
                        <div id="advertise-list-dynamic-time">
                            <i class="fa fa-bullseye"></i><span id="advertise-list-dynamic-time-label"><?php echo $row['voucher_candie'] ?> candies</span>
                        </div>
                    <?php } ?>
                </div>
                <?php
            }
            ?>

            <div id='advertise-list-box'>
                
                <?php if($this->router->fetch_method() != 'merchant_dashboard') { ?>
                <div id="advertise-list-title1">
                    <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                </div>
                <?php } ?>

                <div id="advertise-list-photo">
                    <div id="advertise-list-photo-box">
                        <a href='<?php echo $advertise_detail_url ?>'><img src='<?php echo base_url($this->album_merchant . $row['image']) ?>'></a>
                    </div>
                </div>
                
                <div id="advertise-list-title2">
                    <a href='" . $advertise_detail_url . "'><?php echo $row['title'] ?></a>
                </div>
                
                <div id="advertise-list-info">
                    <table border="0" cellpadding="4px" cellspacing="0px">
                        <tr>
                            <td>Category</td>
                            <td>:</td>
                            <td>
                                <div id="advertise-list-info-category"><?php echo $this->m_custom->display_category($row['sub_category_id']) ?></div>
                            </td>
                        </tr>
                        <tr>
                            <td>Like</td>
                            <td>:</td>
                            <td><?php echo $this->m_custom->activity_like_count($row['advertise_id'], 'adv'); ?></td>
                        </tr>
                        <tr>
                            <td>Comment</td>
                            <td>:</td>
                            <td><?php echo $this->m_custom->activity_comment_count($row['advertise_id'], 'adv'); ?></td>
                        </tr>
                    </table>
                </div>
                
                <?php if ($row['advertise_type'] == 'hot') { ?>
                    <div id="advertise-list-dynamic-time">
                        <i class="fa fa-clock-o"></i><span id="advertise-list-dynamic-time-label" data-countdown='<?php echo $row['end_time'] ?>'></span>
                    </div>
                <?php } ?>

                <?php if ($row['advertise_type'] == 'pro') { ?>
                    <div id="advertise-list-dynamic-time">
                        <i class="fa fa-bullseye"></i><span id="advertise-list-dynamic-time-label"><?php echo $row['voucher_candie'] ?> candies</span>
                    </div>
                <?php } ?>

            </div>
        
            <?php
        }
        ?>
        
    </div>
</div>
