<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>

<script type="text/javascript">
    //FB SHARE
    FB.init({
         appId  : '<?php echo fb_appID(); ?>',
         status : true, // check login status
         cookie : true, // enable cookies to allow the server to access the session
         xfbml  : true  // parse XFBML
       });

    function fbShare(){        
        FB.ui({
            method : 'feed', 
            link   :  '<?php echo base_url() . uri_string(); ?>',
            caption:  'KEPPO.MY',
            picture: '<?php echo $image_url; ?>',
            name:'<?php echo $user_name; ?>',
            description: '<?php echo limit_character($description, 150, 1); ?>'
       });
       <?php $this->m_custom->activity_share($picture_id, 'usa'); ?>
    }
</script>

<?php
//URI
$fetch_method = $this->router->fetch_method();
?>

<div id="redemption">
    <div id="fb-root"></div>
    <div id="redemption-content">
        <h1><?php echo $page_title; ?></h1>
        <div id="redemption-edit-link">
            <?php
            if (check_is_login())
            {
                $user_id = $this->ion_auth->user()->row()->id;
                $allowed_list = $this->m_custom->get_list_of_allow_id('user_album', 'user_id', $user_id, 'user_album_id');
                if (check_correct_login_type($this->config->item('group_id_user'), $allowed_list, $picture_id))
                {
                    $edit_url = base_url() . "user/edit_user_picture/" . $picture_id;
                    ?>
                    <a href="<?php echo $edit_url ?>">
                        <div id="redemption-edit-link-icon"><i class="fa fa-pencil"></i></div>
                        <div id="redemption-edit-link-label">Edit Picture</div>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
        <div id="float-fix"></div>
        
        <div id="print-area"></div>
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
                <a href="#"><?php echo $title ?></a>
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
if ($fetch_method == 'user_picture')
{
    ?>
    <div id="redemption-bottom-spacing"></div>
    <?php
}
