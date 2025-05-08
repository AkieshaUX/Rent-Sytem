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
    <?php
    if (isset($_GET['room_id'])) {
      $query = mysqli_query($conn, "SELECT * FROM `room`  WHERE `room_id` = '$_GET[room_id]'");
      $result = mysqli_fetch_array($query);
      extract($result);
    }
    ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-sm-6">
              <h1>Room-<?php echo $roomnumber . '|' . $roomtype ?> Billing</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-bolt"></i></span>
                <?php
                $currentYear = date('Y'); 
                $room_id = intval($_GET['room_id']); 
                $query = mysqli_query($conn, "SELECT SUM(amount) AS total_bill FROM room_bill WHERE room_id = $room_id AND billtype = 'Electricity' AND YEAR(date) = $currentYear");
                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $total_bill = isset($result['total_bill']) ? floatval($result['total_bill']) : 0.00;
                } else {
                  $total_bill = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Electric Consume</span>
                  <span class="info-box-number"><?php echo number_format($total_bill, 2); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-success" style="background: #007bff !important;"><i class="fa-solid fa-water"></i></span>
                <?php
                $query = mysqli_query($conn, "SELECT SUM(amount) AS total_water FROM room_bill WHERE room_id = $room_id AND billtype = 'Water' AND YEAR(date) = $currentYear");
                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $total_water = isset($result['total_water']) ? floatval($result['total_water']) : 0.00;
                } else {
                  $total_water = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Water Consume</span>
                  <span class="info-box-number"><?php echo number_format($total_water, 2); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <a href="room-billing-view.php?room_id=<?php echo $_GET['room_id']?>">
                <div class="info-box">
                  <span class="info-box-icon bg-warning" style="color: #fff !important;"><i class="fa-solid fa-circle-info"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">View More</span>
                  </div>
                </div>
              </a>
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
                  <div class="row mb-2">
                    <div class="col-sm-6">
                      <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#Electricity" data-toggle="tab">Electric Bill</a></li>
                        <li class="nav-item"><a class="nav-link " href="#water" data-toggle="tab">Water Bill</a></li>
                      </ul>
                    </div>
                    <div class="col-sm-6">
                      <div class="float-right">
                        <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-addnew"><i class="fa-solid fa-receipt"></i> Add New</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="active tab-pane" id="Electricity">
                      <table id="example1" class="table  table-striped">
                        <thead>
                          <tr>
                            <th style="width: 25%;">Bill Type</th>
                            <th style="width: 25%;">Amount Bill</th>
                            <th style="width: 25%;">Date</th>
                            <th style="width: 25%;"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = 1;
                          $currentYear = date('Y'); 
                          $query = mysqli_query($conn, "
                          SELECT room.*, room_bill.* 
                          FROM room_bill 
                          INNER JOIN room ON room_bill.room_id = room.room_id 
                          WHERE room_bill.room_id = $_GET[room_id] AND billtype = 'Electricity' 
                            AND YEAR(date) = $currentYear 
                          ORDER BY date DESC
                        ");
                          while ($result = mysqli_fetch_array($query)) {
                            extract($result);
                          ?>
                            <tr>
                              <td><?php echo $billtype; ?></td>
                              <td><?php echo number_format($amount, 2); ?></td>
                              <td><?php echo date('M d, Y', strtotime($date)); ?></td>
                              <td style="text-align: center;">
                                <button class="btn btn-info btn-sm" id="EDITroomBill" data-roombill-id="<?php echo $room_bill_id; ?>" data-toggle="modal" data-target="#modal-edit">
                                  <i class="fas fa-pencil-alt"></i> Edit
                                </button>
                              </td>
                            </tr>
                          <?php
                            $no++;
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="tab-pane" id="water">
                      <table id="examplep" class="table table-striped">
                        <thead>
                          <tr>
                            <th>Bill Type</th>
                            <th>Amount Bill</th>
                            <th>Date</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $no = 1;
                          $currentYear = date('Y'); 
                          $query = mysqli_query($conn, "
                          SELECT room.*, room_bill.* 
                          FROM room_bill 
                          INNER JOIN room ON room_bill.room_id = room.room_id 
                          WHERE billtype = 'Water' 
                            AND YEAR(date) = $currentYear 
                          ORDER BY date DESC
                        ");
                          while ($result = mysqli_fetch_array($query)) {
                            extract($result);
                          ?>
                            <tr>
                              <td><?php echo $billtype; ?></td>
                              <td><?php echo number_format($amount, 2); ?></td>
                              <td><?php echo date('M d, Y', strtotime($date)); ?></td>
                              <td style="text-align: center;">
                                <button class="btn btn-info btn-sm" id="EDITroomBill" data-roombill-id="<?php echo $room_bill_id; ?>" data-toggle="modal" data-target="#modal-edit">
                                  <i class="fas fa-pencil-alt"></i> Edit
                                </button>
                              </td>
                            </tr>
                          <?php
                            $no++;
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="modal fade" id="modal-addnew">
                    <div class="modal-dialog modal-lg" style="max-width: 500px !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">ADD NEW BILL</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="roombillingFORM" class="form-horizontal" method="post" action="../inc/controller.php">
                          <div class="card-body">
                            <div class="form-group row">
                              <input type="hidden" name="room_id" value="<?php echo $_GET['room_id'] ?>">
                              <label class="col-sm-3 col-form-label">Bill Type:</label>
                              <div class="col-sm-9">
                                <select name="billtype" class="form-control custom-select" required>
                                  <option selected disabled>Select one</option>
                                  <option>Electricity</option>
                                  <option>Water</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Date:</label>
                              <div class="col-sm-9">
                                <input type="date" name="date" class="form-control" required>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Total Bill:</label>
                              <div class="col-sm-9">
                                <input type="number" name="amount" placeholder="Total Bill" class="form-control" required>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="roombillingFORM" class="btn btn-primary">Save changes</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="modal fade" id="modal-edit">
                    <div class="modal-dialog modal-lg" style="max-width: 500px !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">EDIT ROOM BILL</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="EditroombillingFORM" class="form-horizontal">
                          <div class="card-body">
                            <div class="form-group row">
                              <input type="hidden" name="room_bill_id" id="room_bill_id">
                              <input type="hidden" name="room_id" id="room_id">
                              <label class="col-sm-3 col-form-label">Bill Type:</label>
                              <div class="col-sm-9">
                                <select name="billtype" id="billtype" class="form-control custom-select" required>
                                  <option selected disabled>Select one</option>
                                  <option>Electricity</option>
                                  <option>Water</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Date:</label>
                              <div class="col-sm-9">
                                <input type="date" class="form-control" name="date" id="date" required>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Total Bill:</label>
                              <div class="col-sm-9">
                                <input type="number" name="amount" id="amount" placeholder="Total Bill" class="form-control" required>
                              </div>
                              <input type="hidden" name="oldamount" id="oldamount" value="<?php echo $amount ?>">
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="submitbtn" class="btn btn-primary">Save changes</button>
                          </div>
                        </form>
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
    $("#examplep").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "searching": true,
      "ordering": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#examplep_wrapper .col-md-6:eq(0)');
  </script>
</body>
</html>