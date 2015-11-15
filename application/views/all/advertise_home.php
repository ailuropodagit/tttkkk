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
//CONFIG DATA
$this->album_merchant = $this->config->item('album_merchant');
$this->album_admin = $this->config->item('album_admin');

//URI
$fetch_method = $this->router->fetch_method();
?>

<div id="advertise-list">
    <?php if (!empty($title))
        { ?>
    <div id="advertise-list-title"><?php echo $title ?></div>
    <div id='advertise-list-title-bottom-line'></div>
        <?php } ?>
    <div id="advertise-list-content">
        
        <?php
        $bottom_empty_message = '';
        if (empty($hotdeal_list))
        {
            $empty_message = 'No Picture';
            //EMPTY
            ?><div id='empty-message'><?php echo $empty_message ?></div><?php
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
                if ($advertise_type == 'adm')
                {
                    $image_url = base_url($this->album_admin . $row['image']);
                }
                else
                {
                    $image_url = base_url($this->album_merchant . $row['image']);
                }
                $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id;
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
                    <?php
                    if ($advertise_type == 'hot')
                    { 
                        if ($row['post_hour'] != 0)
                        { 
                            ?>
                            <div id="advertise-list-dynamic-time">
                                <i class="fa fa-clock-o"></i><span id="advertise-list-dynamic-time-label" data-countdown='<?php echo $row['end_time'] ?>'></span>
                            </div>
                            <?php
                        } 
                    }
                    if ($advertise_type == 'pro' || $advertise_type == 'adm')
                    {
                        ?>
                        <div id="advertise-list-dynamic-time">
                            <i class="fa fa-bullseye"></i><span id="advertise-list-dynamic-time-label"><?php echo $row['voucher_candie'] ?> candies</span>
                        </div>
                        <?php 
                    } 
                    ?>                    
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

<div id="advertise-list-empty-bottom-fix"></div>