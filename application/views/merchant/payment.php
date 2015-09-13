<div id="infoMessage"><?php echo $message; ?></div>

<div id="profile">
    <h1><?php echo "Payment"; ?></h1>

    <a href="#">Print Statement</a>
<?php

echo '<table style="text-align:right">';
    echo '<th>Items</th><th>RM</th><th>Quantity</th><th>RM (Total)</th>';
    echo '<tr><td>Last Month Balance</td><td colspan="2"></td><td>'.$last_month_balance.'</td></tr>';
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
    echo "</table><br/><br/>";

?>
<b>Your current balance RM<?php echo $this_month_balance; ?></b><br/><br/>
Your balance cannot less then RM50. System will auto freezon your account until top-up with value.<br/>

Please reload to this account: xxxxxxxxxxx<br/><br/>
If need furthur assistance please contact us: xxx-xxxxxxxx