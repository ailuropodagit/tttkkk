<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/datetime-moment.js"></script>
<?php echo link_tag('js/datatables/css/jquery.dataTables.min.css') ?>

<script type="text/javascript">
    $(document).ready(function () {
        $.fn.dataTable.moment('DD-MM-YYYY');
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

<div id="profile">
    <h1>Merchant Top Up History</h1>
    <div id="payment-charge-content">
        <div style="float:left">
            <?php 
            $back_url = base_url() . 'admin/merchant_management';
            if ($low_balance_only == 1)
            {
                $back_url = base_url() . 'admin/merchant_management/1';
            }
            ?>           
            <div><a href='<?php echo $back_url; ?>' class="a-href-button">Back</a></div>
        </div>
        <div style="float:right">
            <?php 
            $add_new_url = base_url() . 'admin/merchant_topup_add/' . $merchant_id; 
            if ($low_balance_only == 1)
            {
                $add_new_url = base_url() . 'admin/merchant_topup_add/' . $merchant_id . "/1"; 
            }
            ?>           
            <div><a href='<?php echo $add_new_url; ?>' class="a-href-button">Add New Top Up</a></div>
        </div>
        <div id="float-fix"></div>
        <div id='profile-info-form-each'>
            <div id='profile-info-form-each-label'><?php echo 'Merchant Name : ' . $this->m_custom->generate_merchant_link($merchant_id); ?></div>
        </div>
        <div id='profile-info-form-each'>
            <div id='profile-info-form-each-label'><?php echo 'Merchant Current Balance : ' . $this->m_merchant->merchant_balance_color($merchant_id, 2); ?></div>
        </div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Merchant Name</th>
                        <th>Amount (RM)</th>
                        <th>Transaction Bank</th>
                        <th>Transaction Date</th>
                        <th>Transaction No</th>
                        <th>Remark</th>
                        <th>Top Up Record By Admin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $merchant_name = $this->m_custom->display_users($row['merchant_id']);
                        $topup_trans_date = displayDate($row['topup_trans_date']);
                        $admin_name = $this->m_custom->display_users($row['admin_id']);
                        $url_edit = base_url() . "admin/merchant_topup_edit/" . $row['merchant_id'] . "/" . $row['topup_id'];
                        if ($low_balance_only == 1)
                        {
                            $url_edit = base_url() . "admin/merchant_topup_edit/" . $row['merchant_id'] . "/" . $row['topup_id'] . "/1";
                        }
                        echo '<tr>';
                        echo "<td>" . $merchant_name . "</td>";
                        echo "<td style='text-align:right'>" . $row['topup_amount'] . "</td>";
                        echo "<td>" . $row['topup_bank'] . "</td>";
                        echo "<td>" . $topup_trans_date . "</td>";
                        echo "<td>" . $row['topup_trans_no'] . "</td>";
                        echo "<td>" . $row['topup_remark'] . "</td>";
                        echo "<td>" . $admin_name . "</td>";
                        echo "<td>";
                        echo "<a href='" . $url_edit . "' ><img src='". base_url() . "/image/btn-edit.png' title='Edit' alt='Edit' class='normal-btn-image'></a>";
                        echo "</td>";
                        echo '</tr>';
                    }
                    ?>
                </tbody>    
            </table>
        </div>
    </div>
</div>