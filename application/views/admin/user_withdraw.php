<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/jquery.dataTables.min.js"></script>
<?php echo link_tag('js/datatables/css/jquery.dataTables.min.css') ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myTable').DataTable({
            "pageLength": 25,
            "order": []
        });
    });
</script>

<?php
//MESSAGE
if (isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="payment-charge">
    <h1>Request Cash Back Withdraw</h1>
    <div id="payment-charge-content">
        <?php 
            $this->load->view('all/notification_sub_menu');
        ?>
        <div id="payment-charge-go" style="float:left">
            <?php echo form_open(uri_string()); ?>
                <span id="payment-charge-go-label"><?php echo "Filter "; ?></span>
                <span id="payment-charge-go-dropdown"><?php echo form_dropdown($view_status_id, $view_status_list, $view_status_selected); ?></span>
                <span id="payment-charge-go-button"><button name="button_action" type="submit" value="filter_result">Go</button></span>
            <?php echo form_close();  ?>
        </div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>User Name</th> 
                        <th>Bank Name</th> 
                        <th>Bank Account</th>
                        <th>Extra Info</th>                        
                        <th>Withdraw Status</th>
                        <th>Status Change By Admin</th>
                        <th>Admin Reply</th>
                        <th>Balance (RM)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $msg_status_text = '';
                        if($row['msg_status'] == 1){
                            $msg_status_text = 'Success Withdraw';
                        }else if($row['msg_status'] == 2){
                            $msg_status_text = 'Fail Withdraw';
                        }
                        $user_name = $this->m_custom->generate_user_link($row['msg_from_id']);
                        $admin_name = $this->m_custom->display_users($row['status_change_by']);
                        $user_balance_text = $this->m_user->user_check_balance($row['msg_from_id']);
                        $url_balance_adjust = base_url() . "admin/user_balance_adjust/" . $row['msg_from_id'] . "/" . $row['msg_id'];
                        $url_special_action = base_url() . "admin/user_withdraw";
                        echo '<tr>';
                        echo "<td>" . $user_name . "</td>";
                        echo "<td>" . $row['msg_content'] . "</td>";
                        echo "<td>" . $row['msg_desc'] . "</td>";
                        echo "<td>" . $row['msg_remark'] . "</td>";                       
                        echo "<td>" . $msg_status_text . "</td>";
                        echo "<td>" . $admin_name . "</td>";
                        echo "<td>" . $row['msg_reply'] . "</td>";
                        echo "<td style='text-align:right'>" . $user_balance_text . "</td>";
                        echo "<td>";       
                            echo form_open($url_special_action); 
                            echo form_hidden('id', $row['msg_id']); 
                            echo form_hidden('msg_from_id', $row['msg_from_id']); 
                            if($this->m_admin->check_worker_role(75)) {
                            echo "<a href='" . $url_balance_adjust . "' ><img src='". base_url() . "/image/btn-balance.png' title='Go Do Withdraw' alt='Go Do Withdraw' class='normal-btn-image'></a>";
                            }
                            
                            ?>
                            <button name="button_action" type="submit" value="success" title='Success Withdraw' class='normal-btn-submit'>
                                <img src='<?php echo base_url() . "/image/btn-approve.png"; ?>' title='Success Withdraw' alt='Success Withdraw' class='normal-btn-image'></button>
                            <button name="button_action" type="submit" value="fail" title='Fail Withdraw' class='normal-btn-submit'>
                                <img src='<?php echo base_url() . "/image/btn-remove.png"; ?>' title='Fail Withdraw' alt='Fail Withdraw' class='normal-btn-image'></button>    
                            <?php
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