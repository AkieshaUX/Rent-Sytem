<?php
include '../inc/queries.php';
?>
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
        <a href="index.php" class=""><img src="../dist/img/house_14860255.png" style="height: 160px !important;" alt=""></a>
      </div>
      <div class="card-body">

        <?php
        $checkAdminQuery = "SELECT COUNT(*) as count FROM `admin`";
        $result = $conn->query($checkAdminQuery);
        $row = $result->fetch_assoc();
        $adminExists = $row['count'] > 0;
        ?>

        <form action="../inc/controller.php" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Email" name="admin_user" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="admin_pass" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" name="login_btn" class="btn btn-primary btn-block">Login</button>
            </div>
          </div>

          <?php if (isset($_GET['invalid'])): ?>
            <p class="invalid">Sorry, your password was incorrect. Please double-check your password.</p>
          <?php endif; ?>
        </form>

        <?php if (!$adminExists):
        ?>
          <p class="mb-2">
            <a href="register.php">Create new account</a>
          </p>
        <?php endif; ?>

      </div>

    </div>
  </div>
  <?php include 'includes/script.php' ?>
</body>

</html>