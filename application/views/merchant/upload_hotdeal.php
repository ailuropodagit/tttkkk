<h1>Hot Deal</h1>
<br/>

<style>
    .image-hot-deal{
        min-height: 100px;
        min-width: 100px;
        max-height: 200px;
        max-width: 200px;
    }
    #register-form-each-input textarea{
        width:190px;
    }
</style>

<?php 
$hotdeal_per_day = $this->config->item("hotdeal_per_day");
echo 'Today Date : ' . date($this->config->item('keppo_date_format')) . '<br/>'; 
echo 'Today Hot Deal : 3 / ' . $hotdeal_per_day; 
?>
<br/><br/>
<?php echo form_open_multipart(uri_string()); ?>
<?php for ($i = 1; $i <= $hotdeal_per_day; $i++) { ?>
    <div style="float: left;width:250px;margin-bottom:20px">
        <?php echo "<img  class='image-hot-deal' id='hotdeal-img-" . $i . "'>"; ?>
        <br/>
        <br/>
        <input type="file" name="userfile" size="20" />
        <br /><br />   
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang("hotdeal_description_label", "desc-" . $i); ?></div>
            <div id='register-form-each-input'>
                <?php
                $hotdeal_desc = 'hotdeal_desc' . $i;
                echo form_textarea($hotdeal_desc);
                ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang("hotdeal_hour_label", "hour-" . $i); ?></div>
            <div id='register-form-each-input-dob-day'>
                <?php
                $hotdeal_hour = 'hotdeal_hour' . $i;
                //$hotdeal_hour_selected = 'hotdeal_hour_selected' . $i;
                echo form_dropdown($hotdeal_hour, $hour_list);
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