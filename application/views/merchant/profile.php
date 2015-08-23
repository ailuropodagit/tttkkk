<div id="infoMessage"><?php echo $message; ?></div>

<img src="<?php echo base_url($logo_url); ?>" id='header-logo-img'><br/>
<?php echo form_open_multipart(uri_string()); ?>
<?php if (check_correct_login_type($this->main_group_id)) { ?>
    <input type="file" name="userfile" size="20" />

    <br /><br />

    <button name="button_action" type="submit" value="change_image" >Change Logo</button>
<?php } ?>
</form>
<?php echo form_open(uri_string()); ?>
<div id='register'>
    <div id='register-form'>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_company_label', 'company'); ?></div>
            <div id='register-form-each-text'><?php echo form_input($company); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_companyssm_label', 'me_ssm'); ?></div>
            <div id='register-form-each-text'><?php echo form_input($me_ssm); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_address_label', 'address'); ?></div>
            <div id='register-form-each-text'><?php echo form_textarea($address); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_phone_label', 'phone'); ?></div>
            <div id='register-form-each-text'><?php echo form_input($phone); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_website_label', 'website'); ?></div>
            <div id='register-form-each-text'><?php echo form_input($website); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_facebook_url_label', 'facebook_url'); ?></div>
            <div id='register-form-each-text'><?php echo form_input($facebook_url); ?></div>
        </div>

        <?php echo form_hidden('id', $user->id); ?>
        <?php echo form_hidden($csrf); ?>



        <div id='register-form-submit'>
            <button name="button_action" type="submit" value="add_branch" >Add Branch</button>
            <button name="button_action" type="submit" value="view_branch" >View Branch</button>           
            <?php if (check_correct_login_type($this->main_group_id)) { ?>
                <button name="button_action" type="submit" value="add_supervisor" >Add Supervisor</button>
                <button name="button_action" type="submit" value="view_supervisor" >View Supervisor</button>          
                <button name="button_action" type="submit" value="confirm">Confirm</button>
            <?php } ?>
        </div>

    </div>

    <?php echo form_close(); ?>
</div>
<?php if (check_correct_login_type($this->main_group_id)) { ?>
    <div id='login-tnc'><a href='<?php echo base_url(); ?>' target='_blank'>Submit SSM forms</a></div>
<?php } ?>