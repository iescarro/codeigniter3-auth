<h2>Forgot password?</h2>
<?php echo form_open('auth/forgot'); ?>
<p>
  Email address<br>
  <?php echo form_input('email', $this->input->post('email'), ''); ?>
</p>
<p>
  <?php echo form_submit('submit', 'Send password reset link'); ?>
</p>
<p>
  Nevermind, I remember my account. <?php echo anchor('login', 'Login'); ?>
</p>
<?php echo form_close(); ?>