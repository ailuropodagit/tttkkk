<div id="infoMessage"><?php echo $message; ?></div>

<div id="profile">
    <h1><?php echo $title; ?></h1>
   <div id='profile-content'>
<?php
$voucher_active = $this->config->item('voucher_active');
if ($show_used == 1)
{
    $voucher_active = $this->config->item('voucher_used');
}
else if ($show_used == 2)
{
    $voucher_active = $this->config->item('voucher_expired');
}
foreach($promotion_list as $promotion_row){
    $advertise_url = "<a target='_blank' href='" . base_url() . "all/advertise/" . $promotion_row['advertise_id'] . "'>" . $promotion_row['title'] . "</a>";
    echo $advertise_url ." (" . $promotion_row['voucher'].")<br/><br/>";
    $redeem_list = $this->m_custom->getUserRedemption($promotion_row['advertise_id'],$voucher_active);
    echo '<table>';
    if ($show_used == 0)
    {
        echo '<td>Mark As</td>';
    }
    echo '<td>Name</td><td>IC</td><td>Phone</td><td>Email</td><td>Expire Date</td>';
    foreach($redeem_list as $redeem_row){
        $user_info = $this->m_custom->getUser($redeem_row['user_id']);
        $user_name = $this->m_custom->display_users($redeem_row['user_id']);
        $action_url = base_url() . "merchant/redeem_done";
        echo '<tr>';
        if ($show_used == 0)
        {
        echo "<td>";       
        $confirm_message = "Confirm that you want to change ".$user_name." voucher ".$promotion_row['voucher']." status?";
        ?>
        <form action="<?php echo $action_url; ?>" onSubmit="return confirm('<?php echo $confirm_message; ?>')" method="post" accept-charset="utf-8">
        <?php
        echo "<input type='hidden' name='redeem_id' id='redeem_id' value='" . $redeem_row['redeem_id'] . "'/>";
        echo "<input type='hidden' name='user_id' id='user_id' value='" . $redeem_row['user_id'] . "'/>";
        echo "<input type='hidden' name='advertise_id' id='advertise_id' value='" . $redeem_row['advertise_id'] . "'/>";       
        echo "<input type='hidden' name='current_url' id='current_url' value='" . get_current_url() . "'/>";

            echo "<button name='button_action' type='submit' value='submit_used' >Used</button><br/>";
            echo "<button name='button_action' type='submit' value='submit_expired' >Expired</button><br/>";

        echo "<br/>";
        echo form_close();       
        echo "</td>";
        }
        $user_url = "<a target='_blank' href='" . base_url() . "all/user_dashboard/" . $user_info['id'] . "'>" . $user_name . "</a>";
        echo "<td>".$user_url."</td>";
        echo "<td>".$user_info['us_ic']."</td>";
        echo "<td>".$user_info['phone']."</td>";
        echo "<td>".$user_info['email']."</td>";
        echo "<td>".displayDate($redeem_row['expired_date'])."</td>";
        echo '</tr>';
    }
    echo "</table><br/><br/>";
}

?>
        <div id="profile-bottom-link-right">
            <div id="profile-bottom-link-right-each">
                <a href='<?php echo base_url() . "merchant/merchant_redemption_page/2" ?>' >Expired History</a>
            </div>
            <div id='float-fix'></div>
        </div>
        <div id="profile-bottom-link-right">
            <div id="profile-bottom-link-right-each">
                <a href='<?php echo base_url() . "merchant/merchant_redemption_page/1" ?>' >Used History</a>
            </div>
            <div id='float-fix'></div>
        </div>

   </div>