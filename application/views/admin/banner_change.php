<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/chosen/chosen.jquery.min.js"></script>
<?php echo link_tag('js/chosen/chosen.min.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>

<script type="text/javascript">   
    $(document).ready(function () {    
        $(function () {
            $("#banner_start_time").datepicker({
//                showOn: "both",
//                buttonImage: calendar_url,
//                buttonImageOnly: true,
                dateFormat: "dd-mm-yy",
            });
            $("#banner_end_time").datepicker({
//                showOn: "both",
//                buttonImage: calendar_url,
//                buttonImageOnly: true,
                dateFormat: "dd-mm-yy",
            });
            $(".chosen-select").chosen();
        });
        
        var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>';
        var temp_folder = '<?php echo $temp_folder ?>';
        $('#image-file-name').ajaxfileupload({
            'action': 'http://' + $(location).attr('hostname') + keppo_path + 'all/upload_image_temp',
            'params': {
              'file_name': 'image-file-name',
              'image_box_id': 'image-item'
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

<div id="candie-promotion">   
    <?php
    if ($is_edit == 0)
    {
        echo '<h1>Banner Add</h1>';
    }
    else
    {
        echo '<h1>Banner Edit</h1>';
    }                       
    ?>
    <div id='profile-content'>       
        <?php echo form_open_multipart(uri_string()); ?>
        <div id="candie-promotion-form-photo" style="float:left;">
                <div id='profile-info-form-each-label'><?php echo lang('banner_image', 'image-item'); ?></div>
                <div id="candie-promotion-form-photo-box" style="width:400px;height:400px">
                    <img src="<?php echo base_url($image_item) ?>" id="image-item" >
                </div>
                <div id='candie-promotion-form-input-file'>
                    <input type='file' accept='image/*' name='image-file-name' id='image-file-name'/>
                </div>
        </div>
        <div id='profile-info'>
            
            <div id='profile-info-form' style="float:left">   
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_position', 'banner_position_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($banner_position_id, $banner_position_list, $banner_position_selected); ?></div>
                </div>
                <div id="dashboard-photo-note" >Make sure the image size is suitable, can check the banner view in home page after save</div>
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_merchant', 'merchant_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($merchant_id, $merchant_list, $merchant_selected); ?></div>
                </div>                               
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_start_time', 'banner_start_time'); ?></div>
                    <div id='profile-info-form-each-input' class="candie-promotion-form-each-input-datepicker"><?php echo form_input($banner_start_time); ?></div>
                </div>
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_end_time', 'banner_end_time'); ?></div>
                    <div id='profile-info-form-each-input' class="candie-promotion-form-each-input-datepicker"><?php echo form_input($banner_end_time); ?></div>
                </div>
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_url', 'banner_url'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($banner_url); ?></div>
                </div>                              
                
                <?php
                echo form_hidden($edit_id);
                $remove_or_recover = $result['hide_flag'] == 1? 'recover' : 'frozen';
                $remove_or_recover_text = $result['hide_flag'] == 1? 'Recover' : 'Hide';
                ?>
                <div id='profile-info-form-submit'>
                    <?php 
                    if($is_edit != 0){ ?>
                    <button name="button_action" type="submit" value="<?php echo $remove_or_recover; ?>" onclick="return confirm('Are you sure want to <?php echo $remove_or_recover_text; ?> it?')"><?php echo $remove_or_recover_text; ?></button>                
                    <?php } ?>
                    <button name="button_action" type="submit" value="back">Back</button>
                    <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
                </div>
            </div>
            
        </div>
        
        <div id="float-fix"></div>

            <?php echo form_close(); ?>
    </div>
</div>
