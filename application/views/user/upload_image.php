<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="upload-for-merchant">
    <h1>User Upload Image</h1>
    <div id="upload-for-merchant-content">
        
        <div id="upload-for-merchant-merchant-album">
            <?php
            if (check_correct_login_type($this->config->item('group_id_user')))
            {
                $user_id = $this->ion_auth->user()->row()->id;
                ?>
                <div id="album-user-navigation">
                    <div id="album-user-navigation-upload">
                        <a href="<?php echo base_url() ?>all/album_user/<?php echo $user_id ?>">My Album</a>
                    </div>
                    <div id="album-user-navigation-separater">|</div>
                    <div id="album-user-navigation-upload">
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
                        <?php echo "<input type='file' name='image-file-" . $i . "' id='image-file-" . $i . "' />"; ?> 
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