<script type="text/javascript" src="<?php echo base_url() ?>js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<?php echo link_tag('js/jquery-ui-1.11.4.custom/jquery-ui.css') ?>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script>

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
        
       var keppo_path = '<?php echo $this->config->item('keppo_path'); ?>';  
       var temp_folder = '<?php echo $temp_folder ?>';
            $('#candie-file').ajaxfileupload({
      'action': 'http://' + $(location).attr('hostname') + keppo_path + 'all/upload_image_temp',
      'params': {
        'file_name': 'candie-file',
        'image_box_id': 'candie-image'
      },
      'onComplete': function(response) {
        //alert(JSON.stringify(response));
        var post_url = 'http://' + $(location).attr('hostname') + keppo_path + temp_folder;
        //var post_image = "<img src='" + post_url + response + "'>";
        var post_image = post_url + response[0];
        //$( '#upload-for-merchant-form-photo-box' ).html(post_image);
        $('img#'+ response[1]).attr('src', post_image);
      }
    });  
    });
    
    function showextrainfodiv()
    {
        var e = document.getElementById("show_extra_info");
        var selectedValue = e.options[e.selectedIndex].value;
        document.getElementById('extra_info_price_before_after').style.display = 'none';
        document.getElementById('extra_info_worth').style.display = 'none';
        document.getElementById('extra_info_get_off').style.display = 'none';
        document.getElementById('extra_info_buy_get').style.display = 'none';
        if (selectedValue == '121')
        {
            document.getElementById('extra_info_price_before_after').style.display = 'inline';
        }else if (selectedValue == '122'){
            document.getElementById('extra_info_worth').style.display = 'inline';
        }else if (selectedValue == '123'){
            document.getElementById('extra_info_get_off').style.display = 'inline';
        }else if (selectedValue == '124'){
            document.getElementById('extra_info_buy_get').style.display = 'inline';
        }
    }
</script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="candie-promotion">
    <h1>Candie Voucher</h1>
    <div id="candie-promotion-content">
        <div id='candie-promotion-form'>
            <?php echo form_open_multipart(uri_string()); ?>

            <?php
            //INPUT TYPE HIDDEN
            echo form_hidden($candie_id);
            ?>

            <div id="candie-promotion-form-go">
                <span id="candie-promotion-form-go-label"><?php echo lang("candie_year_month_label") ?></span>
                <span id="candie-promotion-form-go-year"><?php echo form_dropdown($candie_year, $year_list, $candie_year_selected); ?></span>
                <span id="candie-promotion-form-go-month"><?php echo form_dropdown($candie_month, $month_list, $candie_month_selected); ?></span>
                <span id="candie-promotion-form-go-button"><button name="button_action" type="submit" value="search_voucher">Go</button></span>
            </div>

            <div id="candie-promotion-form-photo">
                <div id="candie-promotion-form-photo-box">
                    <img src="<?php echo base_url($candie_image) ?>" id="candie-image" >
                </div>
                <div id='candie-promotion-form-photo-note'>
                    <?php echo $this->config->item('upload_guide_image') ?>
                </div>
                <div id='candie-promotion-form-input-file'>
                    <input type='file' accept='image/*' name='candie-file' id='candie-file'/>
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
                <div id='candie-promotion-form-each' style="display:none">
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
                
                <div id='candie-promotion-form-each'>
                    <div id='candie-promotion-form-each-label'><?php echo lang("candie_show_extra_info_label"); ?></div>
                    <div id='candie-promotion-form-each-input'>
                        <?php
                        echo form_dropdown($show_extra_info, $show_extra_info_list, $show_extra_info_selected);
                        ?>
                    </div>
                </div>
                
                <div id='extra_info_price_before_after' <?php if($show_extra_info_selected == 121){ echo 'style="display:inline"';}else{echo 'style="display:none"';} ?> >
                    <div id='candie-promotion-form-each'>
                        <div id='candie-promotion-form-each-label'><?php echo lang("hotdeal_price_before_label"); ?><?php //echo form_checkbox($price_before_show); ?></div>
                        <div id='candie-promotion-form-each-input'>
                            <?php
                            echo form_input($promotion_price_before);
                            ?>
                        </div>
                    </div>
                    <div id='candie-promotion-form-each'>
                        <div id='candie-promotion-form-each-label'><?php echo lang("hotdeal_price_after_label"); ?><?php //echo form_checkbox($price_after_show); ?></div>
                        <div id='candie-promotion-form-each-input'>
                            <?php
                            echo form_input($promotion_price_after);
                            ?>
                        </div>
                    </div>
                </div>      
                
                <div id='extra_info_worth' <?php if($show_extra_info_selected == 122){ echo 'style="display:inline"';}else{echo 'style="display:none"';} ?> >
                    <div id='candie-promotion-form-each'>
                        <div id='candie-promotion-form-each-label'><?php echo lang("candie_adv_worth_label"); ?></div>
                        <div id='candie-promotion-form-each-input'>
                            <?php
                            echo form_input($adv_worth);
                            ?>
                        </div>
                    </div>
                </div>
                
                <div id='extra_info_get_off' <?php if($show_extra_info_selected == 123){ echo 'style="display:inline"';}else{echo 'style="display:none"';} ?> >
                    <div id='candie-promotion-form-each'>
                        <div id='candie-promotion-form-each-label'><?php echo lang("candie_get_off_label"); ?></div>
                        <div id='candie-promotion-form-each-input'>
                            <?php
                            echo form_input($get_off_percent) . " %";
                            ?>
                        </div>
                    </div>
                </div>

                <div id='extra_info_buy_get' <?php if($show_extra_info_selected == 124){ echo 'style="display:inline"';}else{echo 'style="display:none"';} ?> >
                    <div id='candie-promotion-form-each'>
                        <div id='candie-promotion-form-each-label'><?php echo lang("candie_buy_get_label"); ?></div>
                        <div id='candie-promotion-form-each-input'>
                            <?php
                            echo 'Buy ' . form_input($how_many_buy) . " Get " . form_input($how_many_get);
                            ?>
                        </div>
                    </div>
                </div>

                <div id="candie-promotion-form-voucher-checkbox">
                    <div id="candie-promotion-form-voucher-checkbox-title">Select Terms & Conditions :</div>
                    <?php
                    foreach ($candie_term as $key => $value)
                    {
                        $checked_or_not = '';
                        if (in_array($key, $candie_term_current))
                        {
                            $checked_or_not = 'checked';
                        }
                        ?>
                            <div id="candie-promotion-form-voucher-checkbox-each">
                                    <table border="0" cellpadding="0px" cellspacing="0px">
                                        <tr>
                                            <td valign="top"><input type='checkbox' id="candie-term-<?php echo $key ?>" name='candie_term[]' value='<?php echo $key ?>' <?php echo $checked_or_not; ?>></td>
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
                    ?>  
                </div>
                <div id='candie-promotion-extra-terms-n-conditions'>
                    <div id='candie-promotion-extra-terms-n-conditions-title'><?php echo lang("candie_extra_term_label"); ?></div>
                    <?php echo form_textarea($extra_term); ?>
                </div>
                <div id="candie-promotion-form-branch-checkbox">
                    <div id="candie-promotion-form-branch-checkbox-title">Select Branch :</div>
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
                        $checked_or_not = '';
                        if (in_array($key, $candie_branch_current))
                        {
                            $checked_or_not = 'checked';
                        }
                            ?>
                            <div id="candie-promotion-form-branch-checkbox-each">
                                <table border="0" cellpadding="0px" cellspacing="0px">
                                    <tr>
                                        <td valign="top"><input type='checkbox' id="candie-branch-<?php echo $key ?>" name='candie_branch[]' value='<?php echo $key ?>' <?php echo $checked_or_not; ?>></td>
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
                    ?>  
                </div>
                <div id='candie-promotion-form-submit'>
                <?php 
                if ($is_history == 0 && $this->session->userdata('user_group_id') == $this->config->item('group_id_merchant'))
                { 
                ?>                   
                    <button name="button_action" type="submit" value="submit">Save</button>
                <?php
                }
                else
                {                    
                    echo " You cannot make changes to this candie voucher, either it already is history, not yet reach this month or you don't have privilege.";
                } 
                
                if($promotion_id != ''){
                    echo '<input type="hidden" name="promotion_id" value="'.$promotion_id.'" />';
                    if($promotion_frozen == 0){ 
                ?>
                   <button name="button_action" type="submit" value="frozen_hotdeal" onclick="return confirm('Are you sure want to frozen this promotion? After frozen then it will not show publicly until you unfrozen it.')" >Frozen</button>
                   <?php }else{ ?>
                   <button name="button_action" type="submit" value="unfrozen_hotdeal" >Unfrozen</button>
                   <?php } 
                }
                ?>
                 </div>
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