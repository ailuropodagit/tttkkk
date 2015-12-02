<?php
//CONFIG DATA
$this->album_merchant = $this->config->item('album_merchant');
$this->album_admin = $this->config->item('album_admin');

//URI
$fetch_method = $this->router->fetch_method();
?>

<div id="share-hot-deal-redemption-list">
    <div id="share-hot-deal-redemption-list-title"><?php echo $title ?></div>
    <div id="share-hot-deal-redemption-list-box">
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
            $price_after = $row['price_after'];
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
            <div class='share-hot-deal-redemption-list-box-each'>
                <a href='<?php echo $advertise_detail_url ?>'>
                    <div class="share-hot-deal-redemption-list-box-each-photo">
                        <div class="share-hot-deal-redemption-list-box-each-photo-box">
                            <img src='<?php echo $image_url ?>'>
                        </div>
                    </div>
                    <div class="share-hot-deal-redemption-list-box-each-separator"></div>
                    <div class="share-hot-deal-redemption-list-box-each-information">
                        <div class="share-hot-deal-redemption-list-box-each-information-title-rating">
                            <div class="share-hot-deal-redemption-list-box-each-information-title">
                                <?php echo $row['title'] ?>
                            </div>
                            <!--<div class="share-hot-deal-redemption-list-box-each-information-rating"></div>-->
                        </div>
                        
                        <?php
                        if ($advertise_type == 'hot')
                        {
                            ?>
                            <div class="share-hot-deal-redemption-list-box-each-information-price">
                                <div class="share-hot-deal-redemption-list-box-each-information-price-after">
                                    <?php
                                    if ($price_after)
                                    {
                                        echo 'RM ' . $price_after;
                                    }
                                    ?>
                                </div>
                                <div class="share-hot-deal-redemption-list-box-each-information-price-before">
                                    <?php
                                    if ($price_before)
                                    {
                                        echo 'RM ' . $price_before;
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        
                    </div>
                </a>
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
        ?>
    </div>
</div>