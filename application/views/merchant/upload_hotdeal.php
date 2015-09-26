<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id='hot-deal-advertise'>
    <h1>Hot Deal Advertise</h1>
    <div id='hot-deal-advertise-content'>
        
        <?php 
        $hotdeal_per_day = $this->config->item("hotdeal_per_day");
        ?>
        
        <div id='hot-deal-advertise-today'>
            Today Hot Deal <?php echo $hotdeal_today_count . ' / ' . $hotdeal_per_day ?> per day
        </div>
        
        <div id='hot-deal-advertise-upload-image-note'>
            Upload Image Rule : <?php echo $this->config->item('upload_guide_image') ?>
        </div>

        <?php 
        //FORM OPEN
        echo form_open_multipart(uri_string());
        
        //LOOP FORM
        for ($i = 0; $i < $hotdeal_per_day; $i++) 
        {
            //HIDDEN INPUT TEXT
            echo form_hidden(${'hotdeal_id' . $i});
            ?>
        
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
                <?php
                //REMOVE CHECKBOX
                if (${'advertise_id_value' . $i} != 0)
                {
                    ?>
                    <div id='hot-deal-advertise-remove'>
                        <?php echo form_checkbox(${'hotdeal_hide' . $i}); ?>
                        <label for="hotdeal_hide-<?php echo $i ?>" id="hot-deal-advertise-remove-label">Remove</label>
                    </div>
                    <?php
                }
                ?>
            </div>
        
            <?php 
        }
        ?>
                  
        <div id='hot-deal-advertise-submit'>
            <button name="button_action" type="submit" value="upload_hotdeal">Save</button>
        </div>
            
        <?php 
        //FORM CLOSE
        echo form_close();
        ?>
                
    </div>
</div>