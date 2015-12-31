<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>
 
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
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Merchant Name : ' . $this->m_custom->generate_merchant_link($merchant_id); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo "Fee Charge Type"; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($feecharge_id, $feecharge_list); ?></div>
                </div>
                <div id="dashboard-photo-note" >Make sure choose the correct fee charge type, cannot change after save</div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Transaction Remark'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($feecharge_remark); ?></div>
                </div>
            </div>
            <?php
            if (isset($edit_id))
            {
                echo form_hidden('id', $edit_id);
            }
            ?>
            <div id='profile-info-form-submit'>              
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="back_list">Back To List</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div id="float-fix"></div>
    </div>
</div>