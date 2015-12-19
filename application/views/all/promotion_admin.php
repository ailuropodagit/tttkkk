<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<style type="text/css">
.modal-backdrop {
  z-index: -1;
}
</style>

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
            <?php
            if (!empty($expire_date))
            {
                echo "Expiry Date: " . $expire_date;
            }
            ?>
        </div>
        <div id="redemption-candies">
            Require <?php echo $voucher_candie ?> Candies
        </div>
        <div id="float-fix"></div>
        <?php
        if (check_correct_login_type($this->config->item('group_id_user')))
        {
            ?>
            <div id='redemption-redempt-submit'>
                <button type="submit" data-toggle="modal" data-target="#myModal_Redeem">Redeem</button>
            </div>        
            <br/><br/><br/>
            <?php
        } 
        ?>
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
                            <div id="redemption-photo">
                                <div id="redemption-photo-box" class="zoom-image">
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
                            <div id="redemption-description">
                                <?php echo $description ?>
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
    
<?php
if (check_correct_login_type($this->config->item('group_id_user')))
{
    ?>
    <div class="modal fade" id="myModal_Redeem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="bootstrap-close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">
                        Please Key In The Phone Number
                    </h4>
                </div>
                <div class="modal-body">
                    <?php
                    //FORM OPEN            
                    $action_url = base_url() . "all/user_redeem_voucher";
                    $confirm_message = "Confirm that you want to redeem this voucher? ";
                    if ($phone_required == 1)
                    {
                        $confirm_message = "Confirm that this is the correct phone number to top up? ";
                    }
                    ?>
                    <form action="<?php echo $action_url; ?>" onSubmit="return confirm('<?php echo $confirm_message ?>')" method="post" accept-charset="utf-8">
                        <?php
                        echo form_input($item_id);
                        if ($phone_required == 1)
                        {
                            ?>
                            <div class="bootstrap-form">
                                <div class="bootstrap-form-label">
                                    <div style="color:red; font-weight:bold">Please Make Sure You Key In The Correct Phone Number To Prevent Wrong Top Up!</div>
                                </div>
                                <div class="bootstrap-form-input">
                                    <input type="text" placeholder="Top Up Phone Number (Example: 012-345 6789)" id="phone" name="phone"><br/>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <input type='hidden' name='phone_required' id='phone_required' value='<?php echo $phone_required ?>'/>        
                        <input type='hidden' name='current_url' id='current_url' value='<?php echo get_current_url() ?>'/>
                        <div class="bootstrap-form-button">
                            <button name="button_action" type="submit" value="redeem" >Redeem</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}