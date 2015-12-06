<script>
    $(function(){
        $('#share-row-list3-hot-deal-container').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            prevArrow: '#share-row-list3-hot-deal-prev',
            nextArrow: '#share-row-list3-hot-deal-next',
            responsive: [
                {
                    breakpoint: 1200,
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
        
        $('.share-row-list3-hot-deal-box-timer-box-relative').each(function(){
            var _this = $(this);
            var end_date = $(this).attr('end_date');
            _this.countdown(end_date, function(event) {
                //$(this).html(event.strftime('%D days %H:%M:%S'));
                $(this).children().find('.share-row-list3-hot-deal-box-timer-box1-time-day').html(event.strftime('%D'));
                $(this).children().find('.share-row-list3-hot-deal-box-timer-box2-time-hour').html(event.strftime('%H'));
                $(this).children().find('.share-row-list3-hot-deal-box-timer-box3-time-minute').html(event.strftime('%M'));
                $(this).children().find('.share-row-list3-hot-deal-box-timer-box4-time-second').html(event.strftime('%S'));
            });
        });
    });
</script>

<div id='share-row-list3-hot-deal-title'>Today's Deals</div>
<div id='share-row-list3-hot-deal-navigation'>
    <div id='share-row-list3-hot-deal-prev'> < </div>
    <div id='share-row-list3-hot-deal-prev-next-separator'></div>
    <div id='share-row-list3-hot-deal-next'> > </div>
</div>
<div id='float-fix'></div>
<div id='share-row-list3-hot-deal-title-bottom-line'></div>

<div id="share-row-list3-hot-deal-container">
    <?php
    $hotdeal_list = $this->m_custom->getAdvertise('hot', NULL, NULL, 0, NULL, NULL, 1);
    foreach ($hotdeal_list as $hotdeal)
    {
        $advertise_type = $hotdeal['advertise_type'];
        $advertise_id = $hotdeal['advertise_id'];
        $sub_category_id = $hotdeal['sub_category_id'];
        $merchant_id = $hotdeal['merchant_id'];
        $image = $hotdeal['image'];
        $title = $hotdeal['title'];
        $price_before_show = $hotdeal['price_before_show'];
        $price_before = $hotdeal['price_before'];
        $price_after_show = $hotdeal['price_after_show'];
        $price_after = $hotdeal['price_after'];
        $end_time = $hotdeal['end_time']; 
        $post_hour = $hotdeal['post_hour'];
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
        <div class="share-row-list3-hot-deal-box">
            <a href='<?php echo base_url("all/advertise/$advertise_id") ?>'>
                <?php                
                if ($post_hour != 0)
                { 
                    ?>
                    <div class="share-row-list3-hot-deal-box-timer-box-absolute">
                        <div class="share-row-list3-hot-deal-box-timer-box-relative" end_date="<?php echo $end_time ?>">
                            <div class="share-row-list3-hot-deal-box-timer-box1">
                                <div class="share-row-list3-hot-deal-box-timer-box1-time-day">1</div>
                                <div class="share-row-list3-hot-deal-box-timer-box1-label">Days</div>
                            </div>
                            <div class="share-row-list3-hot-deal-box-timer-box2">
                                <div class="share-row-list3-hot-deal-box-timer-box2-time-hour">2</div>
                                <div class="share-row-list3-hot-deal-box-timer-box2-label">Hours</div>
                            </div>
                            <div class="share-row-list3-hot-deal-box-timer-box3">
                                <div class="share-row-list3-hot-deal-box-timer-box3-time-minute">3</div>
                                <div class="share-row-list3-hot-deal-box-timer-box3-label">Mins</div>
                            </div>
                            <div class="share-row-list3-hot-deal-box-timer-box4">
                                <div class="share-row-list3-hot-deal-box-timer-box4-time-second">4</div>
                                <div class="share-row-list3-hot-deal-box-timer-box4-label">Secs</div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="share-row-list3-hot-deal-box-photo-box">
                    <?php echo img($image_url) ?>
                </div>
                <div class="share-row-list3-hot-deal-box-separator"></div>
                <div class="share-row-list3-hot-deal-box-information">
                    <div class="share-row-list3-hot-deal-box-information-title-rating">
                        <div class="share-row-list3-hot-deal-box-information-title">
                            <?php echo $title ?>
                        </div>
                        <div class="share-row-list3-hot-deal-box-information-rating">
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
                    <div class="share-row-list3-hot-deal-box-information-price">
                        <div class="share-row-list3-hot-deal-box-information-price-after">
                            <?php
                            if ($price_after_show)
                            {
                                echo 'RM ' . $price_after;
                            }
                            ?>
                        </div>
                        <div class="share-row-list3-hot-deal-box-information-price-before">
                            <?php 
                            if ($price_before_show) 
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
    ?>
</div>