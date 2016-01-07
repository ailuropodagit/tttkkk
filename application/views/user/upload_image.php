<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/multiple-upload/jquery.fileuploadmulti.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url('js/multiple-upload/uploadfilemulti.css') ?>">

<script type="text/javascript">
    $(document).ready(function(){        
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
    <h1>Upload Picture for My Album</h1>
    <div id="upload-for-merchant-content">
        <div id="upload-for-merchant-merchant-album" style="float:left">
            <?php
            $this->load->view('all/album_user_sub_menu');
            ?>                       
        </div>
        <div style="float:right">
              Upload Multiple (Max 5) : <div id="mulitplefileuploader">Upload</div>
              <div id="status"></div>
        </div>
        <div id="float-fix"></div>
        <div id="upload-for-merchant-upload-image-note" style="display:none">
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
                        <?php 
                        echo "<img src='" . base_url(${'image_url' . $i}) . "' id='image_url-" . $i . "'>"; 
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
                        <div id='upload-for-merchant-form-each-label'><?php echo lang("album_main_label"); ?></div>
                        <div id='upload-for-merchant-form-each-input'>
                            <?php
                            echo form_dropdown(${'image_main_album' . $i}, $main_album_list);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
        }
        ?>

        <div id="float-fix"></div>
        <button name="button_action" type="submit" value="upload_image" >Upload</button>

        <?php
        //CLOSE FORM 
        echo form_close(); 
        ?>
        
    </div>
</div>
