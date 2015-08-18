<div id='wrapper'>
    <div id='body'>
        <div id='body-content'>

            <h1><?php echo "Merchant Log In" ?></h1>
            <p>Don't have an account? <a href='./register'>Sign Up</a></p>

            <div id="infoMessage"><?php echo $message; ?></div>

            <?php echo form_open("merchant/login"); ?>

            <p>
                <?php echo lang('login_identity_label', 'identity'); ?><br />
                <?php echo form_input($identity); ?>
            </p>

            <p>
                <?php echo lang('login_password_label', 'password'); ?><br />
                <?php echo form_input($password); ?>
            </p>

            <p>
                <?php echo lang('login_remember_label', 'remember'); ?>
                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>  <a href="retrieve_password"><?php echo lang('login_forgot_password'); ?></a>
            </p>

            <p>
                <?php echo form_submit('submit', lang('login_submit_btn')); ?>
            </p>

            <?php echo form_close(); ?>

                  by clicking Log In<br/>
                  you agree to our new <a href='http://www.google.com'>T&C's</a>
                  
        </div>
    </div>
</div>