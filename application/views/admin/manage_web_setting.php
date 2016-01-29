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
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_keppo_company_address', 'keppo_company_address'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($keppo_company_address); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_keppo_company_phone', 'keppo_company_phone'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($keppo_company_phone); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_keppo_company_fax', 'keppo_company_fax'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($keppo_company_fax); ?></div>
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
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_friend_success_register_get_money', 'friend_success_register_get_money'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($friend_success_register_get_money); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_register_promo_code_get_candie', 'register_promo_code_get_candie'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($register_promo_code_get_candie); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_merchant_promo_code_get_candie', 'merchant_promo_code_get_candie'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($merchant_promo_code_get_candie); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_popular_hotdeal_number', 'popular_hotdeal_number'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($popular_hotdeal_number); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_popular_redemption_number', 'popular_redemption_number'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($popular_redemption_number); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('web_setting_min_rating_get_for_sort_list', 'min_rating_get_for_sort_list'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($min_rating_get_for_sort_list); ?></div>
                </div> 
            </div>
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        
        <div id="float-fix"></div>
        
    </div>
</div>