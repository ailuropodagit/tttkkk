<?php
//IF LOGGED IN
if ($this->ion_auth->logged_in())
{
    redirect('user/profile');
}
?>

<script type="text/javascript">
    function showpassword()
    {
        if (document.getElementById('show_password').checked)
        {
            document.getElementById('password').type = 'text';
        } 
        else
        {
            document.getElementById('password').type = 'password';
        }
    }
    
    // Call from FB.getLoginStatus().
    function statusChangeCallback(response) {
        //console.log(response);
        // Login status
        if (response.status === 'connected') {
            // connected
            FB.api('/me/permissions/public_profile', function (response) {
                if (response.data[0].status === 'granted') {
                    //public profile granted
                    FB.api('/me/permissions/email', function (response) {
                        if (response.data[0].status === 'granted') {
                            //email granted
                            FB.api('/me', {fields: 'id,first_name,last_name,email,gender'}, function (response) {
                                var fb_id = response.id;
                                var fb_email = response.email;
                                var fb_first_name = response.first_name;
                                var fb_last_name = response.last_name;
                                var fb_gender = response.gender;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url() ?>user/login_facebook_check",
                                    data: {fb_id:fb_id, fb_email:fb_email, fb_first_name:fb_first_name, fb_last_name:fb_last_name, fb_gender:fb_gender},
                                    success: function(data) {
                                        var $response = $(data);
                                        var login_fb_merchant_email = $response.filter('#login-fb-merchant-email').text()
                                        var login_fb_id_not_exists = $response.filter('#login-fb-id-not-exists').text()
                                        var login_fb_id_success = $response.filter('#login-fb-id-success').text();
                                        if (login_fb_merchant_email) {
                                            fbLogout();
                                            $('#login-facebook-label').html('Facebook login is not available for Merchant. <a href="<?php echo base_url() ?>merchant/login">Click Here</a> for merchant login');
                                        }
                                        if (login_fb_id_not_exists) {
                                            window.location.replace("<?php echo base_url() ?>user/login_facebook_first_time");
                                        }
                                        if (login_fb_id_success) {
                                            var login_user_id = $response.filter('#login-user-id').text();
                                            window.location.replace("<?php echo base_url() ?>all/user_dashboard/"+login_user_id);
                                        }
                                    }
                                });
                            });
                        } else {
                            //email declined
                            document.getElementById('login-facebook-label').innerHTML = "Email Declined";
                            FB.login(function(response) {
                                //console.log(response);
                            }, {
                                scope: 'email',
                                auth_type: 'rerequest'
                            });
                        }
                    });
                } else {
                    //public profile declined
                    document.getElementById('login-facebook-label').innerHTML = "Public Profile Declined";
                }
            });
        } else if (response.status === 'not_authorized') {
            // not_authorized
        } else {
            // unknown
        }
    }

    // Check Login Status
    function checkLoginState() {
        $('#login-facebook-label').html('<img src="<?php echo base_url() ?>image/loading.gif" style="width:15px; margin: 0px 5px 0px 0px;"> Please Wait. Facebook logging in.');
        FB.getLoginStatus(function (response) {
            statusChangeCallback(response);
        });
    }

    // Logout
    function fbLogout() {
    //    //log out both facebook and app
    //    FB.logout(function(response) {
    //        console.log(response);
    //        document.getElementById('login-facebook-label').innerHTML = "Log In with facebook";
    //    });
        //logout fb only but required permission pop up
        FB.api('/me/permissions', 'delete', function(response) {
            console.log(response.status); // true for successful logout.
        });
    }

    window.fbAsyncInit = function () {
        FB.init({
            appId: '1682555468669559',
            cookie: true,
            xfbml: true,
            version: 'v2.5'
        });
        //Get if logged in
        FB.getLoginStatus(function (response) {
            statusChangeCallback(response);
        });
    };

    // Load the SDK asynchronously
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<?php
//MESSAGE
if(isset($message))
{
    ?><div id="message"><?php echo $message; ?></div><?php
}
?>

<div id="login">
    <div id='login-link'>
        <a href='<?php echo base_url(); ?>merchant/login'>
            <i class="fa fa-user-secret" id="login-link-icon"></i>Merchant Login
        </a>
    </div>
    <div id='float-fix'></div>
    <div id='login-title'>User Log In</div>
    <div id='login-subtitle'>Don't have an account? <a href='<?php echo base_url(); ?>user/register'>Sign Up</a></div>
    <div id='login-facebook'>
        <div id="login-facebook-button"><fb:login-button data-size="large" scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button></div>
        <div id="login-facebook-label">Log In with facebook</div>
    </div>

    <?php echo form_open("user/login"); ?>       
    <div id='login-form'>
        <div id='login-horizontal-line'></div>
        <div id='login-form-each'>
            <div id='login-form-each-label'><?php echo lang('login_identity_label', 'identity'); ?></div>
            <div id='login-form-each-input'><?php echo form_input($identity); ?></div>
        </div>
        <div id='login-form-each'>
            <div id='login-form-each-label'><?php echo lang('login_password_label', 'password'); ?></div>
            <div id='login-form-each-input'><?php echo form_input($password); ?></div>
        </div>
        <div id='login-form-show-password'>
            <input type="checkbox" id="show_password" name="show_password" onclick="showpassword();"/>
            <label for='show_password'>Show Password</label>
        </div>   
        <div id='login-form-remember-me'>
            <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?> 
            <?php echo lang('login_remember_label', 'remember'); ?>
        </div>
        <div id='login-form-forgot-password'>
            <a href="retrieve-password"><?php echo lang('login_forgot_password'); ?></a>
        </div>
        <div id='login-form-submit'><?php echo form_submit('submit', lang('login_submit_btn')); ?></div>
    </div>
    <?php echo form_close(); ?>

    <div id='login-agree'>
        By logging in, you agree to our 
        <a href='<?php echo base_url() ?>terms-of-service' target='_blank'>Terms of Service</a>
        and
        <a href='<?php echo base_url() ?>privacy-policy' target='_blank'>Privacy Policy.</a>
    </div>
</div>