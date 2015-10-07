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

<div id="profile">
    <h1>Search Result<?php echo $state_name; ?></h1></br>
    <div id='profile-content'>     
        <?php
//var_dump($home_search_merchant);
//var_dump($home_search_hotdeal);
//var_dump($home_search_promotion);

        echo "<h2>----- Merchant -----</h2></br>";
        if ($home_search_merchant != null)
        {

            foreach ($home_search_merchant as $row)
            {
                $merchant_id = $row['id'];
                $merchant_name = $row['company'];
                $merchant_dashboard_url = $row['merchant_dashboard_url'];
                $average_rating = $this->m_custom->merchant_rating_average($merchant_id, 'adv');
                $rating_count = $this->m_custom->merchant_rating_average($merchant_id, 'adv', 1);
                ?>
                <div id='advertise-list-box'>

                    <div id="advertise-list-title1">
                        <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                    </div>

                    <div id="advertise-list-photo">
                        <div id="advertise-list-photo-box">
                            <a href='<?php echo $merchant_dashboard_url ?>'><img src='<?php echo base_url($this->config->item('album_merchant_profile') . $row['profile_image']) ?>'></a>
                        </div>
                    </div>
                    <div id="advertise-list-info">
                        <table border="0" cellpadding="4px" cellspacing="0px">
                            <tr>
                                <td>Category</td>
                                <td>:</td>
                                <td>
                                    <div id="advertise-list-info-category"><?php echo $row['me_category_name'] ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Like</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->merchant_like_count($merchant_id, 'adv'); ?></td>
                            </tr>
                            <tr>
                                <td>Comment</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->merchant_comment_count($merchant_id, 'adv'); ?></td>
                            </tr>
                            <tr>
                                <td>Picture</td>
                                <td>:</td>
                                <td><a href='<?php echo $merchant_dashboard_url."/picture"; ?>'><?php echo $this->m_custom->merchant_picture_count($merchant_id, 1); ?></a></td>
                            </tr>
                            <tr>
                                <td>Average Rating</td>
                                <td>:</td>
                                <td>
                                    <div id="form-rate2">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++)
                                        {
                                            if ($i == round($average_rating))
                                            {
                                                echo "<input class='star' type='radio' name='a-rating-$merchant_id' disabled='disabled' value='" . $i . "' checked='checked'/>";
                                            }
                                            else
                                            {
                                                echo "<input class='star' type='radio' name='a-rating-$merchant_id' disabled='disabled' value='" . $i . "'/>";
                                            }
                                        } //end of for
                                        echo $rating_count . " reviews";
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Share</td>
                                <td>:</td>
                                <td>
                                    <span id="hot-deal-share-facebook">
                                        <a href="<?php echo $row['me_facebook_url']; ?>" target="_blank"><i class="fa fa-facebook-square"></i></a>
                                    </span>
                                    <span id="hot-deal-share-instagram">
                                        <a href="https://instagram.com" target="_blank"><i class="fa fa-instagram"></i></a>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php
            }
        }
        echo "<div id='float-fix'></div></br></br>";
        echo "<h2>----- Hot Deal -----</h2></br>";
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
                        <a href='<?php echo $advertise_detail_url ?>'><?php echo $row['title'] ?></a>
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
                                <td><?php echo $this->m_custom->generate_like_list_link($row['advertise_id'], 'adv'); ?></td>
                            </tr>
                            <tr>
                                <td>Comment</td>
                                <td>:</td>
                                <td><?php echo $this->m_custom->activity_comment_count($row['advertise_id'], 'adv'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <?php if($row['post_hour']!=0) { ?>
                    <div id="advertise-list-dynamic-time">
                        <i class="fa fa-clock-o"></i><span id="advertise-list-dynamic-time-label" data-countdown='<?php echo $row['end_time'] ?>'></span>
                    </div>
                    <?php } ?>
                </div>
                <?php
            }
        }

        echo "<div id='float-fix'></div></br></br>";
        echo "<h2>----- Promotion -----</h2></br>";
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
                        <a href='<?php echo $advertise_detail_url ?>'><?php echo $row['title'] ?></a>
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
                                <td><?php echo $this->m_custom->generate_like_list_link($row['advertise_id'], 'adv'); ?></td>
                            </tr>
                            <tr>
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