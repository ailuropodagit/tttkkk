<?php
//CONFIG DATA
$empty_image = $this->config->item('empty_image');
$image_path_user_profile = $this->config->item('album_user_profile');
?>

<div id="notification">
    <h1>Your notifications</h1>
    <div id="notification-content">
        <?php
        if (check_correct_login_type($this->group_id_merchant) || check_correct_login_type($this->group_id_admin) || check_correct_login_type($this->group_id_worker))
        {
            ?>
            <div id="notification-monitoring-remove-action">
                <a href='<?php echo base_url() . "all/monitor-remove" ?>' >Monitoring Remove Action<?php echo " (" . $this->m_custom->display_row_monitor(1) . " new)"; ?></a>
            </div>
            <div id='float-fix'></div>
            <?php
        }
        ?>
        <div id="notification-table">
            <table border='0px' cellpadding='0px' cellspacing='0px'>
                <?php
                foreach ($notification_list as $row)
                {
                    //DATA
                    $notification_from_user_id = $row['noti_by_id'];                
                    $noti_read = $row['noti_read_already'];
                    $noti_message = $row['noti_message'];
                    $noti_url = $row['noti_url'];

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
                        <td></td>
                        <td>
                            <div id='notification-table-photo'>
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