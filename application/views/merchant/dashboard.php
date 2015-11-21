<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
             var temp_folder = '<?php echo $temp_folder ?>';
            $('#userfile').ajaxfileupload({
      'action': 'http://' + $(location).attr('hostname') + '/keppo/all/upload_image_temp',
      'params': {
        'file_name': 'userfile',
        'image_box_id': 'userimage'
      },
      'onComplete': function(response) {
        //alert(JSON.stringify(response));
        var post_url = 'http://' + $(location).attr('hostname') + '/keppo/' + temp_folder
        //var post_image = "<img src='" + post_url + response + "'>";
        var post_image = post_url + response[0];
        //$( '#upload-for-merchant-form-photo-box' ).html(post_image);
        $('img#'+ response[1]).attr('src', post_image);
      }
    });

    
    });
</script>

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

//DASHBOARD
$dashboard_merchant_slug = $this->uri->segment(3);
$where_read_user = array('slug'=>$dashboard_merchant_slug);
$dashboard_users_id = $this->albert_model->read_user($where_read_user)->row()->id;

//LOGGED
if($this->ion_auth->user()->num_rows())
{  
    //LOGGED
    $logged_main_group_id = $this->ion_auth->user()->row()->main_group_id; 
    $logged_user_id = $this->session->userdata('user_id');
    
    //DASHBOARD
    $where_read_user = array('id'=>$dashboard_users_id);
    $dashboard_user_group_id = $this->albert_model->read_user($where_read_user)->row()->main_group_id;
}
?>

<div id="dashboard">
    <h1>Dashboard</h1>
    <div id="dashboard-content">
        <div id='dashboard-photo'>
            <div id="dashboard-photo-box">
                <?php            
                if(IsNullOrEmptyString($image))
                {
                    ?>
                    <img src="<?php echo base_url().$this->config->item('empty_image'); ?>" id="userimage">
                    <?php
                }
                else
                {
                    ?>
                    <img src="<?php echo base_url() . $image_path . $image ?>" id="userimage">
                    <?php
                }
                ?>
            </div>
            <?php if (check_correct_login_type($this->config->item('group_id_merchant'))) { ?>
                <?php echo form_open_multipart('merchant/update_profile_image'); ?>
                <div id="profile-photo-note">
                    <?php echo $this->config->item('upload_guide_image'); ?>
                </div>
                <div id="profile-photo-input-file">
                    <input type="file" name="userfile" id="userfile" size="10"/>
                </div>
                <div id="profile-photo-button">
                    <button name="button_action" type="submit" value="change_image" >Change Logo</button>
                </div>          
                <?php echo form_close(); ?>
            <?php  } ?>
        </div>
        <div id="dashboard-info">
            <div id="dashboard-info-title">
                <div id="dashboard-info-title-name">
                    <?php echo $company_name; ?>
                </div>
                <div id="dashboard-info-edit-link">
                    <?php 
                    if (check_correct_login_type($this->config->item('group_id_merchant')) && $dashboard_users_id == $logged_user_id){
                    echo "<a href='".base_url()."merchant/profile'>Edit My Profile</a>";
                    }
                    ?>
                </div>
                <?php
                //LOGGED IN
                if($this->ion_auth->user()->num_rows())
                {  
                    //USER ONLY
                    if($logged_main_group_id == 5)
                    {
                        ?>
                        <div id="dashboard-info-title-follow">
                            <?php
                            $where_user_follow = array('follower_main_id'=>$logged_user_id, 'following_main_id'=>$dashboard_users_id);
                            $num_rows_user_follow = $this->albert_model->read_user_follow($where_user_follow)->num_rows();  
                            if($num_rows_user_follow)
                            {
                                ?>
                                <form method="POST" action="<?php echo base_url() ?>all/delete_user_follow">
                                    <input type="hidden" name="follower_main_id" value="<?php echo $logged_user_id ?>">
                                    <input type="hidden" name="following_main_id" value="<?php echo $dashboard_users_id ?>">
                                    <input type="hidden" name="current_url" value="<?php echo current_url() ?>">
                                    <input type="submit" value="Unfollow" id="submit-simple">
                                </form>
                                <?php
                            }
                            else
                            {
                                ?>
                                <form method="POST" action="<?php echo base_url() ?>all/create_user_follow">
                                    <input type="hidden" name="follower_id" value="<?php echo $logged_user_id ?>">
                                    <input type="hidden" name="follower_main_id" value="<?php echo $logged_user_id ?>">
                                    <input type="hidden" name="follower_group_id" value="<?php echo $logged_main_group_id ?>">
                                    <input type="hidden" name="following_id" value="<?php echo $dashboard_users_id ?>">
                                    <input type="hidden" name="following_main_id" value="<?php echo $dashboard_users_id ?>">
                                    <input type="hidden" name="following_group_id" value="<?php echo $dashboard_user_group_id ?>">
                                    <input type="hidden" name="current_url" value="<?php echo current_url() ?>">
                                    <input type="submit" value="Follow" id="submit-simple">
                                </form>
                                <?php
                            }
                            ?>
                        </div>        
                        <?php
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
            <div id="dashboard-info-company-description">
                <?php echo nl2br($description); ?>
            </div>
            <div id="dashboard-info-table">
                <table border="0px" cellspacing="0px" cellpadding="5px" style="width: 100%; table-layout: fixed;">
                    <colgroup style="width:125px;"></colgroup>
                    <colgroup style="width:15px;"></colgroup>
                    <tr>
                        <td>Phone</td>
                        <td>:</td>
                        <td><div class="text-ellipsis"><?php echo "<a href='tel:".$phone."' >".$phone."</a>"; ?></div></td>
                    </tr>
                    <tr>
                        <td>Website</td>
                        <td>:</td>
                        <td><div class="text-ellipsis"><?php echo "<a target='_blank' href='".$website_url."' >".$website_url."</a>";?></div></td>
                    </tr>
                    <tr>
                        <td>Facebook URL</td>
                        <td>:</td>
                        <td><div class="text-ellipsis"><?php echo "<a target='_blank' href='".$facebook_url."' >".$facebook_url."</a>"; ?></div></td>
                    </tr>
                </table>
            </div>
            <div id="dashboard-info-followers-following">
                <div id="dashboard-info-followers">
                    User followers : <a href='<?php echo base_url() ?>merchant/follower/user/<?php echo $user_id ?>'><?php echo $follower_count ?></a>
                </div>
                <div id="dashboard-info-following">
                    Merchants following : <a href='<?php echo base_url() ?>merchant/following/user/<?php echo $user_id ?>'><?php echo $following_count ?></a>
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
        </div>
        <div id="float-fix"></div>
    </div>
</div>
