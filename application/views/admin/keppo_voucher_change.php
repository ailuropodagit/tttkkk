<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $(function () {
            $("#start_date,#end_date").datepicker({
                dateFormat: "dd-mm-yy",
            });
            $("#expire_date").datepicker({
                dateFormat: "dd-mm-yy",
            });
        });
        
       var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>'; 
       var temp_folder = '<?php echo $temp_folder ?>';
       $('#candie-file').ajaxfileupload({
      'action': 'http://' + $(location).attr('hostname') + keppo_path + 'all/upload_image_temp',
      'params': {
        'file_name': 'candie-file',
        'image_box_id': 'candie-image'
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
        echo '<h1>Keppo Voucher Add</h1>';
    }
    else
    {
        echo '<h1>Keppo Voucher Edit</h1>';
    }                       
    ?>
    <div id="candie-promotion-content">
        <div id='candie-promotion-form'>
            <?php echo form_open_multipart(uri_string()); ?>

            <div id="candie-promotion-form-photo">
                <div id="candie-promotion-form-photo-box">
                    <img src="<?php echo base_url($candie_image) ?>" id="candie-image" >
                </div>
                <div id='candie-promotion-form-photo-note'>
                    <?php echo $this->config->item('upload_guide_image') ?>
                </div>
                <div id='candie-promotion-form-input-file'>
                    <input type='file' accept='image/*' name='candie-file' id='candie-file'/>
                </div>
            </div>

            <div id="candie-promotion-form-info">
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_title_label'); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php
                        echo form_input($candie_title);
                        ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang("candie_sub_category_label"); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php
                        if ($is_edit == 0)
                        {
                            echo form_dropdown($candie_category, $sub_category_list, $candie_category_selected);
                        }
                        else
                        {
                            echo form_input($candie_category);
                        }                       
                        ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_point_label'); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php echo form_input($candie_point); ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_worth_label'); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php echo form_input($candie_worth); ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang("candie_description_label"); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php echo form_textarea($candie_desc); ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_start_date_label'); ?></div>
                    <div id='candie-promotion-form-each-input' class="candie-promotion-form-each-input-datepicker">
                        <?php echo form_input($start_date); ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_end_date_label'); ?></div>
                    <div id='candie-promotion-form-each-input' class="candie-promotion-form-each-input-datepicker">
                        <?php echo form_input($end_date); ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_expire_date_label'); ?></div>
                    <div id='candie-promotion-form-each-input' class="candie-promotion-form-each-input-datepicker">
                        <?php echo form_input($expire_date); ?>
                    </div>
                </div>
                <div id="candie-promotion-form-voucher-checkbox">
                    <div id="candie-promotion-form-voucher-checkbox-title">Select Terms & Conditions :</div>
                    <?php
                    foreach ($candie_term as $key => $value)
                    {
                        $checked_or_not = '';
                        if (in_array($key, $candie_term_current))
                        {
                            $checked_or_not = 'checked';
                        }
                        ?>
                            <div id="candie-promotion-form-voucher-checkbox-each">
                                    <table border="0" cellpadding="0px" cellspacing="0px">
                                        <tr>
                                            <td valign="top"><input type='checkbox' id="candie-term-<?php echo $key ?>" name='candie_term[]' value='<?php echo $key ?>' <?php echo $checked_or_not; ?>></td>
                                            <td valign="top">
                                                <div id="candie-promotion-form-voucher-checkbox-each-label">
                                                    <label for="candie-term-<?php echo $key ?>"><?php echo $value ?></label>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                            </div>
                    <?php
                    }
                    ?>  
                </div>
                <div id='candie-promotion-extra-terms-n-conditions'>
                    <div id='candie-promotion-extra-terms-n-conditions-title'><?php echo lang("candie_extra_term_label"); ?></div>
                    <?php echo form_textarea($extra_term); ?>
                </div>
                <?php 
                    echo form_hidden($candie_id);
                    $remove_or_recover = $result['hide_flag'] == 1? 'recover' : 'frozen';
                    $remove_or_recover_text = $result['hide_flag'] == 1? 'Recover' : 'Frozen';
                ?>
                <div id='candie-promotion-form-submit'>        
                    <button name="button_action" type="submit" value="<?php echo $remove_or_recover; ?>" onclick="return confirm('Are you sure want to <?php echo $remove_or_recover_text; ?> it?')"><?php echo $remove_or_recover_text; ?></button>                
                    <button name="button_action" type="submit" value="back">Back</button>
                    <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
