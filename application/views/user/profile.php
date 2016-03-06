<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<script type="text/javascript">
    function showraceother()
    {
        var race_id_selected = document.getElementById("race_id");
        var selectedText = race_id_selected.options[race_id_selected.selectedIndex].text;
        if (selectedText == 'Other')
        {
            document.getElementById('race_other_label').style.display = 'inline';
            document.getElementById('race_other').style.display = 'inline';
        } else {
            document.getElementById('race_other_label').style.display = 'none';
            document.getElementById('race_other').style.display = 'none';
        }
    }

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
    
</script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1><?php echo $title; ?></h1>
    <div id='profile-content'>
        
        <?php
        if ($can_edit == 1)
        { 
            ?>
            <div id="profile-photo">
                <div id="profile-photo-box">
                    <?php
                    if (IsNullOrEmptyString($image))
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
                        <img src="<?php echo base_url() . $image_path . $image ?>" id="userimage">
                        <?php
                    }
                    ?>
                </div>
                <?php 
                if (check_correct_login_type($this->config->item('group_id_user')))
                { 
                    //FORM OPEN
                    echo form_open_multipart('user/update_profile_image'); 
                        ?>
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
            </div>
            <?php
        }
        ?>
        
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_first_name_label', 'first name'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($first_name); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_last_name_label', 'last name'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($last_name); ?></div>
                </div>
                
                <?php if ($can_edit == 1)
                { 
                    ?>
                    <div id='profile-info-form-each'>
                        <div id='profile-info-form-each-label'><?php echo lang('create_user_promo_code_label', 'promo_code_no'); ?></div>
                        <div id='profile-info-form-each-input'><?php echo form_input($promo_code_no); ?></div>
                    </div>    
                    <div id='profile-info-form-each'>
                        <div id='profile-info-form-each-label'><?php echo lang('promo_code_redeem_count') . $promo_code_url; ?></div>
                    </div>
                    <?php 
                } 
                ?>
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_description_label', 'description'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($description); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_contact_number_label', 'contact number'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($phone); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_dob_label'); ?></div>
                    <div id='profile-info-form-each-input-dob'>
                        <div id='profile-info-form-each-input-dob-day'><?php echo form_dropdown($day, $day_list,$b_day); ?></div>
                        <div id='profile-info-form-each-input-dob-month'><?php echo form_dropdown($month, $month_list,$b_month); ?></div>
                        <div id='profile-info-form-each-input-dob-year'><?php echo form_dropdown($year, $year_list,$b_year); ?></div>
                        <br/><br/>
                    </div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_age_label', 'age'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($age); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_gender_label', 'gender_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($gender_id, $gender_list, $us_gender_id); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_race_label', 'race_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($race_id, $race_list, $us_race_id); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'>
                        <?php echo form_label(lang('create_user_race_other_label', 'race_other'), 'race_other_label', $race_other_attributes); ?>
                    </div>
                    <div id='profile-info-form-each-input'><?php echo form_input($race_other); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_email_label', 'email address'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($email); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_username_label', 'username'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($username); ?></div>
                </div>                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><label for='instagram_url'>Instagram URL:</label><a href="<?php echo base_url() ?>image/exclamation-instagram-url.jpg" target="_blank"><span id="profile-info-form-each-label-icon"><i class="fa fa-exclamation-circle"></i></span></a></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($instagram_url); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><label for='facebook_url'>Facebook URL:</label><a href="<?php echo base_url() ?>image/exclamation-facebook-url.jpg" target="_blank"><span id="profile-info-form-each-label-icon"><i class="fa fa-exclamation-circle"></i></span></a></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($facebook_url); ?></div>
                </div>
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_is_blogger_label', 'is_blogger'); ?>
                        <?php echo form_checkbox($is_blogger); ?></div>
                </div>
                
                <?php 
                $div_show_hide = "style='display:none'";
                if ($us_is_blogger == 1)
                {
                    $div_show_hide = "style='display:inline'";
                }
                ?>
              
                <div id='profile-blogger-div' <?php echo $div_show_hide; ?>>
                    <div id='profile-info-form-each'>
                        <div id='profile-info-form-each'>
                            <div id='profile-info-form-each-label'><?php echo lang('create_user_validation_blog_label', 'blogger_url'); ?><a href="<?php echo base_url() ?>image/exclamation-blogspot-url.jpg" target="_blank"><span id="profile-info-form-each-label-icon"><i class="fa fa-exclamation-circle"></i></span></a></div>
                            <div id='profile-info-form-each-input'><?php echo form_input($blog_url); ?></div>
                        </div>
                    </div>
                    <div id="candie-promotion-form-voucher-checkbox">
                        <div id="candie-promotion-form-voucher-checkbox-title">Select your blogger type :</div>
                        <?php
                        foreach ($blogger_list as $key => $value)
                        {
                            $checked_or_not = '';
                            if (in_array($key, $blogger_current))
                            {
                                $checked_or_not = 'checked';
                            }
                                ?>
                                <div id="candie-promotion-form-voucher-checkbox-each">
                                    <table border="0" cellpadding="0px" cellspacing="0px">
                                        <tr>
                                            <td valign="top"><input type='checkbox' id="blogger_list-<?php echo $key ?>" name='blogger_list[]' value='<?php echo $key ?>' <?php echo $checked_or_not; ?>></td>
                                            <td valign="top">
                                                <div id="candie-promotion-form-voucher-checkbox-each-label">
                                                    <label for="blogger_list-<?php echo $key ?>"><?php echo $value ?></label>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php                  
                        }
                        ?>  
                    </div>
                </div>     
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_is_photographer_label', 'is_photographer'); ?>
                        <?php echo form_checkbox($is_photographer); ?></div>
                </div>
                
                <?php
                $div_show_hide = "style='display:none'";
                if ($us_is_photographer == 1)
                {
                    $div_show_hide = "style='display:inline'";
                }
                ?>
              
                <div id='profile-photographer-div' <?php echo $div_show_hide; ?>>
                    <div id='profile-info-form-each'>
                        <div id='profile-info-form-each-label'><?php echo lang('create_user_photography_url_label', 'photography_url'); ?></div>
                        <div id='profile-info-form-each-input'><?php echo form_input($photography_url); ?></div>
                    </div>
                    <div id="candie-promotion-form-voucher-checkbox">
                        <div id="candie-promotion-form-voucher-checkbox-title">Select your photography type :</div>
                        <?php
                        foreach ($photography_list as $key => $value)
                        {
                            $checked_or_not = '';
                            if (in_array($key, $photography_current))
                            {
                                $checked_or_not = 'checked';
                            }
                            ?>
                            <div id="candie-promotion-form-voucher-checkbox-each">
                                <table border="0" cellpadding="0px" cellspacing="0px">
                                    <tr>
                                        <td valign="top"><input type='checkbox' id="photography_list-<?php echo $key ?>" name='photography_list[]' value='<?php echo $key ?>' <?php echo $checked_or_not; ?>></td>
                                        <td valign="top">
                                            <div id="candie-promotion-form-voucher-checkbox-each-label">
                                                <label for="photography_list-<?php echo $key ?>"><?php echo $value ?></label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php                  
                        }
                        ?>  
                    </div>
                </div>
            </div>
            <?php echo form_hidden('id', $user_id); ?>
            <?php echo form_hidden($csrf); ?>
            <div id='profile-info-form-submit'>
                <?php if($can_edit == 1){ ?>
                <button name="button_action" type="submit" value="confirm">Confirm</button>
                <?php }else{ ?>
                <button name="button_action" type="submit" value="back">Back</button>
                <?php } ?>
            </div>
        </div>
        <div id="float-fix"></div>
    </div>
</div>