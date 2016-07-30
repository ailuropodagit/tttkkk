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
    <h1>Notification Food & Beverage Expired</h1>
    <div id="payment-charge-content">
        <?php 
            $this->load->view('all/notification_sub_menu');
        ?>
        <div id="float-fix"></div>
        <div id='payment-charge-table'>
            <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                <thead>
                    <tr style="text-align:center">
                        <th>Title</th>
                        <th>Image</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($the_result as $row)
                    {
                        $noti_url = base_url() . 'all/advertise/' . $row['advertise_id'];
                        $image_url = base_url() . $this->config->item('album_merchant') . $row['image'];
                        $start_time = displayDate($row['start_time']);
                        $end_time = displayDate($row['end_time']);
                        $url_special_action = base_url() . "merchant/hotdeal_expired";
                        echo '<tr>';
                        echo "<td><a href='$noti_url' target='_blank'>" . $row['title'] . "</a></td>";
                        echo "<td>";
                        if(!empty($row['image'])){
                        echo "<img style='max-height:200px;max-width:200px' src='" . $image_url . "'>";
                        }
                        echo "</td>";
                        echo "<td>" . $start_time . "</td>";
                        echo "<td>" . $end_time . "</td>";                  
                        echo "<td>";       
                        $have_role = $this->m_custom->check_role_su_can_uploadhotdeal();       
                        if($have_role == 1){
                            echo form_open($url_special_action); 
                            echo form_hidden('id', $row['advertise_id']); 
                            echo form_hidden('title', $row['title']); 
                            ?>
                            <button name="button_action" type="submit" value="change_to_preview" title='Remain for Preview' class='normal-btn-submit'>
                                <img src='<?php echo base_url() . "/image/btn-approve.png"; ?>' title='Remain for Preview' alt='Remain for Preview' class='normal-btn-image'></button>
                            <button name="button_action" type="submit" value="frozen" title='Frozen' class='normal-btn-submit'>
                                <img src='<?php echo base_url() . "/image/btn-hide.png"; ?>' title='Frozen' alt='Frozen' class='normal-btn-image'></button> 
                            <button name="button_action" type="submit" value="remove" title='Remove' class='normal-btn-submit'>
                                <img src='<?php echo base_url() . "/image/btn-remove.png"; ?>' title='Remove' alt='Remove' class='normal-btn-image'></button>    
                            <?php
                            echo form_close(); 
                        }
                        else {
                           echo "You don't have permission to edit food & beverage";
                        }
                        echo "</td>";
                        echo '</tr>';
                    }
                    ?>
                </tbody>    
            </table>
        </div>
    </div>
</div>