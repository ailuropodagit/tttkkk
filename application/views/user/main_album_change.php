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
        echo '<h1>Create Album</h1>';
    }
    else
    {
        echo '<h1>Edit Album</h1>';
    }                       
    ?>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang("main_album_title_label"); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($album_title); ?></div>
                </div>              
            </div>
            <?php
            echo form_hidden($edit_id);
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
