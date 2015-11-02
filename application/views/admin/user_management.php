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
    <h1>User Management</h1>
    <div id="payment-charge-content">
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Username</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Race</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Birthday</th>
                        <th>Frozen Already</th>   
                        <th>Actions</th>
                        <th>Special Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $race_text = $this->m_custom->display_static_option($row['us_race_id']);
                        $gender_text = $this->m_custom->display_static_option($row['us_gender_id']);
                        $birthday_text = displayDate($row['us_birthday']);
                        $remove_row = $row['hide_flag'] == 1 ? 'Frozen' : '';
                        $url_edit = base_url() . "admin/user_edit/" . $row['id'];
                        $url_dashboard = base_url() . "all/user_dashboard/" . $row['id'];
                        $url_special_action = base_url() . "admin/user_special_action";
                        echo '<tr>';
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $race_text . "</td>";
                        echo "<td>" . $row['us_age'] . "</td>";
                        echo "<td>" . $gender_text . "</td>";
                        echo "<td>" . $birthday_text . "</td>";
                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>";
//                        echo "<a href='" . $url_edit . "' >Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo "<a href='" . $url_dashboard . "' target='_blank' >Dashboard</a>";
                        echo "</td>";
                        echo "<td>";                       
                        echo form_open($url_special_action); 
                        echo form_hidden('id', $row['id']); 
                        $remove_or_recover = $row['hide_flag'] == 1 ? 'recover' : 'frozen';
                        $remove_or_recover_text = $row['hide_flag'] == 1 ? 'Unfrozen' : 'Frozen';
                        ?>
                        <button name="button_action" type="submit" value="log_in_as">Log In As User</button>
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