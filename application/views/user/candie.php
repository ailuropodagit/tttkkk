<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
//var_dump($this_month_candie);
?>

<script type="text/javascript">

    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }

</script>
    
<div id="payment">
    <h1><?php echo "Total Candies"; ?></h1>
    <h1 style="float:right;">Current Candies : <?php echo $current_balance; ?></h1>
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
            <?php echo form_open(uri_string()); ?>
            <div id="candie-promotion-form-go">
                <span id="candie-promotion-form-go-label"><?php echo "Period "; ?></span>
                <span id="candie-promotion-form-go-month"><?php echo form_dropdown($the_month, $month_list, $the_month_selected); ?></span>
                <span id="candie-promotion-form-go-button"><button name="button_action" type="submit" value="search_history">Go</button></span>
            </div>
            <?php echo form_close(); ?>
        <div id="print-area">
            <h2><?php echo $the_month_selected_text. ' Balance'; ?></h2>
            <div id='payment-table'>
                <table border='1px' cellspacing='0px' cellpadding='0px'>
                    <colgroup>
                        <col>
                        <col style='width: 320px;'>
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
                        <td><?php echo $previous_month_selected_text['month_year_text']; ?> End Candies</td>
                        <td colspan="5"></td>
                        <td style="text-align:right"><?php echo $previous_end_month_balance ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $the_month_selected_text; ?> Candies Gain</td>
                        <td colspan="5"></td>
                        <td style="text-align:right"><a href="#" onclick="toggle_visibility('how-candie-gain');">+ <?php echo $this_month_candie_gain ?></a></td>
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
                        echo '<td><b>Month End Candies</b></td><td colspan="5"></td><td style="text-align:right">'.$end_month_balance.'</td>';
                    ?>
                </table> 
            </div>
            <br/><br/>
            <div id="how-candie-gain" style="display:none;">
                <h3>How Candie Gain: </h3>
                <div id='payment-table' style="text-align:right;">                   
                    <table border='1px' cellspacing='0px' cellpadding='0px'>
                        <colgroup>
                            <col>
                            <col style='width: 150px;'>
                            <col style='width: 150px;'>
                            <col style='width: 120px;'>
                        </colgroup>
                        <tr>
                            <th>Action</th>
                            <th>Candie Per Action</th>
                            <th>Number Of Times</th>
                            <th>Candie (Total)</th>
                        </tr>
                        <?php
                            $candie_gain_only = 0;
                            foreach($this_month_candie as $row){
                                $conf_row = $this->m_custom->get_one_table_record('transaction_config','trans_conf_id',$row['trans_conf_id'],1);
                                if($conf_row['change_type'] == 'inc'){
                                $amount_change = $conf_row['change_type'] == 'inc'? $conf_row['amount_change']: ($conf_row['amount_change']*-1);
                                $candie_sub_total = number_format($row['plus'] - $row['minus'],0);
                                $candie_gain_only += $candie_sub_total;
                                echo '<tr>';
                                echo "<td>".$conf_row['conf_name']."</td>";
                                echo "<td>".number_format($amount_change,0)."</td>";
                                echo "<td>".$row['quantity']."</td>";
                                echo "<td>".$candie_sub_total."</td>";
                                echo '</tr>';
                                }
                            }
                            echo '<td><b>'.$the_month_selected_text.' Candies Gain</b></td><td colspan="2"></td><td>'.$candie_gain_only.'</td>';
                        ?>
                    </table> 
                </div>
            </div>
        </div>
        
    </div>
</div>