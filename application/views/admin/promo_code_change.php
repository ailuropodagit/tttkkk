<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="candie-promotion">   
    <?php
    if ($is_edit == 0)
    {
        echo '<h1>Promo Code Add</h1>';
    }
    else
    {
        echo '<h1>Promo Code Edit</h1>';
    }                       
    ?>
    <div id='profile-content'>              
        <div id='profile-info'> 
            <?php echo form_open(uri_string()); ?>
            <div id='profile-info-form'>
                
                <?php if($code_type == 'user'){ ?>   <!-- if promo code type is user, show user name -->
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'User Name : ' . $this->m_custom->generate_user_link($result['code_user_id']); ?></div>
                </div>
                <?php } ?>
                <?php if($code_type == 'merchant'){ ?>  <!-- if promo code type is merchant, show merchant name -->
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Merchant Name : ' . $this->m_custom->generate_user_link($result['code_user_id']); ?></div>
                </div>
                <?php } ?>        
                
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('promo_code_no', 'code_no'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($code_no); ?></div>
                </div>          
                
                <?php if($code_type == 'user'){ ?>  <!-- if promo code type is user, show default candie that friend will get when register using this user promo code -->
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Default Register Candie Give : ' . $this->m_custom->web_setting_get('register_promo_code_get_candie'); ?></div>
                </div>
                <?php } ?>
                <?php if($code_type == 'merchant'){ ?>  <!-- if promo code type is merchant, show default candie that user key in this merchant promo code -->
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Default Merchant Candie Give : ' . $this->m_custom->web_setting_get('merchant_promo_code_get_candie'); ?></div>
                </div>
                <?php } ?>       
                
                <?php if($code_type == 'user' || $code_type == 'merchant'){ ?>   <!-- if promo code type is user/merchant, can tick this to key in the overwrite candie value -->
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo 'Overwrite Candie Give'; ?>
                        <?php echo form_checkbox($code_candie_overwrite); ?></div>
                </div>
                <?php 
                $div_show_hide = "style='display:none'";
                if ($code_candie_overwrite_value == 1)
                {
                    $div_show_hide = "style='display:inline'";
                }
                ?>
                <div id='code-candie-div' <?php echo $div_show_hide; ?>>
                    <div id='profile-info-form-each'>  <!-- key in the overwrite candie value -->
                        <div id='profile-info-form-each-label'><?php echo lang('promo_code_candie', 'code_candie'); ?></div>
                        <div id='profile-info-form-each-input'><?php echo form_input($code_candie); ?></div>
                    </div> 
                </div>
                <?php } ?> 
                
                <?php if($code_type == 'user'){ ?> 
                <div id='profile-info-form-each'>   <!-- if promo code type is user, show default cash back user can get when friend register using this user promo code -->
                    <div id='profile-info-form-each-label'><?php echo 'Default Cash Back : ' . $this->m_custom->web_setting_get('friend_success_register_get_money', 'set_decimal'); ?></div>
                </div>
                <div id='profile-info-form-each'>   <!-- if promo code type is user, can tick this to key in the overwrite cash back value -->
                    <div id='profile-info-form-each-label'><?php echo 'Overwrite Cash Back'; ?>
                        <?php echo form_checkbox($code_money_overwrite); ?></div>
                </div>
                <?php 
                $div_show_hide = "style='display:none'";
                if ($code_money_overwrite_value == 1)
                {
                    $div_show_hide = "style='display:inline'";
                }
                ?>
                <div id='code-money-div' <?php echo $div_show_hide; ?>>
                    <div id='profile-info-form-each'>   <!-- key in the overwrite cash back value -->
                        <div id='profile-info-form-each-label'><?php echo lang('promo_code_money', 'code_money'); ?></div>
                        <div id='profile-info-form-each-input'><?php echo form_input($code_money); ?></div>
                    </div>        
                </div>
                <?php } ?>
                
                <?php if($code_type == 'event'){ ?>  
                <div id='profile-info-form-each'>  <!-- if promo code type is event, key in the candie that user can get when redeem this special promo code -->
                    <div id='profile-info-form-each-label'><?php echo lang('promo_code_candie', 'code_candie'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($code_candie); ?></div>
                </div> 
                <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('promo_code_event_name', 'code_event_name'); ?></div>
                    <div id='profile-info-form-each-input'><?php echo form_input($code_event_name); ?></div>
                </div>              
                <?php } ?>
                
                <?php
                echo form_hidden($edit_id);
                ?>
                <div id='profile-info-form-submit'>              
                    <button name="button_action" type="submit" value="back">Back</button>
                    <button name="button_action" type="submit" value="save" onclick="return confirm('Confirm that information is correct before save it?')">Save</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        
    </div>
</div>
