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
              <h1>Annual Income Summary</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fa-solid fa-chart-simple"></i></span>
                <?php
                if (isset($_GET['year'])) {
                  $year = $_GET['year'];
                  $query = mysqli_query($conn, "SELECT (SUM(payment) + SUM(deposit)) AS total_income FROM rent WHERE YEAR(datepayment) = '$year'");
                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    $total_income = isset($result['total_income']) ? floatval($result['total_income']) : 0.00;
                  } else {
                    $total_income = 0.00;
                  }
                } else {
                  $total_income = 0.00; // Default if year is not set
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Annual Income</span>
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

                  <?php
                  if (isset($_GET['year'])) {
                    $year = $_GET['year'];

                    // Query to sum payment and deposit based on the year from the URL
                    $query = mysqli_query($conn, "SELECT 
                                    room.room_id, 
                                    room.roomnumber, 
                                    room.roomtype, 
                                    SUM(rent.payment) + SUM(rent.deposit) AS total_income,
                                    MIN(rent.datepayment) AS min_date,
                                    MAX(rent.datepayment) AS max_date
                                  FROM 
                                    rent 
                                  JOIN 
                                    room ON room.room_id = rent.room_id 
                                  WHERE 
                                    rent.status = 'Paid' 
                                    AND YEAR(rent.datepayment) = '$year'
                                  GROUP BY 
                                    room.room_id 
                                  ORDER BY 
                                    total_income DESC");
                  ?>
                    <table id="example1" class="table table-striped">
                      <thead>
                        <tr>
                          <th style="width: 33%;">Room No.</th>
                          <th style="width: 33%;">Total Income</th>
                          <th style="width: 33%;">Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        while ($result = mysqli_fetch_array($query)) {
                          extract($result);
                        ?>
                          <tr>
                            <td>Room <?php echo $roomnumber . ' | ' . $roomtype; ?></td>
                            <td><?php echo number_format($total_income, 2); ?></td>
                            <td><a href="total-income-monthly.php?room_id=<?php echo $room_id?>&year=<?php echo $year; ?>"><?php echo date('M d, Y', strtotime($min_date)) . ' - ' . date('M d, Y', strtotime($max_date)); ?></a></td>
                          </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                    </table>
                  <?php
                  } else {
                    echo "Year not specified in the URL.";
                  }
                  ?>


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