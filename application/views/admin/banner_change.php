<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/chosen/chosen.jquery.min.js"></script>
<?php echo link_tag('js/chosen/chosen.min.css') ?>

<script type="text/javascript">
    $(document).ready(function () {    
        $(function () {
            $("#banner_start_time").datepicker({
//                showOn: "both",
//                buttonImage: calendar_url,
//                buttonImageOnly: true,
                dateFormat: "dd-mm-yy",
            });
            $("#banner_end_time").datepicker({
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

<div id="candie-promotion">   
    <?php
    if ($is_edit == 0)
    {
        echo '<h1>Banner Add</h1>';
    }
    else
    {
        echo '<h1>Banner Edit</h1>';
    }                       
    ?>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>   
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_position', 'banner_position_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($banner_position_id, $banner_position_list, $banner_position_selected); ?></div>
                </div>
                <div id="dashboard-photo-note" >Make sure the image size is suitable, can check the banner view in home page after save</div>
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_merchant', 'merchant_id'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_dropdown($merchant_id, $merchant_list, $merchant_selected); ?></div>
                </div>                               
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_start_time', 'banner_start_time'); ?></div>
                    <div id='profile-info-form-each-input' class="candie-promotion-form-each-input-datepicker"><?php echo form_input($banner_start_time); ?></div>
                </div>
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_end_time', 'banner_end_time'); ?></div>
                    <div id='profile-info-form-each-input' class="candie-promotion-form-each-input-datepicker"><?php echo form_input($banner_end_time); ?></div>
                </div>
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('banner_url', 'banner_url'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($banner_url); ?></div>
                </div>                              
                
                <?php
                echo form_hidden($edit_id);
                ?>
                <div id='profile-info-form-submit'>              
                    <button name="button_action" type="submit" value="back">Back</button>
                    <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        
    </div>
</div>
