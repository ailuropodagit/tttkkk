<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id='hot-deal-advertise'>
    <h1>Hot Deal Edit</h1>
    <div id='hot-deal-advertise-content'>
        
        <div id='hot-deal-advertise-today'>
            Hot Deal Date : <?php echo $hotdeal_date ?>
        </div>
        
        <div id='hot-deal-advertise-upload-image-note'>
            Upload Image Rule : <?php echo $this->config->item('upload_guide_image') ?>
        </div>

        <?php echo form_open_multipart(uri_string()); ?>
            <div id="hot-deal-advertise-form">
                <div id='hot-deal-advertise-form-photo-box'>
                    <?php echo "<img src='".base_url($hotdeal_image)."' id='hotdeal-img'>"; ?>
                </div>
                <div id='hot-deal-advertise-form-input-file'>
                    <?php echo "<input type='file' accept='image/*' name='hotdeal-file' />"; ?> 
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang('hotdeal_title_label'); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_input($hotdeal_title);
                        echo form_hidden($hotdeal_id);
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_sub_category_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_dropdown($hotdeal_category, $sub_category_list, $hotdeal_category_selected);
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_description_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_textarea($hotdeal_desc);
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_hour_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_input($hotdeal_hour);
                        ?>
                    </div>
                </div>
                <div id="float-fix"></div>
                <br/>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                            echo 'Remove : ' . form_checkbox($hotdeal_hide);
                        ?>
                    </div>
                </div>
            </div>
            <div id="float-fix"></div>

            <button name="button_action" type="submit" value="edit_hotdeal" >Save</button>

        <?php echo form_close(); ?>

    </div>
</div>