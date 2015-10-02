<script>
    $(function(){
        //race other
        $("#race").on('change', function() {
            if($("#race option:selected").val() === 'other')
            {
                $("#register-form-each-other").css({display: 'block'});
            }
            else
            {
                $("#register-form-each-other").css({display: 'none'});
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
                <div id='register-form-each-label'>E-mail Address</div>
                <div id='register-form-each-input'><input type="text" name="email"></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'>Contact Number</div>
                <div id='register-form-each-input-contact-number'>+60 <input type="text" name="contact_number"></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_user_dob_label'); ?></div>
                <div id='register-form-each-input-dob'>
                    <div id='register-form-each-input-dob-day'>
                        <select name="dob_day">
                            <?php 
                            foreach($dob_day_array as $day) 
                            {
                                ?>
                                <option value="<?php echo $day ?>"><?php echo $day ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div id='register-form-each-input-dob-month'>
                        <select name="dob_month">
                            <?php
                            $result_array_static_option_month = $query_static_option_month->result_array();
                            foreach ($result_array_static_option_month as $static_option_month)
                            {
                                $month_value = $static_option_month['option_value'];
                                $month_text = $static_option_month['option_text'];
                                ?>
                                <option value="<?php echo $month_value ?>"><?php echo $month_text ?>-<?php echo $month_value ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div id='register-form-each-input-dob-year'>
                        <select name="dob_year">
                            <?php 
                            foreach($dob_year_array as $year) 
                            {
                                ?>
                                <option value="<?php echo $year ?>"><?php echo $year ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div id="float-fix"></div>
                </div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'>Race:</div>
                <div id='register-form-each-input'>
                    <select name="race" id="race">
                        <?php
                        $result_array_static_option_race = $query_static_option_race->result_array();
                        foreach($result_array_static_option_race as $static_option_race)
                        {
                            $race_value = $static_option_race['option_value'];
                            $race_text = $static_option_race['option_text'];
                            ?>
                            <option value="<?php echo $race_value ?>"><?php echo $race_text ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div id='register-form-each-other'>
                <div id='register-form-each-label'>Other:</div>
                <div id='register-form-each-input'><input type="text" name="other"></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'>Gender:</div>
                <div id='register-form-each-input'>
                    <select name="gender">
                        <?php
                        $result_array_static_option_gender = $query_static_option_gender->result_array();
                        foreach($result_array_static_option_gender as $static_option_gender)
                        {
                            $gender_value = $static_option_gender['option_value'];
                            $gender_text = $static_option_gender['option_text'];
                            ?>
                            <option value="<?php echo $gender_value ?>"><?php echo $gender_text ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
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
    
    <?php
    //echo $post_value_array['fb_id'];
    ?>
</div>