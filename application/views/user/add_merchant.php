<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<script type="text/javascript">  
    function get_SubCategory()
    {
        var dep_selected = $('select[name=me_category_id]').val();
        var post_url = "<?php echo base_url(); ?>" + 'merchant/get_sub_category_by_category/' + dep_selected;
        $.ajax({
            type: 'POST',
            url: post_url,
            dataType: 'html',
            success: function (data) 
            {
                $('#me_sub_category_id').empty();
                $('#me_sub_category_id').html(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
                alert(errorThrown);
            }
        });
    }
    
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

<div id='profile'>
    <h1>Add Shop</h1>
    <?php echo form_open_multipart(uri_string()); ?>
        <div id="profile-photo">
            <div id='profile-info-form-each-label'><?php echo 'Merchant Logo : '; ?></div><br/>
            <div id="profile-photo-box">
                    <img src="<?php echo base_url().$this->config->item('empty_image'); ?>" id="userimage">
            </div>          
            <div id="profile-photo-note">
                <?php echo $this->config->item('upload_guide_image'); ?>
            </div>
            <div id="dashboard-photo-input-file">
                <div id="dashboard-photo-choose-button">
                    <div class="fileUpload btn btn-primary">
                        <span>Choose</span>
                        <input type="file" name="userfile" id="userfile" accept='image/*' class="upload"/>
                    </div>
                </div>
                <div id="float-fix"></div>
            </div>
        </div>
    <div id='profile-info'> 
        <div id='profile-info-form'>
<!--            <div id='profile-info-form-each'>
                <div id='profile-info-form-each-label'><?php //echo lang('create_merchant_company_main_label', 'company_main'); ?></div>
                <div id='profile-info-form-each-input'><?php //echo form_input($company_main); ?></div>
            </div>-->
            <div id='profile-info-form-each'>
                <div id='profile-info-form-each-label'><?php echo lang('create_merchant_company_label', 'company'); ?></div>
                <div id='profile-info-form-each-input'><?php echo form_input($company); ?></div>
            </div>
            <div id='profile-info-form-each'>
                <div id='profile-info-form-each-label'><?php echo lang('create_merchant_address_label', 'address'); ?></div>
                <div id='profile-info-form-each-input'><?php echo form_textarea($address); ?></div>
            </div>
<!--            <div id='profile-info-form-each'>
                <div id='profile-info-form-each-label'><?php //echo lang('create_merchant_postcode_label', 'postcode'); ?></div>
                <div id='profile-info-form-each-input'><?php //echo form_input($postcode); ?></div>
            </div>-->
            <div id='profile-info-form-each'>
                <div id='profile-info-form-each-label'><?php echo lang('create_merchant_state_label', 'me_state_id'); ?></div>
                <div id='profile-info-form-each-input'><?php echo form_dropdown($me_state_id, $state_list); ?></div>
            </div>
<!--            <div id='profile-info-form-each'>
                <div id='profile-info-form-each-label'><?php //echo lang('create_merchant_category_label', 'me_category_id'); ?></div>
                <div id='profile-info-form-each-input'><?php //echo form_dropdown($me_category_id, $category_list, 1); ?></div>
            </div>-->
            <div id='profile-info-form-each'>
                <div id='profile-info-form-each-label'><?php echo lang('create_merchant_sub_category_label', 'me_sub_category_id'); ?></div>
                <div id='profile-info-form-each-input'><?php echo form_dropdown($me_sub_category_id, $sub_category_list); ?></div>
            </div>      
<!--            <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'>
                         <img src="<?php //echo base_url() . "/image/logo-halal.png"; ?>" class="logo-halal2"/>  : <?php //echo form_checkbox($me_is_halal); ?>
                    </div>
            </div>  -->
            <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'>
                        <?php echo $this->m_custom->display_halal_label(); ?>  : 
                        <?php echo form_dropdown($me_halal_way, $halal_way_list); ?>
                    </div>
            </div>  
            <div id='profile-info-form-submit'>
                <?php echo form_submit('submit', 'Add Shop'); ?>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
