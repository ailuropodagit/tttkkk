<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Transaction Config Edit</h1>
    <div id='profile-content'>  
        <?php
        $this->load->view('admin/manage_setting_sub_menu');
        ?>
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <?php
                    foreach ($editable_list as $row)
                    {
                        $conf_type = $row['conf_type'];
                        $change_type = $row['change_type'];
                        $conf_slug = generate_label_name($row['conf_name']).$row['trans_conf_id'];
                        $field_desc = $row['conf_name'] . ' (' . $row['trans_conf_desc'] . ')';
                        
                        if($conf_type == 'can'){
                    ?>   
                        <div id='profile-info-form-each' style='width:500px'>
                            <div id='profile-info-form-each-label'><?php echo $field_desc; ?></div>
                            <?php if($change_type == 'dec'){echo '- ';} echo form_input(${$conf_slug}); ?> candies
                        </div>
                <?php }else{ ?>    
                        <div id='profile-info-form-each' style='width:500px'>
                            <div id='profile-info-form-each-label'><?php echo $field_desc; ?></div>
                            RM <?php if($change_type == 'dec'){echo '- ';} echo form_input(${$conf_slug}); ?>
                        </div>
                <?php }
                echo '<br/>';
                    }?>
            </div>
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div id="float-fix"></div>
        
    </div>
</div>