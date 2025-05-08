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
              <h1>Annual Withdraw Summary</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">

              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-money-bill-transfer"></i></span>
                <?php
                $query = mysqli_query($conn, "SELECT SUM(amount) AS admin_withdraw FROM withdraw WHERE `admin` = 'admin' ");
                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $admin_withdraw = isset($result['admin_withdraw']) ? floatval($result['admin_withdraw']) : 0.00;
                } else {
                  $admin_withdraw = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Annual Withdrawal</span>
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
                        <th style="width: 33%;">Name</th>
                        <th style="width: 33%;">Amount</th>
                        <th style="width: 33%;">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // Query to get the sum of amounts grouped by year, with min and max date
                      $query = mysqli_query($conn, "
                      SELECT `admin`, YEAR(`date`) AS year, 
                            SUM(amount) AS total_amount, 
                            MIN(`date`) AS min_date, 
                            MAX(`date`) AS max_date
                      FROM `withdraw` 
                      WHERE `admin` = 'admin' 
                      GROUP BY YEAR(`date`) 
                      ORDER BY year DESC
                    ");

                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                      ?>
                        <tr>
                          <td><?php echo $admin; ?></td>
                          <td><?php echo number_format($total_amount, 2); ?></td>
                          <td><a href="withdraw-admin-monthly.php?admin=<?php echo $admin ?>&year=<?php echo $year ?>">
                              <?php echo date('M d, Y', strtotime($min_date)) . ' - ' . date('M d, Y', strtotime($max_date)); ?>
                            </a></td>
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