<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="user-redemption">
    <h1><?php echo $title; ?></h1>
    <div id='user-redemption-content'>
        
        <!--SEARCH-->
        <div id="user-redemption-search">
            <?php echo form_open(uri_string()); ?>
            <div id="user-redemption-search-input"><input type="text" placeholder="Search: User ID, User Name, Email, Voucher Code" name="search_word"></div>
            <div id="user-redemption-search-submit"><button name="button_action" type="submit" value="search">Search</button></div>
            <div id="blogger-search-clear"><a href='<?php echo current_url() ?>' class="a-href-button">Clear</a></div>
            <?php echo form_close(); ?>
        </div>
        
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
            echo $advertise_url . "<br/><br/>";
            $redeem_list = $this->m_merchant->getUserRedemption($promotion_row['advertise_id'], $voucher_active, 0, $search_word);
            ?>
            <div id="user-redemption-table">
                <div id="table-all">
                    <table border='1px' cellpadding='0px' cellspacing='0px' style="table-layout: fixed;">
                        <!--<colgroup>-->
                            <?php
                            if ($show_used == 0)
                            {
                                ?>
                                <!--<col style='width: 120px;'>-->
                                <?php
                            }
                            ?>
                        <!--</colgroup>-->
                        <colgroup>
                            <col class="user-redemption-table-column-1">
                        </colgroup>
                        <tr>
                            <?php
                            if ($show_used == 0)
                            {
                                ?>
                                <th>
                                    <div class="table-text-overflow-ellipsis">
                                        Mark As
                                    </div>
                                </th>
                                <?php
                            }
                            ?>                       
                            <th>
                                <div class="table-text-overflow-ellipsis">
                                    User ID
                                </div>
                            </th>
                            <th>
                                <div class="table-text-overflow-ellipsis">
                                    Name
                                </div>
                            </th>
                            <th>
                                <div class="table-text-overflow-ellipsis">
                                    Date of Birth
                                </div>
                            </th>
                            <th>
                                <div class="table-text-overflow-ellipsis">
                                    Email
                                </div>
                            </th>
                            <th>
                                <div class="table-text-overflow-ellipsis">
                                    Voucher
                                </div>
                            </th>
                            <th>
                                <div class="table-text-overflow-ellipsis">
                                    Expire Date
                                </div>    
                            </th>
                            <?php
                                if ($show_used != 0)
                                { ?>
                            <th>
                                <div class="table-text-overflow-ellipsis">
                                    Changed By
                                </div>    
                            </th>
                            <?php } ?>
                        </tr>
                        <?php
                        foreach ($redeem_list as $redeem_row)
                        {
                            $user_id = $redeem_row['user_id'];
                            $user_info = $this->m_custom->getUser($user_id);
                            $user_name = $this->m_custom->display_users($user_id);
                            $action_url = base_url() . "admin/keppo_voucher_redeem_done";
                            ?>
                            <tr>
                                <?php
                                if ($show_used == 0)
                                {
                                    $confirm_message = "Confirm that you want to change " . $user_name . " voucher " . $redeem_row['voucher'] . " status?";
                                    ?>
                                    <td>
                                        <div class="table-text-overflow-ellipsis">
                                            <form action="<?php echo $action_url; ?>" onSubmit="return confirm('<?php echo $confirm_message ?>')" method="post" accept-charset="utf-8">
                                                <input type='hidden' name='redeem_id' id='redeem_id' value='<?php echo $redeem_row['redeem_id'] ?>'/>
                                                <input type='hidden' name='user_id' id='user_id' value='<?php echo $user_id ?>'/>
                                                <input type='hidden' name='advertise_id' id='advertise_id' value='<?php echo $redeem_row['advertise_id'] ?>'/>
                                                <input type='hidden' name='current_url' id='current_url' value='<?php echo get_current_url() ?>'/>
                                                <input type='hidden' name='voucher' id='voucher' value='<?php echo $redeem_row['voucher'] ?>'/>
                                                <button name='button_action' type='submit' value='submit_used' id='button-a-href' title='Used' class='normal-btn-submit'>
                                                    <img src='<?php echo base_url() . "/image/btn-used.png"; ?>' title='Used' alt='Used' class='normal-btn-image'></button>
                                                <button name='button_action' type='submit' value='submit_expired' id='button-a-href' title='Expired' class='normal-btn-submit'>
                                                    <img src='<?php echo base_url() . "/image/btn-expired.png"; ?>' title='Expired' alt='Expired' class='normal-btn-image'></button>
                                            </form>
                                        </div>
                                    </td>
                                    <?php
                                } 
                                ?>
                                <td>
                                    <div class="table-text-overflow-ellipsis">
                                        <?php echo $user_id ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-text-overflow-ellipsis">
                                        <?php 
                                        echo $this->m_custom->generate_user_link($user_info['id']);
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-text-overflow-ellipsis">
                                        <?php echo displayDate($user_info['us_birthday']) ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-text-overflow-ellipsis">
                                        <?php echo $user_info['email'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-text-overflow-ellipsis">
                                        <?php echo $redeem_row['voucher'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="table-text-overflow-ellipsis">
                                        <?php echo displayDate($redeem_row['expired_date']) ?>
                                    </div>    
                                </td>
                                <?php
                                if ($show_used != 0)
                                { ?>
                                <td>
                                    <div class="table-text-overflow-ellipsis">
                                        <?php 
                                        echo $this->m_custom->display_users($redeem_row['done_by']);
                                        ?>
                                    </div>    
                                </td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                        ?>        
                    </table>
                </div>
            </div>
            <?php
        }
        ?>
        
        <style>
            .table-text-overflow-ellipsis {
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                width: 100%;
            }
        </style>
        
        <div id="user-redemption-navigation">
            <div id="user-redemption-navigation-each">
                <a href='<?php echo base_url() . "admin/keppo_voucher_redemption_page" ?>'>Active Redeem</a>
            </div>
            <div id="user-redemption-navigation-each-separater"> | </div>
            <div id="user-redemption-navigation-each">
                <a href='<?php echo base_url() . "admin/keppo_voucher_redemption_page/2" ?>'>Expired History</a>
            </div>
            <div id="user-redemption-navigation-each-separater"> | </div>
            <div id="user-redemption-navigation-each">
                <a href='<?php echo base_url() . "admin/keppo_voucher_redemption_page/1" ?>'>Used History</a>
            </div>
        </div>
        
    </div>
</div>