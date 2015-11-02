<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Profile</h1>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Category Name'; ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($category_label); ?></div>
                </div>               
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Is Sub Category?'; ?>
                        <?php echo form_checkbox($category_level); ?></div>
                </div>
                <?php 
                $div_show_hide = "style='display:none'";
                if ($category_level_value == 1)
                {
                    $div_show_hide = "style='display:inline'";
                }
                ?>
                <div id='category-sub-div' <?php echo $div_show_hide; ?>>
                    <div id='profile-info-form-each'>
                        <div id='profile-info-form-each-label'><?php echo 'Under Which Main Category?'; ?></div>
                        <div id='profile-info-form-each-input'><?php echo form_dropdown($main_category_id, $main_category_list, $main_category_selected); ?></div>
                    </div>
                </div>
            </div>
            <?php 
                echo form_hidden('id', $result['category_id']); 
                $remove_or_recover = $result['hide_flag'] == 1? 'recover' : 'frozen';
                $remove_or_recover_text = $result['hide_flag'] == 1? 'Recover' : 'Remove';
            ?>
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="<?php echo $remove_or_recover; ?>" onclick="return confirm('Are you sure want to <?php echo $remove_or_recover_text; ?> it?')"><?php echo $remove_or_recover_text; ?></button>                
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
        </div>
        
    </div>
</div>