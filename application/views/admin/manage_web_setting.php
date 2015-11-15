<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Web Setting Edit</h1>
    <div id='profile-content'>  
        <?php
        $this->load->view('admin/manage_setting_sub_menu');
        ?>
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_keppo_company_name', 'keppo_company_name'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($keppo_company_name); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_keppo_admin_email', 'keppo_admin_email'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($keppo_admin_email); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_merchant_minimum_balance', 'merchant_minimum_balance'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($merchant_minimum_balance); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_merchant_max_hotdeal_per_day', 'merchant_max_hotdeal_per_day'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($merchant_max_hotdeal_per_day); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_user_max_picture_per_day', 'user_max_picture_per_day'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($user_max_picture_per_day); ?></div>
                </div>  
            </div>
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        
    </div>
</div>