<script type="text/javascript">
    function showpassword()
    {
        if (document.getElementById('show_password').checked) 
        {
            document.getElementById('old').type = 'text';
            document.getElementById('new').type = 'text';
            document.getElementById('new_confirm').type = 'text';
        } 
        else
        {
            document.getElementById('old').type = 'password';
            document.getElementById('new').type = 'password';
            document.getElementById('new_confirm').type = 'password';
        }
    }
</script>

<div id="infoMessage"><?php echo $message;?></div>

<div id="change-password">
    <h1>Change Password</h1>
    <div id='change-password-content'>
        <?php echo form_open($function_use_for);?>
        <div id='change-password-form'>
            <div id='change-password-form-each'>
                <div id='change-password-form-each-label'><?php echo lang('change_password_old_password_label', 'old_password'); ?></div>
                <div id='change-password-form-each-input'><?php echo form_input($old_password); ?></div>
            </div>
            <div id='change-password-form-each'>
                <div id='change-password-form-each-label'><label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label></div>
                <div id='change-password-form-each-input'><?php echo form_input($new_password); ?></div>
            </div>
            <div id='change-password-form-each'>
                <div id='change-password-form-each-label'><?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?></div>
                <div id='change-password-form-each-input'><?php echo form_input($new_password_confirm); ?></div>
            </div>
            <div id='change-password-password-visible'>
                <input type="checkbox" id="show_password" name="show_password" onclick="showpassword();"/>
                <span><label for='show_password'>Show Password</label></span>
            </div>
            <div id='change-password-submit'>
                <?php echo form_submit('submit', lang('change_password_submit_btn'));?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>