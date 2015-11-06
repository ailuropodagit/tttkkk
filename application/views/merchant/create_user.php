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
    
    function get_SubCategory()
    {
        var dep_selected = $('select[name=me_category_id]').val();
        var post_url = "<?php echo base_url(); ?>" + 'merchant/get_sub_category_by_category/' + dep_selected;
        $.ajax({
            type: 'POST',
            url: post_url,
            dataType: 'html',
            success: function (data) 
            {
                $('#me_sub_category_id').empty();
                $('#me_sub_category_id').html(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert(textStatus);
                alert(errorThrown);
            }
        });
    }   
    
    $(document).ready(function () {  
        $('#phone').blur(function () {
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
        }).focus(function () {
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

<div id='register'>
    <div id='register-link'>
        <a href='<?php echo base_url(); ?>user/register'>
            <i class="fa fa-user-plus" id="register-link-icon"></i>User Register
        </a>
    </div>
    <div id='float-fix'></div>
    <div id='register-title'>Merchant Sign Up</div>
    <div id='register-subtitle'>Already have register? <a href='<?php echo base_url(); ?>merchant/login'>Log In</a></div>
    <div id='register-horizontal-line'></div>
    
    <?php echo form_open($function_use_for); ?>
        <div id='register-form'>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_company_main_label', 'company_main'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($company_main); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_companyssm_label', 'me_ssm'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($me_ssm); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_company_label', 'company'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($company); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_address_label', 'address'); ?></div>
                <div id='register-form-each-input'><?php echo form_textarea($address); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_postcode_label', 'postcode'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($postcode); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_state_label', 'me_state_id'); ?></div>
                <div id='register-form-each-input'><?php echo form_dropdown($me_state_id, $state_list); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_country_label', 'me_country'); ?></div>
                <div id='register-form-each-input'><?php echo form_dropdown($me_country, $country_list); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_category_label', 'me_category_id'); ?></div>
                <div id='register-form-each-input'><?php echo form_dropdown($me_category_id, $category_list); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_sub_category_label', 'me_sub_category_id'); ?></div>
                <div id='register-form-each-input'><?php echo form_dropdown($me_sub_category_id, $sub_category_list); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_phone_label', 'phone'); ?></div>
                <div id='register-form-each-input-contact-number'>+60 <?php echo form_input($phone); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_username_label', 'username'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($username); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_email_label', 'email'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($email); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_password_label', 'password'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($password); ?></div>
            </div>
            <div id='register-form-each'>
                <div id='register-form-each-label'><?php echo lang('create_merchant_password_confirm_label', 'password_confirm'); ?></div>
                <div id='register-form-each-input'><?php echo form_input($password_confirm); ?></div>
            </div>
            <div id='register-form-forgot-password'>
                <input type="checkbox" id="show_password" name="show_password" onclick="showpassword();"/>
                <span class="checkbox-text"><label for='show_password'>Show Password</label></span>
            </div>   
            <div id="register-form-agree-checkbox">
                <input type="checkbox" name="accept_terms" value="1" /> I agree to the Terms of Service and Privacy Policy.
            </div>
            <div id='register-form-submit'>
                <?php echo form_submit('submit', 'Sign Up'); ?>
            </div>
        </div>
    <?php echo form_close(); ?>
    
    <div id='register-agree-link'>
        By creating an account, you agree to our 
        <a href='<?php echo base_url() ?>terms-of-service' target='_blank'>Terms of Service</a>
        and
        <a href='<?php echo base_url() ?>privacy-policy' target='_blank'>Privacy Policy.</a>
    </div>
    
</div>