<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="login">

    <h1><?php echo lang('forgot_password_heading'); ?></h1>
    <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?></p>

    <?php echo form_open("admin/retrieve_password"); ?>

    <p>
        <label for="username_email"><?php echo sprintf(lang('forgot_password_username_email_label'), $identity_label); ?>:</label> <br />
        <?php echo form_input($username_email); ?>
    </p>

    <p><?php echo form_submit('submit', lang('forgot_password_submit_btn')); ?></p>

    <?php echo form_close(); ?>

</div>
