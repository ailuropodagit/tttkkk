<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="main-album-change">
    
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
    
    <div id="main-album-change-sub-menu">
        <?php
        $this->load->view('all/album_user_sub_menu');
        ?>
    </div>
    
    <div id='main-album-change-content'>     
        
        <?php echo form_open(uri_string()); ?>
        
            <div id='main-album-change-form'>
                <div id='main-album-change-form-each'>
                    <div id='main-album-change-form-each-label'><?php echo lang("main_album_title_label"); ?></div>
                    <div id='main-album-change-form-each-input'><?php echo form_input($album_title); ?></div>
                </div>              
            </div>

            <?php echo form_hidden($edit_id) ?>
        
            <div id='main-album-change-form-submit'>              
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="remove_real" onclick="return confirm('Are you sure want to remove it? Remove cannot be undo. All Image under this album also will remove together')">Remove</button>  
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
        
        <?php echo form_close(); ?>
        
    </div>
    <div id="float-fix"></div>   
</div>