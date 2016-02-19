<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="invite_friend">
    <h1>Promo Code</h1>
    <div id="invite-friend-content">
        
        <div id="invite-friend-form">
            <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'>Merchant Promo Code</div>
                    <div id='invite-friend-form-input'><?php echo form_input($promo_code_no); ?></div>
            </div>   
            <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('promo_code_redeem_count') . $promo_code_url; ?></div>
            </div>
        </div>
        
    </div>
</div>