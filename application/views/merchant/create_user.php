
<?php echo link_tag('css/main.css'); ?>

<h1><?php echo $title ?></h1>
<p><?php echo 'Please enter the merchant information below.';?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open($function_use_for);?>

      <p>
            <?php echo lang('create_user_username_label', 'username');?> <br />
            <?php echo form_input($username);?>
      </p>
      
      <p>
            <?php echo lang('create_user_fname_label', 'first_name');?> <br />
            <?php echo form_input($first_name);?>
      </p>

      <p>
            <?php echo lang('create_user_lname_label', 'last_name');?> <br />
            <?php echo form_input($last_name);?>
      </p>

      <p>
            <?php echo lang('create_user_company_label', 'company');?> <br />
            <?php echo form_input($company);?>
      </p>

      <p>
            <?php echo lang('create_user_companyssm_label', 'me_ssm');?> <br />
            <?php echo form_input($me_ssm);?>
      </p>
      
      <p>
            <?php echo lang('create_user_address_label', 'address');?> <br />
            <?php echo form_textarea($address);?>
      </p>
      
      <p>
            <?php echo lang('create_user_state_label', 'me_state_id');?> <br />
            <?php echo form_dropdown($me_state_id,$state_list);?>
      </p>
      
      <p>
            <?php echo lang('create_user_email_label', 'email');?> <br />
            <?php echo form_input($email);?>
      </p>

      <p>
            <?php echo lang('create_user_phone_label', 'phone');?> <br />
            <?php echo form_input($phone);?>
      </p>

      <p>
            <?php echo lang('create_user_website_label', 'website');?> <br />
            <?php echo form_input($website);?>
      </p>
      
      <p>
            <?php echo lang('create_user_password_label', 'password');?> <br />
            <?php echo form_input($password);?>
      </p>

      <p>
            <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
            <?php echo form_input($password_confirm);?>
      </p>


      <p><?php echo form_submit('submit', 'Submit');?></p>

<?php echo form_close();?>
