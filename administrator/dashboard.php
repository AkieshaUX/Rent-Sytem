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
  <?php include 'includes/link.php' ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php
    include 'includes/sidebar.php';
    ?>
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-md-3 col-sm-6 col-12">
              <h1 class="m-0">Dashboard</h1>
            </div>
          </div>
        </div>
      </div>
      <section class="content" id="loadpageScan">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-6">
              <?php
              $query = mysqli_query($conn, "SELECT SUM(amount) AS total_income FROM payment_income WHERE status = 1");
              if ($query) {
                $result = mysqli_fetch_assoc($query);
                $total_income = isset($result['total_income']) ? floatval($result['total_income']) : 0.00;
              } else {
                $total_income = 0.00;
              }
              ?>
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?php echo number_format($total_income, 2) ?></h3>
                  <p>Total Income</p>
                </div>
                <div class="icon">
                  <i style="font-size: 70px;" class="fa-solid fa-chart-simple"></i>
                </div>
                <a href="total-overall-income.php" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <?php
                  $query = mysqli_query($conn, "SELECT SUM(withdraw) AS total_profit FROM payment_income WHERE `status` = 1");
                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    $total_profit = isset($result['total_profit']) ? floatval($result['total_profit']) : 0.00;
                  } else {
                    $total_profit = 0.00;
                  }
                  ?>
                  <h3> <?php echo number_format($total_profit, 2); ?></h3>
                  <p>Total Profit</p>

                </div>
                <div class="icon">
                  <i style="font-size: 70px;" class="fas fa-solid fa-hands-holding"></i>
                </div>
                <a href="withdraw-admin.php" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-warning">
                <div class="inner">
                  <?php
                  $query = mysqli_query($conn, "SELECT SUM(amount) AS total_bill FROM room_bill");
                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    $total_bill = isset($result['total_bill']) ? floatval($result['total_bill']) : 0.00;
                  } else {
                    $total_bill = 0.00;
                  }
                  ?>
                  <h3 style="color: white !important;"> <?php echo number_format($total_bill, 2); ?></h3>
                  <p style="color: white !important;">Total Cost Bill</p>
                </div>
                <div class="icon">
                  <i style="font-size: 70px;" class="fa-solid fa-bolt"></i>
                </div>
                <a href="room-billing.php" style="color: white !important;" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-danger">
                <div class="inner">
                  <?php
                  $query = mysqli_query($conn, "SELECT SUM(amount) AS total_maintenance FROM maintenance");
                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    $total_maintenance = isset($result['total_maintenance']) ? floatval($result['total_maintenance']) : 0.00;
                  } else {
                    $total_maintenance = 0.00;
                  }
                  ?>
                  <h3 style="color: white !important;"> <?php echo number_format($total_maintenance, 2); ?></h3>
                  <p style="color: white !important;">Total Cost Maintenance</p>
                </div>
                <div class="icon">
                  <i style="font-size: 70px;" class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <a href="room-maintenance.php" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="row">
            <div class=" col-md-3 col-sm-6 col-12 ">
              <a href="room-list.php" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-info elevation-1"><i style="color: white;" class=" fas fa-solid fa-person-shelter"></i></span>
                  <?php
                  $sql = "SELECT COUNT(*) AS total_rooms FROM `room`";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_rooms = $result['total_rooms'];
                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Rooms</span>
                      <span class="info-box-number"><?php echo $total_rooms ?></span>
                    </div>
                  <?php } ?>
                </div>
              </a>
            </div>
            <div class=" col-md-3 col-sm-6 col-12 ">
              <a href="tenants-list.php" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i style="color: white;" class="fas fa-users"></i></span>
                  <?php
                  $sql = "SELECT COUNT(*) AS total_tenants FROM `tenants` WHERE `status` = 1";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_tenants = $result['total_tenants'];
                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Tenants</span>
                      <span class="info-box-number"><?php echo $total_tenants ?></span>
                    </div>
                  <?php } ?>
                </div>
              </a>
            </div>
            <div class=" col-md-3 col-sm-6 col-12 ">
              <a href="In-Out-view.php?action=IN" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i style="color: white;" class="fas fa-users"></i></span>
                  <?php
                  $sql = "SELECT COUNT(*) AS total_IN FROM `tenants` WHERE `status` = 1 AND `action` = 'IN'";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_IN = $result['total_IN'];
                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Tenants IN</span>
                      <span class="info-box-number"><?php echo $total_IN ?></span>
                    </div>
                  <?php } ?>
                </div>
              </a>
            </div>
            <div class="col-md-3 col-sm-6 col-12 ">
              <a href="In-Out-view.php?action=OUT" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-danger elevation-1"><i style="color: white;" class="fas fa-users"></i></span>
                  <?php
                  $sql = "SELECT COUNT(*) AS total_OUT FROM `tenants` WHERE `status` = 1 AND `action` = 'OUT'";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_OUT = $result['total_OUT'];
                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Tenants OUT</span>
                      <span class="info-box-number"><?php echo $total_OUT ?></span>
                    </div>
                  <?php } ?>
                </div>
              </a>
            </div>
            <div class=" col-md-3 col-sm-6 col-12 ">
              <a href="payment-upcoming.php" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i style="color: white;" class="fa-solid fa-peseta-sign"></i></span>
                  <?php
                  date_default_timezone_set('Asia/Manila');
                  $today = new DateTime();
                  $todayFormatted = $today->format('Y-m-d');
                  $sql = "SELECT COUNT(*) AS total_upcoming FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE rent.status = 'Unpaid' AND DATE_FORMAT(rent.duedate, '%Y-%m-%d') = DATE_ADD('$todayFormatted', INTERVAL 3 DAY)";
                  $query = mysqli_query($conn, $sql);
                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    
                    if (isset($result['total_upcoming'])) {
                      $total_upcoming = $result['total_upcoming'];
                    } else {
                      $total_upcoming = 0; 
                    }
                  } else {
                    $total_upcoming = 0; 
                  }
                  ?>
                  <div class="info-box-content">
                    <span class="info-box-text">Upcoming Payments</span>
                    <span class="info-box-number"><?php echo $total_upcoming; ?></span>
                  </div>
                </div>
              </a>
            </div>
            <div class=" col-md-3 col-sm-6 col-12 ">
              <a href="payment-current.php" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-peseta-sign"></i></span>
                  <?php
                  date_default_timezone_set('Asia/Manila');
                  $today = new DateTime();
                  $todayFormatted = $today->format('Y-m-d');
                  $sql = "SELECT COUNT(*) AS total_unpaid_today FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE rent.status = 'Unpaid' AND DATE_FORMAT(rent.duedate, '%Y-%m-%d') = '$todayFormatted'";
                  $query = mysqli_query($conn, $sql);
                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
         
                    if (isset($result['total_unpaid_today'])) {
                      $total_unpaid_today = $result['total_unpaid_today'];
                    } else {
                      $total_unpaid_today = 0; 
                    }
                  } else {
                    $total_unpaid_today = 0;
                  }
                  ?>
                  <div class="info-box-content">
                    <span class="info-box-text">Due Today</span>
                    <span class="info-box-number"><?php echo $total_unpaid_today; ?></span>
                  </div>
                </div>
              </a>
            </div>
            <div class="clearfix hidden-md-up"></div>
            <div class="col-md-3 col-sm-6 col-12">
              <a href="payment-overdue.php" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-danger elevation-1">
                    <i class="fa-solid fa-peseta-sign"></i>
                  </span>
                  <?php
                  date_default_timezone_set('Asia/Manila');
                  $today = new DateTime(); 
                  $today->setTime(0, 0, 0);
                  $query = mysqli_query($conn, "SELECT tenants.*, room.*, rent.* FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE rent.status = 'Unpaid' ORDER BY duedate");
                  $overdue_count = 0;
                  while ($result = mysqli_fetch_array($query)) {
                    extract($result);
                    $dueDate = new DateTime($duedate);
                    $dueDate->setTime(0, 0, 0); 

                    if ($dueDate < $today && $status === 'Unpaid') {
                      $overdue_count++;
                    }
                  }
                  ?>
                  <div class="info-box-content">
                    <span class="info-box-text">Overdue</span>
                    <span class="info-box-number"><?php echo $overdue_count; ?></span>
                  </div>
                </div>
              </a>
            </div>
            <div class=" col-md-3 col-sm-6 col-12 ">
              <a href="payment-pending.php" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-info elevation-1"><i class="fa-solid fa-stopwatch"></i></span>
                  <?php
                  $sql = "SELECT COUNT(*) AS total_pending FROM `rent` WHERE `confirmation` = 0";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_pending = $result['total_pending'];
                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Payment Pending</span>
                      <span class="info-box-number"><?php echo $total_pending ?></span>
                    </div>
                  <?php } ?>
                </div>
              </a>
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