<style>
  form label {
    font-size: 0.8em;
    text-transform: uppercase;
  }
</style>
<h3>Profile</h3>
<?php if (isset($info)): ?>
  <small class="text-success">
    <?= $info; ?>
  </small>
<?php endif; ?>
<?= form_open('auth/profile'); ?>
<p>
  <label for="name">Name</label>
  <input type="text" name="name" value="<?= $user->name; ?>" class="form-control">
  <?= form_error('name', '<small class="text-danger">', '</small>'); ?>
</p>
<p>
  <label for="email">Email</label>
  <input type="email" name="email" value="<?= $user->email; ?>" class="form-control">
</p>
<p>
  <label for="password">Password</label>
  <input type="password" class="form-control">
</p>
<p>
  <label for="confirm_password">Confirm password</label>
  <input type="password" class="form-control">
</p>
<p>
  <button type="submit" class="btn btn-outline-secondary">Update profile</button>
</p>
<?= form_close(); ?>