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
                        <?php //if($this->m_admin->check_worker_role(62)) { ?>
<!--                        <th>Username</th> 
                        <th>Password</th>-->
                        <?php //} ?>
                        <th>Register Date</th>
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
                        $url_view = base_url() . "admin/user_view/" . $row['id'];
                        //$url_edit = base_url() . "admin/user_edit/" . $row['id'];
                        $url_bonus_candie = base_url() . "admin/user_bonus_candie/" . $row['id'];
                        $url_balance_adjust = base_url() . "admin/user_balance_adjust/" . $row['id'];
                        $url_promo_code = base_url() . "admin/promo_code_change_user/" . $row['id'];   
                        $url_dashboard = base_url() . "all/user_dashboard/" . $row['id'];
                        $url_special_action = base_url() . "admin/user_special_action";
                        echo '<tr>';
//                        if($this->m_admin->check_worker_role(62)) {
//                        echo "<td>" . $row['username'] . "</td>";                                                
//                        echo "<td>" . $row['password_visible'] . "</td>";
//                        }
                        echo "<td>" . date($this->config->item('keppo_format_date_display'), $row['created_on']) . "</td>";
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
                        echo "<a href='" . $url_view . "' ><img src='". base_url() . "/image/btn-view.png' title='View' alt='View' class='normal-btn-image'></a>";
                        if($row['remove_flag'] == 0){
                            if($this->m_admin->check_worker_role(74)) {
                            echo "<a href='" . $url_bonus_candie . "' ><img src='". base_url() . "/image/btn-bonus-candie.png' title='Bonus Candie' alt='Bonus Candie' class='normal-btn-image'></a>";
                            }
                            if($this->m_admin->check_worker_role(75)) {
                            echo "<a href='" . $url_balance_adjust . "' ><img src='". base_url() . "/image/btn-balance.png' title='Adjust Balance' alt='Adjust Balance' class='normal-btn-image'></a>";
                            }
                            if($this->m_admin->check_worker_role(77)) {
                            echo "<a href='" . $url_promo_code . "' ><img src='". base_url() . "/image/btn-promocode.png' title='Promocode' alt='Promocode' class='normal-btn-image'></a>";
                            }
                        }
                        echo "</td>";
                        echo "<td>";                       
                        echo form_open($url_special_action); 
                        echo form_hidden('id', $row['id']); 
                        $ror = $row['hide_flag'] == 1 ? 'recover' : 'frozen';
                        $ror_text = $row['hide_flag'] == 1 ? 'Unfrozen' : 'Frozen';
                        $ror_image = $row['hide_flag'] == 1 ? base_url() . '/image/btn-unfrozen.png' : base_url() . '/image/btn-frozen.png';
                        $ror2 = $row['remove_flag'] == 1 ? 'recover_remove' : 'remove';
                        $ror_text2 = $row['remove_flag'] == 1 ? 'Recover' : 'Hide';
                        $ror_image2 = $row['remove_flag'] == 1 ? base_url() . '/image/btn-recover.png' : base_url() . '/image/btn-hide.png';
                        ?>
                        <?php if($this->m_admin->check_worker_role(61)  && $row['remove_flag'] == 0) { ?>
                        <button name="button_action" type="submit" value="log_in_as" title='Log In As User' class='normal-btn-submit'>
                            <img src='<?php echo base_url() . "/image/btn-login-as.png"; ?>' title='Log In As User' alt='Log In As User' class='normal-btn-image'></button>
                        <?php } ?>
                        <?php if($this->m_admin->check_worker_role(85)  && $row['remove_flag'] == 0) { ?>
                        <button name="button_action" type="submit" value="<?php echo $ror; ?>" onclick="return confirm('Are you sure want to <?php echo $ror_text; ?> it?')" title='<?php echo $ror_text; ?>' class='normal-btn-submit'>
                        <img src='<?php echo $ror_image; ?>' title='<?php echo $ror_text; ?>' alt='<?php echo $ror_text; ?>' class='normal-btn-image'></button> 
                        <?php } ?>
                        <?php if($this->m_admin->check_worker_role(85)) { ?>
                        <button name="button_action" type="submit" value="<?php echo $ror2; ?>" onclick="return confirm('Are you sure want to <?php echo $ror_text2; ?> it?')" title='<?php echo $ror_text2; ?>' class='normal-btn-submit'>
                        <img src='<?php echo $ror_image2; ?>' title='<?php echo $ror_text2; ?>' alt='<?php echo $ror_text2; ?>' class='normal-btn-image'></button> 
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