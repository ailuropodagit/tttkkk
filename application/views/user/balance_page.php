<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/jquery.dataTables.min.js"></script>
<?php echo link_tag('js/datatables/css/jquery.dataTables.min.css') ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myTable').DataTable({
            "paging": false,
            "order": []
        });
    });
</script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="payment">
    <h1>Keppo Wallet</h1>
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
                            if($row['trans_conf_id'] == 23){    //If is User Balance Adjust/Withdraw need see detail transaction
                                ?>
                                <td>
                                <a href="#payment-charge-table" onclick="toggle_visibility('payment-charge-table');"><?php echo $row['quantity'] ?></a>
                                </td>
                                <?php
                            }else{
                                echo "<td>".$row['quantity']."</td>";
                            }
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
            <?php $minimum_withdraw = $this->config->item('minimum_withdraw_amount'); ?>
            <div id='payment-your-balance'>
                If your balance reach RM<?php echo $minimum_withdraw; ?>. You can contact keppo admin to get a cash back.
            </div>
            <div id="payment-balance-payout-button">
                <a href='<?php echo base_url(); ?>user/contact_admin' class="a-href-button">Payout</a>
            </div>
            <div id='float-fix'></div>
            <div id='payment-assistance'>
                <?php
                $keppo_company_phone = $this->m_custom->web_setting_get('keppo_company_phone', 'set_desc');
                echo "If need further assistance please contact us: " . $keppo_company_phone;
                ?>
            </div>
            <br/>
            <div id='payment-charge-table' style="display:none;">
                 <h2>User Balance Adjust/Withdraw History:</h2>
                <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                    <thead>
                        <tr style="text-align:center">
                            <th style='width: 100px;'>Date Time</th>                           
                            <th>Adjust/Withdraw Reason</th> 
                            <th>Amount (RM)</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($this_month_transaction_user_balance as $row)
                        {
                            $trans_time = displayDate($row['trans_time'], 1);
                            echo '<tr>';
                            echo "<td>" . $trans_time . "</td>";                            
                            echo "<td>" . $row['trans_remark'] . "</td>";
                            echo "<td style='text-align:right'>" . $row['amount_change'] . "</td>";
                            echo '</tr>';
                        }
                        ?>
                    </tbody>    
                </table>
            </div>
            
        </div>
        
    </div>
</div>