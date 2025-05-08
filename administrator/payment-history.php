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
  <?php include 'includes/link.php' ?>
</head>
<style>
  .action-btn {
    vertical-align: middle !important;
    text-align: center !important;
  }

  td {
    vertical-align: middle !important;
  }
</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-sm-6">
              <h1>Payment History Summary</h1>
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
                    <table id=".example1" class="table table-striped">
                      <thead>
                        <tr>
                          <th>Room No.</th>
                          <th>Name</th>
                          <th>Due Date</th>
                          <th>Date of Payment</th>
                          <th>Monthly</th>
                          <th>Payment Type</th>
                          <th>Reference No:</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $query = mysqli_query($conn, "SELECT tenants.*, room.*, rent.* FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE rent.status = 'Paid'  AND `confirmation`= 1  ORDER BY datepayment DESC");
                        while ($result = mysqli_fetch_array($query)) {
                          extract($result);
                        ?>
                          <tr>
                            <td>Room <?php echo $roomnumber . ' | ' . $roomtype ?></td>
                            <td><?php echo $name ?></td>
                            <td><?php echo date('M d, Y', strtotime($duedate)); ?></td>
                            <td><?php echo date('M d, Y', strtotime($datepayment)); ?></td>
                            <td>
                              <?php
                              $payment = floatval($payment);
                              $deposit = floatval($deposit);
                              if ($payment == 0.00) {
                                echo number_format($deposit, 2);
                              } else {
                                echo number_format($payment, 2);
                              }
                              ?>
                            </td>
                            <td><?php echo $type ?></td>
                            <td style="height: 70px;">
                              <a class="image-popup-vertical-fit" href="../gcash/<?php echo $receipt ?>">
                                <img style="width: 100% !important; max-height: 70px; object-fit: cover;" src="../gcash/<?php echo $receipt ?>" alt="">
                              </a>
                            </td>
                            <td class="project-state">
                              <span class="badge badge-success"><?php echo $status ?></span>
                            </td>
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
    $(document).ready(function() {
      $('.image-popup-vertical-fit').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
          verticalFit: true
        }
      });
    });
  </script>
</body>

</html>