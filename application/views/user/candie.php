<script type="text/javascript">
    function toggle_visibility(id) {
        var e = document.getElementById(id);
        if (e.style.display == 'block') {
            e.style.display = 'none';
        } else {
            e.style.display = 'block';
        }
    }
</script>

<?php
//MESSAGE
if (isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="candie">
    <h1>Total Candies</h1>
    <div id="candie-content">
        
        <div id="candie-navigation">
            <div id='candie-navigation-each'><a href="<?php echo $candie_url; ?>" >Candies Balance</a></div>
            <div id='candie-navigation-each-separator'>|</div>
            <div id='candie-navigation-each'><a href="<?php echo $voucher_active_url; ?>" ><?php echo $voucher_active_count; ?></a></div>
            <div id='candie-navigation-each-separator'>|</div>
            <div id='candie-navigation-each'><a href="<?php echo $voucher_used_url; ?>" ><?php echo $voucher_used_count; ?></a></div>
            <div id='candie-navigation-each-separator'>|</div>
            <div id='candie-navigation-each'><a href="<?php echo $voucher_expired_url; ?>" ><?php echo $voucher_expired_count; ?></a></div>
        </div>
        <div id="float-fix"></div>
        
        <div id="candie-go">
            <?php echo form_open(uri_string()); ?>
            <span id="candie-go-label">Period : </span>
            <span id="candie-go-dropdown"><?php echo form_dropdown($the_month, $month_list, $the_month_selected); ?></span>
            <span id="candie-go-button"><button name="button_action" type="submit" value="search_history">Go</button></span>
            <?php echo form_close(); ?>
        </div>
        <div id="candie-balance">Current Candies : <?php echo $current_balance ?></div>
        <div id='float-fix'></div>
                
        <div id='candie-subtitle'>
            <?php echo $the_month_selected_text ?> Balance
        </div>
        <div id="candie-date-time">
            Date & Time : <?php echo date($this->config->item('keppo_format_date_time_display')) ?>
        </div>
        <div id='float-fix'></div>
        
        <div id="print-area">
            <div id="candie-table">
                <div id='table-all'>
                    <table border='1px' cellspacing='0px' cellpadding='0px'>
                        <colgroup>
                            <col style="width: 180px;">
                            <col style='width: 90px;'>
                            <col style='width: 80px;'>
                            <col style='width: 60px;'>
                            <col style='width: 100px;'>
                            <col style='width: 100px;'>
                            <col style='width: 40px;'>
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
                            <td style="text-align:right">
                                <a href="#" onclick="toggle_visibility('candie-how-gain');">+ <?php echo $this_month_candie_gain ?></a>
                            </td>
                        </tr>
                        <?php
                        foreach ($this_month_redemption as $row)
                        {
                            //$adv_row = $this->m_custom->get_one_table_record('advertise','advertise_id',$row['advertise_id'],1);
                            $can_row = $this->m_user->get_candie_history_from_redemption($row['redeem_id']);
                            echo '<tr>';
                            echo "<td>" . $this->m_merchant->get_merchant_link_from_advertise($row['advertise_id']) . "</td>";
                            echo "<td>" . $this->m_custom->generate_advertise_link($row['advertise_id']) . "</td>";
                            echo "<td>" . $row['voucher'] . "</td>";
                            echo "<td>" . $this->m_custom->display_static_option($row['status_id']) . "</td>";
                            echo "<td>" . displayDate($row['redeem_time'], 1) . "</td>";
                            echo "<td>" . displayDate($row['expired_date']) . "</td>";
                            echo "<td style='text-align:right'>- " . $can_row['candie_minus'] . "</td>";
                            echo '</tr>';
                        }
                        echo '<td><b>Month End Candies</b></td><td colspan="5"></td><td style="text-align:right">' . $end_month_balance . '</td>';
                        ?>
                    </table>
                </div>
            </div>
                
            <div id="candie-how-gain" style="display:none;">
                <h1>How Candie Gain:</h1>
                <div id="candie-how-gain-content">
                    <div id='table-all'>                   
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
                            foreach ($this_month_candie as $row)
                            {
                                $conf_row = $this->m_custom->get_one_table_record('transaction_config', 'trans_conf_id', $row['trans_conf_id'], 1);
                                if ($conf_row['change_type'] == 'inc')
                                {
                                    $amount_change = $conf_row['change_type'] == 'inc' ? $conf_row['amount_change'] : ($conf_row['amount_change'] * -1);
                                    $candie_sub_total = number_format($row['plus'] - $row['minus'], 0);
                                    $candie_gain_only += $candie_sub_total;
                                    echo '<tr>';
                                    echo "<td>" . $conf_row['conf_name'] . "</td>";
                                    echo "<td>" . number_format($amount_change, 0) . "</td>";
                                    echo "<td>" . $row['quantity'] . "</td>";
                                    echo "<td>" . $candie_sub_total . "</td>";
                                    echo '</tr>';
                                }
                            }
                            echo '<td><b>' . $the_month_selected_text . ' Candies Gain</b></td><td colspan="2"></td><td>' . $candie_gain_only . '</td>';
                            ?>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>