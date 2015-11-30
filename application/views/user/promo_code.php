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
                    <div id='profile-info-form-each-label'>My Promo Code (Copy this for friend register)</div>
                    <div id='invite-friend-form-input'><?php echo form_input($promo_code_no); ?></div>
            </div>   
            <div id='profile-info-form-each'>
                    <div id='profile-info-form-each-label'><?php echo lang('promo_code_redeem_count') . $promo_code_url; ?></div>
            </div>
            <form method="POST" action="<?php echo base_url() ?>user/promo_code">
            <div id="invite-friend-form-label">Key in the Promo Code to get surprise!</div>
            <div id="invite-friend-form-input"><input type="text" name="promo_code"></div>
            <div id="invite-friend-form-submit"><button name="button_action" type="submit" value="save">Submit</button></div>
            </form>
        </div>
        
    </div>
</div>