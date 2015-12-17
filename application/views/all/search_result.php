<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>

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
    $(function () {
        $('#form-rate :radio.star').rating();
    });
</script>

<?php
//CONFIG DATA
$merhant_profile_album = $this->config->item('album_merchant_profile');
$profile_image_empty = $this->config->item('empty_image');
?>

<div id="search-result">
    <h1>Search Result<?php echo $state_name ?></h1>
    <div id='search-result-content'>
        
        <!--MERCHANT RESULT-->
        <div id="search-result-merchant">
            <?php
            $data['title'] = 'Retailer';
            $data['review_list'] = $home_search_merchant;
            $this->load->view('share/merchant_list3.php', $data);
            ?>
        </div>
        
        <!--HOT DEAL RESULT-->
        <div id="search-result-hot-deal">
            <div id="search-result-hot-deal-title">Hot Deal</div>
            <?php
            if ($home_search_hotdeal != null)
            {
                foreach ($home_search_hotdeal as $row)
                {
                    $advertise_id = $row['advertise_id'];
                    $sub_category_id = $row['sub_category_id'];
                    $merchant_id = $row['merchant_id'];
                    $merchant_name = $this->m_custom->display_users($merchant_id);
                    $merchant_dashboard_url = base_url() . "all/merchant-dashboard/" . generate_slug($merchant_name);
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id;
                    ?>
                    <div id='advertise-list-box'>
                        <div id="advertise-list-title1">
                            <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                        </div>
                        <div id="advertise-list-photo">
                            <div id="advertise-list-photo-box">
                                <a href='<?php echo $advertise_detail_url ?>'><img src='<?php echo base_url($this->album_merchant . $row['image']) ?>'></a>
                            </div>
                        </div>
                        <div id="advertise-list-title2">
                            <a href='<?php echo $advertise_detail_url ?>'><?php echo $row['title'] ?>&nbsp;</a>
                        </div>
                        <div id="advertise-list-info">
                            <table border="0" cellpadding="4px" cellspacing="0px">
                                <tr valign="top">
                                    <td>Category</td>
                                    <td>:</td>
                                    <td>
                                        <?php echo $this->m_custom->display_category($row['sub_category_id']) ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Like</td>
                                    <td>:</td>
                                    <td><?php echo $this->m_custom->generate_like_list_link($row['advertise_id'], 'adv'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <td>Comment</td>
                                    <td>:</td>
                                    <td><?php echo $this->m_custom->activity_comment_count($row['advertise_id'], 'adv'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <?php 
                        if($row['post_hour'] != 0)
                        { 
                            ?>
                            <div id="advertise-list-dynamic-time">
                                <i class="fa fa-clock-o"></i><span id="advertise-list-dynamic-time-label" data-countdown='<?php echo $row['end_time'] ?>'></span>
                            </div>
                            <?php 
                        }
                        ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <!--PROMOTION RESULT-->
        <div id="search-result-promotion">
            <div id="search-result-promotion-title">Redemption</div>
            <?php            
            if ($home_search_promotion != null)
            {
                foreach ($home_search_promotion as $row)
                {
                    $advertise_id = $row['advertise_id'];
                    $sub_category_id = $row['sub_category_id'];
                    $merchant_id = $row['merchant_id'];
                    $merchant_name = $this->m_custom->display_users($merchant_id);
                    $merchant_dashboard_url = base_url() . "all/merchant-dashboard/" . generate_slug($merchant_name);
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id;
                    ?>
                    <div id='advertise-list-box'>
                        <div id="advertise-list-title1">
                            <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                        </div>
                        <div id="advertise-list-photo">
                            <div id="advertise-list-photo-box">
                                <a href='<?php echo $advertise_detail_url ?>'><img src='<?php echo base_url($this->album_merchant . $row['image']) ?>'></a>
                            </div>
                        </div>
                        <div id="advertise-list-title2">
                            <a href='<?php echo $advertise_detail_url ?>'><?php echo $row['title'] ?>&nbsp;</a>
                        </div>
                        <div id="advertise-list-info">
                            <table border="0" cellpadding="4px" cellspacing="0px">
                                <tr valign="top">
                                    <td>Category</td>
                                    <td>:</td>
                                    <td>
                                        <?php echo $this->m_custom->display_category($row['sub_category_id']) ?>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <td>Like</td>
                                    <td>:</td>
                                    <td><?php echo $this->m_custom->generate_like_list_link($row['advertise_id'], 'adv'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <td>Comment</td>
                                    <td>:</td>
                                    <td><?php echo $this->m_custom->activity_comment_count($row['advertise_id'], 'adv'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div id="advertise-list-dynamic-time">
                            <i class="fa fa-bullseye"></i><span id="advertise-list-dynamic-time-label"><?php echo $row['voucher_candie'] ?> candies</span>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
    </div>
</div>