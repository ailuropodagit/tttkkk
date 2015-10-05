<?php
if($this->session->flashdata('message'))
{
    $message = $this->session->flashdata('message');
}
?>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<?php 
//CONFIG DATA
$empty_image = $this->config->item('empty_image');
$album_user_profile = $this->config->item('album_user_profile');
?>

<?php
//DASHBOARD USER ID
$dashboard_users_id = $this->uri->segment(3);
?>

<div id="dashboard">
    <h1>Dashboard</h1>
    <div id="dashboard-content">
        <?php
        //USERS ROW
        $row_users = $query_users->row_array();
        //USERS DATA
        $profile_image = $row_users['profile_image'];
        $first_name = $row_users['first_name'];
        $last_name = $row_users['last_name'];
        $description = $row_users['description'];
        $blog_url = $row_users['us_blog_url'];
        $instagram_url = $row_users['us_instagram_url'];
        $facebook_url = $row_users['us_facebook_url'];
        ?>
        <div id="dashboard-photo-box">
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
                <div id="dashboard-info-title-name">
                    <?php echo $first_name.' '.$last_name; ?>
                </div>
                <?php
                if($this->ion_auth->user()->num_rows())
                {
                    $logged_main_group_id = $this->ion_auth->user()->row()->main_group_id;                    
                    if($logged_main_group_id == 3 || $logged_main_group_id == 5)
                    {
                        $logged_user_id = $this->session->userdata('user_id');
                        if($dashboard_users_id != $logged_user_id)
                        {       
                            ?>
                            <div id="dashboard-info-title-follow">
                                <?php
                                $exists_user_follow = $this->albert_model->exists_user_follow($logged_user_id, $dashboard_users_id);
                                if($exists_user_follow)
                                {
                                    ?>
                                    <form method="POST" action="<?php echo base_url() ?>all/delete_user_follow">
                                        <input type="submit" value="Unfollow" id="submit-simple">
                                        <input type="hidden" name="follow_from_id" value="<?php echo $logged_user_id ?>">
                                        <input type="hidden" name="follow_to_id" value="<?php echo $dashboard_users_id ?>">
                                        <input type="hidden" name="current_url" value="<?php echo current_url() ?>">
                                    </form>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <form method="POST" action="<?php echo base_url() ?>all/create_user_follow">
                                        <input type="submit" value="Follow" id="submit-simple">
                                        <input type="hidden" name="follow_from_id" value="<?php echo $logged_user_id ?>">
                                        <input type="hidden" name="follow_to_id" value="<?php echo $dashboard_users_id ?>">
                                        <input type="hidden" name="current_url" value="<?php echo current_url() ?>">
                                    </form>
                                    <?php
                                }
                                ?>
                            </div>        
                            <?php
                        }
                    }
                }
                ?>
                <div id="float-fix"></div>
            </div>
            <div id="user-comment-input">
                <textarea id="descripton" name="descripton"><?php echo $description; ?></textarea>
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
                <?php
                //FOLLOWER
                $num_rows_user_follow_follower =  $query_user_follow_follower->num_rows();
                //FOLLOWING
                $num_rows_user_follow_following =  $query_user_follow_following->num_rows();
                ?>
                <div id="dashboard-info-followers">
                    Follower : <a href='<?php echo base_url() ?>all/follower/all/<?php echo $users_id ?>'><?php echo $num_rows_user_follow_follower ?></a>
                </div>
                <div id="dashboard-info-following">
                    Following : <a href='<?php echo base_url() ?>all/following/all/<?php echo $users_id ?>'><?php echo $num_rows_user_follow_following ?></a>
                </div>
            </div>
        </div>
        <div id="float-fix"></div>
        
        <div id="dashboard-navigation">
            <div id="dashboard-navigation-each">
                <a href="<?php echo base_url() ?>all/user_dashboard/<?php echo $dashboard_users_id ?>"><i class="fa fa-picture-o dashboard-navigation-each-icon"></i>User Album</a>
            </div>
            <div id="dashboard-navigation-separater">|</div>
            <div id="dashboard-navigation-each">
                <a href="<?php echo base_url() ?>all/user_dashboard/<?php echo $dashboard_users_id ?>/merchant_album"><i class="fa fa-file-image-o dashboard-navigation-each-icon"></i>Merchant Album</a>
            </div>
            <div id="float-fix"></div>
        </div>
        
    </div>
</div>