<!--RATING-->
<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<!--JGROWL-->
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<!--COUNTDOWN-->
<script type="text/javascript" src="<?php echo base_url('js/jquery.countdown.js') ?>"></script>
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>

<!--SCRIPT-->
<script type="text/javascript">
    $(function(){
        var end_date = $('#hot-deal-information-countdown-time').attr('end_date');        
        $('#hot-deal-information-countdown-time').countdown(end_date, function(event) {
            $(this).html(event.strftime('\
                <div id="hot-deal-information-countdown-time-day"><span id="hot-deal-information-countdown-time-day-text">%D</span></div>\n\
                <div id="hot-deal-information-countdown-time-hour"><span id="hot-deal-information-countdown-time-hour-text">%H</span></div>\n\
                <div id="hot-deal-information-countdown-time-month"><span id="hot-deal-information-countdown-time-month-text">%M</span></div>\n\
                <div id="hot-deal-information-countdown-time-second"><span id="hot-deal-information-countdown-time-second-text">%S</span></div>\n\
            '));
        });        
    });
    
    //FB SHARE
    FB.init({
         appId  : '<?php echo fb_appID(); ?>',
         status : true, // check login status
         cookie : true, // enable cookies to allow the server to access the session
         xfbml  : true  // parse XFBML
       });

    function fbShare(){    
        
        var the_id = '<?php echo $advertise_id; ?>';
        var post_url = '<?php echo base_url(); ?>' + 'all/fb_share';
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: "json",
            data: "&advertise_id=" + the_id + "&advertise_type=adv",
        });
        
        FB.ui({ 
            method: 'feed', 
            link: '<?php echo base_url() . uri_string(); ?>',
            caption: 'KEPPO.MY',
            picture: '<?php echo $image_url; ?>',
            name: '<?php echo $merchant_name; ?>',
            description: '<?php echo limit_character($description, 150, 1); ?>'
        });
    }          
</script>

<div id='hot-deal'>
    <div id="fb-root"></div>
    <div id="hot-deal-header">
        <div id="hot-deal-header-title">
            Hot Deal
        </div>
        <div id="hot-deal-header-edit-link">
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
                    <a href='<?php echo base_url() . "merchant/edit_hotdeal/" . $advertise_id ?>' class="a-href-button">Edit Hot Deal</a>
                    <?php
                }
            }
            ?>
        </div>
        <div class="float-fix"></div>
        <div id="hot-deal-header-title-bottom-line"></div>
    </div>
    <div id='hot-deal-content'>
        <div id="hot-deal-category">
            Category: <?php echo $sub_category; ?>
        </div>
        <div id='hot-deal-photo'>
            <div id='hot-deal-table'>
                <div id='hot-deal-table-row'>
                    <div id='hot-deal-table-row-cell' class='hot-deal-left-cell'>
                        <div id='hot-deal-left'>
                            <?php
                            $hide_next_previous = $this->uri->segment(8);
                            if ($hide_next_previous != 1)
                            {
                                if (!empty($previous_url))
                                {
                                    ?><a href="<?php echo $previous_url ?>"><i class="fa fa-angle-double-left"></i></a><?php
                                }
                                else 
                                {
                                    ?><div id='hot-deal-left-gray'><i class="fa fa-angle-double-left"></i></div><?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div id='hot-deal-table-row-cell' class="hot-deal-center-cell">
                        <div id='hot-deal-center'>
                            <div id='hot-deal-photo-box' class="zoom-image">
                                <img src='<?php echo $image_url ?>' >                               
                            </div>      
                        </div>      
                    </div>
                    <div id='hot-deal-table-row-cell' class='hot-deal-right-cell'>
                        <div id='hot-deal-right'>
                            <?php
                            if ($hide_next_previous != 1)
                            {
                                if (!empty($next_url))
                                {
                                    ?><a href="<?php echo $next_url ?>"><i class="fa fa-angle-double-right"></i></a><?php
                                }
                                else 
                                {
                                    ?><div id='hot-deal-right-gray'><i class="fa fa-angle-double-right"></i></div><?php
                                }
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
            <!--SHOW ME ADDRESS-->
            <div id="hot-deal-information-show-me-address">
                <?php
                $merchant_info = $this->m_merchant->getMerchant(0, NULL, $merchant_name);
                $merchant_info_id = $merchant_info['id'];
                $show_outlet = base_url() . 'all/merchant_outlet/' . generate_slug($merchant_name) . '/' . $merchant_info_id . '#outlet';
                ?>
                <a href="<?php echo $show_outlet ?>" target="_blank">
                    <img src="<?php echo base_url() . "/image/icon-map.png"; ?>"/>Show me Address
                </a>
            </div>
            <div class="float-fix"></div>
            <?php
            //COUNTDOWN
            if ($post_hour != 0)
            { 
                ?>
                <div id="hot-deal-information-countdown">
                    <!--<div id="hot-deal-information-countdown-icon"><i class="fa fa-clock-o"></i></div>-->
                    <div id="hot-deal-information-countdown-time" end_date="<?php echo $end_time ?>"></div>
                    <div class="float-fix"></div>
                </div>
                <?php
            }
            ?>
            <!--SUB TITLE-->
            <div id="hot-deal-information-sub-title">
                <?php echo $sub_title ?>
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
//                    $rate_candie_earn = $this->m_custom->display_trans_config(3);
//                    echo "Earn : " . $rate_candie_earn . " candies";
                    ?>
                </div>    
            </div>
            <?php
            //PRICE
            if($price_before_show == 1 || $price_after_show == 1)
            {
                ?>
                <div id="hot-deal-information-extra-info">
                    <div id='hot-deal-information-extra-info-price'>
                        <div id='hot-deal-information-extra-info-price-after'>
                            <?php
                            if($price_after_show == 1)
                            {
                                echo 'RM ' . $price_after;
                            }        
                            ?>
                        </div>
                        <div id='hot-deal-information-extra-info-price-before'>
                            <?php
                            if($price_before_show == 1)
                            {
                                echo 'RM ' . $price_before;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            //DESCRIPTION
            if ($description)
            {
                ?>
                <div id="hot-deal-information-description">
                    <?php echo nl2br($description) ?>
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
//                    $like_comment_candie_earn = $this->m_custom->display_trans_config(2);
//                    echo "Earn : " . $like_comment_candie_earn . " candies"; 
                    ?>
                    CLICK BY EARN CANDIES
                </div>
            </div>
            <div id="hot-deal-information-horizontal-separator"></div>
            <!--SHARE-->
            <div id="hot-deal-information-share">
                <div id="hot-deal-information-share-label">
                    Share This Deal
                </div>
                <div id="hot-deal-information-share-facebook" onclick="fbShare(); return false;">
                    <img src="<?php echo base_url() . 'image/social-media-facebook-share.png'; ?>" >
                </div>
                <div id="hot-deal-information-share-earn-candie">
                    <?php //echo "Earn : " . $this->m_custom->display_trans_config(10) . " candies"; ?>
                </div>
            </div>
            <!--PEOPLE REACH-->
            <div id="hot-deal-information-people-reach">
                <?php echo "People Reached " . $this->m_custom->activity_view_count($advertise_id) . " users"; ?>
            </div>
            <!--TAB BOX-->
            <div id="hot-deal-information-tab-box">
                <div id="hot-deal-information-tab-box-title">User Comment</div>
                <div id="hot-deal-information-tab-box-user-comment">
                    <?php $this->load->view('all/comment_form') ?>
                </div>
            </div>
        </div>
        <div class="float-fix"></div>
    </div>
</div>

<div id="hot-deal-suggestion">
    <?php
    if (!empty($advertise_suggestion_list))
    {     
        $data['share_hotdeal_redemption_list'] = $advertise_suggestion_list;
        $data['title'] = "Hot Deal Suggestion";
        $this->load->view('share/hot_deal_suggestion_list5', $data);
    }
    else
    {
        ?><br/><br/><br/><?php
    }
    ?>
</div>