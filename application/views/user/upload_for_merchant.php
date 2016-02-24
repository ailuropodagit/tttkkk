<script type="text/javascript" src="<?php echo base_url() ?>js/chosen/chosen.jquery.min.js"></script>
<?php echo link_tag('js/chosen/chosen.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/multiple-upload/jquery.fileuploadmulti.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url('js/multiple-upload/uploadfilemulti.css') ?>">
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
                //if (title_input == "" || title_input.length == 0 || title_input == null || title_input.trim() == "" || merchant_select.selectedIndex == -1)
                if (merchant_select.selectedIndex == -1)
                {
                    validate_fail = 1;
                    //alert("Title and Merchant cannot be empty.");
                    alert("Merchant cannot be empty.");
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
        var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>';         
        var temp_folder = '<?php echo $temp_folder ?>';
        for (var counter = 0; counter < <?php echo $box_number ?>; counter++) {
            $('#image-file-'+counter).ajaxfileupload({
      'action': 'http://' + $(location).attr('hostname') + keppo_path + 'all/upload_image_temp',
      'params': {
        'file_name': 'image-file-'+counter,
        'image_box_id': 'image_url-'+counter
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
    }
    
    var temp_folder_cut = '<?php echo $temp_folder_cut ?>';
        var empty_image = '<?php echo $empty_image ?>';
        var settings = {
	url: 'http://' + $(location).attr('hostname') + keppo_path + 'all/upload_image_temp_multiple',
	method: "POST",
	allowedTypes:"gif,jpg,png,bmp,ico,jpeg,jpe",
	fileName: "myfile",
	multiple: true,
        maxFileCount: <?php echo $box_number ?>,
        showDone: false,
        showStatusAfterSuccess: false,
	onSuccess:function(files,data,xhr)
	{
		//$("#status").html("<font color='green'>Upload is success</font>");
                for (var counter = 0; counter < <?php echo $box_number ?>; counter++) {
                var images = $('img#image_url-'+counter).attr('src');
                    if (images.indexOf(empty_image) >= 0){
                        data = data.replace(/\"/g, '');
                        $('img#image_url-'+counter).attr('src', 'http://' + $(location).attr('hostname') + keppo_path + temp_folder_cut + data);
                        $('input[name="hideimage-'+counter+'"]').val(data);
                        break;
                    }                
                }
                
        },
        afterUploadAll:function()
        {
                //alert("all images uploaded!!");
        },
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Upload is Failed</font>");
	}
        }
        $("#mulitplefileuploader").uploadFile(settings); 
        
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
    <div id="upload-for-merchant-title">
        <h1>Upload Picture for Merchant Album</h1>
    </div>
    
    <?php
    //UPLOAD FOR MERCHANT NAVIGATION
    $this->load->view('all/album_user_sub_menu');
    ?>

    <div id="upload-for-merchant-multiple-upload">
          Upload Multiple (Max 5) : <div id="mulitplefileuploader">Upload</div>
          <div id="status"></div>
    </div>     
        
    <div id="upload-for-merchant-content">
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
                        <?php 
                        echo "<img src='" . base_url(${'image_url' . $i}) . "' id='image_url-" . $i . "'>"; 
                        echo "<input type='hidden' name='hideimage-" . $i . "' >"; 
                        ?>
                    </div>
                    <div id='upload-for-merchant-form-input-file'>
                        <?php echo "<input type='file' accept='image/*' name='image-file-" . $i . "' id='image-file-" . $i . "' />"; ?> 
                    </div>
                    <div id='upload-for-merchant-form-each' style="display:none">
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
        <a href='<?php echo base_url(uri_string()) ?>' class="a-href-button">Clear</a>
        <?php
        //CLOSE FORM 
        echo form_close(); 
        ?>
        
    </div>
</div>
