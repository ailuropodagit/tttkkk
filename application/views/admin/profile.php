<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>

<script type="text/javascript">
    
    $(document).ready(function () {
        var temp_folder = '<?php echo $temp_folder ?>';
        $('#userfile').ajaxfileupload({
            'action': 'http://' + $(location).attr('hostname') + '/keppo/all/upload_image_temp',
            'params': {
                'file_name': 'userfile',
                'image_box_id': 'userimage'
            },
            'onComplete': function (response) {
                //alert(JSON.stringify(response));
                var post_url = 'http://' + $(location).attr('hostname') + '/keppo/' + temp_folder
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
            <?php if ($this->m_custom->check_is_any_admin()) { ?>
                <?php echo form_open_multipart('admin/update_profile_image'); ?>
                    <div id="profile-photo-note">
                        <?php echo $this->config->item('upload_guide_image'); ?>
                    </div>
                    <div id="profile-photo-input-file">
                        <input type="file" name="userfile" id="userfile" size="10"/>
                    </div>
                    <div id="profile-photo-button">
                        <button name="button_action" type="submit" value="change_image" >Change Image</button>
                    </div>
                <?php echo form_close(); ?>
            <?php  } ?>
        </div>
        
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
            </div>
            <?php echo form_hidden('id', $user->id); ?>
            <?php echo form_hidden($csrf); ?>
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="confirm">Confirm</button>
            </div>
        </div>
        
    </div>
</div>