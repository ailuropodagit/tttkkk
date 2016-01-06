<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>';      
        var temp_folder = '<?php echo $temp_folder ?>';
            $('#post-file').ajaxfileupload({
      'action': 'http://' + $(location).attr('hostname') + keppo_path + 'all/upload_image_temp',
      'params': {
        'file_name': 'post-file',
        'image_box_id': 'post-img'
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
    <h1>Edit Picture For User</h1>
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
                    <?php echo "<input type='file' accept='image/*' name='post-file' id='post-file' />"; ?> 
                </div>
                <div id='hot-deal-advertise-form-each' style="display:none">
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang('hotdeal_title_label'); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_input($picture_title);                        
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("hotdeal_description_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_textarea($picture_desc);
                        echo form_hidden($picture_id);
                        ?>
                    </div>
                </div>
                <div id='hot-deal-advertise-form-each'>
                    <div id='hot-deal-advertise-form-each-label'><?php echo lang("album_main_label"); ?></div>
                    <div id='hot-deal-advertise-form-each-input'>
                        <?php
                        echo form_dropdown($picture_main_album, $main_album_list, $picture_main_album_selected);
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