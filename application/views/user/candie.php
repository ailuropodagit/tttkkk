<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="payment">
    <h1><?php echo "Total Candies"; ?></h1>
    <h1 style="float:right;">Current Candies : <?php echo $this_month_balance; ?></h1>
    <div id='payment-content'>
        
        <div id='payment-print'>
            <a href="<?php echo $candie_url; ?>" >Candies Balance</a> | 
            <a href="<?php echo $voucher_active_url; ?>" ><?php echo $voucher_active_count; ?></a> | 
            <a href="<?php echo $voucher_used_url; ?>" ><?php echo $voucher_used_count; ?></a> | 
            <a href="<?php echo $voucher_expired_url; ?>" ><?php echo $voucher_expired_count; ?></a>
        </div><br/>
        <div id='float-fix'></div>
        <div id='footer-server-time' style="float:right;">
            Date & Time : <?php echo date($this->config->item('keppo_format_date_time_display')) ?>
        </div>
        <div id="print-area">
            <div id='payment-table'>
                <table border='1px' cellspacing='0px' cellpadding='0px'>
                    <colgroup>
                        <col>
                        <col style='width: 300px;'>
                        <col style='width: 110px;'>
                        <col style='width: 110px;'>
                        <col style='width: 120px;'>
                    </colgroup>
                    <tr>
                        <th>Company</th>
                        <th>Voucher Title</th>
                        <th>Voucher</th> 
                        <th>Status</th>
                        <th>Redeem Time</th>
                        <th>Expire Date</th>
                        <th>Candies</th>
                    </tr>
                    <tr>
                        <td>Last Month Candies</td>
                        <td colspan="5"></td>
                        <td style="text-align:right"><?php echo $last_month_balance ?></td>
                    </tr>
                    <tr>
                        <td>This Month Candies Gain</td>
                        <td colspan="5"></td>
                        <td style="text-align:right">+ <?php echo $this_month_candie_gain ?></td>
                    </tr>
                    <?php
                        foreach($this_month_redemption as $row){
                        //$adv_row = $this->m_custom->get_one_table_record('advertise','advertise_id',$row['advertise_id'],1);
                            $can_row = $this->m_user->get_candie_history_from_redemption($row['redeem_id']);
                            echo '<tr>';
                            echo "<td>".$this->m_merchant->get_merchant_link_from_advertise($row['advertise_id'])."</td>";
                            echo "<td>".$this->m_custom->generate_advertise_link($row['advertise_id'])."</td>";
                            echo "<td>".$row['voucher']."</td>";
                            echo "<td>".$this->m_custom->display_static_option($row['status_id'])."</td>";
                            echo "<td>".displayDate($row['redeem_time'], 1)."</td>";
                            echo "<td>".displayDate($row['expired_date'])."</td>";
                            echo "<td style='text-align:right'>- ".$can_row['candie_minus']."</td>";
                            echo '</tr>';
                        }
                        echo '<td><b>Current Candies</b></td><td colspan="5"></td><td style="text-align:right">'.$this_month_balance.'</td>';
                    ?>
                </table> 
            </div>
        </div>
        
    </div>
</div>