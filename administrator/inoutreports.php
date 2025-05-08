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
              <h1>In/Out Reports</h1>
            </div>
            <div class="col-sm-6">
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col-md-8">
                      <?php
                      $dateFilter = isset($_GET['datepayment']) ? $_GET['datepayment'] : '';
                      if (!$dateFilter) {
                        $dateFilter = date('Y-m');
                      }
                      ?>
                      <h3 class="card-title">Filter Data for: <?php echo date('F Y', strtotime($dateFilter)); ?></h3>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group mb-0">
                        <form method="GET" action="" style="display: flex; gap: 15px;">
                          <input name="datepayment" type="month" id="datepayment" class="form-control" value="<?php echo isset($_GET['datepayment']) ? $_GET['datepayment'] : ''; ?>">
                          <div class="btn-wrap d-flex">
                            <button type="submit" class="btn btn-primary mr-2">Filter</button>
                            <button type="button" id="resetFilter" class="btn btn-danger">Reset</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <table id="example1" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query = mysqli_query($conn, "SELECT * FROM tenants
                                  INNER JOIN tenants_logs ON tenants.tenants_id = tenants_logs.tenants_id
                                  WHERE DATE_FORMAT(tenants_logs.timestamp, '%Y-%m') = '$dateFilter'
                                  ORDER BY tenants_logs.tenants_logs_id DESC");
                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                      ?>
                        <tr>
                          <td><a href="tenants-profile.php?tenants_id=<?php echo $tenants_id ?>"><?php echo $name ?></a></td>
                          <td class="project-state">
                            <?php
                            $status = ($action === 'IN') ? 'IN' : 'OUT';
                            $statusClass = ($action === 'IN') ? 'badge-success' : 'badge-danger';
                            ?>
                            <span style="font-size: 90%;" class="badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                          </td>
                          <td><?php echo date('M d, Y g:i A', strtotime($timestamp)); ?></td>
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const resetButton = document.getElementById('resetFilter');
      resetButton.addEventListener('click', function() {
        const datepaymentInput = document.getElementById('datepayment');
        datepaymentInput.value = '';
        window.location.href = window.location.pathname;
      });
    });
  </script>
</body>

</html>