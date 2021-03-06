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
$is_suggestion = $this->m_custom->check_is_suggestion_list($title);
?>

<div id="share-redemption-grid-list5">
    <div id='share-redemption-grid-list5-header'>
        <div id='share-redemption-grid-list5-header-table'>
            <div id='share-redemption-grid-list5-header-table-row'>
                <div id='share-redemption-grid-list5-header-table-row-cell'>
                    <div id="share-redemption-grid-list5-header-title"><?php echo $title ?></div>
                </div>
            </div>
        </div>
    </div>
    <div id="share-redemption-grid-list5-container">
        <?php     
        if ($share_hotdeal_redemption_list != null)
        {
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
                                <div class="share-redemption-grid-list5-box-photo-box-redemption-count">
                                    <div class="share-redemption-grid-list5-box-photo-box-redemption-count-text">
                                        <?php echo $redeem_count ?><br/>Redeemed
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
                                    $postfix = '';
                                    if($is_suggestion){
                                        $postfix = 'sgt';
                                    }
                                    if ($row['advertise_type'] != 'adm')
                                    {
                                        for ($i = 1; $i <= 5; $i++)
                                        {
                                            if ($i == round($average_rating))
                                            {
                                                echo "<input class='star' type='radio' name='a-rating$postfix-$advertise_id' disabled='disabled' value='" . $i . "' checked='checked'/>";
                                            }
                                            else
                                            {
                                                echo "<input class='star' type='radio' name='a-rating$postfix-$advertise_id' disabled='disabled' value='" . $i . "'/>";
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="float-fix"></div>
                                </div>
                            </div>
                            <?php
                            //EXTRA INFO
                            if($show_extra_info)
                            {
                                ?>
                                <div class="share-redemption-grid-list5-box-information-extra-info">
                                    <?php
                                    //PRICE
                                    if($show_extra_info == 121)
                                    {
                                        ?>
                                        <div class="share-redemption-grid-list5-box-information-extra-info-price">
                                            <div class="share-redemption-grid-list5-box-information-extra-info-price-after">
                                                <?php
                                                if ($price_after_show == 1)
                                                {
                                                    echo 'RM ' . $price_after;
                                                }
                                                ?>
                                            </div>
                                            <div class="share-redemption-grid-list5-box-information-extra-info-price-before">
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
                                        <div class="share-redemption-grid-list5-box-information-extra-info-general">
                                            <?php echo "Worth RM " . $voucher_worth ?>
                                        </div>
                                        <?php
                                    }
                                    //GET OFF PERCENTAGE
                                    if ($show_extra_info == 123)
                                    {
                                        ?>
                                        <div class="share-redemption-grid-list5-box-information-extra-info-general">
                                            <?php echo "Get off - " . $get_off_percent . "%" ?>
                                        </div>
                                        <?php
                                    }
                                    //BUY X GET X
                                    if ($show_extra_info == 125)
                                    {
                                        ?>
                                        <div class="share-redemption-grid-list5-box-information-extra-info-general">
                                            <?php echo "Buy " . $how_many_buy . " Get " . $how_many_get ?>
                                        </div>
                                        <?php
                                    } 
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="share-redemption-grid-list5-box-information-candie">
                                <div class="share-redemption-grid-list5-box-information-candie-label">
                                    <?php echo $candie ?> Candies
                                </div>
                                <div class="share-redemption-grid-list5-box-information-candie-icon">
                                    <img src="<?php echo base_url('image/candy.png') ?>">
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
                <div id='share-redemption-grid-list5-pagination'>
                    <?php echo $paging_links; ?>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div id="share-redemption-grid-list5-empty">
                No Redemption
            </div>
            <?php
        }
        ?>
    </div>
</div>