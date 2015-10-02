<script type="text/javascript" src="<?php echo base_url() ?>js/star-rating/jquery.rating.js"></script>
<?php echo link_tag('js/star-rating/jquery.rating.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jgrowl/jquery.jgrowl.js"></script>
<?php echo link_tag('js/jgrowl/jquery.jgrowl.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<div id='hot-deal'>
    <h1><?php echo $page_title; ?></h1>
    <div id='hot-deal-content'>       
        
        <div id="hot-deal-edit-link">
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
        
        <div id='hot-deal-table'>
            <div id='hot-deal-table-row'>
                <div id='hot-deal-table-row-cell' class='hot-deal-left-cell'>
                    <div id='hot-deal-left'>
                        <?php
                        if (!empty($previous_url))
                        {
                            ?><a href="<?php echo $previous_url ?>"><i class="fa fa-angle-double-left"></i></a><?php
                        }
                        else 
                        {
                            ?><div id='hot-deal-left-gray'><i class="fa fa-angle-double-left"></i></div><?php
                        }
                        ?>
                    </div>
                </div>
                <div id='hot-deal-table-row-cell' class='hot-deal-center-cell'>
                    <div id='hot-deal-center'>
                        <div id="hot-deal-title">
                            <a href='<?php echo $merchant_dashboard_url ?>'> <?php echo $merchant_name ?></a>
                        </div>
                        <div id='hot-deal-photo-box'>
                            <img src='<?php echo $image_url ?>'>
                        </div>
                        <div id="hot-deal-sub-title">
                            <?php echo $title ?>
                        </div>
                        <div id="hot-deal-rate-time">
                            <div id="hot-deal-rate">
                                <div style="display:inline;">
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
                                    } //end of for
                                    ?>
                                </div>
                            </div>
                            <div id="hot-deal-time">
                                <span id="hot-deal-time-label" >Upload by : <?php echo $user_name_url; ?></span>
                            </div>
                            <div id="float-fix"></div>
                        </div>
                        <div id="hot-deal-description">
                            <?php echo $description ?>
                        </div>
                        <div id="hot-deal-like-comment-share">
                            <div id="hot-deal-like">
                                <?php echo $like_url; ?>
                            </div>
                            <div id="hot-deal-comment">
                                <?php echo $comment_url; ?>
                            </div>
                            <div id="hot-deal-share">
                                <?php echo "Share :"; ?>
                                <span id="hot-deal-share-facebook">
                                    <a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook-square"></i></a>
                                </span>
                                <span id="hot-deal-share-instagram">
                                    <a href="https://instagram.com" target="_blank"><i class="fa fa-instagram"></i></a>
                                </span>
                            </div>
                            <div id="float-fix"></div>
                        </div>
                        <div id="hot-deal-comment-list">
                            <?php
                            $this->load->view('all/comment_form');
                            ?>
                        </div>
                    </div>
                </div>
                <div id='hot-deal-table-row-cell' class='hot-deal-right-cell'>
                    <div id='hot-deal-right'>
                        <?php
                        if (!empty($next_url))
                        {
                            ?><a href="<?php echo $next_url ?>"><i class="fa fa-angle-double-right"></i></a><?php
                        }
                        else 
                        {
                            ?><div id='hot-deal-right-gray'><i class="fa fa-angle-double-right"></i></div><?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>