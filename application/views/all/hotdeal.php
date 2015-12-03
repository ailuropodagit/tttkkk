<!--RATING-->
<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<!--JGROWL-->
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<!--COUNTDOWN-->
<script type="text/javascript" src="<?php echo base_url('js/jquery.countdown.js') ?>"></script>
<!--SCRIPT-->
<script type="text/javascript">
    //FB SHARE
//    function fbShare() {
//        FB.ui({
//            method: 'share',
//            href: 'http://keppo.my/keppo/all/advertise/56/hot/26',           
//            picture: 'http://keppo.my/keppo/folder_upload/album_merchant/KFC13.jpg',
//            title: 'title here',
//            description: "description here"
//        }, function(response){
//        
//        });
//    }
    
    $(function(){
        var end_time = $('#hot-deal-information-countdown-time').attr('end_time');
        $('#hot-deal-information-countdown-time').countdown(end_time, function(event) {
            $(this).html(event.strftime('%D Days &nbsp; %H Hours &nbsp; %M Minutes &nbsp; %S Seconds'));
        });
    });
</script>

<div id='hot-deal'>
    <h1>Hot Deal</h1>
    <div id='hot-deal-content'>
        <div id="hot-deal-category">
            Category: <?php echo $sub_category; ?>
        </div>
        <div id="hot-deal-edit-link">
            <?php
            if (check_is_login())
            {
                $merchant_id = $this->ion_auth->user()->row()->id;
                if (check_correct_login_type($this->config->item('group_id_supervisor')))
                {
                    $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
                }
                $allowed_list = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id');
                if (check_correct_login_type($this->config->item('group_id_merchant'), $allowed_list, $advertise_id) || check_correct_login_type($this->config->item('group_id_supervisor'), $allowed_list, $advertise_id))
                {
                    ?>
                    <a href='<?php echo base_url() . "merchant/edit_hotdeal/" . $advertise_id ?>' >
                        <div id="redemption-edit-link-icon"><i class="fa fa-pencil"></i></div>
                        <div id="redemption-edit-link-label">Edit Hot Deal</div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
        <div class="float-fix"></div>
        <div id='hot-deal-photo'>
            <div id='hot-deal-table'>
                <div id='hot-deal-table-row'>
                    <div id='hot-deal-table-row-cell' class='hot-deal-left-cell'>
                        <div id='hot-deal-left'>
                            <?php
                            if (!empty($previous_url))
                            {
                                ?><a href="<?php echo $previous_url ?>"><i class="fa fa-angle-double-left"></i></a><?php
                            }
                            else 
                            {
                                ?><div id='hot-deal-left-gray'><i class="fa fa-angle-double-left"></i></div><?php
                            }
                            ?>
                        </div>
                    </div>
                    <div id='hot-deal-table-row-cell' class="hot-deal-center-cell">
                        <div id='hot-deal-center'>
                            <div id='hot-deal-photo-box'>
                                <img src='<?php echo $image_url ?>'>
                            </div>      
                        </div>      
                    </div>
                    <div id='hot-deal-table-row-cell' class='hot-deal-right-cell'>
                        <div id='hot-deal-right'>
                            <?php
                            if (!empty($next_url))
                            {
                                ?><a href="<?php echo $next_url ?>"><i class="fa fa-angle-double-right"></i></a><?php
                            }
                            else 
                            {
                                ?><div id='hot-deal-right-gray'><i class="fa fa-angle-double-right"></i></div><?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div id='hot-deal-information'>
            <!--TITLE-->
            <div id="hot-deal-information-title">
                <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
            </div>
            <!--SUB TITLE-->
            <div id="hot-deal-information-sub-title">
                <?php echo $title ?>
            </div>
            <!--RATE-->
            <div id="hot-deal-information-rate">
                <div id="hot-deal-information-rate-star">
                    <?php                                    
                    echo form_input($item_id);
                    echo form_input($item_type);
                    for ($i = 1; $i <= 5; $i++)
                    {
                        if ($i == round($average_rating))
                        {
                            echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "' checked='checked'/>";
                        }
                        else
                        {
                            echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "'/>";
                        }
                    }
                    ?>
                </div>
                <div id="hot-deal-information-rate-review">
                    <?php
                    $rating_count = $this->m_custom->activity_rating_count($advertise_id, 'adv');
                    echo $rating_count . ' Review(s)';
                    ?>
                </div>
                <div id="hot-deal-information-rate-earn-candie">
                    <?php
                    $rate_candie_earn = $this->m_custom->display_trans_config(3);
                    echo "Earn : " . $rate_candie_earn . " candies";
                    ?>
                </div>    
            </div>
            <?php
            //PRICE
            if($price_before_show == 1 || $price_after_show == 1)
            {
                ?>
                <div id='hot-deal-information-price'>
                    <div id='hot-deal-information-price-after'>
                        <?php
                        if($price_before_show == 1)
                        {
                            echo 'RM ' . $price_before;
                        }
                        ?>
                    </div>
                    <div id='hot-deal-information-price-before'>
                        <?php
                        if($price_after_show == 1)
                        {
                            echo 'RM ' . $price_after;
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
            //DESCRIPTION
            if ($description)
            {
                ?>
                <div id="hot-deal-information-description">
                    <?php echo $description ?>
                </div>
                <?php
            }
            ?>
            <!--LIKE COMMENT-->
            <div id="hot-deal-information-like-comment">
                <div id="hot-deal-information-like">
                    <?php echo $like_url; ?>
                </div>
                <div id="hot-deal-information-comment">
                    <?php echo $comment_url; ?>
                </div>
                <div id="hot-deal-information-like-comment-earn-candie">
                    <?php
                    $like_comment_candie_earn = $this->m_custom->display_trans_config(2);
                    echo "Earn : " . $like_comment_candie_earn . " candies"; 
                    ?>
                </div>
            </div>
            <?php
            //COUNTDOWN
            if ($post_hour != 0)
            { 
                ?>
                <div id="hot-deal-information-countdown">
                     <div id="hot-deal-information-countdown-icon"><i class="fa fa-clock-o"></i></div>
                    <div id="hot-deal-information-countdown-time" end_time="<?php echo $end_time ?>"></div>
                </div>
                <?php
            }
            ?>
            <div id="hot-deal-information-horizontal-separator"></div>
            <!--SHARE-->
            <div id="hot-deal-information-share">
                <div id="hot-deal-information-share-label">
                    Share This Deal
                </div>
                <div id="hot-deal-information-share-facebook" onclick="fbShare()">
                    <img src='http://localhost/keppo/image/social-media-facebook-share.png'>
                </div>
                <div id="hot-deal-information-share-earn-candie">
                    <?php echo " (Earn : " . $this->m_custom->display_trans_config(10) . " candies)"; ?>
                </div>
            </div>
            <!--PEOPLE REACH-->
            <div id="hot-deal-information-people-reach">
                <?php echo "People Reached " . $this->m_custom->activity_view_count($advertise_id) . " users"; ?>
            </div>
        </div>
        <div class="float-fix"></div>
        <!--HOT DEAL TAB BOX-->
        <div id="hot-deal-tab-box">
            <div id="hot-deal-tab-box-title">User Comment</div>
            <div id="hot-deal-tab-box-user-comment">
                <?php $this->load->view('all/comment_form') ?>
            </div>
        </div>
    </div>
</div>