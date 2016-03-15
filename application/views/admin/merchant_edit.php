<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/datatables/js/jquery.dataTables.min.js"></script>
<?php echo link_tag('js/datatables/css/jquery.dataTables.min.css') ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myTable').DataTable({
            "paging": false,
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
    <h1><?php echo $title; ?></h1>
    <div id='profile-content'>        
        <?php echo form_open(uri_string()); ?>
        <div id='profile-info' style="float:left">
            
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_username_label', 'username'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($username); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_user_email_label', 'email address'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($email); ?></div>
                </div>                              
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_company_main_label', 'company_main'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($company_main); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_company_label', 'company'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($company); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_person_incharge_label', 'me_person_incharge'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($me_person_incharge); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_person_contact_label', 'me_person_contact'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($me_person_contact); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_companyssm_label', 'me_ssm'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($me_ssm); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_category_label', 'me_category_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($me_category_id, $category_list, $category_selected); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_address_label', 'address'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($address); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_postcode_label', 'postcode'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($postcode); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_state_label', 'me_state_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($me_state_id, $state_list, $state_selected); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_phone_label', 'phone'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($phone); ?></div>
                </div>        
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('create_merchant_halal_label'); ?>
                        <?php echo form_checkbox($me_is_halal); ?></div>
                </div>  
            </div>
            <?php 
                echo form_hidden('id', $result['id']); 
                $ror = $result['hide_flag'] == 1? 'recover' : 'frozen';
                $ror_text = $result['hide_flag'] == 1? 'Recover' : 'Frozen';
            ?>
            
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="back">Back</button>
                <?php if($can_edit == 1){ ?>
                <button name="button_action" type="submit" value="<?php echo $ror; ?>" onclick="return confirm('Are you sure want to <?php echo $ror_text; ?> it?')"><?php echo $ror_text; ?></button>                               
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
                <?php } ?>
            </div>
            
            
        </div>
        <?php 
        //if ($this->m_admin->check_is_any_admin(87) && $can_edit == 1){ 
        if (check_correct_login_type($this->group_id_admin) && $can_edit == 1){ 
            ?> 
        <div id="candie-promotion-form-voucher-checkbox" style="margin-left:30px;padding-top:25px;float:left;width:50%;">
                    <div id="candie-promotion-form-voucher-checkbox-title">Select Which Worker Can Open This Merchant :</div>
                             <div id="candie-promotion-form-voucher-checkbox-each">
                                    <table border='1px' cellspacing='0px' cellpadding='0px' id="myTable" class="display">
                                        <thead>
                                            <tr style="text-align:center">
                                                <th></th>
                                                <th>Name</th> 
                                                <th>Worker ID</th>
                                                <th>Department</th>
                                            </tr>
                                        </thead>
                    <?php                    
                    foreach ($merchant_worker as $row)
                    {
                        $key = $row['id'];
                        $name = $row['first_name'] . ' ' . $row['last_name'];
                        $wo_worker_id = $row['wo_worker_id'];
                        $wo_department = $row['wo_department'];
                        $checked_or_not = '';
                        if (in_array($key, $merchant_worker_current))
                        {
                            $checked_or_not = 'checked';
                        }
                        ?>
                                        <tr>
                                            <td valign="top"><input type='checkbox' id="merchant-worker-<?php echo $key ?>" name='merchant_worker[]' value='<?php echo $key ?>' <?php echo $checked_or_not; ?>></td>
                                            <td valign="top">
                                                <div id="candie-promotion-form-voucher-checkbox-each-label">
                                                    <label for="merchant-worker-<?php echo $key ?>"><?php echo $name ?></label>
                                                </div>
                                            </td>
                                            <?php
                                            echo "<td>" . $wo_worker_id . "</td>";
                                            echo "<td>" . $wo_department . "</td>";
                                            ?>
                                        </tr>
                                    
                    <?php
                    }
                    ?>  
                             </table>
                  </div>
        </div>
        <?php } ?>
        <?php echo form_close(); ?>
        <div id="float-fix"></div>
        
    </div>
</div>