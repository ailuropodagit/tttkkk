<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
            
            <div id="register">
                
                <div id='login-link'><a href='<?php echo base_url(); ?>merchant/register'>Merchant Register</a></div>
                <div id='float-fix'></div>
                
                <div id='register-title'>Sign Up</div>

                <div id='register-subtitle'>Already have register? <a href='<?php echo base_url(); ?>user/login'>Log In</a></div>
            
                <div id="infoMessage"><?php echo $message; ?></div>

                <div id='register-facebook-icon'><img src='<?php echo base_url(); ?>image/facebook-icon.png'></div>

                <div id='register-horizontal-line'></div>
                
                <?php echo form_open("user/register"); ?>       
                
                <div id='register-form'>
                    
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_user_first_name_label', 'first name'); ?></div>
                        <div id='register-form-each-input'><?php echo form_input($first_name); ?></div>
                    </div>
                    
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_user_last_name_label', 'last name'); ?></div>
                        <div id='register-form-each-input'><?php echo form_input($first_name); ?></div>
                    </div>
                    
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_user_contact_number_label', 'contact number'); ?></div>
                        <div id='register-form-each-input'><?php echo form_input($first_name); ?></div>
                    </div>
                   
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_user_username_label', 'username'); ?></div>
                        <div id='register-form-each-input'><?php echo form_input($first_name); ?></div>
                    </div>
                    
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_user_email_label', 'email address'); ?></div>
                        <div id='register-form-each-input'><?php echo form_input($first_name); ?></div>
                    </div>
                    
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_merchant_password_label', 'password');?></div>
                        <div id='register-form-each-input'><?php echo form_input($password);?></div>
                    </div>

                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_merchant_password_confirm_label', 'password_confirm');?></div>
                        <div id='register-form-each-input'><?php echo form_input($password_confirm);?></div>
                    </div>
                    
                    <div id='register-form-submit'>
                        <?php echo form_submit('submit', 'Sign Up');?>
                    </div>
                    
                </div>
                
                <?php echo form_close(); ?>

                <div id='login-tnc'>by clicking Log In, Facebook you agree to our new <a href='<?php echo base_url(); ?>terms-and-conditions' target='_blank'>T&C's</a></div>
                
            </div>
            
        </div>
    </div>
</div>