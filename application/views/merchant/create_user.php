<div id='register'>

    <div id='register-link'><a href='<?php echo base_url(); ?>user/register'>User Register</a></div>
    <div id='float-fix'></div>

    <div id='register-title'>Merchant Sign Up</div>

    <div id='register-subtitle'>Already have register? <a href='<?php echo base_url(); ?>merchant/login'>Log In</a></div>

    <div id="infoMessage"><?php echo $message;?></div>

    <div id='register-horizontal-line'></div>

    <?php echo form_open($function_use_for); ?>

    <div id='register-form'>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_company_label', 'company'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($company);?></div>
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
            <div id='register-form-each-label'><?php echo lang('create_merchant_category_label', 'me_category_id'); ?></div>
            <div id='register-form-each-input'><?php echo form_dropdown($me_category_id, $category_list); ?></div>
        </div>
        
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_state_label', 'me_state_id'); ?></div>
            <div id='register-form-each-input'><?php echo form_dropdown($me_state_id, $state_list); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_phone_label', 'phone'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($phone);?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_username_label', 'username'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($username); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_email_label', 'email'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($email); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_password_label', 'password'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($password); ?></div>
        </div>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_password_confirm_label', 'password_confirm'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($password_confirm); ?></div>
        </div>

        <div id='register-form-submit'>
            <?php echo form_submit('submit', 'Sign Up');?>
        </div>

    </div>

    <?php echo form_close();?>

    <div id='login-tnc'>by clicking Sign Up you agree to our new <a href='<?php echo base_url(); ?>terms-and-conditions' target='_blank'>T&C's</a></div>

</div>