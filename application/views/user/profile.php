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
                    <img src="<?php echo base_url().$this->config->item('empty_image'); ?>">
                    <?php
                }
                else
                {
                    ?>
                    <img src="<?php echo base_url() . $image_path . $image ?>">
                    <?php
                }
                ?>
            </div>
            <?php echo form_open_multipart(uri_string()); ?>
                <div id="profile-photo-note">
                    <?php echo $this->config->item('upload_guide_image'); ?>
                </div>
                <div id="profile-photo-input-file">
                    <input type="file" name="userfile" size="10"/>
                </div>
                <div id="profile-photo-button">
                    <button name="button_action" type="submit" value="change_image" >Change Image</button>
                </div>
            <?php echo form_close(); ?>
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
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_ic_number_label', 'ic number'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($ic_number); ?></div>
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
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_username_label', 'username'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($username); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_email_label', 'email address'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($email); ?></div>
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
            </div>
            <?php echo form_hidden('id', $user->id); ?>
            <?php echo form_hidden($csrf); ?>
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="confirm">Confirm</button>
            </div>
        </div>
        
    </div>
</div>