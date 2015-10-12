<div id="monitoring">
    <h1>Monitoring Remove Action</h1>
    <div id="monitoring-content">
        <div id='table-all'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <colgroup>
                    <col>                        
                    <col style='width: 150px;'>
                    <col style='width: 120px;'>
                    <col style='width: 150px;'>
                    <col>
                </colgroup>
                <thead>
                    <tr style="text-align:center">
                        <th>Description</th>
                        <th>Removed Item Type</th>
                        <th>Removed By</th>
                        <th>Removed Time</th>
                        <th>Post Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($notification_list as $row)
                    {
                        echo '<tr>';
                        echo "<td>" . $row['hide_item_desc'] . "</td>";
                        echo "<td>" . $row['hide_item_type'] . "</td>";
                        echo "<td>" . $row['hide_by_text'] . " (" . $row['hide_by_type_text'] . ")" . "</td>";
                        echo "<td>" . $row['hide_time_text'] . "</td>";
                        echo "<td><img style='max-height:200px;max-width:200px' src='" . $row['post_image'] . "'></td>";
                        echo "<td>";

                        echo form_open("all/monitor_process");
                        ?>
                    <input type='hidden' name='the_id' id='the_id' value='<?php echo $row['mon_id'] ?>'/>
                    <input type='hidden' name='current_url' id='current_url' value='<?php echo get_current_url() ?>'/>
                    <?php
                    echo "<button name='button_action' type='submit' value='removed_approve' title='Approve This Removed'>Approve</button> &nbsp&nbsp&nbsp";
                    echo "<button name='button_action' type='submit' value='removed_recover' title='Undo This Removed'>Recover</button>";
                    echo form_close();

                    echo "</td>";
                    echo '</tr>';
                }
                ?>
                </tbody>    
            </table> 
        </div>
    </div>
</div>