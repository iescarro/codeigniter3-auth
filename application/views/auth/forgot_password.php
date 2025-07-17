<!doctype html>
<html lang="en">

<head>
  <base href="<?php echo base_url(); ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <title></title>

  <style>
    .forgot-password {
      width: 350px;
      margin: auto;
    }

    .forgot-password label {
      font-size: .8em;
      text-transform: uppercase;
    }
  </style>
</head>

<body>

  <div class="forgot-password  mt-5">
    <div class="card shadow-sm p-2">
      <div class="card-body">
        <center>
          <img class="mt-3 mb-3" src="data:image/png;base64,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">
        </center>
        <?php if (isset($info)): ?>
          <small class="text-success">
            <?= $info; ?>
          </small>
        <?php endif; ?>
        <?= form_open('auth/forgot_password'); ?>
        <p>
          <label for="email">Email address</label>
          <input type="email" name="email" value="<?= set_value('email'); ?>" class="form-control">
          <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
        </p>
        <p>
          <button type="submit" class="btn btn-outline-secondary ">Send password reset link</button>
        </p>
        <p>
          Already have an account. <?= anchor('auth/login', 'Login'); ?>
        </p>
        <?= form_close(); ?>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>