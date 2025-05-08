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
              <h1>Annual Maintenance Summary </h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                <?php
                if (isset($_GET['room_id'])) {
                  $query = mysqli_query($conn, "SELECT SUM(amount) AS total_amount FROM maintenance WHERE room_id = $_GET[room_id] ");

                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    $total_maintenance = isset($result['total_amount']) ? floatval($result['total_amount']) : 0.00;
                  } else {
                    $total_maintenance = 0.00;
                  }
                } else {
                  $total_maintenance = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Annual  Maintenance Cost</span>
                  <span class="info-box-number"><?php echo number_format($total_maintenance, 2); ?></span>
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
                  <div class="table-responsive">
                    <table id="example1" class="table table-striped ">
                      <thead>
                        <tr>
                          <th style="width: 33%;">Room No.</th>
                          <th style="width: 33%;">Cost</th>
                          <th style="width: 33%;">Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $no = 1;
                        if (isset($_GET['room_id'])) {
                          $query = mysqli_query($conn, "SELECT room.room_id, room.roomnumber, room.roomtype, 
                            SUM(maintenance.amount) AS total_amount, 
                            MIN(maintenance.date) AS start_date, 
                            MAX(maintenance.date) AS end_date, 
                            YEAR(maintenance.date) AS maintenance_year 
                            FROM maintenance 
                            INNER JOIN room ON maintenance.room_id = room.room_id 
                            WHERE maintenance.room_id = $_GET[room_id] GROUP BY room.room_id, YEAR(maintenance.date) 
                            ORDER BY start_date DESC ");



                          while ($result = mysqli_fetch_array($query)) {
                            extract($result);
                        ?>

                            <tr>
                              <td>Room-<?php echo $roomnumber . ' | ' . $roomtype ?></td>
                              <td><?php echo number_format($total_amount, 2); ?></td>
                              <td><a href="room-maintenance-monthly.php?room_id=<?php echo $room_id ?>&year=<?php echo $maintenance_year ?>">
                                  <?php echo date('M Y', strtotime($start_date)) . ' - ' . date('M Y', strtotime($end_date)); ?>
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