<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<script type="text/javascript">   
    $(document).ready(function () {    
        $(function () {
            $("#wo_join_date").datepicker({
//                showOn: "both",
//                buttonImage: calendar_url,
//                buttonImageOnly: true,
                dateFormat: "dd-mm-yy",
            });
        });
    });
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
                    <div id='register-form-each-label'><?php echo lang('worker_ic_label', 'us_ic'). ':'; ?></div>
                    <div id='register-form-each-input'><?php echo form_input($us_ic); ?></div>
                </div>
                <div id='register-form-each'>
                    <div id='register-form-each-label'><?php echo lang('worker_id_label', 'wo_worker_id'). ':'; ?></div>
                    <div id='register-form-each-input'><?php echo form_input($wo_worker_id); ?></div>
                </div>
                <div id='register-form-each'>
                    <div id='register-form-each-label'><?php echo lang('worker_department_label', 'wo_department'). ':'; ?></div>
                    <div id='register-form-each-input'><?php echo form_input($wo_department); ?></div>
                </div>
                <div id='register-form-each'>
                    <div id='register-form-each-label'><?php echo lang('create_user_contact_number_label', 'contact number'); ?></div>
<!--                    <div id='register-form-each-input-contact-number'>+60 <?php //echo form_input($phone); ?></div>-->
                    <div id='register-form-each-input'><?php echo form_input($phone); ?>
                </div>
                <div id='register-form-each'>
                    <div id='register-form-each-label'><?php echo lang('create_user_email_label', 'email address'); ?></div>
                    <div id='register-form-each-input'><?php echo form_input($email); ?></div>
                </div>
                <div id='register-form-each'>
                    <div id='register-form-each-label'><?php echo lang('worker_joindate_label', 'wo_join_date'). ':'; ?></div>
                    <div id='register-form-each-input' class="candie-promotion-form-each-input-datepicker"><?php echo form_input($wo_join_date); ?></div>
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
     <div id="float-fix"></div>