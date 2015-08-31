<h1><?php echo lang('change_password_heading');?></h1>

<div id="infoMessage"><?php echo $message;?></div>

<script type="text/javascript">
function showpassword()
{
  if (document.getElementById('show_password').checked) 
  {
      document.getElementById('old').type = 'text';
      document.getElementById('new').type = 'text';
      document.getElementById('new_confirm').type = 'text';
  } else {
      document.getElementById('old').type = 'password';
      document.getElementById('new').type = 'password';
      document.getElementById('new_confirm').type = 'password';
  }
}
</script>

<?php echo form_open($function_use_for);?>

      <p>
            <?php echo lang('change_password_old_password_label', 'old_password');?> <br />
            <?php echo form_input($old_password);?>
      </p>

      <p>
            <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label> <br />
            <?php echo form_input($new_password);?>
      </p>

      <p>
            <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?> <br />
            <?php echo form_input($new_password_confirm);?>
      </p>

      <?php echo form_input($user_id);?>
      <p><input type="checkbox" id="show_password" name="show_password" onclick="showpassword();"/><span class="checkbox-text"> Show Password </span></p>
      <p><?php echo form_submit('submit', lang('change_password_submit_btn'));?> </p>

<?php echo form_close();?>
