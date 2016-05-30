<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<div id="redemption">
    <div id="redemption-content">
        <h1>Voucher</h1>
        <div id="redemption-category">
            Category: <?php echo $sub_category; ?>
        </div>
        <div class="float-fix"></div>
        
        <!--PRINT ICON-->
        <div id='redemption-print'>
            <a href="#" onclick="printDiv('redemption-print-area')">
                <i class="fa fa-print"></i> Print Voucher
            </a>
        </div>
        <div class="float-fix"></div>

        <div id="redemption-print-area">
            <div id="redemption-photo">
                <div id='redemption-table'>
                    <div id='redemption-table-row'>
                        <div id='redemption-table-row-cell' class='redemption-left-cell'>
                            <div id='redemption-left'>

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

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="redemption-information">
                <!--VOUCHER BARCODE-->
                <div id="redemption-voucher-barcode">
                    <?php
                    //if ($default_barcode_url != $voucher_barcode && $voucher_not_need == 0)  //if epay not need voucher then add this
                    echo 'Voucher No. : ' . $voucher . '<br/><br/>';
                    $default_barcode_url = base_url('barcode/generate2');
                    if ($default_barcode_url != $voucher_barcode)
                    {
                        ?>
                        <img src='<?php echo $voucher_barcode; ?>' alt='No voucher barcode'/>
                        <?php
                    }
                    ?>
                </div>
                <!--TITLE-->
                <div id="redemption-information-title">
                    <?php if ($advertise_type == "adm")
                    { 
                        ?>
                        <a href='#'><?php echo $title; ?></a>
                        <?php
                    }
                    else
                    { 
                        ?>
                        <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                        <?php
                    } 
                    ?>
                </div>
                <div class="float-fix"></div>
                <!--REQUIRED CANDIES-->
                <div id="redemption-information-required-candies">
                    Require <?php echo $voucher_candie ?> Candies
                </div>
                <!--SUB TITLE-->
                <div id="redemption-information-sub-title">
                    <?php if ($advertise_type == "pro"){
                        echo $title; 
                    }?>
                </div>
                <?php if ($advertise_type == "pro")
                { 
                    ?>       
                    <!--REDEMPTION PERIOD-->
                    <div id="redemption-information-redemption-period">
                        <div id="redemption-information-redemption-period-icon"><i class="fa fa-gift header-menu-icon"></i></div>
                        <div id="redemption-information-redemption-period-label">Redeem Period: <?php echo displayDateEng($start_date) ?> to <?php echo displayDateEng($end_date) ?></div>
                    </div>
                    <!--EXPIRED DATE-->
                    <div id='redemption-information-expired-date'>
                        <div id='redemption-information-expired-date-icon'><i class="fa fa-calendar-o"></i></div>
                        <div id='redemption-information-expired-date-label'>Expiry Date: <?php echo displayDateEng($expire_date) ?></div>
                    </div>  
                    <?php 
                }
                ?>

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
                <?php if ($advertise_type == "pro")
                {
                    ?>
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
                                    <div id='float-fix'></div>
                                </li>
                                <?php
                            }
                            ?>  
                        </ul>
                    </div>
                    <?php
                } 
                ?>                
            </div>
            <div id="float-fix"></div>
        </div>

    </div>
</div>

<div id="redemption-bottom-spacing"></div>