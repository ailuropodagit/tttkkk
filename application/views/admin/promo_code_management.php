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
    <h1>Special Promo Code Management</h1>
    <div id="payment-charge-content">
        <div style="float:left">
        <?php
        $this->load->view('admin/promo_code_sub_menu');
        ?>
        </div>
        <div style="float:right">
            <?php $add_new_url = base_url() . 'admin/promo_code_change_event'; ?>           
            <div><a href='<?php echo $add_new_url; ?>' class="a-href-button">Add New</a></div>
        </div>
        <div id="float-fix"></div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Promo Code</th>
                        <th>Candy</th>
                        <th>Event Name</th>
                        <th>Redeemed Count</th>
                        <th>Created by Admin/Worker</th>
                        <th>Last Modify</th>
                        <th>Hide Already</th>
                        <th>Actions</th>
                        <th>Special Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $redeem_count = $this->m_custom->generate_promo_code_list_link($row['code_no'], 34);
                        $admin_name = $this->m_custom->display_users($row['code_user_id']);
                        $last_modify = $this->m_custom->display_users($row['last_modify_by']);
                        $remove_row = $row['hide_flag'] == 1 ? 'Frozen' : '';
                        $url_edit = base_url() . "admin/promo_code_change_event/" . $row['code_id'];
                        $url_special_action = base_url() . "admin/promo_code_management";
                        echo '<tr>';
                        echo "<td>" . $row['code_no'] . "</td>";
                        echo "<td>" . $row['code_candie'] . "</td>";       
                        echo "<td>" . $row['code_event_name'] . "</td>";
                        echo "<td>" . $redeem_count . "</td>";
                        echo "<td>" . $admin_name . "</td>";
                        echo "<td>" . $last_modify . "</td>";
                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>";
                        echo "<a href='" . $url_edit . "' ><img src='". base_url() . "/image/btn-edit.png' title='Edit' alt='Edit' class='normal-btn-image'></a>";
                        echo "</td>";
                        echo "<td>";                       
                        echo form_open($url_special_action); 
                        echo form_hidden('id', $row['code_id']); 
                        $ror = $row['hide_flag'] == 1 ? 'recover' : 'frozen';
                        $ror_text = $row['hide_flag'] == 1 ? 'Unfrozen' : 'Frozen';
                        $ror_image = $row['hide_flag'] == 1 ? base_url() . '/image/btn-unfrozen.png' : base_url() . '/image/btn-frozen.png';
                        ?>
                        <button name="button_action" type="submit" value="<?php echo $ror; ?>" onclick="return confirm('Are you sure want to <?php echo $ror_text; ?> it?')" title='<?php echo $ror_text; ?>' class='normal-btn-submit'>
                            <img src='<?php echo $ror_image; ?>' title='<?php echo $ror_text; ?>' alt='<?php echo $ror_text; ?>' class='normal-btn-image'></button>  
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