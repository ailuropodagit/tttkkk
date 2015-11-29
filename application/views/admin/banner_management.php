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
    <h1>Banner Management</h1>
    <div id="payment-charge-content">
        <div id="payment-charge-go" style="float:left">
            <?php echo form_open(uri_string()); ?>
                <span id="payment-charge-go-label"><?php echo "Filter "; ?></span>
                <span id="payment-charge-go-dropdown"><?php echo form_dropdown($ignore_hide_id, $ignore_hide_list, $ignore_hide_selected); ?></span>
                <span id="payment-charge-go-button"><button name="button_action" type="submit" value="filter_result">Go</button></span>
            <?php echo form_close(); ?>
        </div>
        <div style="float:right">
            <?php $add_new_url = base_url() . 'admin/banner_change'; ?>           
            <div><a href='<?php echo $add_new_url; ?>' class="a-href-button">Add New</a></div>
        </div>
        <div id="float-fix"></div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Banner Position</th>
                        <th>Merchant</th>
<!--                        <th>Category</th>-->
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Last Modified By</th>
                        <th>Hide Already</th>
                        <th>Actions</th>
                        <th>Special Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $banner_position = $this->m_custom->display_static_option($row['banner_position']);
                        $merchant_name = $this->m_custom->display_users($row['merchant_id']);
                        //$category_name = $this->m_custom->display_category($row['category_id']);
                        $start_time = displayDate($row['start_time']);
                        $end_time = displayDate($row['end_time']);
                        $admin_name = $this->m_custom->display_users($row['last_modify_by']);
                        $remove_row = $row['hide_flag'] == 1 ? 'Frozen' : '';
                        $url_edit = base_url() . "admin/banner_change/" . $row['banner_id'];
                        $url_special_action = base_url() . "admin/banner_management";
                        echo '<tr>';
                        echo "<td>" . $banner_position . "</td>";
                        echo "<td>" . $merchant_name . "</td>";       
                        //echo "<td>" . $category_name . "</td>";
                        echo "<td>" . $start_time . "</td>";
                        echo "<td>" . $end_time . "</td>";
                        echo "<td>" . $admin_name . "</td>";
                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>";
                        echo "<a href='" . $url_edit . "' >Edit</a>";
                        echo "</td>";
                        echo "<td>";                       
                        echo form_open($url_special_action); 
                        echo form_hidden('id', $row['banner_id']); 
                        echo form_hidden('position_id', $row['banner_position']); 
                        echo form_hidden('ignore_hide_id', $ignore_hide_selected);
                        $remove_or_recover = $row['hide_flag'] == 1 ? 'recover' : 'frozen';
                        $remove_or_recover_text = $row['hide_flag'] == 1 ? 'Unfrozen' : 'Frozen';
                        ?>
                        <button name="button_action" type="submit" value="<?php echo $remove_or_recover; ?>" onclick="return confirm('Are you sure want to <?php echo $remove_or_recover_text; ?> it?')"><?php echo $remove_or_recover_text; ?></button> 
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