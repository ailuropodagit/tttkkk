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
    <h1>User Promo Code Management</h1>
    <div id="payment-charge-content">
        <div style="float:left">
        <?php
        $this->load->view('admin/promo_code_sub_menu');
        ?>
        </div>
        <div id="float-fix"></div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Promo Code</th>
                        <th>Candies</th>
                        <?php if($code_type == 'user'){  ?>
                        <th>Cash Back</th>
                        <?php } ?>
                        <th>Redeem Count</th>                        
                        <th>Last Modify</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        if($code_type == 'user'){
                            $redeem_count = $this->m_custom->generate_promo_code_list_link($row['code_no'], 32);                      
                            $url_edit = base_url() . "admin/promo_code_change_user/" . $row['code_user_id'] . "/1";
                        }else{
                            $redeem_count = $this->m_custom->generate_promo_code_list_link($row['code_no'], 33);                      
                            $url_edit = base_url() . "admin/promo_code_change_merchant/" . $row['code_user_id'] . "/1";
                        }
                        $last_modify = $this->m_custom->display_users($row['last_modify_by']);
                        echo '<tr>';
                        echo "<td>" . $row['display_name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['code_no'] . "</td>";
                        echo "<td>" . $row['code_candie'] . "</td>";  
                        if($code_type == 'user'){
                        echo "<td>" . $row['code_money'] . "</td>";    
                        }
                        echo "<td>" . $redeem_count . "</td>";
                        echo "<td>" . $last_modify . "</td>";
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