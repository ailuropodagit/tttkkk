<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="profile">
    <h1>Category Edit</h1>
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
                $ror = $result['hide_flag'] == 1? 'recover' : 'frozen';
                $ror_text = $result['hide_flag'] == 1? 'Recover' : 'Hide';
            ?>
            <div id='profile-info-form-submit'>
                <button name="button_action" type="submit" value="<?php echo $ror; ?>" onclick="return confirm('Are you sure want to <?php echo $ror_text; ?> it?')"><?php echo $ror_text; ?></button>     
                <?php if($result['hide_flag'] == 1){ ?>
                <button name="button_action" type="submit" value="remove_real" onclick="return confirm('Are you sure want to remove it? Remove cannot be undo.')">Remove</button>  
                <?php } ?>
                <button name="button_action" type="submit" value="back">Back</button>
                <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        
        <div id="float-fix"></div>
        
    </div>
</div>