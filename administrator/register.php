<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'includes/link.php' ?>
</head>

<body class="hold-transition login-page">
  <style>
    .invalid {
      color: red;
      margin: 1rem 0;
    }
  </style>
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="register.php" class=""><img src="../dist/img/house_14860255.png" style="height: 160px !important;" alt=""></a>
      </div>
      <div class="card-body">

        <form action="../inc/controller.php" method="post">

          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Email" name="admin_user">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="admin_pass">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" name="register_btn" class="btn btn-primary btn-block">Register</button>
            </div>

          </div>


        </form>
      </div>

    </div>
  </div>
  <?php include 'includes/script.php' ?>
</body>

</html>