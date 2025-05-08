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

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-sm-6">
              <h1>List of Male Room Available</h1>
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
                    <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-assign">ASSIGN TENANT</button>
                  </div>
                </div>
                <div class="card-body ">
                  <table id="example1" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Room No.</th>
                        <th>Room Members</th>
                        <th>Available</th>
                        <th>Image</th>
                        <th class="text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1;
                      $query = mysqli_query($conn, "SELECT *, (SELECT COUNT(DISTINCT tenants.tenants_id, roomgender) FROM rent INNER JOIN tenants ON tenants.tenants_id = rent.tenants_id WHERE rent.room_id = room.room_id) AS tenant_count FROM `room` WHERE room_status IS NULL AND roomgender = 'Male' ORDER BY CASE WHEN (maximum - (SELECT COUNT(DISTINCT tenants.tenants_id) FROM rent INNER JOIN tenants ON tenants.tenants_id = rent.tenants_id WHERE rent.room_id = room.room_id)) > 0 THEN 1 ELSE 2 END, room_id DESC");
                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                        $profileImages = [];
                        $tenantNames = [];
                        $tenantCount = 0;
                        $free = $maximum;
                        $queryCount = mysqli_query($conn, "SELECT COUNT(DISTINCT tenants.tenants_id) AS tenant_count FROM rent INNER JOIN tenants ON tenants.tenants_id = rent.tenants_id WHERE rent.room_id = $room_id AND tenants.status = 1 AND rent.status = 'Unpaid'");
                        if ($countResult = mysqli_fetch_assoc($queryCount)) {
                          $tenantCount = $countResult['tenant_count'];
                          $free = $maximum - $tenantCount;
                        }
                        $queryJoin = mysqli_query($conn, "SELECT DISTINCT tenants.profile, tenants.name FROM rent INNER JOIN tenants ON tenants.tenants_id = rent.tenants_id WHERE rent.room_id = $room_id AND tenants.status = 1 AND rent.status = 'Unpaid' ");
                        while ($tenant = mysqli_fetch_array($queryJoin)) {
                          if (!empty($tenant['profile'])) {
                            $profileImages[] = "../image/" . $tenant['profile'];
                            $tenantNames[] = $tenant['name'];
                          }
                        }
                        $status = $free > 0 ? 'Available' : 'Occupied';
                        $statusClass = $free > 0 ? 'badge-success' : 'badge-danger'; ?>

                        <tr>
                          <td>
                            <a>Room <?php echo $roomnumber . ' | ' . $roomtype; ?></a>
                            <br />
                            <small>Maximum <?php echo $maximum; ?> Bed</small>
                          </td>
                          <td>
                            <ul class="list-inline">
                              <?php foreach ($profileImages as $index => $profileImage) :
                                $tenantName = $tenantNames[$index] ?? 'Unknown Tenant';
                              ?>
                                <li class="list-inline-item">
                                  <a href="<?php echo $profileImage; ?>" class="gallery-item" data-title="<?php echo $tenantName; ?>">
                                    <img alt="Avatar" class="table-avatar" src="<?php echo $profileImage; ?>" style="width: 50px; height: 50px; border-radius: 50%;">
                                  </a>
                                </li>
                              <?php endforeach; ?>
                            </ul>
                          </td>
                          <td class="project_progress">
                            <?php echo $free; ?> Bed
                          </td>
                          <td>
                            <a class="image-popup-vertical-fit" href="../image/<?php echo $roomimage ?>">
                              <img style="width: 100% !important; max-height: 70px; object-fit: cover;" src="../image/<?php echo $roomimage ?>" alt="">
                            </a>
                          </td>
                          <td class="project-state text-center">
                            <span class="badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                          </td>
                        </tr>
                      <?php
                        $no++;
                      }
                      ?>
                    </tbody>
                  </table>
                  <div class="modal fade" id="modal-assign">
                    <div class="modal-dialog modal-lg" style="max-width: 615px !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">ASSIGN TENANT</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="assigntenantForm" enctype="multipart/form-data" action="../inc/controller.php" class="form-horizontal">
                          <div class="modal-body" style="padding-top:0; padding-bottom:0;">
                            <div class="card-body">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tenant Name</label>
                                <div class="col-sm-9">
                                  <select id="selectname" class="form-control select2bs4" data-placeholder="Select Name" required name="tenanupdateID" style="width: 100%;">
                                    <option selected disabled>Select one</option>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM `tenants` WHERE `gender`= 'Male' AND `status` = 0");
                                    while ($result = mysqli_fetch_array($query)) {
                                      extract($result);
                                    ?>
                                      <option value="<?php echo $tenants_id ?>">
                                        <?php echo $name ?>
                                      </option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room No.</label>
                                <div class="col-sm-9">
                                  <select id="selectRoom" class="form-control select2bs4" data-placeholder="Select Room" required name="room_id" style="width: 100%;">
                                    <option value=""></option>
                                    <?php
                                    $query = mysqli_query(
                                      $conn,
                                      "SELECT room.*, (room.maximum - COALESCE((SELECT COUNT(DISTINCT tenants.tenants_id)  FROM rent INNER JOIN tenants ON tenants.tenants_id = rent.tenants_id  WHERE rent.room_id = room.room_id), 0)) AS free_beds FROM `room`  WHERE room_status IS NULL AND roomgender = 'Male'  HAVING free_beds > 0"
                                    );
                                    while ($result = mysqli_fetch_array($query)) {
                                      extract($result);
                                    ?>
                                      <option value="<?php echo $room_id ?>" data-roomsmonthly="<?php echo $roomsmonthly ?>">
                                        Room <?php echo $roomnumber . ' | ' .  $roomtype; ?>
                                      </option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Monthly Rate</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" name="roomsmonthly" placeholder="Monthly Rate" required readonly>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Rental Period</label>
                                <div class="col-sm-9">
                                  <input type="number" class="form-control" name="term" placeholder="Rental Period" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Deposit Coverage</label>
                                <div class="col-sm-9">
                                  <input type="number" class="form-control" name="depositMonths" placeholder="Number of Months for Deposit Coverage">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Rental Deposit</label>
                                <div class="col-sm-9">
                                  <input type="number" class="form-control" name="deposit" placeholder="Rental Deposit" readonly>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="assigntenantForm" class="btn btn-primary">Save changes</button>
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
    $(document).ready(function() {
      $('.image-popup-vertical-fit').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
          verticalFit: true
        }
      });
      $('.select2').select2()
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
      $('#selectRoom').on('change', function() {
        var roomType = $(this).find(':selected').data('roomsmonthly');
        $('input[name="roomsmonthly"]').val(roomType);
      });
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
      $('input[name="depositMonths"]').on('input', function() {
        const monthlyRate = parseFloat($('input[name="roomsmonthly"]').val()) || 0;
        const depositMonths = parseFloat($(this).val());
        if (!depositMonths) {
          $('input[name="deposit"]').val('');
        } else {
          const depositAmount = monthlyRate * depositMonths;
          $('input[name="deposit"]').val(depositAmount);
        }
      });
    });
  </script>
</body>

</html>