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
</style>
<div id='register-form'>
    <?php echo form_open_multipart(uri_string()); ?>
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
    <div id='register-form-each-input-dob'>
        <div id='register-form-each-label'><?php echo lang("candie_month_label"); ?></div>
        <div id='register-form-each-input-dob-month'><?php echo form_dropdown($candie_month, $month_list, $candie_month_selected); ?></div>
    </div>
    <div id='register-form-submit'>
        <button name="button_action" type="submit" value="submit">Submit</button>
    </div>
    <?php echo form_close(); ?>
    <div id="infoMessage"><?php echo $message; ?></div>
</div>