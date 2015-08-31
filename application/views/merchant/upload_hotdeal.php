<h1>Hot Deal</h1>
<br/>

<style>
    .image-hot-deal{
        max-height: 200px;
        max-width: 200px;
    }
    #register-form-each-input input[type='text']{
        width:190px;
    }
    #register-form-each-input textarea{
        width:190px;
    }
    #register-form-each-input select{
        width:205px;
    }
</style>

<?php
$hotdeal_per_day = $this->config->item("hotdeal_per_day");
echo 'Server Date Time : ' . date($this->config->item('keppo_format_date_time_display')) . '<br/>';
echo 'Today Hot Deal : ' . $hotdeal_today_count . ' / ' . $hotdeal_per_day . ' per day<br/>';
echo "<span class='image-upload-guide'>Upload Image Rule : " . $this->config->item('upload_guide_image') . "</span>";
?>
<br/><br/>
<?php echo form_open_multipart(uri_string()); ?>
<?php for ($i = 0; $i < $hotdeal_per_day; $i++)
{ ?>
    <div style="float: left;width:250px;margin-bottom:20px">
        <div style="height:210px;">
    <?php echo "<img src='" . base_url(${'hotdeal_image' . $i}) . "' class='image-hot-deal' id='hotdeal-img-" . $i . "'>"; ?>
        </div>
        <br/>
        <br/>
    <?php echo "<input type='file' name='hotdeal-file-" . $i . "' />"; ?>
        <br /><br />   
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('hotdeal_title_label'); ?></div>
            <div id='register-form-each-input'>
                <?php
                echo form_input(${'hotdeal_title' . $i});
                echo form_hidden(${'hotdeal_id' . $i});
                ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang("hotdeal_sub_category_label"); ?></div>
            <div id='register-form-each-input'>
                <?php
                echo form_dropdown(${'hotdeal_category' . $i}, $sub_category_list, ${'hotdeal_category_selected' . $i});
                ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang("hotdeal_description_label"); ?></div>
            <div id='register-form-each-input'>
                <?php
                echo form_textarea(${'hotdeal_desc' . $i});
                ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang("hotdeal_hour_label"); ?></div>
            <div id='register-form-each-input-dob-day'>
                <?php
                echo form_dropdown(${'hotdeal_hour' . $i}, $hour_list, ${'hotdeal_hour_selected' . $i});
                ?></div>
        </div>
        <div id="float-fix"></div>
        <br/>
        <div id='register-form-each'>
            <div id='register-form-each-input'>
                <?php
                if (${'advertise_id_value' . $i} != 0)
                {
                    echo 'Remove : ' . form_checkbox(${'hotdeal_hide' . $i});
                }
                ?></div>
        </div>
    </div>

<?php } ?>
<div id="float-fix"></div>
<br/>
<br/>
<div style="float: right;">
    <button name="button_action" type="submit" value="upload_hotdeal" >Upload</button><br />        
</div>
<?php echo form_close(); ?>
<div id="infoMessage"><?php echo $message; ?></div>