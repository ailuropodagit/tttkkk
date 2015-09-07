<div id="infoMessage"><?php echo $message; ?></div>
<a href='<?php echo base_url(); ?>user/album_user_merchant'>Merchant Album</a><br/>
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
                $('#d_image_merchant-' + the_I).empty();    
                $('#d_image_merchant-' + the_I).html(data);
            },
        error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
                alert(errorThrown);
            }
        });
    }

</script>
<div id='hot-deal-advertise'>
    <h1>User upload image for merchant</h1>
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
                        <?php echo "<input type='file' name='image-file-" . $i . "' />"; ?> 
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
                            //echo form_dropdown(${'image_merchant' . $i}, $merchant_list, ${'image_merchant_selected' . $i});
                            echo "<div id='d_image_merchant-" . $i ."'>";
                            echo form_dropdown('temporary', array());
                            echo "</div>";
                            ?>
                        </div>
                    </div>            
                </div>
                <?php } ?>
                <div id="float-fix"></div>
                <button name="button_action" type="submit" value="upload_image" >Upload</button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>