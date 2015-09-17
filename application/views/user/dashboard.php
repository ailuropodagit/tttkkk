<?php
//USERS ROW
$row_users = $query_users->row_array();
//USERS DATA
$profile_image = $row_users['profile_image'];
$first_name = $row_users['first_name'];
$last_name = $row_users['last_name'];
$blog_url = $row_users['us_blog_url'];
$instagram_url = $row_users['us_instagram_url'];
$facebook_url = $row_users['us_facebook_url'];
?>

<?php 
//CONFIG DATA
$empty_image = $this->config->item('empty_image');
$album_user_profile = $this->config->item('album_user_profile');
?>

<div id="dashboard">
    <h1>Dashboard</h1>
    <div id="dashboard-content">
        <div id="dashboard-photo">
            <?php            
            if(IsNullOrEmptyString($profile_image))
            {
                ?>
                <img src="<?php echo base_url() . $empty_image ?>">
                <?php
            }
            else
            {
                ?>
                <img src="<?php echo base_url() . $album_user_profile . $profile_image ?>">
                <?php
            }
            ?>
        </div>
        <div id="dashboard-info">
            <div id="dashboard-info-title">
                <?php echo $first_name.' '.$last_name; ?>
            </div>
            <div id="dashboard-info-table">
                <table border="0px" cellspacing="0px" cellpadding="5px" style="width: 100%; table-layout: fixed;">
                    <colgroup style="width:118px;"></colgroup>
                    <colgroup style="width:15px;"></colgroup>
                    <tr>
                        <td>Blog URL</td>
                        <td>:</td>
                        <td>
                            <div class="text-ellipsis">
                                <a href='<?php echo $blog_url ?>'><?php echo $blog_url ?></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Instagram URL</td>
                        <td>:</td>
                        <td>
                            <div class="text-ellipsis">
                                <a href='<?php echo $instagram_url ?>'><?php echo $instagram_url ?></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Facebook URL</td>
                        <td>:</td>
                        <td>
                            <div class="text-ellipsis">
                                <a href='<?php echo $facebook_url ?>'><?php echo $facebook_url ?></a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="dashboard-info-followers-following">
                <div id="dashboard-info-followers">
                    Followers : 10
                </div>
                <div id="dashboard-info-following">
                    Following : 20
                </div>
            </div>
        </div>
        <div id="float-fix"></div>
    </div>    
</div>