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
                    <div id='profile-info-form-each-label'><?php echo lang('worker_ic_label', 'us_ic'). ':'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($us_ic); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('worker_id_label', 'wo_worker_id'). ':'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($wo_worker_id); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('worker_department_label', 'wo_department'). ':'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($wo_department); ?></div>
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
                    <div id='profile-info-form-each-label'><?php echo lang('worker_joindate_label', 'wo_join_date'); ?></div>
                    <div id='profile-info-form-each-input' class="candie-promotion-form-each-input-datepicker"><?php echo form_input($wo_join_date); ?></div>
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
                    foreach ($admin_role as $row)
                    {
                        $key = $row['option_id'];
                        $value = $row['option_text'];
                        $image_url = base_url() . 'image/worker_role/' . $row['option_desc'];
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
                                                    <label for="admin-role-<?php echo $key ?>"><?php echo $value ?></label><a href="<?php echo $image_url ?>" target="_blank"><span id="profile-info-form-each-label-icon"><i class="fa fa-exclamation-circle"></i></span></a>
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