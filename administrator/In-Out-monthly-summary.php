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
                <span class="info-box-icon bg-success elevation-1">
                  <i style="color: white;" class="fas fa-users"></i>
                </span>
                <?php
                if (isset($_GET['month']) && isset($_GET['year'])) {
                  $selected_month = $_GET['month'];
                  $selected_year = $_GET['year'];

                  // Query to count total IN actions for the selected month and year
                  $sql = "SELECT COUNT(*) AS total_IN 
                FROM `tenants_logs` 
                WHERE `action` = 'IN' 
                AND MONTH(`timestamp`) = '$selected_month' 
                AND YEAR(`timestamp`) = '$selected_year'";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_IN = $result['total_IN'];
                ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Tenants IN</span>
                      <span class="info-box-number"><?php echo $total_IN; ?></span>
                    </div>
                <?php
                  }
                }
                ?>
              </div>
            </div>

            <div class=" col-md-3 col-sm-6 col-12 ">
              <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1">
                  <i style="color: white;" class="fas fa-users"></i>
                </span>
                <?php
                if (isset($_GET['month']) && isset($_GET['year'])) {
                  // Query to count total OUT actions for the selected month and year
                  $sql = "SELECT COUNT(*) AS total_OUT 
                FROM `tenants_logs` 
                WHERE `action` = 'OUT' 
                AND MONTH(`timestamp`) = '$selected_month' 
                AND YEAR(`timestamp`) = '$selected_year'";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_OUT = $result['total_OUT'];
                ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Tenants OUT</span>
                      <span class="info-box-number"><?php echo $total_OUT; ?></span>
                    </div>
                <?php
                  }
                }
                ?>
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
                        <th>Name</th>
                        <th>Action</th>
                        <th>Date & Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if (isset($_GET['month']) && isset($_GET['year'])) {
                        $selected_month = $_GET['month'];
                        $selected_year = $_GET['year'];

                        // Query to display detailed logs for the selected month and year
                        $query = mysqli_query($conn, "
                          SELECT 
                            l.tenants_id,
                            t.name AS tenant_name,
                            l.action,
                            l.timestamp
                          FROM 
                            `tenants_logs` l
                          INNER JOIN 
                            `tenants` t ON l.tenants_id = t.tenants_id
                          WHERE 
                            MONTH(l.timestamp) = '$selected_month' AND 
                            YEAR(l.timestamp) = '$selected_year'
                          ORDER BY 
                            l.timestamp ASC
                        ");

                        while ($result = mysqli_fetch_array($query)) {
                          $tenant_name = $result['tenant_name'];
                          $action = $result['action'];
                          $timestamp = $result['timestamp'];
                          $tenants_id = $result['tenants_id'];
                      ?>
                          <tr>
                            <td><a href="tenants-profile.php?tenants_id=<?php echo $tenants_id; ?>"><?php echo $tenant_name; ?></a></td>
                            <td class="project-state">
                              <span style="font-size: 90%;" class="badge <?php echo ($action === 'IN') ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo $action; ?>
                              </span>
                            </td>
                            <td><?php echo date('M d, Y g:i A', strtotime($timestamp)); ?></td>
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