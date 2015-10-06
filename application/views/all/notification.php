<h1>Your notifications</h1></br>
<?php

foreach ($notification_list as $row)
{
    if($row['noti_read_already'] == 0){
        echo "<div style='background-color:lightblue;border:1px solid #DDD' >";
        $read_button_tooltip = "Mark as Read";
    }
    else{
        echo "<div style='border:1px solid #DDD' >";
        $read_button_tooltip = "Mark as Unread";
    }
    echo "<div style='float:left'>".$row['noti_user_url']."<a href=".$row['noti_url']." target='_blank' >".$row['noti_message'] . "</a></div>";
    
    echo "<div style='float:right'>";
    //FORM OPEN
    echo form_open("all/notification_process");
    ?>
    <input type='hidden' name='noti_id' id='noti_id' value='<?php echo $row['noti_id'] ?>'/>
    <input type='hidden' name='current_url' id='current_url' value='<?php echo get_current_url() ?>'/>
    <?php
    echo "<button name='button_action' type='submit' value='read_notification' id='button-a-href' title='".$read_button_tooltip."'><i class='fa fa-circle-o'></i></button> &nbsp&nbsp&nbsp";
    echo "<button name='button_action' type='submit' value='hide_notification' id='button-a-href' title='Remove notification'><i class='fa fa-times'></i></button>";
  
    //FORM CLOSE
    echo form_close();
    echo "</div>";
    echo "</br></br></div>";
}
?>