
<?php echo link_tag('css/main.css'); ?>

<h1><?php echo $title ?></h1>
<p>Already have register? <a href='./login'>Log In</a></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open($function_use_for);?>

      <p>
            <?php echo lang('create_merchant_company_label', 'company');?> <br />
            <?php echo form_input($company);?>
      </p>
      
      <p>
            <?php echo lang('create_merchant_fname_label', 'first_name');?> <br />
            <?php echo form_input($first_name);?>
      </p>
      
       <p>
            <?php echo lang('create_merchant_companyssm_label', 'me_ssm');?> <br />
            <?php echo form_input($me_ssm);?>
      </p>
      
      <p>
            <?php echo lang('create_merchant_address_label', 'address');?> <br />
            <?php echo form_textarea($address);?>
      </p>
      
      <p>
            <?php echo lang('create_merchant_state_label', 'me_state_id');?> <br />
            <?php echo form_dropdown($me_state_id,$state_list);?>
      </p>
 
      <p>
            <?php echo lang('create_merchant_phone_label', 'phone');?> <br />
            <?php echo form_input($phone);?>
      </p>
      
      <p>
            <?php echo lang('create_merchant_username_label', 'username');?> <br />
            <?php echo form_input($username);?>
      </p>
      
      <p>
            <?php echo lang('create_merchant_email_label', 'email');?> <br />
            <?php echo form_input($email);?>
      </p>

      <p>
            <?php echo lang('create_merchant_password_label', 'password');?> <br />
            <?php echo form_input($password);?>
      </p>

      <p>
            <?php echo lang('create_merchant_password_confirm_label', 'password_confirm');?> <br />
            <?php echo form_input($password_confirm);?>
      </p>


      <p><?php echo form_submit('submit', 'Sign Up');?></p>

<?php echo form_close();?>

      by clicking Sign Up you agree to our new <a href='http://www.google.com'>T&C's</a>