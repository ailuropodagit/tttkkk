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
    <h1>Photography Type Management</h1>
    <div id="payment-charge-content">
        <?php
        $this->load->view('admin/manage_setting_sub_menu');
        ?>
        <div style="float:right">
            <?php $add_new_url = base_url() . 'admin/manage_photography_change'; ?>           
            <div><a href='<?php echo $add_new_url; ?>' class="a-href-button">Add New</a></div>
        </div>
        <div id="float-fix"></div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Photography Type</th> 
                        <th>Hide Already</th>
                        <th>Actions</th>
                        <th>Special Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $remove_row = $row['hide_flag'] == 1 ? 'Hide' : '';
                        $url_edit = base_url() . "admin/manage_photography_change/" . $row['option_id'];
                        $url_special_action = base_url() . "admin/manage_photography";
                        echo '<tr>';
                        echo "<td>" . $row['option_desc'] . "</td>";
                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>";
                        echo "<a href='" . $url_edit . "' >Edit</a>";
                        echo "</td>";
                        echo "<td>";                       
                        echo form_open($url_special_action); 
                        echo form_hidden('id', $row['option_id']); 
                        $remove_or_recover = $row['hide_flag'] == 1 ? 'recover' : 'frozen';
                        $remove_or_recover_text = $row['hide_flag'] == 1 ? 'Unhide' : 'Hide';
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