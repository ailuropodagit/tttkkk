<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>

<script type="text/javascript">
    $(document).ready(function () {    
        $(function () {
            $("#topup_trans_date").datepicker({
//                showOn: "both",
//                buttonImage: calendar_url,
//                buttonImageOnly: true,
                dateFormat: "dd-mm-yy",
            });
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
    <h1>Merchant Top Up Add</h1>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Merchant Name : ' . $this->m_custom->generate_merchant_link($merchant_id); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Amount (RM)'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($topup_amount); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Transaction Bank'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($topup_bank); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Transaction Date'; ?></div>
                    <div id='profile-info-form-each-input' class="candie-promotion-form-each-input-datepicker"><?php echo form_input($topup_trans_date); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Transaction No'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($topup_trans_no); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Transaction Remark'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($topup_remark); ?></div>
                </div>
            </div>
            <?php 
                echo form_hidden('id', $merchant_id); 
            ?>
            <div id='profile-info-form-submit'>              
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
        </div>
        
    </div>
</div>