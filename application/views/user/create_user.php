<script type="text/javascript">
    function showpassword()
    {
        if (document.getElementById('show_password').checked)
        {
            document.getElementById('password').type = 'text';
            document.getElementById('password_confirm').type = 'text';
        } 
        else
        {
            document.getElementById('password').type = 'password';
            document.getElementById('password_confirm').type = 'password';
        }
    }
    
    function showraceother()
    {
        var race_id_selected = document.getElementById("race_id");
        var selectedText = race_id_selected.options[race_id_selected.selectedIndex].text;
        if (selectedText == 'Other')
        {
            document.getElementById('race_other_label').style.display = 'inline';
            document.getElementById('race_other').style.display = 'inline';
        } else {
            document.getElementById('race_other_label').style.display = 'none';
            document.getElementById('race_other').style.display = 'none';
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
                                            window.location.replace("<?php echo base_url() ?>user/profile");
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
            appId: '1636247466623391',
            cookie: true,
            xfbml: true,
            version: 'v2.2'
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
    
       $(document).ready(function () {  
    $('#phone').blur(function ()
            {
                var value = $(this).val();

                var text_length = value.length;
                if (text_length == 9) {
                    value = value.slice(0, 2) + " " + value.slice(2, 5) + " " + value.slice(5);
                }
                else if (text_length == 8) {
                    value = value.slice(0, 1) + " " + value.slice(1, 4) + " " + value.slice(4);
                }
                else {
                    value = value.match(/.{1,3}/g).join(" ");
                }

                $(this).val(value);
            })
                    .focus(function () {
                        var value = $(this).val();
                        value = value.replace(/\s/g, '');
                        $(this).val(value);
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

<div id="register">

    <div id='register-link'>
        <a href='<?php echo base_url(); ?>merchant/register'>
            <i class="fa fa-user-plus" id="register-link-icon"></i>Merchant Register
        </a>
    </div>
    <div id='float-fix'></div>

    <div id='register-title'>User Sign Up</div>

    <div id='register-subtitle'>Already have register? <a href='<?php echo base_url(); ?>user/login'>Log In</a></div>

    <div id='login-facebook'>
        <div id="login-facebook-button"><fb:login-button data-size="large" scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button></div>
        <div id="login-facebook-label">Log In with facebook</div>
    </div>

    <div id='register-horizontal-line'></div>

    <?php echo form_open("user/register"); ?>       

    <div id='register-form'>

        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_first_name_label', 'first name'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($first_name); ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_last_name_label', 'last name'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($last_name); ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_contact_number_label', 'contact number'); ?></div>
            <div id='register-form-each-input-contact-number'>+60 <?php echo form_input($phone); ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_dob_label'); ?></div>
            <div id='register-form-each-input-dob'>
                <div id='register-form-each-input-dob-day'><?php echo form_dropdown($day, $day_list); ?></div>
                <div id='register-form-each-input-dob-month'><?php echo form_dropdown($month, $month_list); ?></div>
                <div id='register-form-each-input-dob-year'><?php echo form_dropdown($year, $year_list); ?></div>
                <div id="float-fix"></div>
            </div>
        </div>
        <div id='register-form-each'>            
            <div id='register-form-each-label'><?php echo lang('create_user_gender_label', 'gender_id'); ?></div>
            <div id='register-form-each-input'><?php echo form_dropdown($gender_id, $gender_list); ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_race_label', 'race_id'); ?></div>
            <div id='register-form-each-input'><?php echo form_dropdown($race_id, $race_list); ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><span id="race_other_label" style="display:none"><?php echo lang('create_user_race_other_label', 'race_other'); ?></span></div>
            <div id='register-form-each-input'><?php echo form_input($race_other); ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_email_label', 'email address'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($email); ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_user_username_label', 'username'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($username); ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_password_label', 'password'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($password); ?></div>
        </div>
        <div id='register-form-each'>
            <div id='register-form-each-label'><?php echo lang('create_merchant_password_confirm_label', 'password_confirm'); ?></div>
            <div id='register-form-each-input'><?php echo form_input($password_confirm); ?></div>
        </div>
        <div id='login-form-remember-me-forgot-password'>
            <div id='login-form-forgot-password'>
                <input type="checkbox" id="show_password" name="show_password" onclick="showpassword();"/>
                <span class="checkbox-text"><label for='show_password'>Show Password</label></span>
            </div>   
            <div id='float-fix'></div>
        </div>
        <input type="checkbox" name="accept_terms" value="1" /> I agree to the Terms of Service and Privacy Policy. <br>
        <div id='register-form-submit'>
            <?php echo form_submit('submit', 'Sign Up'); ?>
        </div>

    </div>

    <?php echo form_close(); ?>

    <div id='login-agree'>
        By creating an account, you agree to our 
        <a href='<?php echo base_url() ?>terms-of-service' target='_blank'>Terms of Service</a>
        and
        <a href='<?php echo base_url() ?>privacy-policy' target='_blank'>Privacy Policy.</a>
    </div>
    
</div>
