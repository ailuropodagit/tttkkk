<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<div id="infoMessage"><?php echo $message; ?></div>

<div id="payment">
    <h1><?php echo "Payment"; ?></h1>
    <div id='payment-content'>
        
        <div id='payment-print'>
            <a href="#" onclick="printDiv('print-area')"><i class="fa fa-print"></i> Print Statement</a>
        </div>
        
        <div id="print-area">
            <div id='payment-table'>
                <table border='1px' cellspacing='0px' cellpadding='0px'>
                    <colgroup>
                        <col>
                        <col style='width: 70px;'>
                        <col style='width: 110px;'>
                        <col style='width: 120px;'>
                    </colgroup>
                    <tr>
                        <th>Items</th>
                        <th>RM</th>
                        <th>Quantity</th>
                        <th>RM (Total)</th>
                    </tr>
                    <tr>
                        <td>Last Month Balance</td>
                        <td colspan="2"></td>
                        <td><?php echo $last_month_balance ?></td>
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
                        echo '<td><b>Current Balance</b></td><td colspan="2"></td><td>'.$this_month_balance.'</td>';
                    ?>
                </table> 
            </div>
            <div id='payment-current-balance'>
                <b>Your current balance RM<?php echo $this_month_balance; ?></b>
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
        
    </div>
</div>