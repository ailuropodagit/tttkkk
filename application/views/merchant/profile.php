<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Profile</h1>
    <div id='profile-content'>
        
        <div id="profile-photo">
            <div id="profile-photo-box">
                <?php            
                if(IsNullOrEmptyString($image))
                {
                    ?>
                    <img src="<?php echo base_url().$this->config->item('empty_image'); ?>">
                    <?php
                }
                else
                {
                    ?>
                    <img src="<?php echo base_url() . $image_path . $image ?>">
                    <?php
                }
                ?>  
            </div>
            <?php echo form_open_multipart(uri_string()); ?>
            <?php if (check_correct_login_type($this->main_group_id)) { ?>
                <div id="profile-photo-note">
                    <?php echo $this->config->item('upload_guide_image'); ?>
                </div>
                <div id="profile-photo-input-file">
                    <input type="file" name="userfile" size="10"/>
                </div>
                <div id="profile-photo-button">
                    <button name="button_action" type="submit" value="change_image" >Change Logo</button>
                </div>
            <?php  } ?>
            <?php echo form_close(); ?>
        </div>

        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_company_label', 'company'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($company); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_companyssm_label', 'me_ssm'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($me_ssm); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_category_label', 'me_category_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($me_category_id); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_address_label', 'address'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($address); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_phone_label', 'phone'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($phone); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_website_label', 'website'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($website); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_facebook_url_label', 'facebook_url'); ?><a href="<?php echo base_url() ?>image/exclamation-facebook-url.jpg" target="_blank"><span id="profile-info-form-each-label-icon"><i class="fa fa-exclamation-circle"></i></span></a></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($facebook_url); ?></div>
                </div>
                <?php echo form_hidden('id', $user->id); ?>
                <?php echo form_hidden($csrf); ?>
                <div id='profile-info-form-submit'>
                    <?php 
                    if (check_correct_login_type($this->main_group_id)) 
                    { 
                        ?><button name="button_action" type="submit" value="confirm">Confirm</button><?php
                    }
                    ?>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div id="float-fix"></div>
        
        <?php 
        if($is_supervisor==1)
        { 
            ?>
            <h1>Branch</h1>
            <div id='profile-info' style="margin-top: 20px">
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('branch_name_label', 'branch_name'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($branch_name); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('branch_address_label', 'branch_address'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($branch_address); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('branch_phone_label', 'branch_phone'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($branch_phone); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('branch_state_label', 'branch_state'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($branch_state); ?></div>
                </div>
            </div>
            
            <h1>Supervisor</h1>
            <div id='profile-info' style="margin-top: 20px">
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('supervisor_username_label', 'supervisor_username'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($supervisor_username); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('supervisor_password_label', 'supervisor_password'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($supervisor_password); ?></div>
                </div>
            </div>
            <?php 
        }
        ?>
            
    </div>
</div>

<?php 
 if (check_correct_login_type($this->main_group_id)) 
 { 
     ?>
     <div id='profile-bottom-link'>
         <div id='profile-bottom-link-left'>
             <a href='<?php echo base_url(); ?>merchant/upload_ssm'>Submit SSM forms</a>
         </div>
         <div id="profile-bottom-link-right">
             <div id="profile-bottom-link-right-each">
                 <a href="<?php echo base_url() ?>merchant/branch/add">Add Branch</a>
             </div>
             <div id="profile-bottom-link-right-each">
                 <a href="<?php echo base_url() ?>merchant/branch">View Branch</a>
             </div>
             <div id="profile-bottom-link-right-each">
                 <a href="<?php echo base_url() ?>merchant/supervisor/add">Add Supervisor</a>
             </div>
             <div id="profile-bottom-link-right-each">
                 <a href="<?php echo base_url() ?>merchant/supervisor">View Supervisor</a>
             </div>
             <div id='float-fix'></div>
         </div>
         <div id='float-fix'></div>
     </div>
     <?php
 }
