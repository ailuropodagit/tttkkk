<script type="text/javascript" src="<?php echo base_url() ?>js/chosen/chosen.jquery.min.js"></script>
<?php echo link_tag('js/chosen/chosen.min.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>

<script type="text/javascript">
    function get_Merchant(the_I)
    {
        var dep_selected = $('select[name=image-category-' + the_I + ']').val();
        var post_url = "<?php echo base_url(); ?>" + 'user/get_merchant_by_category/' + the_I + '/' + dep_selected;
        $.ajax({
            type: 'POST',
            url: post_url,
            dataType: 'html',
            success: function (data) 
            {
                $('#image_merchant-' + the_I).empty();
                $('#image_merchant-' + the_I).html(data);
                $(".chosen-select").chosen();        
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
                alert(errorThrown);
            }
        });
    }

    function JSFunctionValidate()
    {
        var validate_fail = 0;
        var box_number = <?php echo $box_number; ?>;
        for (the_I = 0; the_I < box_number; the_I++) 
        {
            var file_upload = document.getElementById('image-file-' + the_I).files.length;
            var title_input = document.getElementById('image-title-' + the_I).value;
            var merchant_select = document.getElementById('image-merchant-' + the_I);
            if (file_upload != 0)
            {
                if (title_input == "" || title_input.length == 0 || title_input == null || title_input.trim() == "" || merchant_select.selectedIndex == -1)
                {
                    validate_fail = 1;
                    alert("Title and Merchant cannot be empty.");
                }
            }
        }
        if (validate_fail == 0) 
        {
            return true;
        }
        return false;
    }
    $(document).ready(function () {
        $(".chosen-select").chosen();
                var temp_folder = '<?php echo $temp_folder ?>';
        for (var counter = 0; counter < <?php echo $box_number ?>; counter++) {
            $('#image-file-'+counter).ajaxfileupload({
      'action': 'http://' + $(location).attr('hostname') + '/keppo/all/upload_image_temp',
      'params': {
        'file_name': 'image-file-'+counter,
        'image_box_id': 'image_url-'+counter
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

<div id="upload-for-merchant">
    <h1>Upload Picture for Merchant Album</h1>
    <div id="upload-for-merchant-content">
        
        <div id="upload-for-merchant-merchant-album">
            <?php
            if (check_correct_login_type($this->config->item('group_id_user')))
            {
                $user_id = $this->ion_auth->user()->row()->id;
                ?>
                <div id="album-user-navigation">
                    <div id="album-user-navigation-each">
                        <a href="<?php echo base_url() ?>all/album_user/<?php echo $user_id ?>">My Album</a>
                    </div>
                    <div id="album-user-navigation-separater">|</div>
                    <div id="album-user-navigation-each">
                        <a href="<?php echo base_url() ?>all/album_user_merchant/<?php echo $user_id ?>">Merchants Album</a>
                    </div>
                    <div id="float-fix"></div>
                </div>
                <?php
            }
            ?>
        </div>
        <div id="upload-for-merchant-upload-image-note">
            Upload Image Rule : <?php echo $this->config->item('upload_guide_image') ?>
        </div>
        
        <?php 
        //OPEN FORM
        echo form_open_multipart(uri_string());
        ?>

        <?php 
        for ($i = 0; $i < $box_number; $i++)
        { 
            ?>
            <div id="upload-for-merchant-form">
                <div id="upload-for-merchant-form-each">
                    <div id='upload-for-merchant-form-photo-box'>
                        <?php echo "<img src='" . base_url(${'image_url' . $i}) . "' id='image_url-" . $i . "'>"; ?>
                    </div>
                    <div id='upload-for-merchant-form-input-file'>
                        <?php echo "<input type='file' accept='image/*' name='image-file-" . $i . "' id='image-file-" . $i . "' />"; ?> 
                    </div>
                    <div id='upload-for-merchant-form-each'>
                        <div id='upload-for-merchant-form-each-label'><?php echo lang('album_title_label'); ?></div>
                        <div id='upload-for-merchant-form-each-input'>
                            <?php
                            echo form_input(${'image_title' . $i});
                            ?>
                        </div>
                    </div>
                    <div id='upload-for-merchant-form-each'>
                        <div id='upload-for-merchant-form-each-label'><?php echo lang("album_description_label"); ?></div>
                        <div id='upload-for-merchant-form-each-input'>
                            <?php
                            echo form_textarea(${'image_desc' . $i});
                            ?>
                        </div>
                    </div>  
                    <div id='upload-for-merchant-form-each'>
                        <div id='upload-for-merchant-form-each-label'><?php echo lang("album_category_label"); ?></div>
                        <div id='upload-for-merchant-form-each-input'>
                            <?php
                            echo form_dropdown(${'image_category' . $i}, $category_list);
                            ?>
                        </div>
                    </div>
                    <div id='upload-for-merchant-form-each'>
                        <div id='upload-for-merchant-form-each-label'><?php echo lang("album_merchant_label"); ?></div>
                        <div id='upload-for-merchant-form-each-input'>
                            <?php
                            echo "<div id='image_merchant-" . $i . "'>";
                            if (empty(${'image_merchant_selected' . $i}))
                            {
                                echo form_dropdown(${'image_merchant' . $i}, array());
                            }
                            else
                            {
                                echo form_dropdown(${'image_merchant' . $i}, $merchant_list, ${'image_merchant_selected' . $i});
                            }
                            echo "</div>";
                            ?>
                        </div>
                    </div>            
                </div>
            </div>
            <?php 
        }
        ?>

        <div id="float-fix"></div>
        <button name="button_action" type="submit" value="upload_image" onclick="return JSFunctionValidate();" >Upload</button>

        <?php
        //CLOSE FORM 
        echo form_close(); 
        ?>
        
    </div>
</div>
