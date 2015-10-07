<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>

<script type="text/javascript">
    $(document).ready(function () {
        var day_add = 0;
        var selected_month = $("#candie_month").val();
        var current_month = parseInt($('input[name= current_month]').val());
        if (selected_month > current_month) {
            day_add = 30;
        }
        //var calendar_url = window.location.origin + '/keppo/image/icon_calendar.png';
        $(function () {
            $("#start_date,#end_date").datepicker({
                //showOn: "both",
                //buttonImage: calendar_url,
                //buttonImageOnly: true,
                minDate: -5,
                maxDate: +(31 + day_add),
                dateFormat: "dd-mm-yy",
            });
            $("#expire_date").datepicker({
                //showOn: "both",
                //buttonImage: calendar_url,
                //buttonImageOnly: true,
                minDate: 0,
                maxDate: +(61 + day_add),
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
    <h1>Candie Promotion</h1>
    <div id="candie-promotion-content">
        <div id='candie-promotion-form'>
            <?php echo form_open_multipart(uri_string()); ?>

            <?php
            //INPUT TYPE HIDDEN
            echo form_hidden($candie_id);
            ?>

            <div id="candie-promotion-form-go">
                <span id="candie-promotion-form-go-label"><?php echo lang("candie_year_month_label"); ?></span>
                <span id="candie-promotion-form-go-year"><?php echo form_dropdown($candie_year, $year_list, $candie_year_selected); ?></span>
                <span id="candie-promotion-form-go-month"><?php echo form_dropdown($candie_month, $month_list, $candie_month_selected); ?></span>
                <span id="candie-promotion-form-go-button"><button name="button_action" type="submit" value="search_voucher">Go</button></span>
            </div>

            <div id="candie-promotion-form-photo">
                <div id="candie-promotion-form-photo-box">
                    <img src="<?php echo base_url($candie_image) ?>">
                </div>
                <div id='candie-promotion-form-photo-note'>
                    <?php echo $this->config->item('upload_guide_image') ?>
                </div>
                <div id='candie-promotion-form-input-file'>
                    <input type='file' name='candie-file'/>
                </div>
            </div>

            <div id="candie-promotion-form-info">
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_title_label'); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php
                        echo form_input($candie_title);
                        ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang("candie_sub_category_label"); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php
                        echo form_dropdown($candie_category, $sub_category_list, $candie_category_selected);
                        ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_point_label'); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php echo form_input($candie_point); ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang("candie_description_label"); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php echo form_textarea($candie_desc); ?>
                    </div>
                </div>
                <!--<div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php //echo lang('candie_vender_label');  ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php //echo form_input($candie_vender); ?>
                    </div>
                </div>-->
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_start_date_label'); ?></div>
                    <div id='candie-promotion-form-each-input' class="candie-promotion-form-each-input-datepicker">
                        <?php echo form_input($start_date); ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_end_date_label'); ?></div>
                    <div id='candie-promotion-form-each-input' class="candie-promotion-form-each-input-datepicker">
                        <?php echo form_input($end_date); ?>
                    </div>
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang('candie_expire_date_label'); ?></div>
                    <div id='candie-promotion-form-each-input' class="candie-promotion-form-each-input-datepicker">
                        <?php echo form_input($expire_date); ?>
                    </div>
                </div>
                <div id="candie-promotion-form-voucher-checkbox">
                    <div id="candie-promotion-form-voucher-checkbox-title">Select Terms & Conditions</div>
                    <?php
                    foreach ($candie_term as $key => $value)
                    {
                        if (in_array($key, $candie_term_current))
                        {
                            ?>
                            <div id="candie-promotion-form-voucher-checkbox-each">
                                <table border="0" cellpadding="0px" cellspacing="0px">
                                    <tr>
                                        <td valign="top"><input type='checkbox' id="candie-term-<?php echo $key ?>" name='candie_term[]' value='<?php echo $key ?>' checked></td>
                                        <td valign="top">
                                            <div id="candie-promotion-form-voucher-checkbox-each-label">
                                                <label for="candie-term-<?php echo $key ?>"><?php echo $value ?></label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php
                        }
                        else
                        {
                            ?>
                            <div id="candie-promotion-form-voucher-checkbox-each">
                                <table border="0" cellpadding="0px" cellspacing="0px">
                                    <tr>
                                        <td valign="top"><input type='checkbox' id="candie-term-<?php echo $key ?>" name='candie_term[]' value='<?php echo $key ?>'></td>
                                        <td valign="top">
                                            <div id="candie-promotion-form-voucher-checkbox-each-label">
                                                <label for="candie-term-<?php echo $key ?>"><?php echo $value ?></label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php
                        }
                    }
                    ?>  
                </div>
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-voucher-checkbox-title'><?php echo lang("candie_extra_term_label"); ?></div>
                    <div>
                        <?php echo form_textarea($extra_term); ?>
                    </div>
                </div>
                <div id="candie-promotion-form-branch-checkbox">
                    <div id="candie-promotion-form-branch-checkbox-title">Select Branch:</div>
                    <div id="candie-promotion-form-branch-checkbox-each">
                        <table border="0" cellpadding="0px" cellspacing="0px">
                            <tr>
                                <td valign="top"><input type="checkbox" id="candie_branch_select_all" onClick="toggle(this)"/> </td>
                                <td>
                                    <span id="candie-promotion-form-branch-checkbox-each-label">
                                        <label for="candie_branch_select_all">Select All</label>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                    foreach ($candie_branch as $key => $value)
                    {
                        if (in_array($key, $candie_branch_current))
                        {
                            ?>
                            <div id="candie-promotion-form-branch-checkbox-each">
                                <table border="0" cellpadding="0px" cellspacing="0px">
                                    <tr>
                                        <td valign="top"><input type='checkbox' id="candie-branch-<?php echo $key ?>" name='candie_branch[]' value='<?php echo $key ?>' checked></td>
                                        <td valign="top">
                                            <div id="candie-promotion-form-branch-checkbox-each-label">
                                                <label for="candie-branch-<?php echo $key ?>"><?php echo $value ?></label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php
                        }
                        else
                        {
                            ?>
                            <div id="candie-promotion-form-branch-checkbox-each">
                                <table border="0" cellpadding="0px" cellspacing="0px">
                                    <tr>
                                        <td valign="top"><input type='checkbox' id="candie-branch-<?php echo $key ?>" name='candie_branch[]' value='<?php echo $key ?>'></td>
                                        <td valign="top">
                                            <div id="candie-promotion-form-branch-checkbox-each-label">
                                                <label for="candie-branch-<?php echo $key ?>"><?php echo $value ?></label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <?php
                        }
                    }
                    ?>  
                </div>
                <?php 
                if ($is_history == 0 && $this->session->userdata('user_group_id') == $this->config->item('group_id_merchant'))
                { 
                    ?>
                    <div id='candie-promotion-form-submit'>
                        <button name="button_action" type="submit" value="submit">Save</button>
                    </div>
                    <?php
                    } 
                ?>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!--Have to put this javascript in lower part to work because it need wait all thing load in page-->
<script type="text/javascript">
    function toggle(source) 
    {
        checkboxes = document.getElementsByName('candie_branch[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) 
        {
            checkboxes[i].checked = source.checked;
        }
    }
</script>