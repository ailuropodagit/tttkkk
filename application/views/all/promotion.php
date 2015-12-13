<!--RATING-->
<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<!--JGROWL-->
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message ?></div><?php
}
?>

<div id="redemption">
    <h1>Redemption</h1>
    <div id="redemption-content">
        <div id="redemption-category">
            Category: <?php echo $sub_category; ?>
        </div>
        <div id="redemption-edit-link">
            <?php
            if (check_is_login())
            {
                $merchant_id = $this->ion_auth->user()->row()->id;
                $allowed_list = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id');
                if (check_correct_login_type($this->config->item('group_id_merchant'), $allowed_list, $advertise_id))
                {
                    ?>
                    <a href='<?php echo base_url() . "merchant/candie_promotion/" . $advertise_id ?>'>
                        <div id="redemption-edit-link-icon"><i class="fa fa-pencil"></i></div>
                        <div id="redemption-edit-link-label">Edit Redemption</div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
        <div id="float-fix"></div>
        
        <div id="print-area"></div>
        
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
                            <div id="redemption-photo-box">
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
            <!--REQUIRED CANDIES-->
            <div id="redemption-information-required-candies">
                Require <?php echo $voucher_candie ?> Candies
            </div>
            <!--SUB TITLE-->
            <div id="redemption-information-sub-title">
                <?php echo $title ?>
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
                    $rate_candie_earn = $this->m_custom->display_trans_config(3);
                    echo "Earn : " . $rate_candie_earn . " candies";
                    ?>
                </div>
            </div>         
            <?php
            //VOUCHER WORTH
            if (!empty($voucher_worth))
            {
                ?>
                <div id="redemption-information-voucher-worth">
                    <?php echo "Worth RM " . $voucher_worth ?>
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
                        $like_comment_candie_earn = $this->m_custom->display_trans_config(2);
                        echo "Earn : " . $like_comment_candie_earn . " candies"; 
                        ?>
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
                if (check_correct_login_type($this->config->item('group_id_user')))
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
                <div id="redemption-information-share-facebook" onclick="fbShare()">
                    <img src='/keppo/image/social-media-facebook-share.png'>
                </div>
                <div id="redemption-information-share-earn-candie">
                    <?php echo "Earn : " . $this->m_custom->display_trans_config(10) . " candies" ?>
                </div>
            </div>
                                        
        </div>        
        <div id="float-fix"></div>
        <div id="redemption-terms-conditions">
            <div id="redemption-terms-conditions-title">Terms & Condition:</div>
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
        <div id="redemption-available-branch">
            <div id="redemption-available-branch-title">Available Branch:</div>
            <ul>
                <?php
                foreach ($candie_branch as $value)
                {
                    ?>
                    <li>
                        <div id="redemption-available-branch-name"><?php echo $value['name'] ?></div>
                        <div id="redemption-available-branch-address"><?php echo $value['address'] ?></div>
                        <div id="redemption-available-branch-tel"><a href='tel: <?php echo $value['phone'] ?>'><?php echo $value['phone'] ?></a></div>
                        <div id="redemption-available-branch-view-map"><a href='<?php echo base_url() ?>all/merchant-map/<?php echo $value['branch_id'] ?>' target='_blank'>View Map</a></div>
                    </li>
                    <?php
                }
                ?>  
            </ul>
        </div>         
        <div id='redemption-tab-box'>
            <div id='redemption-tab-box-title'>User Comment</div>
            <div id="redemption-tab-box-user-comment">
                <?php $this->load->view('all/comment_form') ?>
            </div>
        </div>
    </div>
</div>
