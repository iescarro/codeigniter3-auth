<h2>Login</h2>
<?php echo form_open('auth/login'); ?>
<p>
  Username<br>
  <?php echo form_input('username', $this->input->post('username'), ''); ?>
</p>
<p>
  Password<br>
  <?php echo form_password('password', '', ''); ?>
</p>
<p>
  <?php echo form_submit('submit', 'Login'); ?>
</p>
<p>
  No account yet? <?php echo anchor('register', 'Register'); ?>
</p>
<?php echo form_close(); ?>