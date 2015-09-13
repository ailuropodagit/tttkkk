<div id="infoMessage"><?php echo $message; ?></div>

<div id="profile">
    <h1>Redemption</h1>
   <div id='profile-content'>
<?php
$voucher_active = $this->config->item('voucher_active');
foreach($promotion_list as $promotion_row){
    echo $promotion_row['voucher']."<br/><br/>";
    $redeem_list = $this->m_custom->getUserRedemption($promotion_row['advertise_id'],$voucher_active);
    echo '<table>';
    echo '<td>Name</td><td>IC</td><td>Phone</td><td>Email</td><td>Expire Date</td>';
    foreach($redeem_list as $redeem_row){
        $user_info = $this->m_custom->getUser($redeem_row['user_id']);
        echo '<tr>';
        echo "<td>".$this->m_custom->display_users($user_info['id'])."</td>";
        echo "<td>-------</td>";
        echo "<td>".$user_info['phone']."</td>";
        echo "<td>".$user_info['email']."</td>";
        echo "<td>".displayDate($redeem_row['expired_date'])."</td>";
        echo '</tr>';
    }
    echo "</table><br/>";
}

?>
   </div>