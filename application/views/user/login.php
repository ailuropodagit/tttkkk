<?php
//IF LOGGED IN
if ($this->ion_auth->logged_in())
{
    redirect('user/profile');
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
        <a href='<?php echo base_url(); ?>merchant/login'>
            <i class="fa fa-user-secret" id="login-link-icon"></i>Merchant Login
        </a>
    </div>
    <div id='float-fix'></div>

    <div id='login-title'>User Log In</div>

    <div id='login-subtitle'>Don't have an account? <a href='<?php echo base_url(); ?>user/register'>Sign Up</a></div>

    <div id='login-facebook'>
        <div id="login-facebook-button"><fb:login-button data-size="large" scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button></div>
        <div id="login-facebook-label">Log In with facebook</div>
        <a href="#" onclick="fbLogout()">Logout</a>
    </div>

    <div id='login-horizontal-line'></div>

    <?php echo form_open("user/login"); ?>       

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
            <div id='login-form-forgot-password'>
                <input type="checkbox" id="show_password" name="show_password" onclick="showpassword();"/>
                <span class="checkbox-text"><label for='show_password'>Show Password</label></span>
            </div>   
            <div id='float-fix'></div>
        </div>
        
        <div id='login-form-remember-me-forgot-password'>
            <div id='login-form-remember-me'>
                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?> 
                <?php echo lang('login_remember_label', 'remember'); ?>
            </div>
            <div id='login-form-forgot-password'>
                <a href="retrieve-password"><?php echo lang('login_forgot_password'); ?></a>
            </div>
            <div id='float-fix'></div>
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