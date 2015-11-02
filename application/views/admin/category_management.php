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
    <h1>Category Management</h1>
    <div id="payment-charge-content">
        <!--PAYMENT CHARGE GO-->
        <div id="payment-charge-go" style="float:left">
            <?php echo form_open(uri_string()); ?>
                <span id="payment-charge-go-label"><?php echo "Filter "; ?></span>
                <span id="payment-charge-go-dropdown"><?php echo form_dropdown($main_category_id, $main_category_list, $main_category_selected); ?></span>
                <span id="payment-charge-go-button"><button name="button_action" type="submit" value="filter_result">Go</button></span>
            <?php echo form_close(); ?>
        </div>
        <div style="float:right">
            <?php $add_new_url = base_url() . 'admin/category_add'; ?>           
            <div><a href='<?php echo $add_new_url; ?>' class="a-href-button">Add New</a></div>
        </div>
        <div id="float-fix"></div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Category Name</th>
                        <th>Category Level</th>
                        <th>Main Category (If is Sub Category)</th>
<!--                        <th>Removed Already</th>-->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $category_level_text = $row['category_level'] == 0 ? 'Main Category' : '<i>Sub Category</i>';
                        $main_category_text = $row['category_level'] == 0 ? '' : $this->m_custom->display_category($row['main_category_id']);
                        $remove_row = $row['hide_flag'] == 1 ? 'Removed' : '';
                        $url_edit = base_url() . "admin/category_edit/" . $row['category_id'];
                        echo '<tr>';
                        echo "<td>" . $row['category_label'] . "</td>";
                        echo "<td>" . $category_level_text . "</td>";
                        echo "<td>" . $main_category_text . "</td>";
//                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>";
                        echo "<a href='" . $url_edit . "' >Edit</a>";
                        echo "</td>";
                        echo '</tr>';
                    }
                    ?>
                </tbody>    
            </table>
        </div>
    </div>
</div>