<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<script type="text/javascript">
    
    $(document).ready(function () {        
        var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>';    
        var temp_folder = '<?php echo $temp_folder ?>';
        $('#userfile').ajaxfileupload({
            'action': 'http://' + $(location).attr('hostname') + keppo_path + 'all/upload_image_temp',
            'params': {
                'file_name': 'userfile',
                'image_box_id': 'userimage'
            },
            'onComplete': function (response) {
                //alert(JSON.stringify(response));
                var post_url = 'http://' + $(location).attr('hostname') + keppo_path + temp_folder;
                //var post_image = "<img src='" + post_url + response + "'>";
                var post_image = post_url + response[0];
                //$( '#upload-for-merchant-form-photo-box' ).html(post_image);
                $('img#' + response[1]).attr('src', post_image);
            }
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
    <h1>Profile</h1>
    <div id='profile-content'>
        
        <div id="profile-photo">
            <div id="profile-photo-box">
                <?php            
                if(IsNullOrEmptyString($image))
                {
                    ?>
                    <img src="<?php echo base_url().$this->config->item('empty_image'); ?>" id="userimage">
                    <?php
                }
                else
                {
                    ?>
                    <img src="<?php echo base_url() . $image_path . $image ?>" id="userimage">
                    <?php
                }
                ?>  
            </div>          
            <?php if (check_correct_login_type($this->config->item('group_id_merchant'))) { ?>
                <?php echo form_open_multipart('merchant/update_profile_image'); ?>
            
                <div id="profile-photo-note">
                    <?php echo $this->config->item('upload_guide_image'); ?>
                </div>
                <div id="dashboard-photo-input-file">                      
                    <div class="fileUpload btn btn-primary" style="float:left">
                        <span>Choose</span>
                        <input type="file" name="userfile" id="userfile" accept='image/*' class="upload"/>
                    </div>
                    <div id="dashboard-photo-button" style="float:right">
                        <button name="button_action" type="submit" value="change_image" >Save</button>
                    </div>
                    <div id="float-fix"></div>
                </div>
                <?php echo form_close(); ?>
            <?php  } ?>
        </div>

        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_company_main_label', 'company_main'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($company_main); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_company_label', 'company'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($company); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_promo_code_label', 'promo_code_no'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($promo_code_no); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('promo_code_redeem_count') . $promo_code_url; ?></div>
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
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_sub_category_label', 'me_sub_category_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($me_sub_category_id, $sub_category_list, $sub_category_selected); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_address_label', 'address'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($address); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_postcode_label', 'postcode'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($postcode); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_state_label', 'me_state_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($me_state_id, $state_list, $state_selected); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_country_label', 'me_country'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($me_country, $country_list, $country_selected); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_description_label', 'description'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($description); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_phone_label', 'phone'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($phone); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_person_incharge_label', 'person_incharge'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($person_incharge); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_person_contact_label', 'person_contact'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($person_contact); ?></div>
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
            <div id='profile-branch-info'>
                <div id='profile-info-form'>
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
            </div>
            <div id="float-fix"></div>
            
            <h1>Supervisor</h1>
            <div id='profile-branch-info'>
                <div id='profile-info-form'>
                    <div id='profile-info-form-each'>
                        <div id='profile-info-form-each-label'><?php echo lang('supervisor_username_label', 'supervisor_username'); ?></div>
                        <div id='profile-info-form-each-input'><?php echo form_input($supervisor_username); ?></div>
                    </div>
                    <div id='profile-info-form-each'>
                        <div id='profile-info-form-each-label'><?php echo lang('supervisor_password_label', 'supervisor_password'); ?></div>
                        <div id='profile-info-form-each-input'><?php echo form_input($supervisor_password); ?></div>
                    </div>
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
