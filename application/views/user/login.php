<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
                        
            <div id="login">
                
                <div id='login-link'><a href='../merchant/login'>Merchents Login</a></div>
                <div id='float-fix'></div>

                <div id='login-title'>Log In</div>
                
                <div id='login-signup'>
                    Don't have an account? <a href='./register'>Sign Up</a>
                </div>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div id='login-facebook-icon'><img src='<?php echo base_url(); ?>image/facebook-icon.png'></div>

                <div id='login-horizontal-line'></div>
                
                <?php echo form_open("user/login"); ?>       
                
                <div id='login-form-username'>
                    <div id='login-form-username-label'><?php echo lang('login_identity_label', 'identity'); ?></div>
                    <div id='login-form-username-text'><?php echo form_input($identity); ?></div>
                </div>

                <div id='login-form-password'>
                    <div id='login-form-password-label'><?php echo lang('login_password_label', 'password'); ?></div>
                    <div id='login-form-password-password'><?php echo form_input($password); ?></div>
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

                <div id='login-form-submit'>
                    <?php echo form_submit('submit', lang('login_submit_btn')); ?>
                </div>

                <?php echo form_close(); ?>

                <div id='login-tnc'>
                    by clicking Log In, Facebook you agree to our new <a href='http://www.google.com'>T&C's</a>
                </div>
                
            </div>

        </div>
    </div>
</div>