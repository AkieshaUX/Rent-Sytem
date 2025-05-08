<?php include '../inc/sessions.php' ?>
<!DOCTYPE html>
<html lang="en">


<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'includes/link.php' ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <style>
     #modal-payment-gcash .modal-dialog {
    max-width: 40rem !important;
    margin: 1.75rem auto;
  }
  .modal-body img {
    max-height: 80vh;
  }
  </style>
  <div class="wrapper">



    <?php include 'includes/sidebar.php' ?>


    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-sm-6">
              <h1>Payment</h1>
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header p-2">
                  <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#Upcoming" data-toggle="tab">Upcoming Payments</a></li>
                    <li class="nav-item"><a class="nav-link" href="#Duetoday" data-toggle="tab">Due Today Payments</a></li>
                    <li class="nav-item"><a class="nav-link" href="#Overdue" data-toggle="tab">Overdue Payments</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane" id="Upcoming">

                      <div class="post">
                        <table id="example1" class="table table-bordered table-striped tble1">
                          <thead>
                            <tr>
                              <th>Room(#)</th>
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
                            $todayFormatted = $today->format('Y-m-d');
                            $tenants_id = $_SESSION['tenants_id'];
                            if (isset($_SESSION['tenants_id'])) {
                              $query = mysqli_query($conn, "SELECT tenants.*, room.*,  rent.* FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN  room ON room.room_id = rent.room_id
                            WHERE 
                              rent.status = 'Unpaid' AND tenants.tenants_id = '$tenants_id' AND  DATE_FORMAT(rent.duedate, '%Y-%m-%d') = DATE_ADD('$todayFormatted', INTERVAL 3 DAY)
                            ");
                              while ($result = mysqli_fetch_array($query)) {
                                extract($result);
                                $today = new DateTime();
                                $dueDate = new DateTime($duedate);
                                $interval = $today->diff($dueDate)->days;

                                if ($dueDate > $today && $interval <= 3 && $status === 'Unpaid') {
                                  $status = "Upcoming";
                                  $badgeClass = "badge-warning";
                                }



                            ?>
                                <tr id="rent_id=<?php echo $rent_id; ?>">

                                  <td style="width: 10%;">Room <?php echo $roomnumber . ' | ' . $roomtype ?></td>
                                  <td style="width: 10%;"><?php echo $name ?></td>
                                  <td style="width: 10%;"><?php echo date('M d, Y', strtotime($duedate)); ?></td>
                                  <td style="width: 10%;"><?php echo $roomsmonthly ?></td>
                                  <td style="width: 10%;" class="project-state">
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                  </td>
                                  <td style="width: 15%;" class="action-btn">
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-updateroom" href="#">
                                      <i class="fa-solid fa-bell"></i> Remind
                                    </button>
                                  
                                    <button class="btn btn-primary btn-sm" id="GcashBTN" data-toggle="modal" data-target="#modal-payment-gcash" data-rent-id="<?php echo $rent_id; ?>">
                                      <i class="fa-solid fa-g"></i>cash
                                    </button>
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

                    <div class="tab-pane" id="Duetoday">
                      <table id="example1" class="table table-bordered table-striped tble2">
                        <thead>
                          <tr>
                            <th>Room(#)</th>
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
                          $todayFormatted = $today->format('Y-m-d');
                          $tenants_id = $_SESSION['tenants_id'];
                          if (isset($_SESSION['tenants_id'])) {
                            $query = mysqli_query($conn, "SELECT tenants.*, room.*, rent.* 
                                FROM rent 
                                JOIN tenants ON rent.tenants_id = tenants.tenants_id 
                                JOIN room ON room.room_id = rent.room_id 
                                WHERE rent.status = 'Unpaid' AND tenants.tenants_id = '$tenants_id' 
                                AND DATE_FORMAT(rent.duedate, '%Y-%m-%d') = '$todayFormatted' ");
                            while ($result = mysqli_fetch_array($query)) {
                              extract($result);  // Extract query results into variables
                              $dueDate = new DateTime($duedate);  // Convert the due date from the database to a DateTime object

                              // Check if the due date is today and the status is 'Unpaid'
                              if ($dueDate->format('Y-m-d') === $todayFormatted && $status === 'Unpaid') {
                                $status = "Due Today";  // Update the status to "Due Today"
                                $badgeClass = "badge-danger";  // Set the badge class to indicate urgency
                              }


                          ?>

                              <tr id="rent_id=<?php echo $rent_id; ?>">

                                <td style="width: 10%;">Room <?php echo $roomnumber . ' | ' . $roomtype ?></td>
                                <td style="width: 10%;"><?php echo $name ?></td>
                                <td style="width: 10%;"><?php echo date('M d, Y', strtotime($duedate)); ?></td>
                                <td style="width: 10%;"><?php echo $roomsmonthly ?></td>
                                <td style="width: 10%;" class="project-state">
                                  <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                </td>
                                <td style="width: 15%;" class="action-btn">
                                  <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-updateroom" href="#">
                                    <i class="fa-solid fa-bell"></i> Remind
                                  </button>
                               
                                  <button class="btn btn-primary btn-sm" id="GcashBTN" data-toggle="modal" data-target="#modal-payment-gcash" data-rent-id="<?php echo $rent_id; ?>">
                                    <i class="fa-solid fa-g"></i>cash
                                  </button>
                                </td>
                              </tr>
                          <?php
                            }
                          }
                          ?>
                        </tbody>


                      </table>
                    </div>


                    <div class="tab-pane" id="Overdue">
                      <table id="example1" class="table table-bordered table-striped tble3">
                        <thead>
                          <tr>
                            <th>Room(#)</th>
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
                          $todayFormatted = $today->format('Y-m-d');
                          $currentMonth = $today->format('m'); // Get the current month
                          $currentYear = $today->format('Y'); // Get the current year
                          $tenants_id = $_SESSION['tenants_id'];
                          if (isset($_SESSION['tenants_id'])) {
                            $query = mysqli_query($conn, "SELECT  tenants.*, room.*, rent.* 
                            FROM rent 
                            JOIN tenants ON rent.tenants_id = tenants.tenants_id 
                            JOIN room ON room.room_id = rent.room_id 
                            WHERE rent.status = 'Unpaid' AND tenants.tenants_id = '$tenants_id' ORDER BY rent_id DESC ");


                            while ($result = mysqli_fetch_array($query)) {
                              extract($result);
                              $dueDate = new DateTime($duedate);
                              $dueMonth = $dueDate->format('m'); // Get the due month
                              $dueYear = $dueDate->format('Y'); // Get the due year

                              // Check if the due date is today
                              if ($dueDate->format('Y-m-d') === $todayFormatted) {
                                $status = "Due Today";
                                $badgeClass = "badge-danger";
                              }
                              // Check if the due date is in the current month and is unpaid
                              elseif ($dueMonth === $currentMonth && $dueYear === $currentYear && $status === 'Unpaid') {
                                $status = "Due This Month";
                                $badgeClass = "badge-warning";
                              }
                              // Check if the due date has passed and is unpaid
                              elseif ($dueDate < $today && $status === 'Unpaid') {
                                $status = "Overdue";
                                $badgeClass = "badge-danger";
                              }
                              // Skip entries that are not due yet
                              else {
                                continue; // Skip to the next iteration of the loop
                              }






                          ?>



                              <tr id="rent_id=<?php echo $rent_id; ?>">

                                <td style="width: 10%;">Room <?php echo $roomnumber . ' | ' . $roomtype ?></td>
                                <td style="width: 10%;"><?php echo $name ?></td>
                                <td style="width: 10%;"><?php echo date('M d, Y', strtotime($duedate)); ?></td>
                                <td style="width: 10%;"><?php echo $roomsmonthly ?></td>
                                <td style="width: 10%;" class="project-state">
                                  <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                </td>
                                <td style="width: 15%;" class="action-btn">
                                  <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-updateroom" href="#">
                                    <i class="fa-solid fa-bell"></i> Remind
                                  </button>
                                
                                  <button class="btn btn-primary btn-sm" id="GcashBTN" data-toggle="modal" data-target="#modal-payment-gcash" data-rent-id="<?php echo $rent_id; ?>">
                                    <i class="fa-solid fa-g"></i>cash
                                  </button>
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

                  <div class="modal fade" id="modal-payment-gcash">
                    <div class="modal-dialog">
                      <form id="GCASH_payment_tenants">
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
                                  <img src="../gcash/try1.png" id="preview-gcash" style="width: 100%; height: 225px; object-fit: cover;">
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
      $(".tble1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false

      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $(".tble2").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false

      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $(".tble3").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false

      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('table').removeClass('dataTable ');

      $('.gallery-item').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
          titleSrc: function(item) {
            return item.el.attr('data-title');
          }
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