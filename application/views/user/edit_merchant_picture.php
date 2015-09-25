<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id='hot-deal-advertise'>
    <h1>Edit Picture For Merchant</h1>
    <div id='hot-deal-advertise-content'>
        
        <div id='hot-deal-advertise-today'>
            Picture Date : <?php echo $picture_date ?>
        </div>
        
        <div id='hot-deal-advertise-upload-image-note'>
            Upload Picture Rule : <?php echo $this->config->item('upload_guide_image') ?>
        </div>

        <?php echo form_open_multipart(uri_string()); ?>
            <div id="hot-deal-advertise-form">
                <div id='hot-deal-advertise-form-photo-box'>
                    <?php echo "<img src='".base_url($picture_image)."' id='post-img'>"; ?>
                </div>
                <div id='hot-deal-advertise-form-input-file'>
                    <?php echo "<input type='file' name='post-file' />"; ?> 
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang('hotdeal_title_label'); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_input($picture_title);
                        echo form_hidden($picture_id);
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_sub_category_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_dropdown($picture_merchant, $merchant_list, $picture_merchant_selected);
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_description_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_textarea($picture_desc);
                        ?>
                    </div>
                </div>
                <div id="float-fix"></div>
                <br/>
            </div>
            <div id="float-fix"></div>

            <button name="button_action" type="submit" value="edit_picture" >Save</button>
            <button name="button_action" type="submit" value="remove_picture" onclick="return confirm('Are you sure want to remove it?')" >Remove</button>
            <button name="button_action" type="submit" value="back_picture" >Back</button>
        <?php echo form_close(); ?>

    </div>
</div>