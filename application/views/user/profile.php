<div id="infoMessage"><?php echo $message; ?></div>

<div style="margin: 0px auto 0px auto; width: 200px; padding: 0px 0px 15px 0px;">
    <img src="<?php echo base_url($logo_url); ?>" id='header-logo-img'>
    <br/>
    <?php echo form_open_multipart(uri_string()); ?>
        <input type="file" name="userfile" size="20" />
        <br /><br />
        <button name="button_action" type="submit" value="change_image" >Change Image</button>
    <?php echo "<span class='image-upload-guide'>".$this->config->item('image_upload_guide')."</span>";
    echo form_close(); ?>
</div>
    
<?php echo form_open(uri_string()); ?>
<div id='register'>
    <div id='register-form'>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_first_name_label', 'first name'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($first_name); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_last_name_label', 'last name'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($last_name); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_contact_number_label', 'contact number'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($phone); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_dob_label'); ?></div>
            <div id="register-form-each-input-dob">
                <div id='register-form-each-input-dob-day'><?php echo form_dropdown($day, $day_list,$b_day); ?></div>
                <div id='register-form-each-input-dob-month'><?php echo form_dropdown($month, $month_list,$b_month); ?></div>
                <div id='register-form-each-input-dob-year'><?php echo form_dropdown($year, $year_list,$b_year); ?></div>
                <div id="float-fix"></div>
            </div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_age_label', 'age'); ?></div>
            <div id='register-form-each-input'><?php echo form_dropdown($age, $age_list, $user->us_age); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_gender_label', 'gender_id'); ?></div>
            <div id='register-form-each-input'><?php echo form_dropdown($gender_id, $gender_list, $user->us_gender_id); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_race_label', 'race_id'); ?></div>
            <div id='register-form-each-input'><?php echo form_dropdown($race_id, $race_list, $user->us_race_id); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_username_label', 'username'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($username); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_email_label', 'email address'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($email); ?></div>
        </div>

        <?php echo form_hidden('id', $user->id); ?>
        <?php echo form_hidden($csrf); ?>

        <div id='register-form-submit'>     
                <button name="button_action" type="submit" value="confirm">Confirm</button>
        </div>

    </div>

    <?php echo form_close(); ?>
</div>
