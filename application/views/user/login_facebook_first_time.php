<script>
    $(function(){
        //sessionStorage
        var race_other = sessionStorage['race_other'];
        if (race_other) {
            $("#register-form-each-other").css({display: 'block'});
        }
        //race other
        $("#race").on('change', function() {            
            if($("#race option:selected").val() === '19') //19 = other
            {
                $("#register-form-each-other").css({display: 'block'});
                sessionStorage['race_other'] = "yes";
            }
            else
            {
                $("#register-form-each-other").css({display: 'none'});
                sessionStorage.removeItem('race_other');
            }
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
    <div id='register-title'>Facebook First Time Log In</div>
    
    <div id='register-facebook'>
        <img src='<?php echo base_url(); ?>image/facebook-icon.png'>
    </div>
    
    <div id='register-horizontal-line'></div>
    
    <div id='register-form'>
        <form method="POST">
            <div id='register-form-each'>
                <div id='register-form-each-label'>E-mail Address:</div>
                <div id='register-form-each-input'><input type="text" name="email" value="<?php echo $email ?>"></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'>Contact Number:</div>
                <div id='register-form-each-input-contact-number'>+60 <input type="text" name="contact_number" value="<?php echo $contact_number ?>"></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'>Date of Birth:</div>
                <div id='register-form-each-input-dob'>
                    <div id='register-form-each-input-dob-day'><?php echo form_dropdown('dob_day', $dob_day_array, $dob_day) ?></div>
                    <div id='register-form-each-input-dob-month'><?php echo form_dropdown('dob_month', $dob_month_associative_array, $dob_month) ?></div>
                    <div id='register-form-each-input-dob-year'><?php echo form_dropdown('dob_year', $dob_year_array, $dob_year) ?></div>
                    <div id="float-fix"></div>
                </div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'>Race:</div>
                <div id='register-form-each-input'>
                    <?php
                    $attribute = array('id'=>"race"); 
                    echo form_dropdown('race', $race_associative_array, $race, $attribute);
                    ?>
                </div>
            </div>
            <div id='register-form-each-other'>
                <div id='register-form-each-label'>Race Other:</div>
                <div id='register-form-each-input'><input type="text" name="race_other" value='<?php echo $race_other ?>'></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'>Gender:</div>
                <div id='register-form-each-input'><?php echo form_dropdown('gender', $gender_associative_array, $gender) ?></div>
            </div>
            <div id='register-form-submit'>
                <input type="submit" value="Sign Up">
            </div>
        </form>
    </div>
    
    <div id='login-agree'>
        By logging in, you agree to our 
        <a href='<?php echo base_url() ?>terms-of-service' target='_blank'>Terms of Service</a>
        and
        <a href='<?php echo base_url() ?>privacy-policy' target='_blank'>Privacy Policy.</a>
    </div>
</div>