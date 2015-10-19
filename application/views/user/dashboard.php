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
            'onComplete': function (response) {
                //alert(JSON.stringify(response));
                var post_url = 'http://' + $(location).attr('hostname') + '/keppo/' + temp_folder
                //var post_image = "<img src='" + post_url + response + "'>";
                var post_image = post_url + response[0];
                //$( '#upload-for-merchant-form-photo-box' ).html(post_image);
                $('img#' + response[1]).attr('src', post_image);
            }
        });
    });
    
    $(function(){
        $image = $('#dashboard-photo-box img');
        width = $image.width();
        height = $image.height();

        $image.css({
            left: 0 - (width / 2),
            top: 0 - (height / 2)
        });
    });
</script>

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

//DASHBOARD
$dashboard_users_id = $this->uri->segment(3);

//LOGGED
if($this->ion_auth->user()->num_rows())
{  
    //LOGGED
    $logged_main_group_id = $this->ion_auth->user()->row()->main_group_id; 
    $logged_user_id = $this->session->userdata('user_id');

    //DASHBOARD
    $where_read_user = array('id'=>$dashboard_users_id);
    $dashboard_user_group_id = $this->albert_model->read_user($where_read_user)->row()->main_group_id;
    
    //SUPERVISOR LOGIN
    if($logged_main_group_id == 4)
    {
        //SUPERVISOR LOGIN
        $where_read_user = array('id'=>$logged_user_id);
        $query_read_user = $this->albert_model->read_user($where_read_user);
        $logged_main_id = $query_read_user->row()->su_merchant_id;
    }
    else
    {
        //NOT SUPERVISOR LOGIN
        $logged_main_id = $logged_user_id;
    }
}
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
        <div id='dashboard-photo'>
            <div id="dashboard-photo-box">
                <?php            
                if(IsNullOrEmptyString($profile_image))
                {
                    ?>
                    <img src="<?php echo base_url() . $empty_image ?>" id="userimage">
                    <?php
                }
                else
                {
                    ?>
                    <img src="<?php echo base_url() . $album_user_profile . $profile_image ?>" id="userimage">
                    <?php
                }
                ?>
            </div>
            <?php if (check_correct_login_type($this->config->item('group_id_user'))) { ?>
                <?php echo form_open_multipart('user/update_profile_image'); ?>
                    <div id="dashboard-photo-note">
                        <?php echo $this->config->item('upload_guide_image'); ?>
                    </div>
                    <div id="dashboard-photo-input-file">
                        <input type="file" name="userfile" id="userfile" size="10"/>
                    </div>
                    <div id="dashboard-photo-button">
                        <button name="button_action" type="submit" value="change_image" >Change Image</button>
                    </div>
                <?php echo form_close(); ?>
            <?php  } ?>
        </div>
        <div id="dashboard-info">
            <div id="dashboard-info-title">
                <div id="dashboard-info-title-name">
                    <?php echo $first_name.' '.$last_name; ?>
                </div>
                <div id="dashboard-info-edit-link">
                    <?php 
                    if (check_correct_login_type($this->config->item('group_id_user')) && $dashboard_users_id == $logged_user_id){
                    echo "<a href='".base_url()."user/profile'>Edit My Profile</a>";
                    }
                    ?>
                </div>
                <?php
                //LOGGED IN
                if($this->ion_auth->user()->num_rows())
                {  
                    //USER, MERCHANT, SUPERVISOR ONLY
                    if($logged_main_group_id == 3 || $logged_main_group_id == 4 || $logged_main_group_id == 5)
                    {
                        //PREVENT SELF FOLLOW
                        if($dashboard_users_id != $logged_user_id)
                        {
                            ?>
                            <div id="dashboard-info-title-follow">
                                <?php                                
                                $where_user_follow = array('follower_main_id'=>$logged_main_id, 'following_main_id'=>$dashboard_users_id);
                                $num_rows_user_follow = $this->albert_model->read_user_follow($where_user_follow)->num_rows();  
                                if($num_rows_user_follow)
                                {
                                    ?>
                                    <form method="POST" action="<?php echo base_url() ?>all/delete_user_follow">
                                        <input type="hidden" name="follower_main_id" value="<?php echo $logged_main_id ?>">
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
                                        <input type="hidden" name="follower_main_id" value="<?php echo $logged_main_id ?>">
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
                }
                ?>
                <div id="float-fix"></div>
            </div>
            <div id="dashboard-info-company-description">
                <?php echo nl2br($description); ?>
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
                    Followers : <a href='<?php echo base_url() ?>user/follower/user/<?php echo $user_id ?>'><?php echo $follower_count ?></a>
                </div>
                <div id="dashboard-info-following">
                    Following : <a href='<?php echo base_url() ?>user/following/user/<?php echo $user_id ?>'><?php echo $following_count ?></a>
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
