<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="payment">
    <h1>Payment</h1>
    <div id='payment-content'>
        <!--PAYMENT GO-->
        <div id="payment-go">
            <?php echo form_open(uri_string()); ?>
            <div id="payment-go-label">Statement Period:</div>
            <div id="payment-go-dropdown"><?php echo form_dropdown($the_month, $month_list, $the_month_selected); ?></div>
            <div id="payment-go-button"><button name="button_action" type="submit" value="search_history">Go</button></div>
            <?php echo form_close(); ?>
        </div>
        <!--PAYMENT BALANCE-->
        <div id="payment-balance">
            Current Balance : RM <?php echo $current_balance; ?>
        </div>
        <div id="float-fix"></div>
        <!--PAYMENT PRINT-->
        <div id='payment-print'>
            <a href="#" onclick="printDiv('print-area')"><i class="fa fa-print"></i> Print Statement</a>
        </div>
        <!--PAYMENT MONEY SPEND-->
        <div id="payment-money-spend">
            <?php echo "<a href='" . base_url() . "merchant/payment-charge-page' >View Money Spend By Post</a>"; ?>
        </div>
        <div id='float-fix'></div>
        <div id="print-area">
            <h2><?php echo $the_month_selected_text. ' Statement'; ?></h2>
            <div id='payment-table'>
                <div id='table-all'>
                    <table border='1px' cellspacing='0px' cellpadding='0px'>
                        <colgroup>
                            <col>
                            <col style='width: 140px;'>
                            <col style='width: 80px;'>
                            <col style='width: 90px;'>
                        </colgroup>
                        <tr style="text-align:center">
                            <th>Action</th>
                            <th>Charge Per Action</th>
                            <th>Quantity</th>
                            <th>Total (RM)</th>
                        </tr>
                        <tr style='text-align: left;'>
                            <td><?php echo $previous_month_selected_text['month_year_text']; ?> End Balance</td>
                            <td colspan="2"></td>
                            <td><?php echo $previous_end_month_balance ?></td>
                        </tr>
                        <?php
                        foreach($this_month_transaction as $row)
                        {
                            $conf_row = $this->m_custom->get_one_table_record('transaction_config','trans_conf_id',$row['trans_conf_id'],1);
                            $amount_change = $conf_row['change_type'] == 'inc'? $conf_row['amount_change']: ($conf_row['amount_change']*-1);
                            $amount_change = number_format($amount_change, 2);
                            if($conf_row['amount_change'] == 0){
                                $amount_change = '';
                            }
                            echo '<tr >';
                            echo "<td>".$conf_row['conf_name']."</td>";
                            echo "<td>".$amount_change."</td>";
                            echo "<td>".$row['quantity']."</td>";
                            echo "<td>".number_format($row['plus'] - $row['minus'],2)."</td>";
                            echo '</tr>';
                        }
                        echo '<td><b>Month End Balance</b></td><td colspan="2"></td><td>'.$end_month_balance.'</td>';
                        ?>
                    </table> 
                </div>
            </div>
            <div id='payment-your-current-balance'>
                <b>Your current balance RM<?php echo $current_balance; ?></b>
            </div>
            <div id='payment-your-balance'>
                Your balance cannot less then RM<?php
                                            $merchant_minimum_balance = $this->m_custom->web_setting_get('merchant_minimum_balance', 'set_decimal');
                                            echo $merchant_minimum_balance;
                                        ?>. System will auto frozen your account until top-up with value.
            </div>
            <div id='payment-reload'>
                Please reload to this account: <br/>
                <i>    <?php
                                            $keppo_company_bank = $this->m_custom->web_setting_get('keppo_company_bank', 'set_desc');
                                            echo nl2br($keppo_company_bank);
                                        ?> </i>
            </div>
            <div id='payment-assistance'>
                If need further assistance please contact us: 
                                        <?php
                                            $keppo_company_phone = $this->m_custom->web_setting_get('keppo_company_phone', 'set_desc');
                                            echo $keppo_company_phone;
                                        ?>
            </div>
        </div>
        
        <div id="payment-bank-icon">
            <div id="payment-bank-icon-each">
                <a href="https://www.cimbclicks.com.my/" target="_blank"><img src="<?php echo base_url() ?>/image/payment-cimb.jpg"></a>
            </div>
            <div id="payment-bank-icon-each">
                <a href="http://www.maybank2u.com.my/" target="_blank"><img src="<?php echo base_url() ?>/image/payment-maybank.jpg"></a>
            </div>
            <div id="payment-bank-icon-each">
                <a href="https://www.pbebank.com/" target="_blank"><img src="<?php echo base_url() ?>/image/payment-publicbank.jpg"></a>
            </div>
            <div id="payment-bank-icon-each">
                <a href="https://www.citibank.com.my" target="_blank"><img src="<?php echo base_url() ?>/image/payment-citibank.jpg"></a>
            </div>
            <div id="payment-bank-icon-each">
                <a href="https://www.alliancebank.com.my/" target="_blank"><img src="<?php echo base_url() ?>/image/payment-alliancebank.jpg"></a>
            </div>
        </div>
        
    </div>
</div>