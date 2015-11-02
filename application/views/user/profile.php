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
    
</script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Profile</h1>
    <div id='profile-content'>
        
        <div id="profile-photo">
            <div id="profile-photo-box">
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
            <?php if (check_correct_login_type($this->config->item('group_id_user'))) { ?>
                <?php echo form_open_multipart('user/update_profile_image'); ?>
                    <div id="profile-photo-note">
                        <?php echo $this->config->item('upload_guide_image'); ?>
                    </div>
                    <div id="profile-photo-input-file">
                        <input type="file" name="userfile" id="userfile" size="10"/>
                    </div>
                    <div id="profile-photo-button">
                        <button name="button_action" type="submit" value="change_image" >Change Image</button>
                    </div>
                <?php echo form_close(); ?>
            <?php  } ?>
        </div>
        
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
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($gender_id, $gender_list, $user->us_gender_id); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_race_label', 'race_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($race_id, $race_list, $user->us_race_id); ?></div>
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
                    <div id='profile-info-form-each-label'><label for='blog_url'>Blog URL:</label><a href="<?php echo base_url() ?>image/exclamation-blogspot-url.jpg" target="_blank"><span id="profile-info-form-each-label-icon"><i class="fa fa-exclamation-circle"></i></span></a></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($blog_url); ?></div>
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
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_is_photographer_label', 'is_photographer'); ?>
                        <?php echo form_checkbox($is_photographer); ?></div>
                </div>
                <?php 
                $div_show_hide = "style='display:none'";
                if($us_is_photographer == 1){ 
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
                        if (in_array($key, $photography_current))
                        {
                            ?>
                            <div id="candie-promotion-form-voucher-checkbox-each">
                                <table border="0" cellpadding="0px" cellspacing="0px">
                                    <tr>
                                        <td valign="top"><input type='checkbox' id="photography_list-<?php echo $key ?>" name='photography_list[]' value='<?php echo $key ?>' checked></td>
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
                        else
                        {
                            ?>
                            <div id="candie-promotion-form-voucher-checkbox-each">
                                <table border="0" cellpadding="0px" cellspacing="0px">
                                    <tr>
                                        <td valign="top"><input type='checkbox' id="photography_list-<?php echo $key ?>" name='photography_list[]' value='<?php echo $key ?>'></td>
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
                    }
                    ?>  
                </div>
                    </div>

            </div>
            <?php echo form_hidden('id', $user->id); ?>
            <?php echo form_hidden($csrf); ?>
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="confirm">Confirm</button>
            </div>
        </div>
        
    </div>
</div>