<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
if (!empty($message))
{
    echo $message;
}
?>

<div id="redemption">
    <h1>Redemption</h1>
    <div id="redemption-content">
        
        <div id="hot-deal-category">
            Category: <?php echo $sub_category; ?>
        </div>
        
        <div id="hot-deal-edit-link">
            <?php
            if (check_is_login())
            {
                $merchant_id = $this->ion_auth->user()->row()->id;
                $allowed_list = $this->m_custom->get_list_of_allow_id('advertise', 'merchant_id', $merchant_id, 'advertise_id');
                if (check_correct_login_type($this->config->item('group_id_merchant'), $allowed_list, $advertise_id))
                {
                    ?>
                    <a href='<?php echo base_url() . "merchant/candie_promotion/" . $advertise_id ?>' >Edit Redemption</a>
                    <?php
                }
            }
            ?>
        </div>
        <div id="float-fix"></div>
        
        <div id="redemption-voucher-barcode">
            <img src="<?php echo $voucher_barcode; ?>"  alt="not show"/>
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
                        <div id="redemption-candies">
                            <?php echo $voucher_candie ?> Candies
                        </div>
                        <div id="redemption-rate-time">
                            <div id="redemption-rate">
                                <div style="display:inline;">
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
                            </div>
                            <div id="redemption-time">
                                <i class="fa fa-gift header-menu-icon"></i>
                                <span id="redemption-time-label">Redeem Period:  <?php echo $start_date ?> to <?php echo $end_date ?></span>
                            </div>
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
                        <div id='redemption-expired-date'>
                            Expiry Date: <?php echo $expire_date; ?>
                        </div>
                        <div id='redemption-redempt-submit'>
                            <?php
                            if (check_correct_login_type($this->config->item('group_id_user')))
                            {
                                //FORM OPEN
                                echo form_open("all/user_redeem_voucher");
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
                        <div id='redemption-comment-list'>
                            <?php $this->load->view('all/comment_form'); ?>
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

    </div>
</div>