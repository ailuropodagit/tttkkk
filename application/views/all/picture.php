<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
<style type="text/css">
.modal-backdrop {
  z-index: -1;
}
</style>
<script type="text/javascript">
    //FB SHARE
    FB.init({
         appId  : '<?php echo fb_appID(); ?>',
         status : true, // check login status
         cookie : true, // enable cookies to allow the server to access the session
         xfbml  : true  // parse XFBML
       });

    function fbShare(){
        
        var the_id = '<?php echo $picture_id; ?>';
        var post_url = '<?php echo base_url(); ?>' + 'all/fb_share';
       
        FB.ui({
            method : 'feed', 
            link   :  '<?php echo base_url() . uri_string(); ?>',
            caption:  'KEPPO.MY',
            picture: '<?php echo $image_url; ?>',
            name:'<?php echo $merchant_name; ?>',
            description: '<?php echo limit_character($description, 150, 1); ?>'
       },
        function(response) {
          if (response && !response.error_code) {
            //alert('Post was published.');
            $.ajax({
                type: "POST",
                url: post_url,
                dataType: "json",
                data: "&advertise_id=" + the_id + "&advertise_type=mua",
            });
          } else {
            //alert('Post was not published.');
          }
        });
    }    
</script>

<?php
//URI
$fetch_method = $this->router->fetch_method();
?>

<div id="redemption">
    <div id="fb-root"></div>
    <div id="redemption-header">
        <div id="redemption-header-title"><?php echo $page_title; ?></div>
        <?php
        if (check_is_login())
        {
            $group_id_user = $this->config->item('group_id_user');
            $login_id = $this->ion_auth->user()->row()->id;
            $user_allowed_list = $this->m_custom->get_list_of_allow_id('merchant_user_album', 'user_id', $login_id, 'merchant_user_album_id', 'post_type', 'mer');
            if (check_correct_login_type($group_id_user, $user_allowed_list, $picture_id))
            {
                $edit_url = base_url() . "user/edit_merchant_picture/" . $picture_id;
                ?>
                <div id="redemption-header-edit-link">
                    <a href="<?php echo $edit_url ?>" class="a-href-button">Edit Picture</a>
                </div>
                <?php
            }
            $group_id_merchant = $this->config->item('group_id_merchant');
            $group_id_supervisor = $this->config->item('group_id_supervisor');
            if (check_correct_login_type($group_id_merchant) || check_correct_login_type($group_id_supervisor))
            {
                $merchant_id = $login_id;
                if (check_correct_login_type($group_id_supervisor))
                {                   
                    $merchant_id = $this->ion_auth->user()->row()->su_merchant_id;
                }
                $merchant_allowed_list = $this->m_custom->get_list_of_allow_id('merchant_user_album', 'merchant_id', $merchant_id, 'merchant_user_album_id', 'post_type', 'mer');
                $hide_url = base_url() . "merchant/remove_mua_picture/" . $picture_id;
                if (check_allowed_list($merchant_allowed_list, $picture_id))
                {                       
                    ?>
                    <button type="submit" data-toggle = "modal" data-target = "#myModal_Remove" style="float:right">Remove Picture</button>
                    <div class="modal fade" id="myModal_Remove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="bootstrap-close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class ="modal-title" id="myModalLabel">
                                        Why Need To Remove?
                                    </h4>
                                </div>
                                <div class="modal-body"> 
                                    <?php
                                    $action_url = base_url() . "merchant/remove_mua_picture";
                                    $confirm_message = "Confirm that you want to remove this picture that user upload for your company? ";
                                    ?>
                                    <div class="bootstrap-form">
                                        <form action="<?php echo $action_url; ?>" onSubmit="return confirm('<?php echo $confirm_message ?>')" method="post" accept-charset="utf-8">
                                            <div class="bootstrap-form-label">
                                                <div style="color:red; font-weight:bold">Please put in the reason to remove this :</div>
                                            </div>
                                            <div class="bootstrap-form-input">
                                                <input type="text" placeholder="Remove Reason" id="hide_remark" name="hide_remark"><br/>
                                            </div>
                                            <?php
                                            echo "<input type='hidden' name='hid_picture_id' value='" . $picture_id . "' />";
                                            echo "<input type='hidden' name='hid_upload_by_user_id' value='" . $upload_by_user_id . "' />";
                                            ?>
                                            <div class="bootstrap-form-button">
                                                <button name='button_action' type='submit' value='hide_picture'>Confirm Remove</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>
        <div class='float-fix'></div>
        <div id='album-user-header-title-bottom-line'></div>       
    </div>
        
    <div id="redemption-content">
        <div id="redemption-photo">
            <div id='redemption-table'>
                <div id='redemption-table-row'>
                    <div id='redemption-table-row-cell' class='redemption-left-cell'>
                        <div id='redemption-left'>
                            <?php
                            if (!empty($previous_url))
                            {
                                ?><a href="<?php echo $previous_url ?>"><i class="fa fa-angle-double-left"></i></a><?php
                            }
                            else 
                            {
                                ?><div id='picture-user-left-gray'><i class="fa fa-angle-double-left"></i></div><?php
                            }
                            ?>
                        </div>
                    </div>
                    <div id='redemption-table-row-cell' class='redemption-center-cell'>
                        <div id='redemption-center'>
                            <div id="redemption-photo-box" class="zoom-image">
                                <img src='<?php echo $image_url ?>'>
                            </div>
                        </div>
                    </div>
                    <div id='redemption-table-row-cell' class='redemption-right-cell'>
                        <div id='redemption-right'>
                            <?php
                            if (!empty($next_url))
                            {
                                ?><a href="<?php echo $next_url ?>"><i class="fa fa-angle-double-right"></i></a><?php
                            }
                            else 
                            {
                                ?><div id='picture-user-right-gray'><i class="fa fa-angle-double-right"></i></div><?php
                            }
                            ?>
                        </div>
                    </div>
                    <div id='picture-user-table-row-cell' class='picture-user-right-cell'></div>
                </div>
            </div>
        </div>
        <div id="redemption-information">
            <!--TITLE-->
            <div id="redemption-information-title">
                <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
            </div>
            <div class="float-fix"></div>
            <!--RATE-->
            <div id="redemption-information-rate">
                <div id="redemption-information-rate-star">
                    <?php
                    echo form_input($item_id);
                    echo form_input($item_type);
                    for ($i = 1; $i <= 5; $i++)
                    {
                        if ($i == round($average_rating))
                        {
                            echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "' checked='checked'/>";
                        }
                        else
                        {
                            echo "<input class='auto-submit-star' type='radio' name='rating' " . $radio_level . " value='" . $i . "'/>";
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
            //DESCRIPTION
            if ($description)
            {
                ?>
                <div id="redemption-information-description">
                    <?php echo $description ?>
                </div>
                <?php
            }
            ?>
            <!--LIKE COMMENT-->
            <div id="redemption-information-like-comment">
                <div id="redemption-information-like-comment">
                    <div id="redemption-information-like">
                        <?php echo $like_url; ?>
                    </div>
                    <div id="redemption-information-comment">
                        <?php echo $comment_url; ?>
                    </div>
                    <div id="redemption-information-like-comment-earn-candie">
                        <?php
//                        $like_comment_candie_earn = $this->m_custom->display_trans_config(2);
//                        echo "Earn : " . $like_comment_candie_earn . " candies"; 
                        ?>
                        CLICK BY EARN CANDIES
                    </div>
                </div>
            </div>
            <div id="redemption-information-horizontal-separator"></div>
            <!--SHARE-->
            <div id="redemption-information-share">
                <div id="redemption-information-share-label">
                    Share This Redemption
                </div>
                <div id="redemption-information-share-facebook" onclick="fbShare(); return false;">
                    <img src="<?php echo base_url() . 'image/social-media-facebook-share.png'; ?>" >
                </div>
                <div id="redemption-information-share-earn-candie">
                    <?php //echo "Earn : " . $this->m_custom->display_trans_config(10) . " candies" ?>
                </div>
            </div>
            <div id="redemption-information-upload-by">
                <?php 
                if(check_is_login())
                {
                    $login_id = $this->ion_auth->user()->row()->id;
                    if($picture_user_id != $login_id)
                    {
                        echo 'Upload by : '.$user_name_url;            
                    }
                }
                else
                {
                    echo 'Upload by : '.$user_name_url;            
                }
                ?>
            </div>
            <!--TAB BOX--> 
            <div id='redemption-information-tab-box'>
                <div id='redemption-information-tab-box-title'>User Comment</div>
                <div id="redemption-information-tab-box-user-comment">
                    <?php
                    $this->load->view('all/comment_form');
                    ?>
                </div>
            </div>
        </div>
        <div class="float-fix"></div>
    </div>
</div>

<?php 
if ($fetch_method == 'merchant_user_picture')
{
    ?>
    <div id="redemption-bottom-spacing"></div>
    <?php
}
