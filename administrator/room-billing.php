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
              <h1>Room Billing</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-bolt"></i></i></span>
                <?php
                $query = mysqli_query($conn, "SELECT SUM(amount) AS total_bill FROM room_bill WHERE billtype = 'Electricity'");
                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $total_bill = isset($result['total_bill']) ? floatval($result['total_bill']) : 0.00;
                } else {
                  $total_bill = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Electric Consume</span>
                  <span class="info-box-number"><?php echo number_format($total_bill, 2); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-success" style="background: #007bff !important;"><i class="fa-solid fa-water"></i></i></i></span>
                <?php
                $query = mysqli_query($conn, "SELECT SUM(amount) AS total_water FROM room_bill WHERE billtype = 'Water'");
                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $total_water = isset($result['total_water']) ? floatval($result['total_water']) : 0.00;
                } else {
                  $total_water = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Water Consume</span>
                  <span class="info-box-number"><?php echo number_format($total_water, 2); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <a href="room-billing-overall.php" style="color: #000;">
                <div class="info-box">
                  <span class="info-box-icon bg-warning" style="color: #fff !important;"><i class="fa-solid fa-gauge"></i></span>
                  <?php
                  $query = mysqli_query($conn, "SELECT SUM(amount) AS total_water FROM room_bill ");
                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    $total_water = isset($result['total_water']) ? floatval($result['total_water']) : 0.00;
                  } else {
                    $total_water = 0.00;
                  }
                  ?>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Cost</span>
                    <span class="info-box-number"><?php echo number_format($total_water, 2); ?></span>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <table id=".example1" class="table  table-striped">
                    <thead>
                      <tr>
                        <th >Room No.</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      $query = mysqli_query($conn, "SELECT * FROM `room` ORDER BY roomtype");
                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                      ?>
                        <tr>
                          <td>Room-<?php echo $roomnumber . ' | ' . $roomtype ?></td>
                          <td style="text-align: center;">
                            <a class="btn btn-primary btn-sm" href="room-billing-add.php?room_id=<?php echo $room_id ?>">
                              <i class="fas fa-folder">
                              </i>
                              View
                            </a>
                          </td>
                        </tr>
                      <?php
                        $no++;
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
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