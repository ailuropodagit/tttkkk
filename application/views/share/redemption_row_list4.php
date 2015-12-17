<script>
    $(function(){
        $('#share-redemption-row-list4-container').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            prevArrow: '#share-redemption-row-list4-prev',
            nextArrow: '#share-redemption-row-list4-next',
            responsive: [
                {
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 900,
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
        <div id="share-redemption-row-list4-title">Redemption</div>
        <div id='share-redemption-row-list4-navigation'>
            <div id='share-redemption-row-list4-prev'> < </div>
            <div id='share-redemption-row-list4-next'> > </div>
        </div>
        <div id="float-fix"></div>
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
                            <?php echo $candie ?> Candies
                        </div>
                        <div class="float-fix"></div>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>
</div>