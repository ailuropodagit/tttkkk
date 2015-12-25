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
?>

<div id="share-redemption-grid-list4">
    
    <?php    
    if ($fetch_method == 'hotdeal_list')
    {
        ?><div id="share-redemption-grid-list4-title-green"><?php echo $title ?></div><?php
    }
    else if ($fetch_method == 'promotion_list')
    {
        ?><div id="share-redemption-grid-list4-title-orange"><?php echo $title ?></div><?php
    }
    ?>
    
    <div id="share-redemption-grid-list4-container">
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
            $show_extra_info = $row['show_extra_info'];
            $price_before = $row['price_before'];
            $price_before_show = $row['price_before_show'];
            $price_after = $row['price_after'];
            $price_after_show = $row['price_after_show'];
            $voucher_worth = $row['voucher_worth'];
            $get_off_percent = $row['get_off_percent'];
            $how_many_buy = $row['how_many_buy'];
            $how_many_get = $row['how_many_get'];
            
            $average_rating = $this->m_custom->activity_rating_average($advertise_id, 'adv');
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
            <div class='share-redemption-grid-list4-box'>
                <a href='<?php echo $advertise_detail_url ?>'>
                    <div class="share-redemption-grid-list4-box-photo">
                        <div class="share-redemption-grid-list4-box-photo-box">
                            <img src='<?php echo $image_url ?>'>
                        </div>
                    </div>
                    <div class="share-redemption-grid-list4-box-separator"></div>
                    <div class="share-redemption-grid-list4-box-information">
                        <div class="share-redemption-grid-list4-box-information-title-rating">
                            <div class="share-redemption-grid-list4-box-information-title">
                                <?php echo $row['title'] ?>
                            </div>
                            <div class="share-redemption-grid-list4-box-information-rating">
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
                        <div class="share-redemption-grid-list4-box-information-candie">
                            <?php echo $candie ?> Candies
                        </div>
                        <?php
                        //PRICE
                        if($show_extra_info == 121)
                        {
                            ?>
                            <div class="share-redemption-grid-list4-box-information-price">
                                <div class="share-redemption-grid-list4-box-information-price-after">
                                    <?php
                                    if ($price_after_show == 1)
                                    {
                                        echo 'RM ' . $price_after;
                                    }
                                    ?>
                                </div>
                                <div class="share-redemption-grid-list4-box-information-price-before">
                                    <?php
                                    if ($price_before_show == 1)
                                    {
                                        echo 'RM ' . $price_before;
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }          
                        //VOUCHER WORTH
                        if ($show_extra_info == 122)
                        {
                            ?>
                            <div id="redemption-information-voucher-worth">
                                <?php echo "Worth RM " . $voucher_worth ?>
                            </div>
                            <?php
                        }
                        //GET OFF PERCENTAGE
                        if ($show_extra_info == 123)
                        {
                            ?>
                            <div id="redemption-information-voucher-worth">
                                <?php echo "Get off - " . $get_off_percent . "%" ?>
                            </div>
                            <?php
                        }
                        //BUY X GET X
                        if ($show_extra_info == 124)
                        {
                            ?>
                            <div id="redemption-information-voucher-worth">
                                <?php echo "Buy " . $how_many_buy . " Get " . $how_many_get ?>
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
            <div id='share-redemption-grid-list4-pagination'>
                <?php echo $paging_links; ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
