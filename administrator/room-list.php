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
  <style>
    .btn-button {
      background: #d81b60 !important;
      border-color: none;
    }

    .grid {
      display: grid !important;
      grid-template-rows: auto !important;
    }
    .grid--4 {
      grid-template-columns: repeat(4, 1fr) !important;
    }
    .lead {
      font-size: 20px !important;
      font-weight: 300;
    }
  </style>
  <div class="wrapper">
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-sm-6">
              <h1>List of Room</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
              </ol>
            </div>
          </div>
        </div>
      </section>
      <section class="content" id="loadRoom">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <div class="float-left">
                    <div>
                      <div class="btn-group w-100 mb-2" style="gap: 10px !important;">
                        <a class="btn btn-info active" style="text-transform: uppercase;" href="javascript:void(0)" data-filter="all">All</a>
                        <?php
                        $query = mysqli_query($conn, "SELECT DISTINCT `roomtype` FROM `room` WHERE room_status IS NULL ORDER BY `roomtype` DESC");
                        while ($result = mysqli_fetch_array($query)) {
                          extract($result);
                        ?>
                          <a class="btn btn-info" style="text-transform: uppercase;" href="javascript:void(0)" data-filter="<?php echo $roomtype ?>"><?php echo $roomtype ?></a>
                        <?php   } ?>
                      </div>
                    </div>
                  </div>
                  <div class="float-right">
                    <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-addnew">ADD NEW ROOM</button>
                  </div>
                </div>
                <div class="card-body ">
                  <div class="filter-container p-0 grid grid--4">
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM `room` WHERE room_status IS NULL");
                    while ($result = mysqli_fetch_array($query)) {
                      extract($result);
                      $tenantCheckQuery = "SELECT * FROM `tenants` WHERE `room_id` = '$room_id' AND `status` = 1";
                      $tenantCheckResult = mysqli_query($conn, $tenantCheckQuery);
                      $isRoomInUse = mysqli_num_rows($tenantCheckResult) > 0;
                    ?>
                      <div class="filtr-item col-12 col-sm-6 col-md-3 d-flex align-items-stretch flex-column" data-category="<?php echo $roomtype ?>" data-sort="white sample">
                        <div class="card bg-light d-flex flex-fill piedad">
                          <div class="card-header text-muted border-bottom-0" style="padding: 0;"></div>
                          <div class="card-body pt-0" style="padding: 0;">
                            <div class="img--romm" style="height: 200px !important;">
                              <a class="image-popup-vertical-fit" href="../image/<?php echo $roomimage ?>">
                                <img style="width: 100% !important; height: 100%; object-fit: cover;" src="../image/<?php echo $roomimage ?>" alt="">
                              </a>
                            </div>
                            <h2 class="lead pl-3 pt-3 pr-3"><b>Room - <?php echo $roomnumber  ?></b></h2>
                            <h2 class="lead pl-3 pr-3"><b><?php echo $roomtype  ?></b></h2>
                            <h2 class="lead pl-3 pr-3"><b>For: <?php echo $roomgender ?></b></h2>
                            <h2 class="lead pl-3 pr-3"><b>Maximum: <?php echo $maximum ?></b></h2>
                            <h2 class="lead pl-3 pr-3" style="color: black; font-weight: 500;"><?php echo $roomsmonthly ?>/Month</h2>
                          </div>
                          <div class="card-footer">
                            <div class="text-right">
                              <button class="btn btn-info btn-sm" id="editroombtn" data-toggle="modal" data-target="#modal-updateroom" data-room-id="<?php echo $room_id ?>">
                                <i class="fas fa-pencil-alt"></i> Edit
                              </button>
                              <button class="btn btn-danger btn-sm deleteroom" id="deleteroom" data-room-id="<?php echo $room_id ?>" <?php echo $isRoomInUse ? 'disabled' : ''; ?>>
                                <i class="fas fa-trash"></i> Delete
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php
                    }
                    ?>
                  </div>
                  <div class="modal fade" id="modal-addnew">
                    <div class="modal-dialog modal-lg" style="max-width: 600px !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">ADD NEW ROOM</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="" id="addroomForm" class="form-horizontal">
                          <div class="modal-body" style="padding-top:0; padding-bottom:0;">
                            <div class="card-body">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room No.</label>
                                <div class="col-sm-9">
                                  <input type="number" class="form-control" name="roomnumber" placeholder="Room No." required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room Type</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" name="roomtype" placeholder="Room Type" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room For</label>
                                <div class="col-sm-9">
                                  <select name="roomgender" class="form-control custom-select"  required>
                                    <option selected disabled>Select one</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Maximum</label>
                                <div class="col-sm-9">
                                  <input type="number" class="form-control" name="maximum" placeholder="Maximum" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Image</label>
                                <div class="col-sm-9">
                                  <div class="custom-file">
                                    <input name="roomimage" type="file" accept=".jpeg, .jpg, .png" class="custom-file-input" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose Image</label>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room Monthly</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" name="roomsmonthly" placeholder="Room Monthly" required>
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
                  <div class="modal fade" id="modal-updateroom">
                    <div class="modal-dialog modal-lg" style="max-width: 600px !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">UPDATE ROOM</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="" id="editroomForm" class="form-horizontal">
                          <div class="modal-body" style="padding-top:0; padding-bottom:0;">
                            <div class="card-body">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room No.</label>
                                <div class="col-sm-9">
                                  <input type="hidden" name="room_id" id="room_ids">
                                  <input type="number" class="form-control" name="roomnumber" id="roomnumber" placeholder="Room No." required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room Type</label>
                                <div class="col-sm-9">
                                  <input type="text" id="roomtype" class="form-control" name="roomtype" placeholder="Room Type" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room For</label>
                                <div class="col-sm-9">
                                  <select name="roomgender" id="roomgender" class="form-control custom-select"  required>
                                    <option selected disabled>Select one</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Maximum</label>
                                <div class="col-sm-9">
                                  <input type="number" class="form-control" name="maximum" id="maximum" placeholder="Maximum" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Image</label>
                                <div class="col-sm-9">
                                  <div class="custom-file">
                                    <?php if (!empty($roomimage)) {  ?>
                                      <img src="../image/<?php echo $roomimage ?>" alt="" style="display:none;">
                                    <?php } ?>
                                    <input id="roomimage" name="roomimage" type="file" accept=".jpeg, .jpg, .png" class="custom-file-input">
                                    <input id="current_room" name="current_room" type="hidden" class="custom-file-input" value="<?php echo $roomimage ?>">
                                    <label class="custom-file-label" for="exampleInputFile">Choose Image</label>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Room Monthly</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" name="roomsmonthly" id="roomsmonthly" placeholder="Room Monthly" required>
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
      $('table').removeClass('dataTable ');
    });
    $(function() {
      $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
          alwaysShowClose: true
        });
      });
      $('.filter-container').filterizr({
        gutterPixels: 3
      });
      $('.btn[data-filter]').on('click', function() {
        $('.btn[data-filter]').removeClass('active');
        $(this).addClass('active');
      });
    })
  </script>
</body>
</html>