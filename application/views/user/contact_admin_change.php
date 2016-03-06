<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="candie-promotion">   
    <?php
        echo '<h1>New Withdraw Request</h1>';                  
    ?>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id="dashboard-photo-note" >Please key in the bank account info you wish to withdraw money to. Can add in extra info if any.</div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Account Holder Name :'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($msg_content); ?></div>
                </div>    
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Bank Name :'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($bank_list_id, $bank_list, $bank_list_selected); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Bank Account No :'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($msg_desc); ?></div>
                </div>  
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Extra Info :'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($msg_remark); ?></div>
                </div>                 
            </div>
            <div id='profile-info-form-submit'>              
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        
        <div id="float-fix"></div>
        
    </div>
</div>
