<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>

<?php
//CONFIG DATA
$this->album_merchant = $this->config->item('album_merchant');
$this->album_admin = $this->config->item('album_admin');

//URI
$fetch_method = $this->router->fetch_method();
$uri_segment_4 = $this->uri->segment(4);
?>

<div id="share-redemption-grid-list5">
    <?php        
    if ($fetch_method == 'advertise')
    {
        if ($uri_segment_4 == 'pro')
        {
            ?>
            <div id='share-redemption-grid-list5-header-orange'>
                <div id='share-redemption-grid-list5-header-table'>
                    <div id='share-redemption-grid-list5-header-table-row'>
                        <div id='share-redemption-grid-list5-header-table-row-cell'>
                            <div id="share-redemption-grid-list5-header-title"><?php echo $title ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    else
    {
        ?>
        <div id='share-redemption-grid-list5-header-white'>     
            <div id='share-redemption-grid-list5-header-table'>
                <div id='share-redemption-grid-list5-header-table-row'>
                    <div id='share-redemption-grid-list5-header-table-row-cell'>
                        <div id="share-redemption-grid-list5-header-title"><?php echo $title ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <div id="share-redemption-grid-list5-container">
        <?php
        foreach ($share_hotdeal_redemption_list as $row)
        {
            $advertise_id = $row['advertise_id'];
            $sub_category_id = $row['sub_category_id'];
            $merchant_id = $row['merchant_id'];
            $merchant_name = $this->m_custom->display_users($merchant_id);
            $merchant_dashboard_url = $this->m_custom->generate_merchant_link($merchant_id);
            $candie = $row['voucher_candie'];
            $advertise_type = $row['advertise_type'];
            $price_before_show = $row['price_before_show'];
            $price_before = $row['price_before'];
            $price_after_show = $row['price_after_show'];
            $price_after = $row['price_after'];
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
            <div class='share-redemption-grid-list5-box'>
                <a href='<?php echo $advertise_detail_url ?>'>
                    <div class="share-redemption-grid-list5-box-photo">
                        <div class="share-redemption-grid-list5-box-photo-box">
                            <img src='<?php echo $image_url ?>'>
                            <div class="share-redemption-row-list4-box-photo-box-redemption-count">
                                <div class="share-redemption-row-list4-box-photo-box-redemption-count-text">
                                    <?php echo $redeem_count ?><br/>Redemption
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="share-redemption-grid-list5-box-separator"></div>
                    <div class="share-redemption-grid-list5-box-information">
                        <div class="share-redemption-grid-list5-box-information-title">
                            <?php echo $row['title'] ?>
                        </div>
                        <div class="share-redemption-grid-list5-box-information-rating">
                            <div class="share-redemption-grid-list5-box-information-rating">
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
                        </div>
                        <div class="share-redemption-grid-list5-box-information-candie">
                            <?php echo $candie ?> Candies
                        </div>
                        <div class="share-redemption-grid-list5-box-information-price">
                            <div class="share-redemption-grid-list5-box-information-price-after">
                                <?php
                                if ($price_after)
                                {
                                    echo 'RM ' . $price_after;
                                }
                                ?>
                            </div>
                            <div class="share-redemption-grid-list5-box-information-price-before">
                                <?php
                                if ($price_before)
                                {
                                    echo 'RM ' . $price_before;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }
        //PAGINATION
        if (!empty($paging_links))
        {
            ?>
            <div id='share-redemption-grid-list5-pagination'>
                <?php echo $paging_links; ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>