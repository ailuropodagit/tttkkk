<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>User Add Bonus Candie</h1>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'User Name : ' . $this->m_custom->generate_user_link($result['id']); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Current Candie : ' . $this->m_user->candie_check_balance($result['id']); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Bonus Candie Amount'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($amount_change); ?></div>
                </div>               
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Bonus Reason'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_textarea($trans_remark); ?></div>
                </div>              
            </div>
            <?php 
                echo form_hidden('user_id', $result['id']); 
            ?>
            <div id='profile-info-form-submit'>                         
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Add</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        
    </div>
</div>