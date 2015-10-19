<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<div id='picture-user'>
    <h1><?php echo $page_title; ?></h1>
    <div id='picture-user-content'>       
        
        <div id="picture-user-edit-link">
            <?php
            if (check_is_login())
            {
                $group_id_user = $this->config->item('group_id_user');
                
                $login_id = $this->ion_auth->user()->row()->id;
                $user_allowed_list = $this->m_custom->get_list_of_allow_id('merchant_user_album', 'user_id', $login_id, 'merchant_user_album_id', 'post_type', 'mer');
                if (check_correct_login_type($group_id_user, $user_allowed_list, $picture_id))
                {
                    $edit_url = base_url() . "user/edit_merchant_picture/" . $picture_id;
                    echo "<a href='".$edit_url."'>Edit Picture</a>";
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
                        $action_url = base_url() . "merchant/remove_mua_picture";
                        $confirm_message = "Confirm that you want to remove this picture that user upload for your company? ";
                        ?>
                        <form action="<?php echo $action_url; ?>" onSubmit="return confirm('<?php echo $confirm_message ?>')" method="post" accept-charset="utf-8">
                        <?php
                        echo "<input type='hidden' name='hid_picture_id' value='".$picture_id."' />";
                        echo "<input type='hidden' name='hid_upload_by_user_id' value='".$upload_by_user_id."' />";
                        echo "<button name='button_action' type='submit' value='hide_picture'>Remove Picture</button>";
                        echo form_close(); 
                    }
                }
            }
            ?>
        </div>
        <div id="float-fix"></div>
        
        <div id='picture-user-table'>
            <div id='picture-user-table-row'>
                <div id='picture-user-table-row-cell' class='picture-user-left-cell'>
                    <div id='picture-user-left'>
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
                <div id='picture-user-table-row-cell' class='picture-user-center-cell'>
                    <div id='picture-user-center'>
                        <div id="picture-user-title">
                            <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                        </div>
                        <div id='picture-user-photo-box'>
                            <img src='<?php echo $image_url ?>'>
                        </div>
                        <div id="picture-user-sub-title">
                            <?php echo $title ?>
                        </div>
                        <div id="picture-user-rate-upload-by">
                            <div id="picture-user-rate">
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
                            <div id="picture-user-upload-by">
                                <?php if(check_is_login()){
                                    $login_id = $this->ion_auth->user()->row()->id;
                                    if($picture_user_id != $login_id){
                                        echo 'Upload by : '.$user_name_url;            
                                    }                                                     
                                 }else{
                                     echo 'Upload by : '.$user_name_url;            
                                 }
                                 ?>
                            </div>
                            <div id="float-fix"></div>
                        </div>
                        <div id="picture-user-description">
                            <?php echo $description ?>
                        </div>
                        <div id="picture-user-like-comment-share">
                            <div id="picture-user-like">
                                <?php echo $like_url ?>
                            </div>
                            <div id="picture-user-comment">
                                <?php echo $comment_url ?>
                            </div>
                            <div id="picture-user-share">
                                Share :
                                <span id="picture-user-share-facebook">
                                    <i class="fa fa-facebook-square"></i>
                                </span>
                            </div>
                            <div id="float-fix"></div>
                        </div>
                        <div id="picture-user-comment-list">
                            <?php
                            $this->load->view('all/comment_form');
                            ?>
                        </div>
                    </div>
                </div>
                <div id='picture-user-table-row-cell' class='picture-user-right-cell'>
                    <div id='picture-user-right'>
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
            </div>
        </div>
        
    </div>
</div>