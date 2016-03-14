<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>';     
        var temp_folder = '<?php echo $temp_folder ?>';
            $('#hotdeal-file').ajaxfileupload({
      'action': 'http://' + $(location).attr('hostname') + keppo_path + 'all/upload_image_temp',
      'params': {
        'file_name': 'hotdeal-file',
        'image_box_id': 'hotdeal-img'
      },
      'onComplete': function(response) {
        //alert(JSON.stringify(response));
        var post_url = 'http://' + $(location).attr('hostname') + keppo_path + temp_folder;
        //var post_image = "<img src='" + post_url + response + "'>";
        var post_image = post_url + response[0];
        //$( '#upload-for-merchant-form-photo-box' ).html(post_image);
        $('img#'+ response[1]).attr('src', post_image);
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

<div id='hot-deal-advertise'>
    <h1>Advertisement Edit</h1>
    <div id='hot-deal-advertise-content'>
        
        <div id='hot-deal-advertise-today'>
            Advertisement Date : <?php echo $hotdeal_date ?>
        </div>
        
        <div id='hot-deal-advertise-upload-image-note' style="display:none">
            Upload Image Rule : <?php echo $this->config->item('upload_guide_image') ?>
        </div>

        <?php echo form_open_multipart(uri_string()); ?>
            <div id="hot-deal-advertise-form">
                <div id='hot-deal-advertise-form-photo-box'>
                    <?php echo "<img src='".base_url($hotdeal_image)."' id='hotdeal-img'>"; ?>
                </div>
                <div id='hot-deal-advertise-form-input-file'>
                    <?php echo "<input type='file' accept='image/*' name='hotdeal-file' id='hotdeal-file'/>"; ?> 
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
                <div id='hot-deal-advertise-form-each' style="display:none">
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
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_price_before_label"); ?><?php echo form_checkbox($price_before_show); ?>Show</div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_input($hotdeal_price_before);
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_price_after_label"); ?><?php echo form_checkbox($price_after_show); ?>Show</div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_input($hotdeal_price_after);
                        ?>
                    </div>
                </div>
                <div id="float-fix"></div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'>
                        <?php
                            $frozen_status = $hotdeal_frozen == 0? "No" : "Yes";
                            echo 'Frozen/Hide : ' . $frozen_status;
                        ?>
                    </div>
                </div>
            </div>
            <div id="float-fix"></div>
            <?php $have_role = $this->m_custom->check_role_su_can_uploadhotdeal();  
            if($have_role == 1){
             ?>  
            <button name="button_action" type="submit" value="edit_hotdeal" >Save</button>
            <?php if($hotdeal_frozen == 0){ ?>
            <button name="button_action" type="submit" value="frozen_hotdeal" onclick="return confirm('Are you sure want to temporary frozen this hotdeal? After frozen then it will not show publicly until you unfrozen it.')" >Frozen</button>
            <?php }else{ ?>
            <button name="button_action" type="submit" value="unfrozen_hotdeal" >Unfrozen</button>
            <?php } ?>
            <button name="button_action" type="submit" value="remove_hotdeal" onclick="return confirm('Are you sure want to remove it? It cannot be recover.')" >Remove</button>
            <?php    }
            else {
               echo "You don't have permission to edit advertisement";
            }
            ?>
        <?php echo form_close(); ?>

    </div>
</div>