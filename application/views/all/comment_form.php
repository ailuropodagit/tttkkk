<?php

$all_comment = $this->m_custom->activity_comment_count($item_id['value'],$item_type['value'],1);

foreach($all_comment as $row){
    $user_name = $this->m_custom->display_users($row['act_by_id'],1);
    echo "<b>".$user_name . "</b> : " .$row['comment']."<br/>";
    echo "<i>".displayDate($row['act_time'],1)."</i>  ";
    echo form_open("all/comment_hide");
    echo "<input type='hidden' name='act_history_id' id='act_history_id' value='".$row['act_history_id']."'/>";
    echo "<input type='hidden' name='current_url' id='current_url' value='".get_current_url()."'/>";
    if($this->m_custom->activity_check_access($row['act_history_id'])){
    echo "<button name='button_action' type='submit' value='hide_comment' > X</button><br/>";
    }
    echo "<br/>";
    echo form_close();
}

if (check_is_login())
{
    echo form_open("all/comment_add");
    echo form_input($item_id);
    echo form_input($item_type);
    echo "<input type='hidden' name='current_url' id='current_url' value='".get_current_url()."'/>";
    ?>

    <div id="contact-us-right-form-each">
        <textarea placeholder="Write a comment..." id="comment" name="comment" style="width:40%;height:50px"></textarea>
    </div>
    <button name="button_action" type="submit" value="add_comment" >Add Comment</button>

<?php }
echo form_close();
?>