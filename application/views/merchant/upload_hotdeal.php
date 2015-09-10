<div id="infoMessage"><?php echo $message; ?></div>

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

<div id='hot-deal-advertise'>
    <h1>Hot Deal Advertise</h1>
    <div id='hot-deal-advertise-content'>
        
        <div id='hot-deal-advertise-today'>
            <?php $hotdeal_per_day = $this->config->item("hotdeal_per_day"); ?>
            Today Hot Deal <?php echo $hotdeal_today_count . ' / ' . $hotdeal_per_day ?> per day
        </div>
        
        <div id='hot-deal-advertise-upload-image-note'>
            Upload Image Rule : <?php echo $this->config->item('upload_guide_image') ?>
        </div>

        <?php echo form_open_multipart(uri_string()); ?>
            <?php for ($i = 0; $i < $hotdeal_per_day; $i++) { ?>
            <div id="hot-deal-advertise-form">
                <div id='hot-deal-advertise-form-photo-box'>
                    <?php echo "<img src='" . base_url(${'hotdeal_image' . $i}) . "' id='hotdeal-img-" . $i . "'>"; ?>
                </div>
                <div id='hot-deal-advertise-form-input-file'>
                    <?php echo "<input type='file' name='hotdeal-file-" . $i . "' />"; ?> 
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang('hotdeal_title_label'); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_input(${'hotdeal_title' . $i});
                        echo form_hidden(${'hotdeal_id' . $i});
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_sub_category_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_dropdown(${'hotdeal_category' . $i}, $sub_category_list, ${'hotdeal_category_selected' . $i});
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_description_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_textarea(${'hotdeal_desc' . $i});
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_hour_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        //echo form_dropdown(${'hotdeal_hour' . $i}, $hour_list, ${'hotdeal_hour_selected' . $i});
                        echo form_input(${'hotdeal_hour' . $i});
                        ?>
                    </div>
                </div>
                <div id="float-fix"></div>
                <br/>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        if (${'advertise_id_value' . $i} != 0)
                        {
                            ?><label id="hot-deal-advertise-remove-label">Remove</label>
                            <?php
                            echo form_checkbox(${'hotdeal_hide' . $i});
                        }
                        else 
                        {
                            echo "&nbsp;";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div id="float-fix"></div>
            <button name="button_action" type="submit" value="upload_hotdeal" >Save</button>
        <?php echo form_close(); ?>
                
    </div>
</div>