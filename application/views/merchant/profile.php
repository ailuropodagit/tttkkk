<h1>Profile</h1>

<style>
    #login-form-each-label, #register-form-each-label {
        width: auto;
    }
</style>

<br/>

<div id="infoMessage"><?php echo $message; ?></div>

<div style="float: left">
    <img src="<?php echo base_url($logo_url); ?>" id='header-logo-img'>
    <br/>
    <?php echo form_open_multipart(uri_string()); ?>
        <?php if (check_correct_login_type($this->main_group_id)) { ?>
            <br/>
            <input type="file" name="userfile" size="20" />
            <br /><br />
            <button name="button_action" type="submit" value="change_image" >Change Logo</button>
        <?php } ?>
    <?php echo form_close(); ?>
</div>

<div style="float: left; width: 550px; border: 0px solid red;"> 
    <?php echo form_open(uri_string()); ?>
    <div id='register' style="text-align: left;">
        <div id='register-form'>

            <div id='register-form-each'>
                <div id='register-form-each-label' style="margin: 0px;"><?php echo lang('create_merchant_company_label', 'company'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($company); ?></div>
            </div>

            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_companyssm_label', 'me_ssm'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($me_ssm); ?></div>
            </div>

            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_address_label', 'address'); ?></div>
                <div id='register-form-each-input'><?php echo form_textarea($address); ?></div>
            </div>

            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_phone_label', 'phone'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($phone); ?></div>
            </div>

            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_website_label', 'website'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($website); ?></div>
            </div>

            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_facebook_url_label', 'facebook_url'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($facebook_url); ?></div>
            </div>

            <?php echo form_hidden('id', $user->id); ?>
            <?php echo form_hidden($csrf); ?>

            <div id='register-form-submit'>
                
                <?php if (check_correct_login_type($this->main_group_id)) { ?>
                <button name="button_action" type="submit" value="confirm">Confirm</button>
                <?php } ?>
                
                <br/><br/>
            </div>

        </div>

        <?php echo form_close(); ?>
    </div>
</div>
    
<div id="float-fix"></div>

<?php if (check_correct_login_type($this->main_group_id)) { ?>
    <a href='<?php echo base_url(); ?>' target='_blank'>Submit SSM forms</a>
<?php } ?>

    <div style="float:right">
        <?php if (check_correct_login_type($this->main_group_id)) { ?>
        <a href="<?php echo base_url() ?>merchant/branch/add">Add Branch</a> &nbsp;
        <a href="<?php echo base_url() ?>merchant/branch">View Branch</a> &nbsp;        
        <a href="<?php echo base_url() ?>merchant/supervisor/add">Add Supervisor</a> &nbsp;
        <a href="<?php echo base_url() ?>merchant/supervisor">View Supervisor</a>
        <?php } ?>
    </div>