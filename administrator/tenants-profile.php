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
  .profile-background {
    position: relative;
    height: 250px;
    overflow: hidden;
  }

  .profile-background-image {
    height: 100%;
    width: 100%;
    background-size: cover;
    background-position: center;
  }

  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1;
  }

  .profile-user-img {
    border: 5px solid #fff;
    border-radius: 50%;
  }

  .card .overlay,
  .info-box .overlay,
  .overlay-wrapper .overlay,
  .small-box .overlay {
    border-radius: .25rem;
    -ms-flex-align: center;
    align-items: center;
    background-color: rgba(255, 255, 255, 38%);
    display: -ms-flexbox;
    display: flex;
    -ms-flex-pack: center;
    justify-content: center;
    z-index: 50;
  }
</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Tenant Profile</h1>
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3">
              <?php
              if (isset($_GET['tenants_id'])) {
                $query = mysqli_query($conn, " SELECT tenants.*,room.*,rent.*  FROM rent  JOIN tenants ON rent.tenants_id = tenants.tenants_id  JOIN room ON room.room_id = rent.room_id   WHERE tenants.tenants_id = $_GET[tenants_id] AND tenants.status = 1 AND rent.status = 'Unpaid' ");
                $result = mysqli_fetch_array($query);
                extract($result);
              }
              ?>
              <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                  <?php
                  $tenants_id = $tenants_id;
                  $name = $name;
                  $qrCodePath = "../qrcodes/tenant_{$tenants_id}.png";
                  $profileImagePath = "../image/{$profile}";
                  ?>

                  <div class="profile-background" style="position: relative; height: 250px; overflow: hidden;">
                    <div class="profile-background-image" style="background-image: url('<?php echo $qrCodePath; ?>');"></div>
                    <div class="overlay" style="align-items: end;">
                      <div class="text-center mt-3">
                        <a class="image-popup-vertical-fit" href="<?php echo $profileImagePath; ?>">
                          <img style="height: 150px; width: 150px; border: 1px solid white;" class="profile-user-img img-fluid img-circle" src="<?php echo $profileImagePath; ?>">
                        </a>
                      </div>
                    </div>
                  </div>

                  <h3 class="profile-username text-center"><?php echo $name ?></h3>
                  <p class="text-muted text-center"><?php echo $occupation ?></p>
                  <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                      <b>Gender:</b> <a class="pl-2"><?php echo $gender ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Contact:</b> <a class="pl-2"><?php echo $contact ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Email:</b> <a class="pl-2"><?php echo $email ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Address:</b> <a class="pl-2"><?php echo $address ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Room No:</b> <a class="pl-2"><?php echo $roomnumber . ' | ' . $roomtype ?></a>
                    </li>
                    <li class="list-group-item">
                      <b>Term:</b> <a class="pl-2"><?php echo $term ?>/Months</a>
                    </li>
                    <li class="list-group-item">
                      <b>Monthly Rate:</b> <a class="pl-2"><?php echo $roomsmonthly ?></a>
                    </li>
                  </ul>

                  <div class="text-center">
                    <div class="downloadqrcode d-flex justify-content-center pb-3">
                      <a href="<?php echo $qrCodePath; ?>" download="QRCODE_<?php echo $name; ?>.png">
                        <button class="btn btn-primary">Download QR Code</button>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Parent Tenant Profile</h3>
                </div>
                <div class="card-body">
                  <strong><i class="fa-solid fa-user mr-1"></i> Name</strong>
                  <p class="text-muted">
                    <?php echo $parentname ?>
                  </p>
                  <hr>
                  <strong><i class="fa-solid fa-address-card mr-1"></i> Contact No:</strong>
                  <p class="text-muted"><?php echo $parentcontact ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-9">
              <div class="card">
                <div class="card-header p-2">
                  <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active " href="#unpaid" data-toggle="tab">Unpaid</a></li>
                    <li class="nav-item"><a class="nav-link" href="#paymenthistory" data-toggle="tab">Paid</a></li>
                    <li class="nav-item"><a class="nav-link" href="#inout" data-toggle="tab">In/Out </a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane" id="paymenthistory">
                      <div class="table-responsive">
                        <table class="table  table-striped ">
                          <thead>
                            <tr>
                              <th style="text-align: center;">#</th>
                              <th>Due Date</th>
                              <th>Date of Payment</th>
                              <th>Monthly</th>
                              <th>Payment Type</th>
                              <th>Reference No:</th>
                              <th style="text-align: center;">Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $no = 1;
                            if (isset($_GET['tenants_id'])) {
                              $query = mysqli_query($conn, "SELECT  tenants.*, room.*, rent.* FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE  rent.status = 'Paid' AND rent.confirmation = 1 AND rent.tenants_id = $_GET[tenants_id] ORDER BY datepayment DESC");
                            }
                            while ($result = mysqli_fetch_array($query)) {
                              extract($result);
                            ?>
                              <tr>
                                <td style="text-align: center;"><?php echo $no ?></td>
                                <td><?php echo date('M d, Y', strtotime($duedate)); ?></td>
                                <td><?php echo date('M d, Y', strtotime($datepayment)); ?></td>
                                <td><?php echo $roomsmonthly ?></td>
                                <td><?php echo $type ?></td>
                                <td style="height: 70px;"> <a class="image-popup-vertical-fit" href="../gcash/<?php echo $receipt ?>">
                                    <img style="width: 100% !important; max-height: 70px; object-fit: cover;" src="../gcash/<?php echo $receipt ?>" alt="">
                                  </a></td>
                                <td class="project-state" style="text-align: center;">
                                  <span class="badge badge-success"><?php echo $status ?></span>
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
                    <div class="active tab-pane" id="unpaid">
                      <div class="table-responsive">
                        <table class="table  table-striped">
                          <thead>
                            <tr>
                              <th style="text-align: center;">#</th>
                              <th>Due Date</th>
                              <th>Monthly</th>
                              <th style="text-align: center;">Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $no = 1;
                            if (isset($_GET['tenants_id'])) {
                              $query = mysqli_query($conn, "SELECT  tenants.*, room.*, rent.* FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE  rent.status = 'Unpaid' AND rent.tenants_id = $_GET[tenants_id] ORDER BY duedate ");
                            }
                            while ($result = mysqli_fetch_array($query)) {
                              extract($result);
                            ?>
                              <tr>
                                <td style="text-align: center;"><?php echo $no ?></td>
                                <td><?php echo date('M d, Y', strtotime($duedate)); ?></td>
                                <td><?php echo $roomsmonthly ?></td>
                                <td class="project-state" style="text-align: center;">
                                  <span style="color:white;" class="badge badge-warning"><?php echo $status ?></span>
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
                    <div class="tab-pane" id="inout">
                      <div class="table-responsive">
                        <table class="table  table-striped ">
                          <thead>
                            <tr>
                              <th>No.</th>
                              <th>Name</th>
                              <th>Status</th>
                              <th>Date & Time</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $no = 1;
                            $InOut_tenants = InOut_tenants($conn);
                            while ($result = mysqli_fetch_array($InOut_tenants)) {
                              extract($result);
                            ?>
                              <tr>
                                <td style="width:1%"><?php echo $no ?></td>
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
                              $no++;
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