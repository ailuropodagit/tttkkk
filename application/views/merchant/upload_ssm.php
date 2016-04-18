<h1>Upload SSM</h1>
<br/>

<div id='register-form'>
    <?php echo form_open_multipart(uri_string()); ?>    
    Select File To Upload : <input type="file" name="userfile" multiple="multiple"  /><br/><br/>

    <button name="button_action" type="submit" value="upload_ssm" >Upload</button><br/><br/>

    <?php if (!IsNullOrEmptyString($me_ssm_file))
    { ?>
        Current SSM File : <?php echo $me_ssm_file; ?><br/><br/>

        <button name="button_action" type="submit" value="download_ssm" >Download</button><br/><br/>

    <?php } echo form_close(); ?>
</div>