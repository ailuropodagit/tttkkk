<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>

<script type="text/javascript">
    $(document).ready(function(){        
        var temp_folder = '<?php echo $temp_folder ?>';
        for (var counter = 0; counter < <?php echo $box_number ?>; counter++) {
        $('#hotdeal-file-'+counter).ajaxfileupload({
      'action': 'http://' + $(location).attr('hostname') + '/keppo/all/upload_image_temp',
      'params': {
        'file_name': 'hotdeal-file-'+counter,
        'image_box_id': 'hotdeal-img-'+counter
      },
      'onComplete': function(response) {
        //alert(JSON.stringify(response));
        var post_url = 'http://' + $(location).attr('hostname') + '/keppo/' + temp_folder
        //var post_image = "<img src='" + post_url + response + "'>";
        var post_image = post_url + response[0];
        //$( '#upload-for-merchant-form-photo-box' ).html(post_image);
        $('img#'+ response[1]).attr('src', post_image);
      }
    });
    }
});
</script>

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
        
        <div id='hot-deal-advertise-today'>
            Today Hot Deal <?php echo $hotdeal_today_count . ' / ' . $hotdeal_per_day ?> per day
            <?php
            if($hotdeal_today_count_removed!=0){
                echo "(".$hotdeal_today_count_removed. " hot deal today already removed.)";
            }
            ?>
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
                    <?php echo "<input type='file' accept='image/*' name='hotdeal-file-" . $i . "' id='hotdeal-file-" . $i . "' />"; ?> 
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
                        //echo form_textarea(${'hotdeal_desc' . $i});
                        echo "<textarea name='".${'hotdeal_desc' . $i}."' cols='40' rows='10' id='".${'hotdeal_desc' . $i}."' maxlength='500' placeholder='Max 500 words'>"
                                .${'hotdeal_desc_value' . $i}. "</textarea>";
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
                    <div id='hot-deal-advertise-remove' style="display:none">
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