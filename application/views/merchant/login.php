<?php
//IF MERCHANT LOGGED IN
if($this->ion_auth->logged_in()){
    redirect('merchant/profile');
}
?>

<div id="login">

    <div id='login-link'><a href='<?php echo base_url(); ?>user/login'>User Login</a></div>
    <div id='float-fix'></div>

    <div id='login-title'>Merchant Log In</div>

    <div id='login-subtitle'>Don't have an account? <a href='<?php echo base_url(); ?>merchant/register'>Sign Up</a></div>

    <div id="infoMessage"><?php echo $message; ?></div>

    <div id='login-horizontal-line'></div>

    <?php echo form_open("merchant/login"); ?>

    <div id='login-form'>
        <div id='login-form-each'>
            <div id='login-form-each-label'><?php echo lang('login_identity_label', 'identity'); ?></div>
            <div id='login-form-each-input'><?php echo form_input($identity); ?></div>
        </div>

        <div id='login-form-each'>
            <div id='login-form-each-label'><?php echo lang('login_password_label', 'password'); ?></div>
            <div id='login-form-each-input'><?php echo form_input($password); ?></div>
        </div>

        <div id='login-form-remember-me-forgot-password'>
            <div id='login-form-remember-me'>
                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?> 
                <?php echo lang('login_remember_label', 'remember'); ?>
            </div>
            <div id='login-form-forgot-password'>
                <a href="retrieve_password"><?php echo lang('login_forgot_password'); ?></a>
            </div>
            <div id='float-fix'></div>
        </div>
        <div id='login-form-submit'><?php echo form_submit('submit', lang('login_submit_btn')); ?></div>
    </div>

    <?php echo form_close(); ?>

    <div id='login-tnc'>by clicking Log In, you agree to our new <a href='<?php echo base_url(); ?>terms-and-conditions' target='_blank'>T&C's</a></div>

</div>