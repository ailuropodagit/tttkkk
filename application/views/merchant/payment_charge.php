<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/datetime-moment.js"></script>
<?php echo link_tag('js/datatables/css/jquery.dataTables.min.css') ?>

<script type="text/javascript">
    $(document).ready(function () {
        $.fn.dataTable.moment('DD-MM-YYYY HH:mm');
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
    <h1>Money Spend On Each Post</h1>
    <div id="payment-charge-content">
        <!--PAYMENT CHARGE GO-->
        <div id="payment-charge-go">
            <?php echo form_open(uri_string()); ?>
                <span id="payment-charge-go-label"><?php echo "Filter "; ?></span>
                <span id="payment-charge-go-dropdown"><?php echo form_dropdown($the_adv_type, $adv_type_list, $the_adv_type_selected); ?></span>
                <span id="payment-charge-go-button"><button name="button_action" type="submit" value="search_history">Go</button></span>
            <?php echo form_close(); ?>
        </div>
        
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <colgroup>
                    <col>
                    <col>
                    <col style='width: 150px;'>
                    <col style='width: 120px;'>
                    <col style='width: 120px;'>
                    <col style='width: 120px;'>
                    <col style='width: 120px;'>
                    <col style='width: 120px;'>
                    <col style='width: 120px;'>
                    <col style='width: 120px;'>
                </colgroup>
                <thead>
                    <tr style="text-align:center">
                        <th>Create Date</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>View</th>
                        <th>Like</th>
                        <th>Rating</th>
                        <th>Redeemed</th>
                        <th>User Upload</th>
                        <th>Remove Already</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $redeem_row = $row['redeem_count'] === NULL ? NULL : $row['redeem_count'] . " (" . money($row['redeem_amount']) . ")";
                        //$userupload_row = $row['userupload_count'] === NULL ? NULL : $row['userupload_count'] . " (" . money($row['userupload_amount']) . ")";
                        $userupload_row = $row['userupload_count'] === NULL ? NULL : $row['userupload_count'];
                        $remove_row = $row['hide_flag'] == 1 ? 'Removed' : '';
                        echo '<tr>';
                        echo "<td>" . displayDate($row['create_date'], 1) . "</td>";
                        echo "<td>" . $row['title_url'] . "</td>";
                        echo "<td>" . $row['type_text'] . "</td>";
                        //echo "<td>" . $row['view_count'] . " (" . money($row['view_amount']) . ")</td>";
                        //echo "<td>" . $row['like_count'] . " (" . money($row['like_amount']) . ")</td>";
                        //echo "<td>" . $row['rating_count'] . " (" . money($row['rating_amount']) . ")</td>";
                        echo "<td>" . $row['view_count'] . "</td>";
                        echo "<td>" . $row['like_count'] . "</td>";
                        echo "<td>" . $row['rating_count'] . "</td>";
                        echo "<td>" . $redeem_row . "</td>";
                        echo "<td>" . $userupload_row . "</td>";
                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>" . money($row['total_amount']) . "</td>";
                        echo '</tr>';
                    }
                    ?>
                </tbody>    
            </table>
        </div>
    </div>
</div>