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
              <h1>Room Maintenance </h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                <?php
                $today = new DateTime();
                $currentMonth = $today->format('m');
                $currentYear = $today->format('Y');
                $query = mysqli_query($conn, "SELECT SUM(amount) AS total_maintenance FROM maintenance WHERE MONTH(date) = '$currentMonth' AND YEAR(date) = '$currentYear'");

                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $total_maintenance = isset($result['total_maintenance']) ? floatval($result['total_maintenance']) : 0.00;
                } else {
                  $total_maintenance = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Maintenance Cost </span>
                  <span class="info-box-number"><?php echo number_format($total_maintenance, 2); ?></span>
                </div>
              </div>


            </div>
            <div class="col-md-3 col-sm-6 col-12">
              <a href="room-total-maintenance.php" style="color: #000;">
                <div class="info-box">
                  <span class="info-box-icon bg-success"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                  <?php
                  $query = mysqli_query($conn, "SELECT SUM(amount) AS total_maintenance FROM maintenance");

                  if ($query) {
                    $result = mysqli_fetch_assoc($query);
                    $total_maintenance = isset($result['total_maintenance']) ? floatval($result['total_maintenance']) : 0.00;
                  } else {
                    $total_maintenance = 0.00;
                  }
                  ?>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Maintenance Cost</span>
                    <span class="info-box-number"><?php echo number_format($total_maintenance, 2); ?></span>
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
                  <div class="float-right">
                    <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-addnew"><i class="fa-solid fa-hammer"></i> Add New</button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id=".example1" class="table table-striped ">
                      <thead>
                        <tr>
                          <th style="width: 20%;">Room No.</th>
                          <th style="width: 20%;">Issue</th>
                          <th style="width: 20%;">Cost</th>
                          <th style="width: 20%;">Date</th>
                          <th style="width: 20%;"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $no = 1;
                        $today = new DateTime();
                        $currentMonth = $today->format('m');
                        $currentYear = $today->format('Y');
                        $query = mysqli_query($conn, "SELECT room.*, maintenance.* FROM `maintenance` INNER JOIN room ON maintenance.room_id = room.room_id WHERE MONTH(date) = '$currentMonth' AND YEAR(date) = '$currentYear' ORDER BY maintenance.maintenance_id DESC ");
                        while ($result = mysqli_fetch_array($query)) {
                          extract($result);
                        ?>
                          <tr>

                            <td>Room-<?php echo $roomnumber . ' | ' . $roomtype ?></td>
                            <td><?php echo $issue ?></td>
                            <td><?php echo number_format($amount, 2) ?></td>
                            <td><?php echo date('M d, Y', strtotime($date)); ?></td>
                            <td style="text-align: center;">
                              <button class="btn btn-info btn-sm" id="EDITroomMaintenance" data-maintenance-id="<?php echo $maintenance_id; ?>" data-toggle="modal" data-target="#modal-edit">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
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

                  <div class="modal fade" id="modal-addnew">
                    <div class="modal-dialog modal-lg" style="max-width: 490px !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">ADD NEW MAINTENANCE</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <form id="roomMaintenance" class="form-horizontal">
                          <div class="modal-body" style="padding-top:0; padding-bottom:0;">
                            <div class="card-body">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room No.</label>
                                <div class="col-sm-9">
                                  <select name="room_id" class="form-control custom-select" required>
                                    <option selected disabled>Select Room</option>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM `room` ORDER BY roomtype");
                                    while ($result = mysqli_fetch_array($query)) {
                                      extract($result);
                                    ?>
                                      <option value="<?php echo $room_id ?>">Room-<?php echo $roomnumber . ' | ' . $roomtype ?></option>
                                    <?php  } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Description:</label>
                                <div class="col-sm-9">
                                  <textarea class="form-control" name="issue" placeholder="Issue..." required></textarea>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Amount:</label>
                                <div class="col-sm-9">
                                  <input type="number" name="amount" placeholder="Amount" class="form-control" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Date:</label>
                                <div class="col-sm-9">
                                  <input type="date" name="date" class="form-control" required>
                                </div>
                              </div>
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

                  <div class="modal fade" id="modal-edit">
                    <div class="modal-dialog modal-lg" style="max-width: 490px !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">EDIT MAINTENANCE</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <form id="EditroomMaintenance" class="form-horizontal">
                          <div class="modal-body" style="padding-top:0; padding-bottom:0;">
                            <div class="card-body">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room No.</label>
                                <div class="col-sm-9">
                                  <select name="room_id" id="room_ids" class="form-control custom-select" required>
                                    <option selected disabled>Select Room</option>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM `room` ORDER BY roomtype");
                                    while ($result = mysqli_fetch_array($query)) {
                                      extract($result);
                                    ?>
                                      <option value="<?php echo $room_id ?>">Room-<?php echo $roomnumber . ' | ' . $roomtype ?></option>
                                    <?php  } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Description:</label>
                                <div class="col-sm-9">
                                  <textarea class="form-control" name="issue" id="issue" placeholder="Issue..." required></textarea>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Amount:</label>
                                <div class="col-sm-9">
                                  <input type="number" name="amount" id="amount" placeholder="Amount" class="form-control" required>
                                </div>
                                <input type="hidden" name="oldamount" id="oldamount" value="<?php echo $amount ?>">
                                <input type="hidden" name="maintenance_id" id="maintenance_id">
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Date:</label>
                                <div class="col-sm-9">
                                  <input type="date" id="date" name="date" class="form-control" required>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
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
</body>

</html>