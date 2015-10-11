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
            <div id='payment-table' style="text-align:right">
                <table border='1px' cellspacing='0px' cellpadding='0px'>
                    <colgroup>
                        <col>
                        <col style='width: 150px;'>
                        <col style='width: 110px;'>
                        <col style='width: 120px;'>
                    </colgroup>
                    <tr style="text-align:center">
                        <th>Action</th>
                        <th>Charge Per Action</th>
                        <th>Quantity</th>
                        <th>Total (RM)</th>
                    </tr>
                    <tr>
                        <td><?php echo $previous_month_selected_text['month_year_text']; ?> End Balance</td>
                        <td colspan="2"></td>
                        <td><?php echo $previous_end_month_balance ?></td>
                    </tr>
                    <?php
                        foreach($this_month_transaction as $row){
                            $conf_row = $this->m_custom->get_one_table_record('transaction_config','trans_conf_id',$row['trans_conf_id'],1);
                            $amount_change = $conf_row['change_type'] == 'inc'? $conf_row['amount_change']: ($conf_row['amount_change']*-1);
                            echo '<tr>';
                            echo "<td>".$conf_row['conf_name']."</td>";
                            echo "<td>".number_format($amount_change,2)."</td>";
                            echo "<td>".$row['quantity']."</td>";
                            echo "<td>".number_format($row['plus'] - $row['minus'],2)."</td>";
                            echo '</tr>';
                        }
                        echo '<td><b>Month End Balance</b></td><td colspan="2"></td><td>'.$end_month_balance.'</td>';
                    ?>
                </table> 
            </div>
            <div id='payment-current-balance'>
                <b>Your current balance RM<?php echo $current_balance; ?></b>
            </div>
            <div id='payment-your-balance'>
                Your balance cannot less then RM50. System will auto frozen your account until top-up with value.
            </div>
            <div id='payment-reload'>
                Please reload to this account: xxxxxxxxxxx
            </div>
            <div id='payment-assistance'>
                If need further assistance please contact us: xxx-xxxxxxxx
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