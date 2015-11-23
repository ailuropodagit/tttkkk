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
                        <?php if($this->m_admin->check_worker_role(62)) { ?>
                        <th>Username</th> 
                        <th>Password</th>
                        <?php } ?>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Race</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Birthday</th>
                        <th>Candie</th>
                        <th>Balance (RM)</th>
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
                        $user_candie_text = $this->m_user->candie_check_balance($row['id']);
                        $user_balance_text = $this->m_user->user_check_balance($row['id']);
                        $remove_row = $row['hide_flag'] == 1 ? 'Frozen' : '';
                        //$url_edit = base_url() . "admin/user_edit/" . $row['id'];
                        $url_bonus_candie = base_url() . "admin/user_bonus_candie/" . $row['id'];
                        $url_promo_code = base_url() . "admin/promo_code_change_user/" . $row['id'];   
                        $url_dashboard = base_url() . "all/user_dashboard/" . $row['id'];
                        $url_special_action = base_url() . "admin/user_special_action";
                        echo '<tr>';
                        if($this->m_admin->check_worker_role(62)) {
                        echo "<td>" . $row['username'] . "</td>";                                                
                        echo "<td>" . $row['password_visible'] . "</td>";
                        }
                        echo "<td><a href='" . $url_dashboard . "' target='_blank' >" . $row['first_name'] . "</a></td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $race_text . "</td>";
                        echo "<td>" . $row['us_age'] . "</td>";
                        echo "<td>" . $gender_text . "</td>";
                        echo "<td>" . $birthday_text . "</td>";
                        echo "<td style='text-align:right'>" . $user_candie_text . "</td>";
                        echo "<td style='text-align:right'>" . $user_balance_text . "</td>";
                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>";
                        if($this->m_admin->check_worker_role(74)) {
                        echo "<a href='" . $url_bonus_candie . "' >Bonus Candie</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        }
                        if($this->m_admin->check_worker_role(77)) {
                        echo "<a href='" . $url_promo_code . "' >Promocode</a>";    
                        }
                        echo "</td>";
                        echo "<td>";                       
                        echo form_open($url_special_action); 
                        echo form_hidden('id', $row['id']); 
                        $remove_or_recover = $row['hide_flag'] == 1 ? 'recover' : 'frozen';
                        $remove_or_recover_text = $row['hide_flag'] == 1 ? 'Unfrozen' : 'Frozen';
                        ?>
                        <?php if($this->m_admin->check_worker_role(61)) { ?>
                        <button name="button_action" type="submit" value="log_in_as">Log In As User</button>
                        <?php } ?>
                        <?php if($this->m_admin->check_worker_role(64)) { ?>
                        <button name="button_action" type="submit" value="<?php echo $remove_or_recover; ?>" onclick="return confirm('Are you sure want to <?php echo $remove_or_recover_text; ?> it?')"><?php echo $remove_or_recover_text; ?></button> 
                        <?php } ?>
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