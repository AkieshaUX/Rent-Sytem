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
              <h1>Monthly Withdraw Summary</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-money-bill-transfer"></i></span>
                <?php
                // Check if admin and year are set in the GET parameters
                if (isset($_GET['admin']) && isset($_GET['year'])) {
                  $admin = mysqli_real_escape_string($conn, $_GET['admin']); // Sanitize the admin input
                  $year = intval($_GET['year']); // Ensure the year is an integer for security

                  // Query to sum the withdrawal amounts for the specified admin and year
                  $query = mysqli_query($conn, "SELECT SUM(amount) AS admin_withdraw FROM withdraw WHERE `admin` = '$admin' AND YEAR(`date`) = '$year'");

                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    $admin_withdraw = isset($result['admin_withdraw']) ? floatval($result['admin_withdraw']) : 0.00;
                  } else {
                    $admin_withdraw = 0.00;
                  }
                } else {
                  $admin_withdraw = 0.00; // Default if admin or year is not specified
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Monthly Withdrawal</span>
                  <span class="info-box-number"><?php echo number_format($admin_withdraw, 2); ?></span>
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
                        <th style="width: 25%;">Name</th>
                        <th style="width: 25%;">Purpose</th>
                        <th style="width: 25%;">Amount</th>
                        <th style="width: 25%;">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                      if (isset($_GET['admin']) && isset($_GET['year'])) {
                        $admin = $_GET['admin'];
                        $year = intval($_GET['year']); // Ensure the year is an integer for security

                        // Query to get all withdrawals for the specified admin and year
                        $query = mysqli_query($conn, "
                        SELECT * 
                        FROM `withdraw` 
                        WHERE `admin` = '$admin' 
                        AND YEAR(`date`) = '$year'
                        ORDER BY `date` ASC
                      ");


                        while ($result = mysqli_fetch_array($query)) {
                          extract($result);
                      ?>
                          <tr>
                            <td><?php echo $admin; ?></td>
                            <td><?php echo $type; ?></td>
                            <td><?php echo number_format($amount, 2); ?></td>
                            <td>
                              <?php echo date('M d, Y', strtotime($date)) ?>
                            </td>
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