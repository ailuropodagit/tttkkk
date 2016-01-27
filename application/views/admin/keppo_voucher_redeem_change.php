<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.timepicker/jquery.timepicker.js"></script>
<?php echo link_tag('js/jquery.timepicker/jquery.timepicker.css') ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#top_up_date").datepicker({
                dateFormat: "dd-mm-yy",
        });
        $('#top_up_time').timepicker({ 'scrollDefault': 'now','timeFormat': 'H:i'  });
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
        <div id='profile-info'>
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'>Serial Code</div>
                    <div id='profile-info-form-each-input'><?php echo form_input($top_up_serial_code); ?></div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Top Up Used Already?'; ?>
                        <?php echo form_checkbox($top_up_already); ?></div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'>Top Up Date</div>
                    <div id='candie-promotion-form-each-input' class="candie-promotion-form-each-input-datepicker">
                        <?php echo form_input($top_up_date); ?>
                    </div>
                </div>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'>Top Up Time</div>
                    <div id='profile-info-form-each-input' style='width:100px'><?php echo form_input($top_up_time); ?></div>
                </div>
            </div>
            <?php 
                echo form_hidden('id', $result['redeem_id']); 
            ?>
            
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="back">Back</button>                             
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
            
            <?php echo form_close(); ?>
        </div>
        
        <div id="float-fix"></div>
        
    </div>
</div>