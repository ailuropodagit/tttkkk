<?php
//IF MERCHANT LOGGED IN
if ($this->ion_auth->logged_in()) 
{
    redirect('merchant/profile');
}
?>

<script type="text/javascript">
    function showpassword()
    {
        if (document.getElementById('show_password').checked)
        {
            document.getElementById('password').type = 'text';
        } 
        else 
        {
            document.getElementById('password').type = 'password';
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

<div id="login">
    <div id='login-link'>
        <a href='<?php echo base_url(); ?>user/login'>
            <i class="fa fa-user" id="login-link-icon"></i>User Login
        </a>
    </div>
    <div id='float-fix'></div>
    <div id='login-title'>Merchant Log In</div>
    <div id='login-subtitle'>Don't have an account? <a href='<?php echo base_url(); ?>merchant/register'>Sign Up</a></div>
    <div id='login-horizontal-line'></div>
    
    <?php echo form_open("merchant/login"); ?>
    <div id='login-form'>
        <div id='login-form-each'>
            <div id='login-form-each-label'><?php echo lang('login_identity_label', 'identity'); ?></div>
            <div id='login-form-each-input'><?php echo form_input($identity); ?></div>
        </div>
        <div id='login-form-each'>
            <div id='login-form-each-label'><?php echo lang('login_password_label', 'password'); ?></div>
            <div id='login-form-each-input'><?php echo form_input($password); ?> </div>
        </div>
        <div id='login-form-show-password'>
            <input type="checkbox" id="show_password" name="show_password" onclick="showpassword();"/>
            <span class="checkbox-text"><label for='show_password'>Show Password</label></span>
        </div>
        <div id='login-form-remember-me'>
            <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?> 
            <?php echo lang('login_remember_label', 'remember'); ?>
        </div>
        <div id='login-form-forgot-password'>
            <a href="retrieve_password"><?php echo lang('login_forgot_password'); ?></a>
        </div>
        <div id='login-form-submit'><?php echo form_submit('submit', lang('login_submit_btn')); ?></div>
    </div>
    <?php echo form_close(); ?>

    <div id='login-agree'>
        By logging in, you agree to our 
        <a href='<?php echo base_url() ?>terms-of-service' target='_blank'>Terms of Service</a>
        and
        <a href='<?php echo base_url() ?>privacy-policy' target='_blank'>Privacy Policy.</a>
    </div>
</div>