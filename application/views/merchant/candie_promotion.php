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
    .checkbox-list{
        text-align:left;
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
    var calendar_url = window.location.origin + '/keppo/image/icon_calendar.png';
    $(function () {       
        $("#start_date,#end_date").datepicker({
            showOn: "both",
            buttonImage: calendar_url,
            buttonImageOnly: true,
            minDate: -5,
            maxDate: +(31+day_add),
            dateFormat: "dd-mm-yy",
        });
        $("#expire_date").datepicker({
            showOn: "both",
            buttonImage: calendar_url,
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
<!--    <div id='register-form-each'>
        <div id='register-form-each-label'><?php //echo lang('candie_vender_label'); ?></div>
        <div id='register-form-each-input'>
            <?php //echo form_input($candie_vender); ?></div>
    </div>-->
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
    <div class="checkbox-list">
        <?php
        foreach ($candie_term as $key => $value) :
            if (in_array($key, $candie_term_current))
            {
                echo "<input type='checkbox' name='candie_term[]' value='" . $key . "' checked >&nbsp;" . $value . "<br />";
            }
            else
            {
                echo "<input type='checkbox' name='candie_term[]' value='" . $key . "' >&nbsp;" . $value . "<br />";
            }
        endforeach;
        ?>  
    </div>
    <?php if ($is_history == 0 && $this->session->userdata('user_group_id') == $this->config->item('group_id_merchant')) { ?>
    
        <div id='register-form-submit'>
            <button name="button_action" type="submit" value="submit">Save</button>
        </div>
    <?php } ?>
    <div id="infoMessage"><?php echo $message; ?></div>
</div><br /><br />
<div class="checkbox-list">
    Select Branch : <br/>
    <input type="checkbox" id="candie_branch_select_all" onClick="toggle(this)"/> Select All<br/>
    <?php
    foreach ($candie_branch as $key => $value) :
        if (in_array($key, $candie_branch_current))
        {
            echo "<input type='checkbox' name='candie_branch[]' value='" . $key . "' checked >&nbsp;" . $value . "<br />";
        }
        else
        {
            echo "<input type='checkbox' name='candie_branch[]' value='" . $key . "' >&nbsp;" . $value . "<br />";
        }
    endforeach;
    ?>  
</div>
<?php echo form_close(); ?>

<!--Have to put this javascript in lower part to work because it need wait all thing load in page-->
<script type="text/javascript">
    
        function toggle(source) {
        checkboxes = document.getElementsByName('candie_branch[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>