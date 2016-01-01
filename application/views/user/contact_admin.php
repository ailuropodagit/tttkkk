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
        <div style="float:left">
            <?php $back_url = base_url() . 'user/balance_page'; ?>           
            <div><a href='<?php echo $back_url; ?>' class="a-href-button">Back</a></div>
        </div>
        <div style="float:right">
            <?php $add_new_url = base_url() . 'user/contact_admin_change'; ?>           
            <div><a href='<?php echo $add_new_url; ?>' class="a-href-button">New Withdraw Request</a></div>
        </div>
        <div id="float-fix"></div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Bank Name</th> 
                        <th>Bank Account</th>
                        <th>Extra Info</th>
                        <th>Admin Reply</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        echo '<tr>';
                        echo "<td>" . $row['msg_content'] . "</td>";
                        echo "<td>" . $row['msg_desc'] . "</td>";
                        echo "<td>" . $row['msg_remark'] . "</td>";
                        echo "<td>" . $row['msg_reply'] . "</td>";
                        echo '</tr>';
                    }
                    ?>
                </tbody>    
            </table>
        </div>
    </div>
</div>