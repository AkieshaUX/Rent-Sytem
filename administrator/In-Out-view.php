<?php
session_start();
include '../inc/queries.php';
$checkAdminQuery = "SELECT COUNT(*) as count FROM `admin`";
$result = $conn->query($checkAdminQuery);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
  header('Location: index.php');
  exit();
}


if (!isset($_SESSION['admin_id'])) {
  header('Location: index.php');
  exit();
}
$admin_id = $_SESSION['admin_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include 'includes/link.php';
  ?>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-sm-6">
              <h1>List of Tenants <?php echo $action ?></h1>
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="card card-solid">
          <div class="card-body pb-0">
            <div class="row">
              <?php
              if (isset($_GET['action'])) {
                $action = $_GET['action'];
                $query = mysqli_query($conn, "SELECT * FROM `tenants` WHERE `status` = 1 AND `action` = '$action' ");
                while ($result = mysqli_fetch_array($query)) {
                  extract($result);
                  $text_color = ($action == 'IN') ? '#28a745' : (($action == 'OUT') ? '#dc3545' : 'black');
              ?>
                  <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                    <div class="card bg-light d-flex flex-fill ">
                      <div class="card-header text-muted border-bottom-0">
                        <span class="btn btn-xs" style="background: <?php echo $text_color; ?>;color:white;">
                          <?php echo $occupation; ?>
                        </span>
                      </div>
                      <div class="card-body pt-0">
                        <div class="row" style="align-items: center;">
                          <div class="col-7">
                            <h2 class="lead"><b><?php echo $name ?></b></h2>
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                              <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address:<?php echo $address ?></li>
                              <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone #: <?php echo $contact ?></li>
                            </ul>
                          </div>
                          <div class="col-5 text-center">
                            <img style="height: 150px; width: 150px; border: 1px solid white;" src="../image/<?php echo $profile ?>" alt="user-avatar" class="img-circle img-fluid">
                          </div>
                        </div>
                      </div>
                      <div class="card-footer">
                        <div class="text-right">
                          <a href="tenants-profile.php?tenants_id=<?php echo $tenants_id ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-user"></i> View Profile
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
              <?php
                }
              }
              ?>
            </div>
          </div>
        </div>
      </section>
    </div>
    <footer class="main-footer">
      <div class="float-right d-none d-sm-inline">
        <b>Version</b> 3.1.0
      </div>
      <strong>&copy; 2024 Your Boarding House.</strong> All rights reserved.
    </footer>
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
  </div>
  <?php include 'includes/script.php' ?>
</body>

</html>