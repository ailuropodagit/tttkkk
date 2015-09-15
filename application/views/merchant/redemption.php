<div id="infoMessage"><?php echo $message; ?></div>

<div id="user-redemption">
    <h1><?php echo $title; ?></h1>
    <div id='user-redemption-content'>
        
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
        ?>
        
        <?php
        foreach ($promotion_list as $promotion_row)
        {
            $advertise_url = "<a target='_blank' href='" . base_url() . "all/advertise/" . $promotion_row['advertise_id'] . "'>" . $promotion_row['title'] . "</a>";
            echo $advertise_url . " (" . $promotion_row['voucher'] . ")<br/><br/>";
            $redeem_list = $this->m_custom->getUserRedemption($promotion_row['advertise_id'], $voucher_active);
            ?>
            <table border='1px' cellpadding='0px' cellspacing='0px'>
                <colgroup>
                    <?php
                    if ($show_used == 0)
                    {
                        ?>
                        <col style='width: 120px;'>
                        <?php
                    }
                    ?>
                </colgroup>
                <tr>
                    <?php
                    if ($show_used == 0)
                    {
                        ?>
                        <th>Mark As</th>
                        <?php
                    }
                    ?>
                    <th>Name</th>
                    <th>IC Number</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Expire Date</th>
                </tr>
                <?php
                foreach ($redeem_list as $redeem_row)
                {
                    $user_info = $this->m_custom->getUser($redeem_row['user_id']);
                    $user_name = $this->m_custom->display_users($redeem_row['user_id']);
                    $action_url = base_url() . "merchant/redeem_done";
                    ?>
                    <tr>
                        <?php
                        if ($show_used == 0)
                        {
                            echo "<td align='center'>";
                            $confirm_message = "Confirm that you want to change " . $user_name . " voucher " . $promotion_row['voucher'] . " status?";
                            ?>
                            <form action="<?php echo $action_url; ?>" onSubmit="return confirm('<?php echo $confirm_message ?>')" method="post" accept-charset="utf-8">
                            <?php
                            echo "<input type='hidden' name='redeem_id' id='redeem_id' value='" . $redeem_row['redeem_id'] . "'/>";
                            echo "<input type='hidden' name='user_id' id='user_id' value='" . $redeem_row['user_id'] . "'/>";
                            echo "<input type='hidden' name='advertise_id' id='advertise_id' value='" . $redeem_row['advertise_id'] . "'/>";
                            echo "<input type='hidden' name='current_url' id='current_url' value='" . get_current_url() . "'/>";

                            echo "<button name='button_action' type='submit' value='submit_used' id='button-a-href'>Used</button>";
                            echo " | ";
                            echo "<button name='button_action' type='submit' value='submit_expired' id='button-a-href'>Expired</button>";

                            echo form_close();
                            echo "</td>";
                        }
                        $user_url = "<a target='_blank' href='" . base_url() . "all/user_dashboard/" . $user_info['id'] . "'>" . $user_name . "</a>";
                        echo "<td>" . $user_url . "</td>";
                        echo "<td>" . $user_info['us_ic'] . "</td>";
                        echo "<td>" . $user_info['phone'] . "</td>";
                        echo "<td>" . $user_info['email'] . "</td>";
                        echo "<td>" . displayDate($redeem_row['expired_date']) . "</td>";
                        ?>
                    </tr>
                    <?php
                }
                ?>        
            </table><br/><br/>
            <?php
        }
        ?>
        
        <br/>
        <a href='<?php echo base_url() . "merchant/merchant_redemption_page/2" ?>' >Expired History</a>
        &nbsp; | &nbsp;
        <a href='<?php echo base_url() . "merchant/merchant_redemption_page/1" ?>' >Used History</a>
        
    </div>
</div>