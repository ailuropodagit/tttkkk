<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<div id="redemption">
    <h1>Redemption</h1>
    <div id='redemption-print'>
        <a href="#" onclick="printDiv('redemption-content')"><i class="fa fa-print"></i> Print Voucher</a>
    </div>
            <div id="float-fix"></div>
    <div id="redemption-content">

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
            Require <?php echo $voucher_candie ?> Candy
        </div>
        <div id="float-fix"></div>

        <div id='redemption-table'>
            <div id='redemption-table-row'>
                <div id='redemption-table-row-cell' class='redemption-center-cell'>
                    <div id='redemption-center'>
                        <div id="print-area">
                            <div id="redemption-voucher-barcode">
                                <?php
                                //if ($default_barcode_url != $voucher_barcode && $voucher_not_need == 0)  //if epay not need voucher then add this
                                $default_barcode_url = base_url('barcode/generate');
                                if ($default_barcode_url != $voucher_barcode)
                                {
                                    echo "<img src='" . $voucher_barcode . "' alt='no barcode'/>";
                                }
                                ?>
                            </div>
                            <div id="float-fix"></div>
                            <div id="redemption-title">
                                <?php if($advertise_type == "adm"){ ?>
                                    <?php echo $sub_category; ?>
                                <?php }else{ ?>
                                    <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                                <?php } ?>
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
                            
                            <?php if($advertise_type == "pro"){ ?>                      
                            <div id="redemption-time">
                                <i class="fa fa-gift header-menu-icon"></i>
                                <span id="redemption-time-label">Redeem Period:  <?php echo $start_date ?> to <?php echo $end_date ?></span>
                            </div>
                            <div id="redemption-rate">
                                Category: <?php echo $sub_category; ?>
                            </div>         
                            <?php } ?>
                            
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
                            <?php if($advertise_type == "pro"){ ?>
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
                                            <div id='float-fix'></div>
                                        </li>
                                        <?php
                                    }
                                    ?>  
                                </ul>
                            </div>
                            <?php } ?>
                            <?php
                            //if (check_correct_login_type($this->group_id_user)){
                                ?>
<!--                            <div id='redemption-comment-list'>
                                <div id="redemption-available-branch-title">User Info:</div>
                                <table border="0" cellpadding="4px" cellspacing="0px">
                            <tr>
                                <td>User ID</td>
                                <td>:</td>
                                <td>
                                    <?php //echo $user_id; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>User Name</td>
                                <td>:</td>
                                <td><?php //echo $user_name; ?></td>
                            </tr>
                            <tr>
                                <td>User DOB</td>
                                <td>:</td>
                                <td><?php //echo $user_dob; ?></td>
                            </tr>
                            <tr>
                                <td>User Email</td>
                                <td>:</td>
                                <td><?php //echo $user_email; ?></td>
                            </tr>
                            <tr>
                                <td>Current Candie</td>
                                <td>:</td>
                                <td><?php //echo $current_candie; ?></td>
                            </tr>
                        </table>
                            </div>-->
                                  <?php
                                    //}
                                    ?>  
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
