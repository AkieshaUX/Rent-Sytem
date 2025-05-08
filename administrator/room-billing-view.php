<?php
session_start();
include '../inc/queries.php';

// Check if an admin exists in the database
$checkAdminQuery = "SELECT COUNT(*) as count FROM `admin`";
$result = $conn->query($checkAdminQuery);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // No admin found, redirect to index.php
    header('Location: index.php');
    exit();
}

// Proceed if an admin is found
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
            <h1>Annual Billing Summary</h1>

            </div>
          </div>

          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-bolt"></i></span>
                <?php
                if (isset($_GET['room_id'])) {
                  $query = mysqli_query($conn, "SELECT SUM(amount) AS total_bill FROM room_bill WHERE room_id = {$_GET['room_id']} AND billtype = 'Electricity'");
                } else {
                  $query = null; // Handle case where room_id is not set
                }
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
                <span class="info-box-icon bg-success" style="background: #007bff !important;"><i class="fa-solid fa-water"></i></span>
                <?php
                if (isset($_GET['room_id'])) {
                  $query = mysqli_query($conn, "SELECT SUM(amount) AS total_water FROM room_bill WHERE room_id = {$_GET['room_id']} AND billtype = 'Water'");
                } else {
                  $query = null; // Handle case where room_id is not set
                }
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
            
                <div class="info-box">
                  <span class="info-box-icon bg-warning" style="color: #fff !important;"><i class="fa-solid fa-gauge"></i></span>
                  <?php
                  $query = mysqli_query($conn, "SELECT SUM(amount) AS total_consume FROM room_bill WHERE room_id = {$_GET['room_id']}");
                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    $total_consume = isset($result['total_consume']) ? floatval($result['total_consume']) : 0.00;
                  } else {
                    $total_consume = 0.00;
                  }
                  ?>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Amount</span>
                    <span class="info-box-number"><?php echo number_format($total_consume, 2); ?></span>
                  </div>
                </div>
            
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

                  <table id="example1" class="table table-striped">
                    <thead>
                      <tr>
                        <th style="width: 25%;">Room No.</th>
                        <th style="width: 25%;">Electric Bill</th>
                        <th style="width: 25%;">Water Bill</th>
                        <th style="width: 25%;">Date</th>
                  
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      if (isset($_GET['room_id'])) {
                        $query = mysqli_query($conn, "
                        SELECT 
                            room.room_id, 
                            room.roomnumber, 
                            room.roomtype, 
                            room.roomdesciption,
                            YEAR(room_bill.date) AS bill_year,
                            SUM(CASE WHEN room_bill.billtype = 'Electricity' THEN room_bill.amount ELSE 0 END) AS total_electric_bill,
                            SUM(CASE WHEN room_bill.billtype = 'Water' THEN room_bill.amount ELSE 0 END) AS total_water_bill,
                            MAX(room_bill.date) AS max_date,
                            MIN(room_bill.date) AS min_date
                        FROM room_bill 
                        INNER JOIN room ON room_bill.room_id = room.room_id 
                        WHERE room_bill.room_id = $_GET[room_id] 
                        GROUP BY room.room_id, bill_year 
                        ORDER BY bill_year DESC
                    ");
                        while ($result = mysqli_fetch_array($query)) {
                          extract($result);
                      ?>
                          <tr>
                            <td>Room-<?php echo $roomnumber . ' | ' . $roomtype ?></td>

                            <td><?php echo number_format($total_electric_bill, 2); ?></td>
                            <td><?php echo number_format($total_water_bill, 2); ?></td>
                            <td><a href="room-billing-monthly.php?room_id=<?php echo $room_id ?>&date=<?php echo $bill_year?>">
                            <?php echo date('M Y', strtotime($min_date)) . ' - ' . date('M Y', strtotime($max_date)); ?>
                            </a></td>
                            
                          </tr>
                      <?php
                          $no++;
                        }
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