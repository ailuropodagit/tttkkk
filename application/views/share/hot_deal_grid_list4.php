<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<!--COUNTDOWN-->
<script type="text/javascript" src="<?php echo base_url('js/jquery.countdown.js') ?>"></script>
<script>
    $(function(){
        $('.share-hot-deal-grid-list4-box-timer-box-relative').each(function(){
            var _this = $(this);
            var end_date = $(this).attr('end_date');
            _this.countdown(end_date, function(event) {
                //$(this).html(event.strftime('%D days %H:%M:%S'));
                $(this).children().find('.share-hot-deal-grid-list4-box-timer-box1-time-day').html(event.strftime('%D'));
                $(this).children().find('.share-hot-deal-grid-list4-box-timer-box2-time-hour').html(event.strftime('%H'));
                $(this).children().find('.share-hot-deal-grid-list4-box-timer-box3-time-minute').html(event.strftime('%M'));
                $(this).children().find('.share-hot-deal-grid-list4-box-timer-box4-time-second').html(event.strftime('%S'));
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

<div id="share-hot-deal-grid-list4">
    <div id="share-hot-deal-grid-list4-header">
        <div id='share-hot-deal-row-list4-header-table'>
            <div id='share-hot-deal-row-list4-header-table-row'>
                <div id='share-hot-deal-row-list4-header-table-row-cell'>
                    <div id="share-hot-deal-grid-list4-header-title"><?php echo $title ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($fetch_method != 'redemption_list')
    {
        ?>
        <div id="share-hot-deal-grid-list4-sort">
            <?php echo form_open(uri_string()); ?>
                <span id="share-hot-deal-grid-list4-sort-label"><?php echo "Sort : "; ?></span>
                <span id="share-hot-deal-grid-list4-sort-dropdown"><?php echo form_dropdown($view_status_id, $view_status_list, $view_status_selected); ?></span>
                <span id="share-hot-deal-grid-list4-sort-dropdown"><?php echo form_dropdown($view_status_id2, $view_status_list2, $view_status_selected2); ?></span>
                <span id="share-hot-deal-grid-list4-sort-button"><button name="button_action" type="submit" value="filter_result">Go</button></span>
            <?php echo form_close() ?>
        </div>
        <?php
    } 
    ?>
    <div id="share-hot-deal-grid-list4-container">
        <?php
        foreach ($share_hotdeal_redemption_list as $row)
        {
            $advertise_id = $row['advertise_id'];
            $sub_category_id = $row['sub_category_id'];
            $merchant_id = $row['merchant_id'];
            $merchant_name = $this->m_custom->display_users($merchant_id);
            $merchant_dashboard_url = $this->m_custom->generate_merchant_link($merchant_id);
            $advertise_type = $row['advertise_type'];
            $price_before = $row['price_before'];
            $price_before_show = $row['price_before_show'];
            $price_after = $row['price_after'];
            $price_after_show = $row['price_after_show'];
            $end_time = $row['end_time'];
            $post_hour = $row['post_hour'];
            $candie = $row['voucher_candie'];
            $average_rating = $this->m_custom->activity_rating_average($advertise_id, 'adv');
            $redeem_count = $this->m_custom->promotion_redeem_count($advertise_id);
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
                if ($check_is_main_category)
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/hot/" . $check_is_main_category;
                }
                else
                {
                    $advertise_detail_url = base_url() . "all/advertise/" . $advertise_id . "/hot/" . $sub_category_id;
                }
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
            <div class='share-hot-deal-grid-list4-box'>
                <a href='<?php echo $advertise_detail_url ?>'>
                    <?php                
                    if ($post_hour != 0)
                    {
                        ?>
                        <div class="share-hot-deal-grid-list4-box-timer-box-absolute">
                            <div class="share-hot-deal-grid-list4-box-timer-box-relative" end_date="<?php echo $end_time ?>">
                                <div class="share-hot-deal-grid-list4-box-timer-box1">
                                    <div class="share-hot-deal-grid-list4-box-timer-box1-time-day">1</div>
                                    <div class="share-hot-deal-grid-list4-box-timer-box1-label">Days</div>
                                </div>
                                <div class="share-hot-deal-grid-list4-box-timer-box2">
                                    <div class="share-hot-deal-grid-list4-box-timer-box2-time-hour">2</div>
                                    <div class="share-hot-deal-grid-list4-box-timer-box2-label">Hours</div>
                                </div>
                                <div class="share-hot-deal-grid-list4-box-timer-box3">
                                    <div class="share-hot-deal-grid-list4-box-timer-box3-time-minute">3</div>
                                    <div class="share-hot-deal-grid-list4-box-timer-box3-label">Mins</div>
                                </div>
                                <div class="share-hot-deal-grid-list4-box-timer-box4">
                                    <div class="share-hot-deal-grid-list4-box-timer-box4-time-second">4</div>
                                    <div class="share-hot-deal-grid-list4-box-timer-box4-label">Secs</div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="share-hot-deal-grid-list4-box-photo">
                        <div class="share-hot-deal-grid-list4-box-photo-box">
                            <img src='<?php echo $image_url ?>'>
                        </div>
                    </div>
                    <div class="share-hot-deal-grid-list4-box-separator"></div>
                    <div class="share-hot-deal-grid-list4-box-information">
                        <div class="share-hot-deal-grid-list4-box-information-title">
                            <?php echo $row['title'] ?>
                        </div>
                        <div class="share-hot-deal-grid-list4-box-information-rating">
                            <?php
                            if ($fetch_method != 'redemption_list')
                            {
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
                            }
                            ?>
                            <div class="float-fix"></div>
                        </div>
                        <div class="share-hot-deal-grid-list4-box-information-price">
                            <div class="share-hot-deal-grid-list4-box-information-price-after">
                                <?php
                                if($price_after_show == 1)
                                {
                                    echo 'RM ' . $price_after;
                                }
                                ?>
                            </div>
                            <div class="share-hot-deal-grid-list4-box-information-price-before">
                                <?php
                                if($price_before_show == 1)
                                {
                                    echo 'RM ' . $price_before;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="float-fix"></div>
                    </div>
                </a>
            </div>
            <?php
        }
        //PAGINATION
        if (!empty($paging_links))
        {
            ?>
            <div id='share-hot-deal-grid-list4-pagination'>
                <?php echo $paging_links; ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
