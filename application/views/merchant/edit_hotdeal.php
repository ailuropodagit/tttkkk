<div id="infoMessage"><?php echo $message; ?></div>

<div id='hot-deal-advertise'>
    <h1>Hot Deal Edit</h1>
    <div id='hot-deal-advertise-content'>
        
        <div id='hot-deal-today'>
            Hot Deal Date : <?php echo $hotdeal_date ?>
        </div>
        <div id='float-fix'></div>
        
        <div id='hot-deal-upload-image-note'>
            Upload Image Rule : <?php echo $this->config->item('upload_guide_image') ?>
        </div>
        <div id='float-fix'></div>

        <div id='hot-deal-form'>
            <?php echo form_open_multipart(uri_string()); ?>

                <div id="hot-deal-form-each">
                    <div id='hot-deal-photo-box'>
                        <?php echo "<img src='".base_url($hotdeal_image)."' id='hotdeal-img'>"; ?>
                    </div>
                    <div id='hot-deal-input-file'>
                        <?php echo "<input type='file' name='hotdeal-file' />"; ?> 
                    </div>
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang('hotdeal_title_label'); ?></div>
                        <div id='register-form-each-input'>
                            <?php
                            echo form_input($hotdeal_title);
                            echo form_hidden($hotdeal_id);
                            ?>
                        </div>
                    </div>
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang("hotdeal_sub_category_label"); ?></div>
                        <div id='register-form-each-input'>
                            <?php
                            echo form_dropdown($hotdeal_category, $sub_category_list, $hotdeal_category_selected);
                            ?>
                        </div>
                    </div>
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang("hotdeal_description_label"); ?></div>
                        <div id='register-form-each-input'>
                            <?php
                            echo form_textarea($hotdeal_desc);
                            ?>
                        </div>
                    </div>
                    <div id='register-form-each'>
                        <div id='register-form-each-label'><?php echo lang("hotdeal_hour_label"); ?></div>
                        <div id='register-form-each-input'>
                            <?php
                            echo form_input($hotdeal_hour);
                            ?>
                        </div>
                    </div>
                    <div id="float-fix"></div>
                    <br/>
                    <div id='register-form-each'>
                        <div id='register-form-each-input'>
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
</div>