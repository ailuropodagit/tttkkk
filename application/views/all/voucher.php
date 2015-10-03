<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<div id="redemption">
    <h1>Redemption</h1>
    <div id='redemption-print'>
        <a href="#" onclick="printDiv('redemption-content')"><i class="fa fa-print"></i> Print Statement</a>
    </div>
            <div id="float-fix"></div>
    <div id="redemption-content">

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
                <div id='redemption-table-row-cell' class='redemption-center-cell'>
                    <div id='redemption-center'>
                        <div id="print-area">
                            <div id="redemption-voucher-barcode">
                                <img src="<?php echo $voucher_barcode; ?>"  alt="not show"/>
                            </div>
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
                            <div id="redemption-rate-time">
                                <div id="redemption-category">
                                    Category: <?php echo $sub_category; ?>
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
                                            <div id='float-fix'></div>
                                        </li>
                                        <?php
                                    }
                                    ?>  
                                </ul>
                            </div>
                            <?php
                            if (check_correct_login_type($this->group_id_user)) //Check if user logged in
                            {
                                ?>
                            <div id='redemption-comment-list'>
                                <div id="redemption-available-branch-title">User Info:</div>
                                <table border="0" cellpadding="4px" cellspacing="0px">
                            <tr>
                                <td>User ID</td>
                                <td>:</td>
                                <td>
                                    <div id="advertise-list-info-category"><?php echo $user_id; ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>User Name</td>
                                <td>:</td>
                                <td><?php echo $user_name; ?></td>
                            </tr>
                            <tr>
                                <td>User IC</td>
                                <td>:</td>
                                <td><?php echo $user_ic; ?></td>
                            </tr>
                            <tr>
                                <td>User DOB</td>
                                <td>:</td>
                                <td><?php echo $user_dob; ?></td>
                            </tr>
                            <tr>
                                <td>User Email</td>
                                <td>:</td>
                                <td><?php echo $user_email; ?></td>
                            </tr>
                            <tr>
                                <td>Current Candie</td>
                                <td>:</td>
                                <td><?php echo $current_candie; ?></td>
                            </tr>
                        </table>
                            </div>
                                  <?php
                                    }
                                    ?>  
                        </div>                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>