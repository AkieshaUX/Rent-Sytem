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
              <h1>Monthly Income Summary</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fa-solid fa-chart-simple"></i></span>
                <?php
                // Check if room_id and year are set in the GET parameters
                if (isset($_GET['room_id']) && isset($_GET['year'])) {
                  $room_id = intval($_GET['room_id']); // Ensure room_id is an integer
                  $year = intval($_GET['year']); // Ensure year is an integer

                  // Query to get total income for a specific room and year
                  $query = mysqli_query($conn, "SELECT (SUM(payment) + SUM(deposit)) AS total_income FROM rent WHERE room_id = '$room_id' AND YEAR(datepayment) = '$year'");
                } elseif (isset($_GET['room_id'])) {
                  $room_id = intval($_GET['room_id']); // Ensure room_id is an integer

                  // Query to get total income for a specific room without filtering by year
                  $query = mysqli_query($conn, "SELECT (SUM(payment) + SUM(deposit)) AS total_income FROM rent WHERE room_id = '$room_id'");
                } else {
                  // Default query to get total income for all rooms
                  $query = mysqli_query($conn, "SELECT (SUM(payment) + SUM(deposit)) AS total_income FROM rent");
                }

                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $total_income = isset($result['total_income']) ? floatval($result['total_income']) : 0.00;
                } else {
                  $total_income = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Monthly Income</span>
                  <span class="info-box-number"><?php echo number_format($total_income, 2); ?></span>
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
                        <th style="width: 33%;">Room No.</th>
                        <th style="width: 33%;">Income</th>
                        <th style="width: 33%;">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (isset($_GET['year']) && isset($_GET['room_id'])) {
                        $year = intval($_GET['year']);
                        $room_id = intval($_GET['room_id']);

                        $monthly_query = mysqli_query($conn, "SELECT room.roomnumber, room.roomtype, SUM(rent.payment) + SUM(rent.deposit) AS total_income, MONTH(rent.datepayment) AS month, YEAR(rent.datepayment) AS year, MIN(rent.datepayment) AS min_date FROM rent JOIN room ON room.room_id = rent.room_id WHERE rent.status = 'Paid' AND YEAR(rent.datepayment) = '$year' AND room.room_id = '$room_id' GROUP BY room.roomnumber, room.roomtype, MONTH(rent.datepayment), YEAR(rent.datepayment) ORDER BY year, month ASC");



                        while ($monthly_result = mysqli_fetch_array($monthly_query)) {
                          extract($monthly_result);
                          $month_name = date('F', mktime(0, 0, 0, $month, 1));
                      ?>
                          <tr>
                            <td>Room <?php echo $roomnumber . ' | ' . $roomtype; ?></td>
                            <td><?php echo number_format($total_income, 2); ?></td>
                            <td><a href="total-income-daily.php?room_id=<?php echo $room_id ?>&year=<?php echo $year; ?>&month=<?php echo $month; ?>"><?php echo $month_name . ' ' . $year; ?></a></td>

                          </tr>
                      <?php
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