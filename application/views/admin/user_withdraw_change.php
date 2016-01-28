<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1><?php echo $title; ?></h1>
    <div id='profile-content'>            
        <div id='profile-info'>
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Edit Admin Reply : '; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($msg_reply); ?></div>
                </div>              
            </div>
            <?php 
                echo form_hidden('id', $result['msg_id']); 
            ?>
            
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="back">Back</button>                             
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
            
            <?php echo form_close(); ?>
        </div>
        
        <div id="float-fix"></div>
        
    </div>
</div>