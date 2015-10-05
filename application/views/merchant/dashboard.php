<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>
    
<?php
//DASHBOARD USER ID
$dashboard_slug = $this->uri->segment(3);
$where_user = array('slug' => $dashboard_slug);
$query_user = $this->albert_model->read_users($where_user);
$row_array_dashboard_users_id = $query_user->row_array();
$dashboard_users_id = $row_array_dashboard_users_id['id'];
$dashboard_users_group_id = $row_array_dashboard_users_id['main_group_id'];
?>

<div id="dashboard">
    <h1>Dashboard</h1>
    <div id="dashboard-content">
        <div id="dashboard-photo-box">
            <?php            
            if(IsNullOrEmptyString($image))
            {
                ?>
                <img src="<?php echo base_url().$this->config->item('empty_image'); ?>">
                <?php
            }
            else
            {
                ?>
                <img src="<?php echo base_url() . $image_path . $image ?>">
                <?php
            }
            ?>
        </div>
        <div id="dashboard-info">
            <div id="dashboard-info-title">
                <div id="dashboard-info-title-name">
                    <?php echo $company_name; ?>
                </div>
                <?php
                if($this->ion_auth->user()->num_rows())
                {
                    $logged_main_group_id = $this->ion_auth->user()->row()->main_group_id;                       
                    if($logged_main_group_id == 3 || $logged_main_group_id == 5)
                    {
                        if(($logged_main_group_id == 5 && $dashboard_users_group_id == 3))
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
                }
                ?>
                <div id="float-fix"></div>
            </div>
            <div id="dashboard-info-address">
                <?php echo $address; ?>
            </div>
            <div id="dashboard-info-outlet-address">
                <a href="<?php echo $show_outlet ?>">Show outlet Address <i class="fa fa-map-o"></i></a>
            </div>
            <div id="user-comment-input">
                <textarea id="descripton" name="descripton"><?php echo $description; ?></textarea>
            </div>
            <div id="dashboard-info-table">
                <table border="0px" cellspacing="0px" cellpadding="5px" style="width: 100%; table-layout: fixed;">
                    <colgroup style="width:118px;"></colgroup>
                    <colgroup style="width:15px;"></colgroup>
                    <tr>
                        <td>Phone</td>
                        <td>:</td>
                        <td><div class="text-ellipsis"><?php echo "<a href='tel:".$phone."' >".$phone."</a>"; ?></div></td>
                    </tr>
                    <tr>
                        <td>Website</td>
                        <td>:</td>
                        <td><div class="text-ellipsis"><?php echo anchor_popup($website_url, $website_url); ?></div></td>
                    </tr>
                    <tr>
                        <td>Facebook URL</td>
                        <td>:</td>
                        <td><div class="text-ellipsis"><?php echo anchor_popup($facebook_url, $facebook_url); ?></div></td>
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
                    Follower : <a href='<?php echo base_url() ?>all/follower/user/<?php echo $users_id ?>'><?php echo $num_rows_user_follow_follower ?></a>
                </div>
                <div id="dashboard-info-following">
                    Following : <a href='<?php echo base_url() ?>all/following/user/<?php echo $users_id ?>'><?php echo $num_rows_user_follow_following ?></a>
                </div>
            </div>
        </div>
        <div id="float-fix"></div>
        <div id="dashboard-navigation">
            <div id="dashboard-navigation-each">
                <a href="<?php echo $hot_deal; ?>"><i class="fa fa-fire dashboard-navigation-each-icon"></i>Hot Deal</a>
            </div>
            <div id="dashboard-navigation-separater">|</div>
            <div id="dashboard-navigation-each">
                <a href="<?php echo $candie_promotion; ?>"><i class="fa fa-gift dashboard-navigation-each-icon"></i>Redemption</a>
            </div>
            <div id="dashboard-navigation-separater">|</div>
            <div id="dashboard-navigation-each">
                <a href="<?php echo $user_picture; ?>" ><i class="fa fa-picture-o dashboard-navigation-each-icon"></i>User's Picture</a>
            </div>
            <?php
            if (check_correct_login_type($this->config->item('group_id_user')))
            {
                ?>
                <div id="dashboard-navigation-separater">|</div>
                <div id="dashboard-navigation-each"><a href='<?php echo $user_upload_for_merchant ?>'>Upload Picture</a></div>
                <?php
            }
            ?>
        </div>
        <div id="float-fix"></div>
    </div>
</div>
