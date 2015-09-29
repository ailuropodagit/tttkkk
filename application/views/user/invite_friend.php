<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="invite_friend">
    <h1>Invite Friend</h1>
    <div id="invite-friend-content">
        
        <div id="invite-friend-form">
            <form method="POST" action="<?php echo base_url() ?>user/invite_friend">
            <div id="invite-friend-form-label">E-mail Address:</div>
            <div id="invite-friend-form-input"><input type="text" name="email"></div>
            <div id="invite-friend-form-submit"><input type="submit" value="Invite"></div>
            </form>
        </div>
        
    </div>
</div>