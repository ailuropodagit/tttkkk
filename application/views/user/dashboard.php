<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>'; 
        var temp_folder = '<?php echo $temp_folder ?>';
        $('#userfile').ajaxfileupload({
            'action': 'http://' + $(location).attr('hostname') + keppo_path + 'all/upload_image_temp',
            'params': {
                'file_name': 'userfile',
                'image_box_id': 'userimage'
            },
            'onComplete': function (response) {
                //alert(JSON.stringify(response));
                var post_url = 'http://' + $(location).attr('hostname') + keppo_path + temp_folder;
                //var post_image = "<img src='" + post_url + response + "'>";
                var post_image = post_url + response[0];
                //$( '#upload-for-merchant-form-photo-box' ).html(post_image);
                $('img#' + response[1]).attr('src', post_image);
            }
        });
        
        var can_first_candie_remind = '<?php echo $can_first_candie_remind ?>';
        if(can_first_candie_remind == 1){
            alert('Congratulation! \nYou earn enough candie to have your first Redemption!');
        }
    });
    document.title = "<?php echo $browser_title; ?>";   //Second Level To Set Tab Title
    
    //FB SHARE
    FB.init({
         appId  : '<?php echo fb_appID(); ?>',
         status : true, // check login status
         cookie : true, // enable cookies to allow the server to access the session
         xfbml  : true  // parse XFBML
       });

    function fbShare(){                 
        FB.ui({ 
            method: 'feed', 
            link: '<?php echo base_url() . uri_string(); ?>',
            caption: 'KEPPO.MY',
            picture: '<?php echo $profile_image_url; ?>',
            name: '<?php echo $user_full_name; ?>',
            description: '<?php echo $fb_description; ?>'
        });
    }    
    
    $(function(){
        var $page = 0;
        if ($page == 0){
            $("#help-guide-navigation-previous").hide();
        }
        
        $("#help-guide-navigation-previous").click(function(){
            if ($page != 0){
                $page = $page - 1;
                
                $.ajax({
                    url: "<?php echo base_url() ?>help_guide/user",
                    type: 'post',
                    data: {page: $page},
                    beforeSend: function() {
                        $("#help-guide-step").html("<div id='help-guide-step-loading'><img src='<?php echo base_url() ?>image/loading.gif'></div>");
                    },
                    success: function(data){
                        $("#help-guide-step").html(data);
                    }
                });
                
                if ($page == 0){
                    $("#help-guide-navigation-previous").hide();
                }
                if ($page != 11){
                    $("#help-guide-navigation-next").show();
                }
            }
        });
        
        $("#help-guide-navigation-next").click(function(){
            if ($page != 11){
                $page = $page + 1;
                
                $.ajax({
                    url: "<?php echo base_url() ?>help_guide/user",
                    type: 'post',
                    data: {page: $page},
                    beforeSend: function() {
                        $("#help-guide-step").html("<div id='help-guide-step-loading'><img src='<?php echo base_url() ?>image/loading.gif'></div>");
                    },
                    success: function(data){
                        $("#help-guide-step").html(data);
                    }
                });
                
                if ($page != 0){
                    $("#help-guide-navigation-previous").show();
                }
                if ($page == 11){
                    $("#help-guide-navigation-next").hide();
                }
            }
        });
    });
</script>

<?php
//HELP GUIDE NULL
$where_read_users_help_guide = array('id' => $user_id);
$query_read_users_help_guide = $this->albert_model->read_users_help_guide($where_read_users_help_guide);
$num_rows_read_users_help_guide = $query_read_users_help_guide->num_rows();
if($num_rows_read_users_help_guide != NULL)
{
    ?>
    <script>
        $(function(){
            $('#help-guide-modal').modal('show');
        });
    </script>
    <?php
    $where_update_user = array('id' => $user_id);
    $data_update_user = array('help_guide' => '1');
    $this->albert_model->update_user($where_update_user, $data_update_user);
}
?>

<!--HELP GUIDE MODAL-->
<div id="help-guide-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" id="help-guide-modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="bootstrap-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="help-guide">
                    <div id="help-guide-step">
                        <img src="<?php echo base_url() ?>image/help_guide/user/user-0.png" style="width: 100%;">
                    </div>
                    <div id="help-guide-navigation">
                        <div id="help-guide-navigation-previous">Previous</div>
                        <div id="help-guide-navigation-next">Next</div>
                        <div class="float-fix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

//URI
$uri_segment_4 = $this->uri->segment(4);
$self_open = 0;

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
    
    if (check_correct_login_type($this->config->item('group_id_user')) && $dashboard_users_id == $logged_user_id){
        $self_open = 1;
    }
}
?>

<div id="dashboard">
    <div id="fb-root"></div>
    <div id="dashboard-header">
        <div id="dashboard-header-title">
            Dashboard
        </div>
        <div id="dashboard-header-help-guide-link">
            <div data-toggle="modal" data-target="#help-guide-modal">You Need Help?</div>
        </div>
        <?php if ($self_open == 1)
            { ?>
        <div id="dashboard-header-edit-link">
            <a href='<?php echo base_url('user/profile') ?>' class="a-href-button">Edit My Profile</a>
        </div>
            <?php } ?>
        <div id="dashboard-header-edit-link" onclick="fbShare(); return false;">
            <img src="<?php echo base_url() . 'image/social-media-facebook-share.png'; ?>" style="padding-top:5px" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <div class="float-fix"></div>
        <div id="dashboard-header-title-bottom-line"></div>
    </div>
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
        $us_is_photographer = $row_users['us_is_photographer'];
        $us_photography_url = $row_users['us_photography_url'];
        $us_gender_id = $row_users['us_gender_id'];
        ?>
        <div id='dashboard-photo'>
            <div id="dashboard-user-photo-box">
                <?php            
                if(IsNullOrEmptyString($profile_image))
                {
                    if($us_gender_id == 13)
                    {
                        ?><img src="<?php echo base_url('image/default-image-user-gender-male.png') ?>" id="userimage"><?php
                    }
                    if($us_gender_id == 14)
                    {
                        ?><img src="<?php echo base_url('image/default-image-user-gender-female.png') ?>" id="userimage"><?php
                    }
                }
                else
                {
                    ?>
                    <img src="<?php echo base_url() . $album_user_profile . $profile_image ?>" id="userimage">
                    <?php
                }
                ?>
            </div>
            <?php if ($self_open == 1)
            {
                //FORM OPEN
                echo form_open_multipart('user/update_profile_image');
                ?>
                <div id="dashboard-photo-note">
                    <?php echo $this->config->item('upload_guide_image'); ?>
                </div>
                <div id="dashboard-photo-input-file">    
                    <div id="dashboard-photo-choose-button">
                        <div class="fileUpload btn btn-primary">
                            <span>Choose</span>
                            <input type="file" name="userfile" id="userfile" accept='image/*' class="upload"/>
                        </div>
                    </div>
                    <div id="dashboard-photo-save-button">
                        <button name="button_action" type="submit" value="change_image" >Save</button>
                    </div>
                    <div id="float-fix"></div>
                </div>
                <?php
                //FORM CLOSE
                echo form_close();
            } 
            ?>
            <div id="dashboard-photo-followers-following">
                <div id="dashboard-photo-followers">
                    Followers : <a href='<?php echo base_url() ?>user/follower/user/<?php echo $user_id ?>'><?php echo $follower_count ?></a>
                </div>
                <div id="dashboard-photo-following">
                    Following : <a href='<?php echo base_url() ?>user/following/user/<?php echo $user_id ?>'><?php echo $following_count ?></a>
                </div>
            </div>
        </div>
        <div id="dashboard-info">
            <div id="dashboard-info-title">
                <div id="dashboard-info-title-name">
                    <?php echo $first_name.' '.$last_name; ?>
                </div>
                <?php 
                //CORRECT LOGIN
                if ($self_open == 1)
                {
                    $promo_code = $this->m_custom->promo_code_get('user', $logged_user_id, 1);
                    ?>
                    <div id="dashboard-info-promo-code">
                        Promo Code : <?php echo $promo_code ?>
                    </div>
                    <?php
                }
                ?>
                <div id="float-fix"></div>
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
                    <colgroup style="width:140px;"></colgroup>
                    <colgroup style="width:15px;"></colgroup>
                    <?php
                    //IS PHOTOGRAHPER
                    if($us_is_photographer == 1)
                    {
                        $photography_list = $this->m_custom->many_get_childlist_detail('photography',$dashboard_users_id,'dynamic_option','option_desc', 1);
                        ?>        
                        <tr>
                            <td>Photography URL</td>
                            <td>:</td>
                            <td>
                                <div class="text-ellipsis">
                                    <a href='<?php echo $us_photography_url; ?>' target='_blank'><?php echo $us_photography_url; ?></a>
                                </div>
                            </td>
                        </tr>
<!--                        <tr>
                            <td>Photography Type</td>
                            <td>:</td>
                            <td>
                                <div class="text-ellipsis">
                                    <?php //echo $photography_list ?>
                                </div>
                            </td>
                        </tr>-->
                        <?php 
                    }
                    ?>
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
        </div>
        <div id="float-fix"></div>
        <div id="dashboard-navigation">
            <div id="dashboard-navigation-each" <?php if($uri_segment_4 == ""){ echo 'class="dashboard-navigation-each-active"'; } ?>>
                <a href="<?php echo base_url("all/user_dashboard/$dashboard_users_id#dashboard-navigation") ?>"><i class="fa fa-picture-o dashboard-navigation-each-icon"></i>My Album</a>
            </div>
            <div id="dashboard-navigation-each" <?php if($uri_segment_4 == "merchant_album"){ echo 'class="dashboard-navigation-each-active"'; } ?>>
                <a href="<?php echo base_url("all/user_dashboard/$dashboard_users_id/merchant_album#dashboard-navigation") ?>"><i class="fa fa-file-image-o dashboard-navigation-each-icon"></i>Merchant Album</a>
            </div>
            <div id="float-fix"></div>
        </div>
    </div>
</div>
