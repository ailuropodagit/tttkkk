<div id="infoMessage"><?php echo $message; ?></div>
<?php
echo "<h1>User upload image for merchant</h1>";
if (check_correct_login_type($this->config->item('group_id_user')))
{
    $user_id = $this->ion_auth->user()->row()->id;

    echo "<a href='" . base_url() . "all/album_user_merchant/" . $user_id . "'>Merchant Album</a><br/>";
}
?>
<style>
    #register-form-each-input input[type='text'] {
        width: 190px;
    }
    
    #register-form-each-input textarea {
        width: 190px;
    }
    
    #register-form-each-input select {
        width: 205px;
    }
</style>
<script type="text/javascript">
 
    function get_Merchant(the_I) {

    var dep_selected = $('select[name=image-category-'+the_I+']').val();
    var post_url = "<?php echo base_url(); ?>" + 'user/get_merchant_by_category/'+ the_I + '/' + dep_selected;
    $.ajax({
        type: 'POST',
        url: post_url,
        dataType: 'html',
        success: function (data) {
                $('#image_merchant-' + the_I).empty();    
                $('#image_merchant-' + the_I).html(data);
            },
        error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
                alert(errorThrown);
            }
        });
    }

function JSFunctionValidate()
{
    var validate_fail = 0;
    var box_number = <?php echo $box_number; ?>;
    for (the_I=0; the_I < box_number; the_I++) {
        var file_upload = document.getElementById('image-file-'+ the_I).files.length;
        var title_input = document.getElementById('image-title-'+ the_I).value;
        var merchant_select = document.getElementById('image-merchant-' + the_I);
        if(file_upload != 0 ){
            if(title_input == "" || title_input.length == 0 || title_input == null || title_input.trim() == "" || merchant_select.selectedIndex==-1){
                validate_fail = 1;
                alert("Title and Merchant cannot be empty.");
            }                   
        }
    }
    if(validate_fail == 0){
        return true;
    }
    return false;
}
</script>
<div id='hot-deal-advertise'>
    <div id='hot-deal-advertise-content'>       
        
        <div id='hot-deal-upload-image-note'>
            Upload Image Rule : <?php echo $this->config->item('upload_guide_image') ?>
        </div>
        <div id='float-fix'></div>

        <div id='hot-deal-form'>
            <?php echo form_open_multipart(uri_string()); ?>
                <?php for ($i = 0; $i < $box_number; $i++) { ?>
                <div id="hot-deal-form-each">
                    <div id='hot-deal-photo-box'>
                        <?php echo "<img src='" . base_url(${'image_url' . $i}) . "' id='image_url-" . $i . "'>"; ?>
                    </div>
                    <div id='hot-deal-input-file'>
                        <?php echo "<input type='file' name='image-file-" . $i . "' id='image-file-" . $i . "' />"; ?> 
                    </div>
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('album_title_label'); ?></div>
                        <div id='register-form-each-input'>
                            <?php
                            echo form_input(${'image_title' . $i});
                            ?>
                        </div>
                    </div>
                    <div id='register-form-each'>
                            <div id='register-form-each-label'><?php echo lang("album_description_label"); ?></div>
                            <div id='register-form-each-input'>
                                <?php
                                echo form_textarea(${'image_desc' . $i});
                                ?>
                            </div>
                    </div>  
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang("album_category_label"); ?></div>
                        <div id='register-form-each-input'>
                            <?php
                            echo form_dropdown(${'image_category' . $i}, $category_list);
                            ?>
                    </div>
                    </div>
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang("album_merchant_label"); ?></div>
                        <div id='register-form-each-input'>
                            <?php

                            echo "<div id='image_merchant-" . $i ."'>";
                            if(empty(${'image_merchant_selected' . $i})){
                                echo form_dropdown(${'image_merchant' . $i}, array());
                            }else{
                                echo form_dropdown(${'image_merchant' . $i}, $merchant_list, ${'image_merchant_selected' . $i});
                            }
                            echo "</div>";
                            ?>
                        </div>
                    </div>            
                </div>
                <?php } ?>
                <div id="float-fix"></div>
                <button name="button_action" type="submit" value="upload_image" onclick="return JSFunctionValidate();" >Upload</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>