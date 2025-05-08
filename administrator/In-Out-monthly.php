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
              <h1>Monthly In-Out Summary</h1>
            </div>
          </div>

          <div class="row">
            <div class=" col-md-3 col-sm-6 col-12 ">

              <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i style="color: white;" class="fas fa-users"></i></span>
                <?php
                $sql = "SELECT COUNT(*) AS total_IN FROM `tenants_logs` WHERE `action` = 'IN'";
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


            </div>
            <div class=" col-md-3 col-sm-6 col-12 ">

              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i style="color: white;" class="fas fa-users"></i></span>
                <?php
                $sql = "SELECT COUNT(*) AS total_OUT FROM `tenants_logs` WHERE `action` = 'OUT'";
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
                  <table id=".example1" class="table table-striped ">
                    <thead>
                      <tr>
                        <th>Total Action</th>
                        <th>Month</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;

                      if (isset($_GET['year'])) {
                        $selected_year = $_GET['year'];

                        $query = mysqli_query($conn, "
                          SELECT 
                            MONTH(timestamp) AS month,
                            SUM(CASE WHEN action = 'IN' THEN 1 ELSE 0 END) AS total_in,
                            SUM(CASE WHEN action = 'OUT' THEN 1 ELSE 0 END) AS total_out
                          FROM 
                            `tenants_logs`
                          WHERE 
                            YEAR(timestamp) = '$selected_year'
                          GROUP BY 
                            month
                          ORDER BY 
                            month ASC
                        ");

                        while ($result = mysqli_fetch_array($query)) {
                          $month = $result['month'];
                          $total_in = $result['total_in'];
                          $total_out = $result['total_out'];
                      ?>
                          <tr>
                            <td class="project-state">
                              <span style="font-size: 90%;" class="badge badge-success">IN: <?php echo $total_in; ?></span>
                              <span style="font-size: 90%;" class="badge badge-danger">OUT: <?php echo $total_out; ?></span>
                            </td>
                            <td>
                              <a href="In-Out-monthly-summary.php?month=<?php echo $month; ?>&year=<?php echo $selected_year; ?>">
                                <?php echo date('F Y', mktime(0, 0, 0, $month, 1)); ?>
                              </a>
                            </td>

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