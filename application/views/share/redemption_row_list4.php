<script>
    $(function(){
        $('#share-redemption-row-list4-container').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            prevArrow: '#share-redemption-row-list4-header-navigation-prev',
            nextArrow: '#share-redemption-row-list4-header-navigation-next',
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 700,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>

<div id="share-redemption-row-list4">
    <div id='share-redemption-row-list4-header'>
        <div id='share-redemption-row-list4-header-table'>
            <div id='share-redemption-row-list4-header-table-row'>
                <div id='share-redemption-row-list4-header-table-row-cell'>
                    <div id="share-redemption-row-list4-header-title">Redemption</div>
                </div>
                <div id='share-redemption-row-list4-header-table-row-cell'>
                    <div id='share-redemption-row-list4-header-navigation'>
                        <div id='share-redemption-row-list4-header-navigation-prev'><</div>
                        <div id='share-redemption-row-list4-header-navigation-next'>></div>
                        <div class="float-fix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="share-redemption-row-list4-container">
        <?php 
        $redemption_list = $this->m_custom->getAdvertise('pro', NULL, NULL, 0, NULL, NULL, 1);
        foreach ($redemption_list as $redemption)
        {
            $advertise_type = $redemption['advertise_type'];
            $advertise_id = $redemption['advertise_id'];
            $sub_category_id = $redemption['sub_category_id'];
            $merchant_id = $redemption['merchant_id'];
            $image = $redemption['image'];
            $title = $redemption['title'];
            $show_extra_info = $redemption['show_extra_info'];
            $price_before_show = $redemption['price_before_show'];
            $price_before = $redemption['price_before'];
            $price_after_show = $redemption['price_after_show'];
            $price_after = $redemption['price_after'];
            $voucher_worth = $redemption['voucher_worth'];
            $get_off_percent = $redemption['get_off_percent'];
            $how_many_buy = $redemption['how_many_buy'];
            $how_many_get = $redemption['how_many_get'];
            $candie = $redemption['voucher_candie'];
            $end_time = $redemption['end_time'];
            $average_rating = $this->m_custom->activity_rating_average($advertise_id, 'adv');
            $redeem_count = $this->m_custom->promotion_redeem_count($advertise_id);
            if ($advertise_type == 'adm')
            {
                $image_url = $this->album_admim . $image;
            }
            else 
            {
                $image_url = $this->album_merchant . $image;
            }
            ?>
            <div class="share-redemption-row-list4-box">
                <a href='<?php echo base_url("all/advertise/$advertise_id") ?>'>             
                    <div class="share-redemption-row-list4-box-photo-box">
                        <?php echo img($image_url) ?>
                        <div class="share-redemption-row-list4-box-photo-box-redemption-count">
                            <div class="share-redemption-row-list4-box-photo-box-redemption-count-text">
                                <?php echo $redeem_count ?><br/>Redeem
                            </div>
                        </div>
                    </div>
                    <div class="share-redemption-row-list4-box-separator"></div>
                    <div class="share-redemption-row-list4-box-information">
                        <div class="share-redemption-row-list4-box-information-title">
                            <?php echo $title ?>
                        </div>
                        <div class="share-redemption-row-list4-box-information-rating">
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
                        <div class="share-redemption-row-list4-box-information-candie">
                            <?php echo $candie ?> Candy
                        </div>
                        <?php
                        //EXTRA INFO
                        if($show_extra_info)
                        {
                            ?>
                            <div class="share-redemption-grid-list4-box-information-extra-info">
                                <?php
                                //PRICE
                                if($show_extra_info == 121)
                                {
                                    ?>
                                    <div class="share-redemption-grid-list4-box-information-extra-info-price">
                                        <div class="share-redemption-grid-list4-box-information-extra-info-price-after">
                                            <?php
                                            if ($price_after_show == 1)
                                            {
                                                echo 'RM ' . $price_after;
                                            }
                                            ?>
                                        </div>
                                        <div class="share-redemption-grid-list4-box-information-extra-info-price-before">
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
                                    <div class="share-redemption-grid-list4-box-information-extra-info-general">
                                        <?php echo "Worth RM " . $voucher_worth ?>
                                    </div>
                                    <?php
                                }
                                //GET OFF PERCENTAGE
                                if ($show_extra_info == 123)
                                {
                                    ?>
                                    <div class="share-redemption-grid-list4-box-information-extra-info-general">
                                        <?php echo "Get off - " . $get_off_percent . "%" ?>
                                    </div>
                                    <?php
                                }
                                //BUY X GET X
                                if ($show_extra_info == 124)
                                {
                                    ?>
                                    <div class="share-redemption-grid-list4-box-information-extra-info-general">
                                        <?php echo "Buy " . $how_many_buy . " Get " . $how_many_get ?>
                                    </div>
                                    <?php
                                } 
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>
</div>