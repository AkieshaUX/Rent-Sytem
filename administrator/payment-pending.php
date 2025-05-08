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
  .modal-body img {
    max-height: 80vh;
  }
  #modal-payment-gcash .modal-dialog {
    max-width: 40rem !important;
    margin: 1.75rem auto;
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
              <h1>List of Pending Payments</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-hourglass-start"></i></span>
                <?php
                $sql = "SELECT COUNT(*) AS total_pending FROM `rent` WHERE `confirmation` = 0";
                $query = $conn->query($sql);
                if ($query) {
                  $result = $query->fetch_assoc();
                  $total_pending = $result['total_pending'];
                ?>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Pending Payments</span>
                    <span class="info-box-number"><?php echo $total_pending ?></span>
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
                  <table id="example1" class="table  table-striped">
                    <thead>
                      <tr>
                        <th>Room No.</th>
                        <th>Name</th>
                        <th>Due Date</th>
                        <th>Date Payment</th>
                        <th>Amount</th>
                        <th>Payment type</th>
                        <th>Reference No:</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      date_default_timezone_set('Asia/Manila');
                      $today = new DateTime();
                      $todayFormatted = $today->format('Y-m-d');
                      $query = mysqli_query($conn, "SELECT 
                        tenants.*,
                        room.*,
                        rent.*
                      FROM 
                        rent
                      JOIN 
                        tenants ON rent.tenants_id = tenants.tenants_id
                      JOIN 
                        room ON room.room_id = rent.room_id
                      WHERE 
                        rent.status = 'Paid' AND rent.confirmation = 0
                      ");
                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                      ?>
                        <tr id="rent_id=<?php echo $rent_id; ?>">
                          <td>Room <?php echo $roomnumber . ' | ' . $roomtype ?></td>
                          <td><?php echo $name ?></td>
                          <td><?php echo date('M d, Y', strtotime($duedate)); ?></td>
                          <td><?php echo date('M d, Y', strtotime($datepayment)); ?></td>
                          <td><?php echo $roomsmonthly ?></td>
                          <td><?php echo $type ?></td>
                          <td style="height: 70px;width:10%"> <a class="image-popup-vertical-fit" href="../gcash/<?php echo $receipt ?>">
                              <img style="width: 100% !important; max-height: 70px; object-fit: cover;" src="../gcash/<?php echo $receipt ?>" alt="">
                            </a></td>
                          <td class="project-state">
                            <span style="color: white;" class="badge badge-warning">Pending</span>
                          </td>
                          <td class="action-btn">
                            <button id="rentdecline" class="rentdecline btn btn-danger btn-sm" data-rent-id="<?php echo $rent_id; ?>">
                              <i class="fa-solid fa-thumbs-down"></i>
                            </button>
                            <button id="approve" class="approve btn btn-primary btn-sm" data-rent-id="<?php echo $rent_id; ?>">
                              <i class="fa-solid fa-thumbs-up"></i>
                            </button>
                          </td>
                        </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <div class="modal fade" id="modal-payment">
                  <div class="modal-dialog">
                    <form id="CASH_payment">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Cash Payment</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Room(#)</label>
                            <input type="hidden" id="rent_idss" name="rentId">
                            <input type="hidden" id="contacts" name="contact">
                            <div class="col-sm-9">
                              <input type="text" id="roomnumberss" class="form-control" required readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                              <input type="text" class="form-control" name="name" id="names" required readonly>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Amount</label>
                            <div class="col-sm-9">
                              <input type="text" id="roomsmonthlys" name="amount" class="form-control" required readonly>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save Payment</button>
                        </div>
                      </div>
                    </form>
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
      $("#gcash-receipt").change(function(event) {
        var x = URL.createObjectURL(event.target.files[0]);
        $("#preview-gcash").attr("src", x);
        $("#preview-gcashs").attr("src", x);
        console.log(event)
      });
    });
  </script>
</body>
</html>