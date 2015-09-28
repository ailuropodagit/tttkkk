<h1>Edit Comment</h1></br>
<?php
        echo form_open("all/comment_update");
        echo form_input($act_history_id);
        echo form_input($return_url);
        ?>
    
        <div id="user-comment-input">
            <?php echo form_textarea($comment); ?>
        </div>
        <div id="user-comment-submit">
            <button name="button_action" type="submit" value="add_comment" >Update Comment</button>
        </div>
        <?php
        //FORM CLOSE
        echo form_close();
?>