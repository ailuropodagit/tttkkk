<?php
//GET ALL COMMENT
$all_comment = $this->m_custom->activity_comment_count($item_id['value'],$item_type['value'],1);
?>

<div id="user-comment">
    
    <?php
    //LOOP ALL COMMENT
    foreach($all_comment as $row)
    {
        $user_name = $this->m_custom->display_users($row['act_by_id'],1);
        $user_comment = $row['comment'];
        $user_comment_time = displayDate($row['act_time'],1)
        ?>
        <div id="user-comment-list-each">
            <table border='0px' cellpadding='0px' cellspacing='0px' style='width: 100%;'>
                <colgroup>
                    <col style='width: 130px;'>
                    <col>
                    <col style='width: 20px;'>
                </colgroup>
                <tr>
                    <td valign='top'>
                        <div id="user-comment-list-each-username">
                            <a href='#'><?php echo $user_name; ?></a>
                            <div id="user-comment-list-each-comment-time"><?php echo $user_comment_time; ?></div>
                        </div>
                    </td>
                    <td valign='top'>
                        <div id="user-comment-list-each-comment">
                            <?php echo $user_comment; ?>
                        </div>
                    </td>
                    <td valign='top' align='center'>
                        <?php
                        //FORM OPEN
                        echo form_open("all/comment_hide");
                        ?>
                        <input type='hidden' name='act_history_id' id='act_history_id' value='<?php echo $row['act_history_id'] ?>'/>
                        <input type='hidden' name='current_url' id='current_url' value='<?php echo get_current_url() ?>'/>
                        <?php
                        if($this->m_custom->activity_check_access($row['act_history_id']))
                        {
                            ?>
                            <button name='button_action' type='submit' value='hide_comment' id='button-a-href'><i class="fa fa-times"></i></button>
                            <?php
                        }
                        //FORM CLOSE
                        echo form_close();
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    ?>
    
    <?php
    //USER LOGGED IN
    if (check_is_login())
    {
        //FORM OPEN
        echo form_open("all/comment_add");
        echo form_input($item_id);
        echo form_input($item_type);
        ?>
        <!--INPUT HIDDEN-->
        <input type='hidden' name='current_url' id='current_url' value='<?php echo get_current_url() ?>'/>
    
        <div id="user-comment-input">
            <textarea placeholder="Write a comment..." id="comment" name="comment"></textarea>
        </div>
        <div id="user-comment-submit">
            <button name="button_action" type="submit" value="add_comment" >Add Comment</button>
        </div>
        <?php
        //FORM CLOSE
        echo form_close();
    }
    ?>
        
</div>