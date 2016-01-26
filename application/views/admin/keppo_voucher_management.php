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

<div id="payment-charge">
    <h1>Keppo Voucher Management</h1>
    <div id="payment-charge-content">
        <div id="payment-charge-go" style="float:left">
            <?php echo form_open(uri_string()); ?>
                <span id="payment-charge-go-label"><?php echo "Filter "; ?></span>
                <span id="payment-charge-go-dropdown"><?php echo form_dropdown($sub_category_id, $sub_category_list, $sub_category_selected); ?></span>
                <span id="payment-charge-go-button"><button name="button_action" type="submit" value="filter_result">Go</button></span>
            <?php echo form_close(); ?>
        </div>
        <div style="float:right">
            <?php $add_new_url = base_url() . 'admin/keppo_voucher_change'; ?>           
            <div><a href='<?php echo $add_new_url; ?>' class="a-href-button">Add New</a></div>
        </div>
        <div id="float-fix"></div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Title</th>
                        <th>Category Type</th>
                        <th>Voucher Worth (RM)</th>
                        <th>Candie Require</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Expired Date</th>        
                        <th>Frozen Already</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $sub_category_text = $this->m_custom->display_category($row['sub_category_id']);
                        $start_date_text = displayDate($row['start_time']);
                        $end_date_text = displayDate($row['end_time']);
                        $expire_date_text = displayDate($row['voucher_expire_date']);
                        $remove_row = $row['frozen_flag'] == 1 ? 'Frozen' : '';
                        $url_edit = base_url() . "admin/keppo_voucher_change/" . $row['advertise_id'];
                        $url_special_action = base_url() . "admin/keppo_voucher_management";
                        echo '<tr>';
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . $sub_category_text . "</td>";
                        echo "<td style='text-align:right'>" . $row['voucher_worth'] . "</td>";
                        echo "<td style='text-align:right'>" . $row['voucher_candie'] . "</td>";
                        echo "<td>" . $start_date_text . "</td>";
                        echo "<td>" . $end_date_text . "</td>";
                        echo "<td>" . $expire_date_text . "</td>";
                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>";                       
                        echo form_open($url_special_action); 
                        echo form_hidden('id', $row['advertise_id']); 
                        echo form_hidden('title', $row['title']); 
                        $ror = $row['frozen_flag'] == 1 ? 'recover' : 'frozen';
                        $ror_text = $row['frozen_flag'] == 1 ? 'Unfrozen' : 'Frozen';
                        $ror_image = $row['frozen_flag'] == 1 ? base_url() . '/image/btn-unfrozen.png' : base_url() . '/image/btn-frozen.png';
                        echo "<a href='" . $url_edit . "' ><img src='". base_url() . "/image/btn-edit.png' title='Edit' alt='Edit' class='normal-btn-image'></a>";
                        ?>
                        <button name="button_action" type="submit" value="<?php echo $ror; ?>" onclick="return confirm('Are you sure want to <?php echo $ror_text; ?> it?')" title='<?php echo $ror_text; ?>' class='normal-btn-submit'>
                            <img src='<?php echo $ror_image; ?>' title='<?php echo $ror_text; ?>' alt='<?php echo $ror_text; ?>' class='normal-btn-image'></button>  
                        <button name="button_action" type="submit" value="remove" title='Remove' class='normal-btn-submit'>
                            <img src='<?php echo base_url() . "/image/btn-remove.png"; ?>' title='Remove' alt='Remove' class='normal-btn-image'></button>    
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