<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="redemption">
    <h1>Redemption</h1>
    <div id="redemption-content">
        
        <div id="redemption-category">
            Category: <?php echo $sub_category; ?>
        </div>
        <div id="float-fix"></div>
        
<!--        <div id='redemption-print'>
            <a href="#" onclick="printDiv('print-area')"><i class="fa fa-print"></i> Print Voucher</a>
        </div>-->
        <div id="redemption-edit-link">
            <?php
            if (check_is_login())
            {
                $merchant_id = $this->ion_auth->user()->row()->id;
                $allowed_list = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id');
                if (check_correct_login_type($this->config->item('group_id_merchant'), $allowed_list, $advertise_id))
                {
                    ?>
                    <a href='<?php echo base_url() . "merchant/candie_promotion/" . $advertise_id ?>'><i class="fa fa-pencil"></i> Edit Redemption</a>
                    <?php
                }
            }
            ?>
        </div>
        <div id="float-fix"></div>
        
        <div id='redemption-expired-date'>
            Expiry Date: <?php echo $expire_date; ?>
        </div>
        <div id="redemption-candies">
            Require <?php echo $voucher_candie ?> Candies
        </div>
        <div id="float-fix"></div>
        
        <div id='redemption-table'>
            <div id='redemption-table-row'>
                <div id='redemption-table-row-cell' class='redemption-left-cell'>
                    <div id='redemption-left'>
                        <?php
                        if (!empty($previous_url))
                        {
                            ?><a href="<?php echo $previous_url ?>"><i class="fa fa-angle-double-left"></i></a><?php
                        }
                        else 
                        {
                            ?><div id='redemption-left-gray'><i class="fa fa-angle-double-left"></i></div><?php
                        }
                        ?>
                    </div>
                </div>
                <div id='redemption-table-row-cell' class='redemption-center-cell'>
                    <div id='redemption-center'>
                        <div id="print-area">
                            <div id="float-fix"></div>
                            <div id="redemption-title">
                                <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                            </div>
                            <div id="redemption-photo">
                                <div id="redemption-photo-box">
                                    <img src='<?php echo $image_url ?>'>
                                </div>
                            </div>                                
                            <div id="redemption-sub-title">
                                <?php echo $title ?>
                            </div>
                            <div id="redemption-voucher-worth">
                                <?php
                                if (!empty($voucher_worth))
                                {
                                    echo "Worth : RM " . $voucher_worth;
                                }
                                ?>
                            </div>
                            <div id="redemption-time">
                                <i class="fa fa-gift header-menu-icon"></i><span id="redemption-time-label">Redeem Period:  <?php echo $start_date ?> to <?php echo $end_date ?></span>
                            </div>
                            <div id="redemption-rate">
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
                                <div id="float-fix"></div>
                            </div>
                            <div id="redemption-description">
                                <?php echo $description ?>
                            </div>
                            <div id="redemption-like-comment-share">
                                <div id="redemption-like">
                                    <?php echo $like_url; ?>
                                </div>
                                <div id="redemption-comment">
                                    <?php echo $comment_url; ?>
                                </div>
                                <div id="redemption-share">
                                    <?php echo "Share :"; ?>
                                    <span id="redemption-share-facebook">
                                        <a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook-square"></i></a>
                                    </span>
                                    <span id="redemption-share-instagram">
                                        <a href="https://instagram.com" target="_blank"><i class="fa fa-instagram"></i></a>
                                    </span>
                                </div>
                                <div id="float-fix"></div>
                            </div>
                            <div id="redemption-terms-conditions">
                                <div id="redemption-terms-conditions-title">Terms & Condition:</div>
                                <ul>
                                    <?php
                                    foreach ($candie_term as $value)
                                    {
                                        echo "<li>" . $value['option_desc'] . "</li>";
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
                                            <div id='float-fix'></div>
                                        </li>
                                        <?php
                                    }
                                    ?>  
                                </ul>
                            </div>
                        </div>
                        <div id='redemption-redempt-submit'>
                            <?php
                            if (check_correct_login_type($this->config->item('group_id_user')))
                            {
                                //FORM OPEN
                                $action_url = base_url() . "all/user_redeem_voucher";
                                $confirm_message = "Confirm that you want to redeem this voucher? ";
                                ?>
                                <form action="<?php echo $action_url; ?>" onSubmit="return confirm('<?php echo $confirm_message ?>')" method="post" accept-charset="utf-8">
                                <?php
                                echo form_input($item_id);
                                ?>
                                <input type='hidden' name='current_url' id='current_url' value='<?php echo get_current_url() ?>'/>
                                <button name="button_action" type="submit" value="redeem" >Redeem</button>
                                <?php
                                //FORM CLOSE
                                echo form_close();
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div id='redemption-table-row-cell' class='redemption-right-cell'>
                    <div id='redemption-right'>
                        <?php
                        if (!empty($next_url))
                        {
                            ?><a href="<?php echo $next_url ?>"><i class="fa fa-angle-double-right"></i></a><?php
                        }
                        else 
                        {
                            ?><div id='redemption-right-gray'><i class="fa fa-angle-double-right"></i></div><?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="redemption-line"></div>

        <div id='redemption-comment-list'>
            <?php $this->load->view('all/comment_form'); ?>
        </div>

    </div>
</div>
