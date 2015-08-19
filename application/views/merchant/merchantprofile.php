<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>
<div id="infoMessage"><?php echo $message;?></div>
                <?php echo form_open($function_use_for); ?>
                
                <div id='register-form'>

                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_merchant_company_label', 'company'); ?></div>
                        <div id='register-form-each-text'><?php echo form_input($company);?></div>
                    </div>

                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_merchant_companyssm_label', 'me_ssm'); ?></div>
                        <div id='register-form-each-text'><?php echo form_input($me_ssm); ?></div>
                    </div>

                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_merchant_address_label', 'address'); ?></div>
                        <div id='register-form-each-text'><?php echo form_input($address); ?></div>
                    </div>
                    
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_merchant_phone_label', 'phone'); ?></div>
                        <div id='register-form-each-text'><?php echo form_input($phone);?></div>
                    </div>
                    
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_merchant_website_label', 'website'); ?></div>
                        <div id='register-form-each-text'><?php echo form_input($username); ?></div>
                    </div>
                  
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('create_merchant_facebook_url_label', 'facebook_url'); ?></div>
                        <div id='register-form-each-password'><?php echo form_input($password); ?></div>
                    </div>
                       
                    <div id='register-form-submit'>
                        <?php echo form_submit('submit', 'Confirm Change');?>
                    </div>
                    
                </div>

                <?php echo form_close();?>
       </div>
    </div>
</div>