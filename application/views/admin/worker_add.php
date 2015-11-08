<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<script type="text/javascript">
    function showpassword()
    {
        if (document.getElementById('show_password').checked)
        {
            document.getElementById('password').type = 'text';
            document.getElementById('password_confirm').type = 'text';
        } 
        else
        {
            document.getElementById('password').type = 'password';
            document.getElementById('password_confirm').type = 'password';
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
    <h1>Worker Add</h1>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>   
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
                    <div id='register-form-each-label'><?php echo lang('create_user_email_label', 'email address'); ?></div>
                    <div id='register-form-each-input'><?php echo form_input($email); ?></div>
                </div>
                <div id='register-form-each'>
                    <div id='register-form-each-label'><?php echo lang('create_user_username_label', 'username'); ?></div>
                    <div id='register-form-each-input'><?php echo form_input($username); ?></div>
                </div>
                <div id='register-form-each'>
                    <div id='register-form-each-label'><?php echo lang('create_merchant_password_label', 'password'); ?></div>
                    <div id='register-form-each-input'><?php echo form_input($password); ?></div>
                </div>
<!--                <div id='register-form-each'>
                    <div id='register-form-each-label'><?php //echo lang('create_merchant_password_confirm_label', 'password_confirm'); ?></div>
                    <div id='register-form-each-input'><?php //echo form_input($password_confirm); ?></div>
                </div>
                <div id='login-form-remember-me-forgot-password'>
                    <div id='login-form-forgot-password'>
                        <input type="checkbox" id="show_password" name="show_password" onclick="showpassword();"/>
                        <span class="checkbox-text"><label for='show_password'>Show Password</label></span>
                    </div>   
                    <div id='float-fix'></div>
                </div>-->
            </div>
            <div id='profile-info-form-submit'>                         
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Add</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        
    </div>
</div>