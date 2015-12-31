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
    <h1>Merchant Fee Charge History</h1>
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
            $add_new_url = base_url() . 'admin/merchant_feecharge_add/' . $merchant_id; 
            if ($low_balance_only == 1)
            {
                $add_new_url = base_url() . 'admin/merchant_feecharge_add/' . $merchant_id . "/1"; 
            }
            ?>        
            <div><a href='<?php echo $add_new_url; ?>' class="a-href-button">Add New Fee Charge</a></div>
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
                        <th>Fee Charge Type</th>
                        <th>Transaction Date</th>
                        <th>Remark</th>
                        <th>Fee Charge Record By Admin</th>
<!--                        <th>Actions</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $merchant_name = $this->m_custom->display_users($row['user_id']);
                        $fee_charge_type = $this->m_custom->display_dynamic_option($row['refer_id']);
                        $trans_date = displayDate($row['trans_time']);
                        $admin_name = $this->m_custom->display_users($row['admin_id']);
//                        $url_edit = base_url() . "admin/merchant_feecharge_edit/" . $row['user_id'] . "/" . $row['extra_id'];
//                        if ($low_balance_only == 1)
//                        {
//                            $url_edit = base_url() . "admin/merchant_feecharge_edit/" . $row['user_id'] . "/" . $row['extra_id'] . "/1";
//                        }
                        echo '<tr>';
                        echo "<td>" . $merchant_name . "</td>";
                        echo "<td style='text-align:right'>" . $row['amount_change'] . "</td>";
                        echo "<td>" . $fee_charge_type . "</td>";
                        echo "<td>" . $trans_date . "</td>";
                        echo "<td>" . $row['trans_remark'] . "</td>";
                        echo "<td>" . $admin_name . "</td>";
//                        echo "<td>";
//                        echo "<a href='" . $url_edit . "' ><img src='". base_url() . "/image/btn-edit.png' title='Edit' alt='Edit' class='normal-btn-image'></a>";
//                        echo "</td>";
                        echo '</tr>';
                    }
                    ?>
                </tbody>    
            </table>
        </div>
    </div>
</div>