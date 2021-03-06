<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="login">

    <div id='login-link'>&nbsp;</div>
    <div id="float-fix"></div>

    <div id='login-title'><?php echo lang('forgot_password_heading'); ?></div>

    <div id="login-subtitle"><?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?></div>

    <?php echo form_open("user/retrieve-password"); ?>

    <div id="login-form">
        <div id="login-form-each">
            <div id="login-form-each-label">
                <label for="username_email"><?php echo sprintf(lang('forgot_password_username_email_label'), $identity_label); ?>:</label>
            </div>
            <div id="login-form-each-input">
                <?php echo form_input($username_email); ?>
            </div>
        </div>
        <div id='login-form-submit'>
            <?php echo form_submit('submit', lang('forgot_password_submit_btn')); ?>
        </div>
    </div>

    <?php echo form_close(); ?>

</div>