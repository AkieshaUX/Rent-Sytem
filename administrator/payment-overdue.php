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
  <?php include 'includes/link.php' ?>
</head>
<style>
  .action-btn {
    vertical-align: middle !important;
    text-align: center !important;
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
              <h1>Payment Management</h1>
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
                  <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link " href="payment-upcoming.php">Upcoming</a></li>
                    <li class="nav-item"><a class="nav-link " href="payment-current.php">Due Today</a></li>
                    <li class="nav-item"><a class="nav-link active" href="payment-overdue.php">Overdue</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <table id="example1" class="table  table-striped">
                    <thead>
                      <tr>
                        <th>Room No.</th>
                        <th>Name</th>
                        <th>Due Date</th>
                        <th>Monthly Rate</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      date_default_timezone_set('Asia/Manila');
                      $today = new DateTime();
                      $today->setTime(0, 0, 0); // Reset today's time to 00:00:00 for accurate comparison
                      $fetch_overdue_payment = fetch_overdue_payment($conn);

                      while ($result = mysqli_fetch_array($fetch_overdue_payment)) {
                        extract($result);
                        $dueDate = new DateTime($duedate);
                        $dueDate->setTime(0, 0, 0); // Ensure due date time is also set to 00:00:00

                        // Check if the due date is strictly before today and the payment is unpaid
                        if ($dueDate < $today && $status === 'Unpaid') {
                          $status = "Overdue";
                          $badgeClass = "badge-danger";
                        } else {
                          continue; // Skip any payments that are not overdue
                        }
                      ?>
                        <tr id="rent_id=<?php echo $rent_id; ?>">
                          <td>Room <?php echo $roomnumber . ' | ' . $roomtype ?></td>
                          <td><?php echo $name ?></td>
                          <td><?php echo date('M d, Y', strtotime($duedate)); ?></td>
                          <td><?php echo $roomsmonthly ?></td>
                          <td class="project-state">
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                          </td>
                          <td class="action-btn">

                            <button class="btn btn-success btn-sm" id="CashPAYMENT" data-toggle="modal" data-target="#modal-payment" data-rent-id="<?php echo $rent_id; ?>">
                              <i class="fa-solid fa-credit-card"></i> Pay
                            </button>
                            <button class="btn btn-primary btn-sm" id="GcashBTN" data-toggle="modal" data-target="#modal-payment-gcash" data-rent-id="<?php echo $rent_id; ?>">
                              <i class="fa-solid fa-g"></i>cash
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
                            <input type="hidden" id="parentcontacts" name="parentcontact">
                            <input type="hidden" id="occupations" name="occupation">
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

                <div class="modal fade" id="modal-payment-gcash">
                  <div class="modal-dialog">
                    <form id="GCASH_payment">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Gcash Payment</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body" style="display: grid;grid-template-columns: 60% auto;gap:20px">
                          <div class="piedad">

                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Room(#)</label>
                              <input type="hidden" id="rent_ids" name="rentId">
                              <input type="hidden" id="contact" name="contact">
                              <input type="hidden" id="parentcontact" name="parentcontact">
                              <input type="hidden" id="occupation" name="occupation">
                              <div class="col-sm-9">
                                <input type="text" id="roomnumbers" class="form-control" required readonly>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Name</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" id="name" required readonly>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Amount</label>
                              <div class="col-sm-9">
                                <input type="text" id="roomsmonthly" name="amount" class="form-control" required readonly>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Receipt</label>
                              <div class="col-sm-9">
                                <div class="custom-file">
                                  <input name="gcash" id="gcash-receipt" type="file" accept=".jpeg, .jpg, .png" class="custom-file-input" required>
                                  <label class="custom-file-label" for="exampleInputFile">Choose Gcash Receipt</label>
                                </div>
                              </div>
                            </div>

                          </div>
                          <div class="form-group ">
                            <div>
                              <a href="#" id="gcash-receipt-link" data-toggle="modal" data-target="#gcashreceipt">
                                <img src="../dist/img/try1.png" id="preview-gcash" style="width: 100%; height: 225px; object-fit: cover;">
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Submit Payment</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="modal fade" id="gcashreceipt">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Gcash Receipt</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <img src="" id="preview-gcashs" alt="" style="width: 100%; height: auto;">
                      </div>
                    </div>
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