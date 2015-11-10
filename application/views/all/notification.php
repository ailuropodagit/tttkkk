<?php
//CONFIG DATA
$empty_image = $this->config->item('empty_image');
$image_path_user_profile = $this->config->item('album_user_profile');
?>

<div id="notification">
    <h1>Your notifications</h1>
    <div id="notification-content">
        <div id="candie-navigation">
            <?php 
            $noti_to_id = $this->ion_auth->user()->row()->id;
            $notification_url = base_url() . "all/notification";
            if (check_correct_login_type($this->config->item('group_id_supervisor')))
            {
                $noti_to_id = $this->ion_auth->user()->row()->su_merchant_id;
            }
            if ($this->m_admin->check_is_any_admin())
            {
                $noti_to_id = 0;
                $notification_url = base_url() . "admin/admin_dashboard";
            }
            $notification_count = $this->m_custom->notification_count($noti_to_id);
            ?>
            <div id='candie-navigation-each'><a href='<?php echo $notification_url; ?>' >Notification (<?php echo $notification_count; ?> new)</a></div>
            <?php
            if (check_correct_login_type($this->group_id_merchant) || $this->m_admin->check_is_any_admin(68))
            {
                $monitor_count = $this->m_custom->display_row_monitor(1);               
            ?>
                <div id='candie-navigation-each-separator'>|</div>
                <div id='candie-navigation-each'><a href='<?php echo base_url() . "all/monitor-remove" ?>' >Monitoring Remove Action (<?php echo $monitor_count; ?> new)</a></div>
            <?php } ?>
            <?php
            //to do todo
            if ($this->m_admin->check_is_any_admin(69))
            {
                $banner_expire_count = $this->m_admin->banner_expired_count();               
            ?>
                <div id='candie-navigation-each-separator'>|</div>
                <div id='candie-navigation-each'><a href='<?php echo base_url() . "admin/monitor-expire-banner" ?>' >Banner Expired (<?php echo $banner_expire_count; ?>)</a></div>
            <?php } ?>    
            <?php
            if ($this->m_admin->check_is_any_admin(67))
            {
                $merchant_low_balance_count = $this->m_admin->merchant_low_balance_count();               
            ?>
                <div id='candie-navigation-each-separator'>|</div>
                <div id='candie-navigation-each'><a href='<?php echo base_url() . "admin/monitor-low-balance" ?>' >Merchant Insufficient Fund (<?php echo $merchant_low_balance_count; ?>)</a></div>
            <?php } ?>    
        </div>        
        <div id="float-fix"></div>
        <br/><br/>
        <div id="notification-table">
            <table border='0px' cellpadding='0px' cellspacing='0px'>
                <?php
                foreach ($notification_list as $row)
                {
                    //DATA
                    $notification_from_user_id = $row['noti_by_id'];                
                    $noti_read = $row['noti_read_already'];
                    $noti_message = $row['noti_message'];
                    $noti_url = base_url() .$row['noti_url'];

                    //USER INFO
                    $where_read_user = array('id'=>$notification_from_user_id);
                    $query_read_user = $this->albert_model->read_user($where_read_user);
                    $num_row_read_user = $query_read_user->num_rows();
                    if ($num_row_read_user)
                    {
                        $row_read_user = $query_read_user->row();
                        $notification_from_user_profile_image = $row_read_user->profile_image;
                        $notification_from_user_name = $row_read_user->first_name . ' ' . $row_read_user->last_name;
                        $user_profile_image = $image_path_user_profile . "/" . $notification_from_user_profile_image;
                        $user_name = $notification_from_user_name;
                        $user_dashboard_url = base_url("all/user_dashboard/$notification_from_user_id");
                    }
                    else
                    {
                        $user_profile_image = $empty_image;
                        $user_name = 'User Deleted';
                        $user_dashboard_url = '#';
                    }
                    ?>
                    <tr <?php if($noti_read == 0){ echo "class='notification-table-row-unread'"; } ?>>
                        <td>
                            <div id='notification-table-photo'>
                                <?php echo $row['noti_user_image'] ?>
                            </div>
                        </td>
                        <td>
                            <div id='notification-table-name'>
                                <?php echo $row['noti_user_url'] ?>
                            </div>
                        </td>

                        <td>
                            <div id='notification-table-message'>
                                <?php 
                                if (empty($noti_url)) 
                                { 
                                    echo $noti_message;
                                } 
                                else
                                {
                                    ?>
                                    <a href="<?php echo $noti_url ?>" target='_blank'><?php echo $noti_message ?></a>
                                    <?php
                                }
                                ?>
                            </div>
                        </td>
                        <td>
                            <div id='notification-table-delete'>
                                <?php echo form_open("all/notification_process") ?>
                                <input type='hidden' name='noti_id' id='noti_id' value='<?php echo $row['noti_id'] ?>'/>
                                <input type='hidden' name='current_url' id='current_url' value='<?php echo get_current_url() ?>'/>
                                <?php
                                if ($this->config->item('notification_auto_mark_as_read') == 0)
                                {
                                    if ($noti_read == 0)
                                    {
                                        $read_button_tooltip = "Mark as Read";
                                    }
                                    else
                                    {
                                        $read_button_tooltip = "Mark as Unread";
                                    }
                                    ?>
                                    <button name='button_action' type='submit' value='read_notification' id='button-a-href' title='<?php echo $read_button_tooltip ?>'>
                                        <i class='fa fa-circle-o'></i>
                                    </button>
                                    <?php
                                }
                                ?>
                                <button name='button_action' type='submit' value='hide_notification' id='button-a-href' title='Remove notification'>
                                    <i class='fa fa-times'></i>
                                </button>
                                <?php echo form_close() ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>