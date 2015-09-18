<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<div id="infoMessage"><?php echo $message; ?></div>

<div id="payment">
    <h1><?php echo "Total Candies"; ?></h1>
    <div id='payment-content'>
        
        <div id='payment-print'>
            <?php 
                $user_candie = base_url() . 'user/candie_page'; 
                echo "<a href='".$user_candie."' >Candies Balance</a> | ";
            ?>
            <a href="#" >Active Voucher</a> | 
            <a href="#" >Used Voucher</a> | 
            <a href="#" >Expired Voucher</a>
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
                        <th>Status</th>
                        <th>Expire Date</th>
                        <th>Candies</th>
                    </tr>
                    <tr>
                        <td>Last Month Candies</td>
                        <td colspan="3"></td>
                        <td style="text-align:right"><?php echo $last_month_balance ?></td>
                    </tr>
                    <tr>
                        <td>This Month Candies Gain</td>
                        <td colspan="3"></td>
                        <td style="text-align:right">+ <?php echo $this_month_candie_gain ?></td>
                    </tr>
                    <?php
                        foreach($this_month_redemption as $row){
                            //$adv_row = $this->m_custom->get_one_table_record('advertise','advertise_id',$row['advertise_id'],1);
                            $can_row = $this->m_user->get_candie_history_from_redemption($row['redeem_id']);
                            echo '<tr>';
                            echo "<td>".$this->m_merchant->get_merchant_link_from_advertise($row['advertise_id'])."</td>";
                            echo "<td>".$this->m_custom->generate_advertise_link($row['advertise_id'])."</td>";
                            echo "<td>".$this->m_custom->display_static_option($row['status_id'])."</td>";
                            echo "<td>".displayDate($row['expired_date'])."</td>";
                            echo "<td style='text-align:right'>- ".$can_row['candie_minus']."</td>";
                            echo '</tr>';
                        }
                        echo '<td><b>Current Candies</b></td><td colspan="3"></td><td style="text-align:right">'.$this_month_balance.'</td>';
                    ?>
                </table> 
            </div>
        </div>
        
    </div>
</div>