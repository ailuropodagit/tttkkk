<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Merchant Top Up</h1>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Amount (RM)'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($category_label); ?></div>
                </div>               
            </div>
            <?php 
                echo form_hidden('id', $result['id']); 
            ?>
            <div id='profile-info-form-submit'>            
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Top Up</button>
            </div>
        </div>
        
    </div>
</div>