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
        <div id='payment-charge-table' >
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th><div class="table-text-overflow-ellipsis">Category Name</div></th>
                        <th><div class="table-text-overflow-ellipsis">Category Level</div></th>
                        <th><div class="table-text-overflow-ellipsis">Main Category (If is Sub Category)</div></th>    
                        <th><div class="table-text-overflow-ellipsis">Hide Already</div></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $category_level_text = $row['category_level'] == 0 ? 'Main Category' : '<i>Sub Category</i>';
                        $main_category_text = $row['category_level'] == 0 ? '' : $this->m_custom->display_category($row['main_category_id']);
                        $remove_row = $row['hide_flag'] == 1 ? 'Hide' : '';
                        $url_edit = base_url() . "admin/category_edit/" . $row['category_id'];
                        echo '<tr>';
                        echo "<td><div class='table-text-overflow-ellipsis'>" . $row['category_label'] . "</div></td>";
                        echo "<td><div class='table-text-overflow-ellipsis'>" . $category_level_text . "</div></td>";
                        echo "<td><div class='table-text-overflow-ellipsis'>" . $main_category_text . "</div></td>";
                        echo "<td>" . $remove_row . "</td>";
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