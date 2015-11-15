<?php
//CONFIG DATA
$empty_image = $this->config->item('empty_image');
$image_path_user_profile = $this->config->item('album_user_profile');
?>

<div id="notification">
    <div id="notification-content">
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
                    ?>
                    <tr <?php if($noti_read == 0){ echo "class='notification-table-row-unread'"; } ?>>
                        <td>
                            <div id='notification-table-photo'>
                                <?php echo $row['noti_user_image'] ?>
                            </div>
                        </td>
                        <td style='width:25%'>
                            <div id='notification-table-name'>
                                <?php echo $row['noti_user_url'] ?>
                            </div>
                        </td>
                        <td style='width:65%'>
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
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>