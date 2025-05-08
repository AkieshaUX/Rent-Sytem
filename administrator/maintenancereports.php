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
              <h1>Maintenance Reports</h1>
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
                      $sql = "SELECT room.*, maintenance.* 
                      FROM maintenance 
                      INNER JOIN room ON maintenance.room_id = room.room_id 
                      WHERE DATE_FORMAT(maintenance.date, '%Y-%m') = '$dateFilter'
                      ORDER BY maintenance.date DESC";
                      $query = mysqli_query($conn, $sql);
                      $totalSum = 0;
                      if (mysqli_num_rows($query) > 0) {
                        while ($result = mysqli_fetch_array($query)) {
                          $totalSum += floatval($result['amount']);
                        }
                      }
                      ?>
                      <h3 class="card-title">Total Maintenance Cost for Selected Period: PHP
                        <?php echo number_format($totalSum, 2); ?>
                      </h3>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group mb-0">
                        <form method="GET" action="" style="display: flex; gap: 15px;">
                          <input name="datepayment" required type="month" id="datepayment" class="form-control" value="<?php echo isset($_GET['datepayment']) ? $_GET['datepayment'] : ''; ?>">
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
                        <th style="width: 33%;">Room No.</th>
                        <th style="width: 33%;">Cost</th>
                        <th style="width: 33%;">Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query = mysqli_query($conn, "
                      SELECT room.*, maintenance.* 
                      FROM maintenance 
                      INNER JOIN room ON maintenance.room_id = room.room_id 
                      WHERE DATE_FORMAT(maintenance.date, '%Y-%m') = '$dateFilter'
                      ORDER BY maintenance.date DESC
                    ");
                      $no = 1;
                      if (mysqli_num_rows($query) > 0) {
                        while ($result = mysqli_fetch_array($query)) {
                          extract($result);
                      ?>
                          <tr>
                            <td>Room-<?php echo $roomnumber . ' | ' . $roomtype ?></td>
                            <td><?php echo number_format($amount, 2); ?></td>
                            <td><?php echo date('F d, Y', strtotime($date)); ?></td>
                          </tr>
                      <?php
                          $no++;
                        }
                      } else {
                        echo "<tr><td colspan='3'>No records found for the selected period.</td></tr>";
                      }
                      ?>
                    </tbody>
                  </table>
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