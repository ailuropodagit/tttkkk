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
    });
    document.title = "<?php echo $browser_title; ?>";  //Second Level To Set Tab Title
    
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
            picture: '<?php echo base_url() . $image_path . $image; ?>',
            name: '<?php echo $company_name; ?>',
            description: '<?php echo limit_character($description, 150, 1); ?>'
        });
    }    
    
//    $(document).ready(function () {
//        var halal_url = '<?php //echo base_url(); ?>' + 'merchant/halal_change';
//        $("#checkbox_halal").click(function() {
//
//        if($("#checkbox_halal").is(':checked')) { 
//            $.ajax({
//             url:halal_url,
//             type: 'post',
//             data: "&halal_desire=1",
//             success : function(resp){
//                                 if (resp)
//                                 {}
//                             },
//                             error: function (resp) {
//                             }
//                         });
//                     } else {
//             $.ajax({
//             url:halal_url,
//             type: 'post',
//             data: "&halal_desire=0",
//             success : function(resp){
//                                 if (resp)
//                                 {}
//                             }
//                         });
//                     }
//                     location.reload();
//                 });
//        });
</script>

<style type="text/css">
    #logo-halal{
        float:right;
    }
    #logo-halal img{
        width:20px;
        margin:3px 0px 0px 7px;
    }
</style>

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
//$dashboard_merchant_slug = $this->uri->segment(3);
//$where_read_user = array('slug'=>$dashboard_merchant_slug);
//$dashboard_users_id = $this->albert_model->read_user($where_read_user)->row()->id;
$dashboard_users_id = $user_id;
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
    
    if (check_correct_login_type($this->config->item('group_id_merchant')) && $dashboard_users_id == $logged_user_id)
    { 
         $self_open = 1;
    }
}
?>
    
<?php
if ($self_open == 1)
{
    ?>
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

                    <script>
                        $(function(){
                            var $page = 0;
                            if ($page == 0){
                                $(".help-guide-navigation-previous").hide();
                            }

                            $(".help-guide-navigation-previous").click(function(){
                                if ($page != 0){
                                    $page = $page - 1;

                                    $.ajax({
                                        url: "<?php echo base_url() ?>help_guide/merchant",
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
                                        $(".help-guide-navigation-previous").hide();
                                    }
                                    if ($page != 10){
                                        $(".help-guide-navigation-next").show();
                                    }
                                }
                            });

                            $(".help-guide-navigation-next").click(function(){
                                if ($page != 10){
                                    $page = $page + 1;

                                    $.ajax({
                                        url: "<?php echo base_url() ?>help_guide/merchant",
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
                                        $(".help-guide-navigation-previous").show();
                                    }
                                    if ($page == 10){
                                        $(".help-guide-navigation-next").hide();
                                    }
                                }
                            });
                        });
                    </script>

                    <button type="button" class="bootstrap-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div id="help-guide">
                        <div id="help-guide-step">
                            <img src="<?php echo base_url() ?>image/help_guide/merchant/merchant-0.png" style="width: 100%;">
                        </div>
                        <div id="help-guide-navigation">
                            <div class="help-guide-navigation-previous">Previous</div>
                            <div class="help-guide-navigation-next">Next</div>
                            <div class="float-fix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
        
<div id="dashboard">
    <div id="fb-root"></div>
    <div id="dashboard-header">
        <div id="dashboard-header-title">
            Dashboard
        </div>    
        <?php 
        if ($self_open == 1) 
        {
            ?>
            <div id="dashboard-header-help-guide-link">
                <div data-toggle="modal" data-target="#help-guide-modal">You Need Help?</div>
            </div>
            <div id="dashboard-header-edit-link">
                <a href='<?php echo base_url('merchant/profile') ?>' class="a-href-button">Edit My Profile</a>
            </div>
            <?php
        }
        ?>
        <div id="dashboard-header-share-button" onclick="fbShare(); return false;">
            <img src="<?php echo base_url() . 'image/social-media-facebook-share.png'; ?>" style="padding-top:5px" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <div class="float-fix"></div>
        <div id="dashboard-header-title-bottom-line"></div>
    </div>
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
            <?php 
            if ($self_open == 1)
            { 
                //FORM OPEN
                echo form_open_multipart('merchant/update_profile_image'); ?>
                <div id="profile-photo-note">
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
                    User followers : <a href='<?php echo base_url() ?>merchant/follower/user/<?php echo $user_id ?>'><?php echo $follower_count ?></a>
                </div>
                <div id="dashboard-photo-following">
                    Merchants following : <a href='<?php echo base_url() ?>merchant/following/user/<?php echo $user_id ?>'><?php echo $following_count ?></a>
                </div>
            </div>
        </div>
        <div id="dashboard-info">
            <div id="dashboard-info-title">
                <div id="dashboard-info-title-name">
                    <?php echo $company_name; ?>
                    <?php if($me_halal_way == 191){ ?>
                    <div id="logo-halal">                  
                        <img src="<?php echo base_url() . "/image/logo-halal.png"; ?>"/>                    
                    </div>
                    <?php }else if($me_halal_way == 192){ ?>                  
                    <div id="logo-halal">                  
                        <img src="<?php echo base_url() . "/image/logo-pork-free.png"; ?>"/>                    
                    </div>
                    <?php } ?>   
                </div>
<!--                <div style='display:none'>
                 <?php 
//                    if ($self_open == 1)
//                    {                      
//                        echo '<span style="font-size:x-small;vertical-align:middle">Halal?</span>';  
//                        echo '<span>';
//                        echo form_checkbox($checkbox_halal);     
//                        echo '</span>';                       
//                    }
                ?>
                </div>-->
                <?php
                //CORRECT LOGIN
                if ($self_open == 1)
                {
                    $promo_code = $this->m_custom->promo_code_get('merchant', $logged_user_id, 1);
                    ?>
                    <div id="dashboard-info-promo-code">
                        Promo Code : <?php echo $promo_code ?>
                    </div>
                    <?php
                }
                
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
                    <?php if(!IsNullOrEmptyString($phone)){ ?>
                    <tr>
                        <td>Phone</td>
                        <td>:</td>
                        <td><div class="text-ellipsis"><?php echo "<a href='tel:".$phone."' >".$phone."</a>"; ?></div></td>
                    </tr>
                    <?php } ?>
                    <?php if(!IsNullOrEmptyString($facebook_url)){ ?>
                    <tr>
                        <td>Facebook URL</td>
                        <td>:</td>
                        <td><div class="text-ellipsis"><?php echo "<a target='_blank' href='".display_url($facebook_url)."' >".$facebook_url."</a>"; ?></div></td>
                    </tr>
                    <?php } ?>
                    <?php if(!IsNullOrEmptyString($website_url)){ ?>
                    <tr>
                        <td>Website</td>
                        <td>:</td>
                        <td><div class="text-ellipsis"><?php echo "<a target='_blank' href='".display_url($website_url)."' >".$website_url."</a>";?></div></td>
                    </tr>  
                    <?php } ?>
                </table>
            </div>
        </div>
        <div id="float-fix"></div>      

        <div id="dashboard-navigation">
            <div id="dashboard-navigation-each" <?php if($uri_segment_4 != "promotion" && $uri_segment_4 != "picture"){ echo 'class="dashboard-navigation-each-active"'; } ?>>
                <a href="<?php echo $hot_deal; ?>"><i class="fa fa-fire dashboard-navigation-each-icon"></i>Food & Beverage</a>
            </div>
            <div id="dashboard-navigation-each" <?php if($uri_segment_4 == "promotion"){ echo 'class="dashboard-navigation-each-active"'; } ?>>
                <a href="<?php echo $candie_promotion; ?>"><i class="fa fa-gift dashboard-navigation-each-icon"></i>Redemption</a>
            </div>
            <div id="dashboard-navigation-each" <?php if($uri_segment_4 == "picture"){ echo 'class="dashboard-navigation-each-active"'; } ?>>
                <a href="<?php echo $user_picture; ?>" ><i class="fa fa-picture-o dashboard-navigation-each-icon"></i>User's Picture</a>
            </div>
            <div id="float-fix"></div>
        </div>
    </div>
</div>