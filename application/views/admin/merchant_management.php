<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/formatted-numbers.js"></script>
<?php echo link_tag('js/datatables/css/jquery.dataTables.min.css') ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myTable').DataTable({
            "pageLength": 25,
            "order": [],
//            'columnDefs': [
//            { type: 'formatted-num', targets: [10,11] }
//            ]
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
    <h1>Merchant Management</h1>
    <div id="payment-charge-content">
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">                                                
                        <?php if($this->m_admin->check_worker_role(62)) { ?>
                        <th>Username</th> 
                        <th>Password</th>
                        <?php } ?>
                        <th>Company Name</th>
                        <th>Shop Name</th>
                        <th>Email</th>
                        <th>Company Category</th>
                        <th>Company Contact</th>   
                        <th>Person Incharge</th>
                        <th>Person Contact</th>
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
                        $main_category_text = $this->m_custom->display_category($row['me_category_id']);
                        $state_text = $this->m_custom->display_static_option($row['me_state_id']);
                        $merchant_balance_text = $this->m_merchant->merchant_balance_color($row['id'], 1);
                        $remove_row = $row['hide_flag'] == 1 ? 'Frozen' : '';
                        $url_edit = base_url() . "admin/merchant_edit/" . $row['id'];
                        $url_topup = base_url() . "admin/merchant_topup/" . $row['id'];                      
                        $url_promo_code = base_url() . "admin/promo_code_change_merchant/" . $row['id'];                       
                        $url_dashboard = base_url() . "all/merchant_dashboard/" . $row['slug'];
                        $url_special_action = base_url() . "admin/merchant_special_action";
                        
                        if($low_balance_only == 1){
                            $url_edit = base_url() . "admin/merchant_edit/" . $row['id'] . "/1";
                            $url_topup = base_url() . "admin/merchant_topup/" . $row['id']. "/1";
                            $url_special_action = base_url() . "admin/merchant_special_action/1";
                        }
                        
                        echo '<tr>';                                         
                        if($this->m_admin->check_worker_role(62)) {
                        echo "<td>" . $row['username'] . "</td>";    
                        echo "<td>" . $row['password_visible'] . "</td>";
                        }
                        echo "<td>" . $row['company_main'] . "</td>";
                        echo "<td><a href='" . $url_dashboard . "' target='_blank' >" . $row['company'] . "</a></td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $main_category_text . "</td>";                       
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['me_person_incharge'] . "</td>";
                        echo "<td>" . $row['me_person_contact'] . "</td>";
                        echo "<td style='text-align:right'>" . $merchant_balance_text . "</td>";
                        echo "<td>" . $remove_row . "</td>";
                        echo "<td>";
                        echo "<a href='" . $url_edit . "' >Edit</a>&nbsp;&nbsp;&nbsp;";      
                        if($this->m_admin->check_worker_role(67)) {
                        echo "<a href='" . $url_topup . "' >TopUp</a>&nbsp;&nbsp;&nbsp;";    
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
                        <?php if($this->m_admin->check_worker_role(60)) { ?>
                        <button name="button_action" type="submit" value="log_in_as">Log In As Merchant</button>
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