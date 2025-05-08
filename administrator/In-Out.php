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
              <h1>In-Out Record</h1>
            </div>
          </div>
          <div class="row">
            <div class=" col-md-3 col-sm-6 col-12 ">
              <a href="In-Out-view.php?action=IN" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-success elevation-1"><i style="color: white;" class="fas fa-users"></i></span>
                  <?php
                  $sql = "SELECT COUNT(*) AS total_IN FROM `tenants` WHERE `action` = 'IN'";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_IN = $result['total_IN'];
                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Tenants IN</span>
                      <span class="info-box-number"><?php echo $total_IN ?></span>
                    </div>
                  <?php } ?>
                </div>
              </a>
            </div>
            <div class=" col-md-3 col-sm-6 col-12 ">
              <a href="In-Out-view.php?action=OUT" style="color: #212529;">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-danger elevation-1"><i style="color: white;" class="fas fa-users"></i></span>
                  <?php
                  $sql = "SELECT COUNT(*) AS total_OUT FROM `tenants` WHERE `action` = 'OUT'";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_OUT = $result['total_OUT'];
                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Tenants OUT</span>
                      <span class="info-box-number"><?php echo $total_OUT ?></span>
                    </div>
                  <?php } ?>
                </div>
              </a>
            </div>
            <div class=" col-md-3 col-sm-6 col-12 ">
              <a href="In-Out-overall.php">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-info elevation-1"><i class="fa-solid fa-circle-info"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">View More</span>
                    <span class="info-box-number"></span>
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
                <div class="card-body">
                  <table id=".example1" class="table table-striped ">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      $InOut = InOut($conn);
                      while ($result = mysqli_fetch_array($InOut)) {
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