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
              <h1>Overall Income Summary</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fa-solid fa-chart-simple"></i></span>
                <?php
                $query = mysqli_query($conn, "SELECT (SUM(payment) + SUM(deposit)) AS total_income  FROM rent ");
                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $total_income = isset($result['total_income']) ? floatval($result['total_income']) : 0.00;
                } else {
                  $total_income = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Overall Income</span>
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
                        <th style="width:50%;">Total Income</th>
                        <th style="width:50%;">Year</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query = mysqli_query($conn, "SELECT YEAR(rent.datepayment) AS year, SUM(rent.payment) + SUM(rent.deposit) AS total_income, MIN(rent.datepayment) AS min_date, MAX(rent.datepayment) AS max_date FROM rent JOIN room ON room.room_id = rent.room_id WHERE rent.status = 'Paid' GROUP BY YEAR(rent.datepayment) ORDER BY total_income DESC");
                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                      ?>
                        <tr>
                          <td><?php echo number_format($total_income, 2); ?> PHP</td>
                          <td><a href="total-income-annual.php?year=<?php echo $year; ?>"><?php echo $year; ?></a></td>
                        </tr>
                      <?php
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