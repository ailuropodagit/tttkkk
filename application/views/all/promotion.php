<!--RATING-->
<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<!--JGROWL-->
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>

<script type="text/javascript">
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
            method : 'feed', 
            link   :  '<?php echo base_url() . uri_string(); ?>',
            caption:  'KEPPO.MY',
            picture: '<?php echo $image_url; ?>',
            name:'<?php echo $merchant_name; ?>',
            description: '<?php echo limit_character($description, 150, 1); ?>'
       });     
    }    

</script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message ?></div><?php
}
?>

<div id="redemption">
    <div id="fb-root"></div>
    <div id="redemption-header">
        <div id="redemption-header-title">
            Redemption
        </div>
        <div id="redemption-header-edit-link">
            <?php
            if (check_is_login())
            {
                $merchant_id = $this->ion_auth->user()->row()->id;
                $allowed_list = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id');
                if (check_correct_login_type($this->config->item('group_id_merchant'), $allowed_list, $advertise_id))
                {
                    ?>
                    <a href='<?php echo base_url() . "merchant/candie_promotion/" . $advertise_id ?>' class="a-href-button">Edit Redemption</a>
                    <?php
                }
            }
            ?>
        </div>
        <div class="float-fix"></div>
        <div id="redemption-header-title-bottom-line"></div>
    </div>
    <div id="redemption-content">
        <div id="redemption-category">
            Category: <?php echo $sub_category; ?>
        </div>
        <div id="redemption-photo">
            <div id='redemption-table'>
                <div id='redemption-table-row'>
                    <div id='redemption-table-row-cell' class='redemption-left-cell'>
                        <div id='redemption-left'>
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
                                    ?><div id='redemption-left-gray'><i class="fa fa-angle-double-left"></i></div><?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div id='redemption-table-row-cell' class='redemption-center-cell'>
                        <div id='redemption-center'>
                            <div id="redemption-photo-box" class="zoom-image">
                                <img src='<?php echo $image_url ?>'>
                            </div>
                        </div>
                    </div>
                    <div id='redemption-table-row-cell' class='redemption-right-cell'>
                        <div id='redemption-right'>
                            <?php
                            if ($hide_next_previous != 1)
                            {
                                if (!empty($next_url))
                                {
                                    ?><a href="<?php echo $next_url ?>"><i class="fa fa-angle-double-right"></i></a><?php
                                }
                                else 
                                {
                                    ?><div id='redemption-right-gray'><i class="fa fa-angle-double-right"></i></div><?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="redemption-information">
            <!--TITLE-->
            <div id="redemption-information-title">
                <a href='<?php echo $merchant_dashboard_url ?>'><?php echo $merchant_name ?></a>
            </div>
            <!--SHOW ME ADDRESS-->
            <div id="redemption-information-show-me-address">
                <?php
                $merchant_info = $this->m_merchant->getMerchant(0, NULL, $merchant_name);
                $merchant_info_id = $merchant_info['id'];
                $show_outlet = base_url() . 'all/merchant_outlet/' . generate_slug($merchant_name) . '/' . $merchant_info_id . '#outlet';
                ?>
                <a href="<?php echo $show_outlet ?>" target="_blank">
                    <img src="<?php echo base_url() . "/image/icon-map.png"; ?>"/> 
                    Show me Address
                </a>
            </div>
            <div class="float-fix"></div>
            <!--REQUIRED CANDIES-->
            <div id="redemption-information-required-candies">
                Require <?php echo $voucher_candie ?> Candies
            </div>
            <!--SUB TITLE-->
            <div id="redemption-information-sub-title">
                <?php echo $sub_title ?>
            </div>
            <!--RATE-->
            <div id="redemption-information-rate">
                <div id="redemption-information-rate-star">
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
                <div id="redemption-information-rate-review">
                    <?php
                    $rating_count = $this->m_custom->activity_rating_count($advertise_id, 'adv');
                    echo "Reviews : ". $rating_count;
                    ?>
                </div>
                <div id="redemption-information-rate-earn-candie">
                    <?php
//                    $rate_candie_earn = $this->m_custom->display_trans_config(3);
//                    echo "Earn : " . $rate_candie_earn . " candies";
                    ?>
                </div>
            </div>
            <?php
            //EXTRA INFO
            if($show_extra_info)
            {
                ?>
                <div id='redemption-information-extra-info'>
                    <?php
                    //PRICE
                    if($show_extra_info == 121)
                    {
                        ?>
                        <div id='redemption-information-extra-info-price'>
                            <div id='redemption-information-extra-info-price-after'>
                                <?php
                                if($price_after_show == 1)
                                {
                                    echo 'RM ' . $price_after;
                                }
                                ?>
                            </div>
                            <div id='redemption-information-extra-info-price-before'>
                                <?php                      
                                if($price_before_show == 1)
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
                        <div id="redemption-information-extra-info-general">
                            <?php echo "Worth RM " . $voucher_worth ?>
                        </div>
                        <?php
                    }
                    //GET OFF PERCENTAGE
                    if ($show_extra_info == 123)
                    {
                        ?>
                        <div id="redemption-information-extra-info-general">
                            <?php echo "Get off - " . $get_off_percent . "%" ?>
                        </div>
                        <?php
                    }
                    //BUY X GET X
                    if ($show_extra_info == 124)
                    {
                        ?>
                        <div id="redemption-information-extra-info-general">
                            <?php echo "Buy " . $how_many_buy . " Get " . $how_many_get ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            //DESCRIPTION
            if ($description)
            {
                ?>
                <div id="redemption-information-description">
                    <?php echo $description ?>
                </div>
                <?php
            }
            ?>
            <!--LIKE COMMENT-->
            <div id="redemption-information-like-comment">
                <div id="redemption-information-like-comment">
                    <div id="redemption-information-like">
                        <?php echo $like_url; ?>
                    </div>
                    <div id="redemption-information-comment">
                        <?php echo $comment_url; ?>
                    </div>
                    <div id="redemption-information-like-comment-earn-candie">
                        <?php
//                        $like_comment_candie_earn = $this->m_custom->display_trans_config(2);
//                        echo "Earn : " . $like_comment_candie_earn . " candies"; 
                        ?>
                        CLICK BY EARN CANDIES
                    </div>
                </div>
            </div>
            <!--REDEMPTION PERIOD-->
            <div id="redemption-information-redemption-period">
                <div id="redemption-information-redemption-period-icon"><i class="fa fa-gift header-menu-icon"></i></div>
                <div id="redemption-information-redemption-period-label">Redeem Period: <?php echo $start_date ?> to <?php echo $end_date ?></div>
            </div>
            <!--EXPIRED DATE-->
            <div id='redemption-information-expired-date'>
                <div id='redemption-information-expired-date-icon'><i class="fa fa-calendar-o"></i></div>
                <div id='redemption-information-expired-date-label'>Expiry Date: <?php echo $expire_date ?></div>
            </div>
            <!--REDEEM-->
            <div id='redemption-information-submit'>
                <?php
                if (check_correct_login_type($this->config->item('group_id_user')) && $is_history == 0)
                {
                    $action_url = base_url() . "all/user_redeem_voucher";
                    $confirm_message = "Confirm that you want to redeem this voucher? ";
                    ?>
                    <form action="<?php echo $action_url; ?>" onSubmit="return confirm('<?php echo $confirm_message ?>')" method="post" accept-charset="utf-8">
                        <?php echo form_input($item_id) ?>
                        <input type='hidden' name='current_url' id='current_url' value='<?php echo get_current_url() ?>'/>
                        <button name="button_action" type="submit" value="redeem">Redeem</button>
                    </form>
                    <?php
                }
                ?>
            </div>
            <div id="redemption-information-horizontal-separator"></div>
            <!--SHARE-->
            <div id="redemption-information-share">
                <div id="redemption-information-share-label">
                    Share This Redemption
                </div>
                <div id="redemption-information-share-facebook" onclick="fbShare(); return false;">
                    <img src="<?php echo base_url() . 'image/social-media-facebook-share.png'; ?>" >
                </div>
                <div id="redemption-information-share-earn-candie">
                    <?php //echo "Earn : " . $this->m_custom->display_trans_config(10) . " candies" ?>
                </div>
            </div>
            <!--TERMS AND CONDITIONS-->
            <div id="redemption-information-terms-conditions">
                <div id="redemption-information-terms-conditions-title">Terms & Condition:</div>
                <ul>
                    <?php
                    foreach ($candie_term as $value)
                    {
                        echo "<li>" . $this->m_custom->display_dynamic_option($value['option_id'], $merchant_name) . "</li>";
                    }
                    ?>                              
                    <?php
                    if (!empty($extra_term))
                    {
                        $extra_term_array = explode("\n", $extra_term);
                        foreach ($extra_term_array as $extra_term_row)
                        {
                            echo "<li>" . $extra_term_row . "</li>";
                        }
                    }
                    ?>
                </ul>   
            </div>
            <!--AVAILABLE BRANCH-->
            <div id="redemption-information-available-branch">
                <div id="redemption-information-available-branch-title">Available Branch:</div>
                <ul>
                    <?php
                    foreach ($candie_branch as $value)
                    {
                        ?>
                        <li>
                            <div id="redemption-information-available-branch-name"><?php echo $value['name'] ?></div>
                            <div id="redemption-information-available-branch-address"><?php echo $value['address'] ?></div>
                            <div id="redemption-information-available-branch-tel"><a href='tel: <?php echo $value['phone'] ?>'><?php echo $value['phone'] ?></a></div>
                            <div id="redemption-information-available-branch-view-map"><a href='<?php echo base_url() ?>all/merchant-map/<?php echo $value['branch_id'] ?>#dashboard' target='_blank'>View Map</a></div>
                        </li>
                        <?php
                    }
                    ?>  
                </ul>
            </div>
            <!--TAB BOX--> 
            <div id='redemption-information-tab-box'>
                <div id='redemption-information-tab-box-title'>User Comment</div>
                <div id="redemption-information-tab-box-user-comment">
                    <?php $this->load->view('all/comment_form') ?>
                </div>
            </div>
        </div>
        <div id="float-fix"></div>
    </div>
</div>
        
<div id='redemption-suggestion'>
    <?php
    if (!empty($advertise_suggestion_list))
    {
        $data['share_hotdeal_redemption_list'] = $advertise_suggestion_list;
        $data['title'] = "Redemption Suggestion";
        $this->load->view('share/redemption_suggestion_list5', $data);
    }
    else
    {
        ?><br/><br/><br/><?php
    }
    ?>
</div>