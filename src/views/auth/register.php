<h2>Register</h2>
<?php echo form_open('auth/register'); ?>
<p>
  Name<br>
  <?php echo form_input('name', $this->input->post('name'), ''); ?>
</p>
<p>
  Email<br>
  <?php echo form_input('email', $this->input->post('email'), ''); ?>
</p>
<p>
  Password<br>
  <?php echo form_password('password', '', ''); ?>
</p>
<p>
  Confirm password<br>
  <?php echo form_password('confirm_password', '', ''); ?>
</p>
<p>
  <?php echo form_submit('submit', 'Register'); ?>
</p>
<p>
  Nevermind, I have an account. <?php echo anchor('auth/login', 'Login'); ?>
</p>
<?php echo form_close(); ?>