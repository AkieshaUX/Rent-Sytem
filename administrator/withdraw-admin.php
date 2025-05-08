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
              <h1>Withdraw Summary</h1>
            </div>
          </div>
          <div class="row">


            <div class="col-md-3 col-sm-6 col-12">
              <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fa-solid fa-money-bill-transfer"></i></span>
                <?php
                // Get the current month and year
                $currentMonth = date('m');
                $currentYear = date('Y');

                // Query to sum the amount for the current month and year
                $query = mysqli_query($conn, "
                  SELECT SUM(amount) AS admin_withdraw 
                  FROM withdraw 
                  WHERE `admin` = 'admin' 
                  AND MONTH(`date`) = '$currentMonth' 
                  AND YEAR(`date`) = '$currentYear'
                ");

                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $admin_withdraw = isset($result['admin_withdraw']) ? floatval($result['admin_withdraw']) : 0.00;
                } else {
                  $admin_withdraw = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Total Withdrawal This Month</span>
                  <span class="info-box-number"><?php echo number_format($admin_withdraw, 2); ?></span>
                </div>
              </div>
            </div>


            <div class="col-md-3 col-sm-6 col-12">
            <a href="withdraw-admin-annual.php" style="color: #000;">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fa-solid fa-money-bill-transfer"></i></span>
                <?php
                $query = mysqli_query($conn, "SELECT SUM(amount) AS admin_withdraw FROM withdraw WHERE `admin` = 'admin' ");
                if ($query) {
                  $result = mysqli_fetch_assoc($query);
                  $admin_withdraw = isset($result['admin_withdraw']) ? floatval($result['admin_withdraw']) : 0.00;
                } else {
                  $admin_withdraw = 0.00;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Total Withdrawal</span>
                  <span class="info-box-number"><?php echo number_format($admin_withdraw, 2); ?></span>
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
                    <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-addnew"><i class="fa-solid fa-money-bill-transfer pr-1"></i>Withdraw</button>
                  </div>
                </div>
                <div class="card-body">
                  <table id="example1" class="table table-striped">
                    <thead>
                      <tr>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 20%;">Purpose</th>
                        <th style="width: 20%;">Amount</th>
                        <th style="width: 20%;">Date</th>
                        <th style="width: 20%;"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      // Get the current month and year
                      $currentMonth = date('m');
                      $currentYear = date('Y');

                      // Query to get withdrawals where the month matches the current month and year
                      $query = mysqli_query($conn, "
                      SELECT * 
                      FROM `withdraw` 
                      WHERE `admin` = 'admin' 
                      AND MONTH(`date`) = '$currentMonth' 
                      AND YEAR(`date`) = '$currentYear' 
                      ORDER BY withdraw_id DESC
                    ");

                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                      ?>
                        <tr>
                          <td><?php echo $admin ?></td>
                          <td><?php echo $type ?></td>
                          <td><?php echo $amount ?></td>
                          <td><?php echo date('M d, Y', strtotime($date)); ?></td>
                          <td style="text-align: center;">
                            <button class="btn btn-info btn-sm" id="EDITwithdraw" data-withdraw-id="<?php echo $withdraw_id; ?>" data-toggle="modal" data-target="#modal-edit">
                              <i class="fas fa-pencil-alt"></i>
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


                  <div class="modal fade" id="modal-addnew">
                    <div class="modal-dialog modal-lg" style="max-width: 490px  !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">ADD NEW WITHDRAW</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <form id="adminwithdraw" class="form-horizontal">
                          <div class="modal-body" style="padding-top:0; padding-bottom:0;">
                            <div class="card-body">

                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label"> Purpose of a Withdrawal</label>
                                <div class="col-sm-9">
                                  <textarea class="form-control" name="purpose" placeholder="Type..." required></textarea>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Amount</label>
                                <div class="col-sm-9">
                                  <input type="number" name="amount" placeholder="Amount" class="form-control" required>
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
                    <div class="modal-dialog modal-lg" style="max-width: 490px  !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">EDIT MAINTENANCE</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>

                        <form id="EDITadminwithdraw" class="form-horizontal">
                          <div class="modal-body" style="padding-top:0; padding-bottom:0;">
                            <div class="card-body">
                              <input type="hidden" name="withdraw_id" id="withdraw_id">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label"> Purpose of a Withdrawal</label>
                                <div class="col-sm-9">
                                  <textarea class="form-control" name="purpose" id="purpose" placeholder="Type..." required></textarea>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Amount</label>
                                <div class="col-sm-9">
                                  <input type="number" name="amount" placeholder="Amount" id="amount" class="form-control" required>
                                </div>
                                <input type="hidden" name="oldamount" id="oldamount">
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