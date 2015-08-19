<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>

            <div id="infoMessage"><?php echo $message; ?></div>

            <img src="<?php echo base_url($logo_url); ?>" id='header-logo-img'><br/>
            <?php echo form_open_multipart(uri_string()); ?>

            <input type="file" name="userfile" size="20" />

            <br /><br />

        <?php echo form_submit('submit', 'Upload');?>
            </form>
        </div>
    </div>
</div>