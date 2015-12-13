<script>
    $(function(){
        $('#share-row-list3-redemption-container').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            prevArrow: '#share-row-list3-redemption-prev',
            nextArrow: '#share-row-list3-redemption-next',
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

<div id="share-row-list3-redemption">
    <div id='share-row-list3-redemption-header'>
        <div id="share-row-list3-redemption-title">Redemption</div>
        <div id='share-row-list3-redemption-navigation'>
            <div id='share-row-list3-redemption-prev'> < </div>
            <div id='share-row-list3-redemption-prev-next-separator'></div>
            <div id='share-row-list3-redemption-next'> > </div>
        </div>
        <div id="float-fix"></div>
    </div>
    <div id="share-row-list3-redemption-container">
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
            if ($advertise_type == 'adm')
            {
                $image_url = $this->album_admim . $image;
            }
            else 
            {
                $image_url = $this->album_merchant . $image;
            }
            ?>
            <div class="share-row-list3-redemption-box">
                <a href='<?php echo base_url("all/advertise/$advertise_id") ?>'>
                    <div class="share-row-list3-redemption-box-photo-box">
                        <?php echo img($image_url) ?>
                    </div>
                    <div class="share-row-list3-redemption-box-separator"></div>
                    <div class="share-row-list3-redemption-box-information">
                        <div class="share-row-list3-redemption-box-information-title">
                            <?php echo $title ?>
                        </div>
                        <div class="share-row-list3-redemption-box-information-rating">
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
                        <div class="share-row-list3-redemption-box-information-candie">
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