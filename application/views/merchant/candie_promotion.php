<link href="http://localhost/keppo/js/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://localhost/keppo/js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>

<h1>Candie Promotion</h1>
<br/>
<style type="text/css">
    #register-form{
        text-align:center;
    }
    .image-candie{
        max-height: 300px;
        max-width: 300px;
    }
    .ui-datepicker-trigger{
        max-height:30px;
        max-width:30px;
        position:relative;
        top:7px;
        left:2px;
    }
</style>
<script type="text/javascript">
    $( document ).ready(function() {
    var day_add = 0;
    var selected_month = $( "#candie_month" ).val();
    var current_month = parseInt($('input[name= current_month]').val());
    if(selected_month > current_month) {
        day_add = 30;
    }
    $(function () {       
        $("#start_date,#end_date").datepicker({
            showOn: "both",
            buttonImage: "../image/icon_calendar.png",
            buttonImageOnly: true,
            minDate: -5,
            maxDate: +(31+day_add),
            dateFormat: "dd-mm-yy",
        });
        $("#expire_date").datepicker({
            showOn: "both",
            buttonImage: "../image/icon_calendar.png",
            buttonImageOnly: true,
            minDate: 0,
            maxDate: +(61+day_add),
            dateFormat: "dd-mm-yy",
        });
    });
        });
</script>
<?php echo form_open_multipart(uri_string()); ?>

<?php
echo lang("candie_year_month_label");
echo form_dropdown($candie_year, $year_list, $candie_year_selected);
echo form_dropdown($candie_month, $month_list, $candie_month_selected);
echo '<button name="button_action" type="submit" value="search_voucher">Search</button>';
echo "<br/><span class='image-upload-guide'>Upload Image Rule : " . $this->config->item('upload_guide_image') . "</span>";
?>

<br/>
<div id='register-form'>
    <div>
        <?php echo "<img src='" . base_url($candie_image) . "' class='image-candie' id='candie-img'>"; ?>
    </div>
    <br/>
    <br/>
    <?php echo "<input type='file' name='candie-file' />"; ?>
    <br /><br />  
    <div id='register-form-each'>
        <div id='register-form-each-label'><?php echo lang('candie_title_label'); ?></div>
        <div id='register-form-each-input'>
            <?php
            echo form_input($candie_title);
            echo form_hidden($candie_id);
            ?></div>
    </div>
    <div id='register-form-each'>
        <div id='register-form-each-label'><?php echo lang("candie_sub_category_label"); ?></div>
        <div id='register-form-each-input'>
            <?php
            echo form_dropdown($candie_category, $sub_category_list, $candie_category_selected);
            ?></div>
    </div>
    <div id='register-form-each'>
        <div id='register-form-each-label'><?php echo lang('candie_point_label'); ?></div>
        <div id='register-form-each-input'>
            <?php echo form_input($candie_point); ?></div>
    </div>
    <div id='register-form-each'>
        <div id='register-form-each-label'><?php echo lang("candie_description_label"); ?></div>
        <div id='register-form-each-input'>
            <?php echo form_textarea($candie_desc); ?></div>
    </div>
    <div id='register-form-each'>
        <div id='register-form-each-label'><?php echo lang('candie_vender_label'); ?></div>
        <div id='register-form-each-input'>
            <?php echo form_input($candie_vender); ?></div>
    </div>
    <div id='register-form-each'>
        <div id='register-form-each-label'><?php echo lang('candie_start_date_label'); ?></div>
        <div id='register-form-each-input'>
            <?php echo form_input($start_date); ?></div>
    </div>
    <div id='register-form-each'>
        <div id='register-form-each-label'><?php echo lang('candie_end_date_label'); ?></div>
        <div id='register-form-each-input'>
            <?php echo form_input($end_date); ?></div>
    </div>
    <div id='register-form-each'>
        <div id='register-form-each-label'><?php echo lang('candie_expire_date_label'); ?></div>
        <div id='register-form-each-input'>
            <?php echo form_input($expire_date); ?></div>
    </div>
    <?php if ($is_history == 0) { ?>
        <div id='register-form-submit'>
            <button name="button_action" type="submit" value="submit">Save</button>
        </div>
    <?php } ?>
    <div id="infoMessage"><?php echo $message; ?></div>
</div>
<?php echo form_close(); ?>