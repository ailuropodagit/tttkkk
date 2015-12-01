<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Worker Edit</h1>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_first_name_label', 'first name'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($first_name); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_last_name_label', 'last name'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($last_name); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_contact_number_label', 'contact number'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($phone); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_email_label', 'email address'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($email); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_username_label', 'username'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($username); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_password_label', 'password'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($password); ?></div>
                </div>
                <div id="candie-promotion-form-voucher-checkbox">
                    <div id="candie-promotion-form-voucher-checkbox-title">Select What Worker Can Do :</div>
                    <?php
                    foreach ($admin_role as $key => $value)
                    {
                        $checked_or_not = '';
                        if (in_array($key, $admin_role_current))
                        {
                            $checked_or_not = 'checked';
                        }
                        ?>
                            <div id="candie-promotion-form-voucher-checkbox-each">
                                    <table border="0" cellpadding="0px" cellspacing="0px">
                                        <tr>
                                            <td valign="top"><input type='checkbox' id="admin-role-<?php echo $key ?>" name='admin_role[]' value='<?php echo $key ?>' <?php echo $checked_or_not; ?>></td>
                                            <td valign="top">
                                                <div id="candie-promotion-form-voucher-checkbox-each-label">
                                                    <label for="admin-role-<?php echo $key ?>"><?php echo $value ?></label>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                            </div>
                    <?php
                    }
                    ?>  
                </div>
            </div>
            <?php 
                echo form_hidden('id', $result['id']); 
                $remove_or_recover = $result['hide_flag'] == 1? 'recover' : 'frozen';
                $remove_or_recover_text = $result['hide_flag'] == 1? 'Recover' : 'Frozen';
            ?>
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="<?php echo $remove_or_recover; ?>" onclick="return confirm('Are you sure want to <?php echo $remove_or_recover_text; ?> it?')"><?php echo $remove_or_recover_text; ?></button>                
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        
        <div id="float-fix"></div>
        
    </div>
</div>