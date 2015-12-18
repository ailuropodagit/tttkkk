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
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Manage Redemption Term & Condition</h1>
    <div id='profile-content'>  
        <?php
        $this->load->view('admin/manage_setting_sub_menu');
        ?>
        <div style="float:right">
            <?php $add_new_url = base_url() . 'admin/manage_candie_term_change'; ?>           
            <div><a href='<?php echo $add_new_url; ?>' class="a-href-button">Add New</a></div>
        </div>
        <div id="float-fix"></div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Term & Condition</th> 
                        <th>Add Merchant Name In Front</th> 
                        <th>Keppo Voucher Used Only</th> 
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
                        $option_special = $row['option_special'] == 1 ? 'Yes' : '';
                        $option_level = $row['option_level'] == 1 ? 'Yes' : '';
                        $url_edit = base_url() . "admin/manage_candie_term_change/" . $row['option_id'];
                        $url_special_action = base_url() . "admin/manage_candie_term";
                        echo '<tr>';
                        echo "<td>" . $row['option_desc'] . "</td>";
                        echo "<td>" . $option_special . "</td>";
                        echo "<td>" . $option_level . "</td>";
                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>";
                        echo "<a href='" . $url_edit . "' ><img src='". base_url() . "/image/btn-edit.png' title='Edit' alt='Edit' class='normal-btn-image'></a>";
                        echo "</td>";
                        echo "<td>";                       
                        echo form_open($url_special_action); 
                        echo form_hidden('id', $row['option_id']); 
                        $ror = $row['hide_flag'] == 1 ? 'recover' : 'frozen';
                        $ror_text = $row['hide_flag'] == 1 ? 'Recover' : 'Hide';
                        $ror_image = $row['hide_flag'] == 1 ? base_url() . '/image/btn-recover.png' : base_url() . '/image/btn-hide.png';
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