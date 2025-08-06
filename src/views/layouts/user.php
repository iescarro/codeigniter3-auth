<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- JQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

  <title>
    <?php echo config_item('app_name'); ?>
  </title>

  <style>
    .login {
      width: 350px;
      margin: auto;
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img height="32" src="data:image/png;base64,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">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <?php echo anchor('dashboard', 'Dashboard', 'class="nav-link active" aria-current="page"'); ?>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li> -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Account
            </a>
            <ul class="dropdown-menu">
              <li>
                <?php echo anchor('auth/profile', 'Profile', 'class="dropdown-item"'); ?>
              </li>
              <li>
                <hr class="dropdown-divider">
                <?php echo anchor('auth/logout', 'Logout', 'class="dropdown-item"'); ?>
              </li>
            </ul>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link disabled">Disabled</a>
          </li> -->
        </ul>
        <!-- <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form> -->
      </div>
    </div>
  </nav>

  <div class="container mt-2">
    <?php echo $content; ?>
  </div>

</body>

</html>