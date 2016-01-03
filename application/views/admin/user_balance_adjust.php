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
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>User Balance Adjust/Withdraw</h1>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'User Name : ' . $this->m_custom->generate_user_link($result['id']); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Current Balance : RM ' . $this->m_user->user_check_balance($result['id']); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Balance Adjust Amount (RM) : '; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($amount_change); ?></div>
                </div>               
                <div id="dashboard-photo-note" >Key in negative amount example -50 if user withdraw the cash</div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Adjust Reason : '; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($trans_remark); ?></div>
                </div>              
                <?php if($request_msg_id != NULL){ ?>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Can add an Admin Reply for Notify User : '; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($msg_reply); ?></div>
                </div>   
                <?php } ?>
            </div>
            <?php 
                echo form_hidden('user_id', $result['id']); 
            ?>
            <div id='profile-info-form-submit'>                         
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Add</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        
    </div>
    <div id="float-fix"></div>
    <br/>
    <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Balance Adjust Amount (RM)</th> 
                        <th>Adjust Reason</th> 
                        <th>Adjust by Admin/Worker</th> 
                        <th>Adjust Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $admin_name = $this->m_custom->display_users($row['admin_id']);
                        $trans_time = displayDate($row['trans_time'], 1);
                        echo '<tr>';
                        echo "<td style='text-align:right'>" . $row['amount_change'] . "</td>";
                        echo "<td>" . $row['trans_remark'] . "</td>";
                        echo "<td>" . $admin_name . "</td>";
                        echo "<td>" . $trans_time . "</td>";  
                        echo '</tr>';
                    }
                    ?>
                </tbody>    
            </table>
        </div>
</div>