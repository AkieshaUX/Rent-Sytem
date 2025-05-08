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
  <style>
    a .info-box {
      color: #000;
    }
  </style>


  <div class="wrapper">
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-sm-6">
              <h1>List of Tenants</h1>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
              <a href="">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="fa-solid fa-person"></i></span>
                  <?php
                  $sql = "SELECT COUNT(*) AS total_male FROM `tenants` WHERE `gender` = 'Male' AND `status` = 1";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_male = $result['total_male'];

                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Male</span>
                      <span class="info-box-number"><?php echo $total_male ?></span>
                    </div>
                  <?php } ?>

                </div>
              </a>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
              <a href="">
                <div class="info-box">
                  <span class="info-box-icon bg-success" style="background: #e56399 !important;"><i class="fa-solid fa-person-dress"></i></i></span>

                  <?php
                  $sql = "SELECT COUNT(*) AS total_female FROM `tenants` WHERE `gender` = 'Female' AND `status` = 1";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_female = $result['total_female'];

                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Female</span>
                      <span class="info-box-number"><?php echo $total_female ?></span>
                    </div>
                  <?php } ?>

                </div>
              </a>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
              <a href="">
                <div class="info-box">
                  <span class="info-box-icon bg-warning"><i style="color: white;" class="fa-solid fa-graduation-cap"></i></span>

                  <?php
                  $sql = "SELECT COUNT(*) AS total_student FROM `tenants` WHERE `occupation` = 'Student' AND `status` = 1";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_student = $result['total_student'];

                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Student</span>
                      <span class="info-box-number"><?php echo $total_student ?></span>
                    </div>
                  <?php } ?>

                </div>
              </a>

            </div>

            <div class="col-md-3 col-sm-6 col-12">
              <a href="">
                <div class="info-box">
                  <span class="info-box-icon bg-danger"><i class="fa-solid fa-user-tie"></i></span>
                  <?php
                  $sql = "SELECT COUNT(*) AS total_employee FROM `tenants` WHERE (`occupation` = 'Employee' OR `occupation` = 'Non-Employee') AND `status` = 1";
                  $query = $conn->query($sql);
                  if ($query) {
                    $result = $query->fetch_assoc();
                    $total_employee = $result['total_employee'];

                  ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Employee | Non-Employee</span>
                      <span class="info-box-number"><?php echo $total_employee ?></span>
                    </div>
                  <?php } ?>


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
                    <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-addnew"><i class="fa-solid fa-user-plus"></i> Add New</button>
                  </div>
                </div>
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped tenant_list">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Room No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Occupation</th>
                        <th>Address</th>

                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      if (isset($_GET['gender']) && $_GET['occupation']) {
                        $query =  $query = mysqli_query($conn, "SELECT t.*, r.* FROM tenants t INNER JOIN room r ON t.room_id = r.room_id WHERE `gender` = $_GET[gender] AND `occupation` = $_GET[occupation] AND `status` = 1 ORDER BY t.tenants_id DESC");
                        while ($result = mysqli_fetch_array($fetchTenantsData)) {
                          extract($result);




                      ?>
                          <tr id="tenants_id=<?php echo $tenants_id; ?>">
                            <td style="width:1%"><?php echo $no ?></td>
                            <td>Room <?php echo $roomnumber . ' | ' . $roomtype ?></td>
                            <td><?php echo $name ?></td>
                            <td><?php echo $gender ?></td>
                            <td><?php echo $occupation ?></td>
                            <td><?php echo $address ?></td>

                            <td>
                              <a class="btn btn-primary btn-sm" href="tenants-profile.php?tenants_id=<?php echo $tenants_id ?>">
                                <i class="fas fa-folder">
                                </i>
                                View
                              </a>
                              <button class="btn btn-info btn-sm" id="edit_tenantBTN" data-tenants-id="<?php echo $tenants_id; ?>" data-toggle="modal" data-target="#modal-edit">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                              </button>
                              <button class="btn btn-danger btn-sm" id="removetenants" data-tenants-id="<?php echo $tenants_id ?>">
                                <i class="fas fa-trash">
                                </i>
                                Delete
                              </button>
                            </td>
                          </tr>
                      <?php
                          $no++;
                        }
                      }

                      ?>
                    </tbody>
                  </table>

                  <div class="modal fade" id="modal-addnew">
                    <div class="modal-dialog modal-lg" style="max-width: 600px !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">ADD NEW TENANT</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="register_tenants" action="../inc/controller.php" method="POST" enctype="multipart/form-data" class="form-horizontal">
                          <div class="modal-body" style="padding-top:0; padding-bottom:0;">
                            <div class="card-body">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" name="name" placeholder="Name" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Gender</label>
                                <div class="col-sm-9">
                                  <select name="gender" class="form-control custom-select" required>
                                    <option selected disabled>Select one</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Contact No:</label>
                                <div class="col-sm-9">
                                  <input name="contact" type="tel" placeholder="Enter Contact No." class="form-control" maxlength="11" pattern="\d{11}" title="Please enter a valid contact number." required>

                                </div>
                              </div>

                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Address</label>
                                <div class="col-sm-9">
                                  <input name="address" type="text" class="form-control" placeholder="Address" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Occupation</label>
                                <div class="col-sm-9">
                                  <select name="occupation" class="form-control custom-select" required>
                                    <option selected disabled>Select one</option>
                                    <option>Student</option>
                                    <option>Employee</option>
                                    <option>Non-Employee</option>
                                  </select>
                                </div>

                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                  <input name="email" type="email" class="form-control" placeholder="Email" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                  <input name="password" type="password" class="form-control" placeholder="Password" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tenant Profile</label>
                                <div class="col-sm-9">
                                  <div class="custom-file">
                                    <input name="profile" type="file" accept=".jpeg, .jpg, .png" class="custom-file-input" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose Profile</label>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Parent Name</label>
                                <div class="col-sm-9">
                                  <input name="parentname" type="text" class="form-control" placeholder="Parent Name">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Parent Contact</label>
                                <div class="col-sm-9">
                                  <input name="parentcontact" type="tel" placeholder="Enter Contact No." maxlength="11" pattern="\d{11}" title="Please enter a valid contact number." class="form-control">

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
                    <div class="modal-dialog modal-lg" style="max-width: 600px !important;">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">EDIT TENANT DETAILS</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="edit_tenantsForm" method="POST" enctype="multipart/form-data" class="form-horizontal">
                          <div class="modal-body" style="padding-top:0; padding-bottom:0;">
                            <div class="card-body">
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                  <input type="hidden" id="tenants_ids" name="haha">
                                  <input id="name" type="text" class="form-control" name="name" placeholder="Name" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Gender</label>
                                <div class="col-sm-9">
                                  <select id="gender" name="gender" class="form-control custom-select" required>
                                    <option selected disabled>Select one</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Contact No:</label>
                                <div class="col-sm-9">
                                  <input name="contact" id="contact" type="tel" placeholder="Enter Contact No." class="form-control" maxlength="12" pattern="\d{11}" title="Please enter a valid contact number." required>

                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Address</label>
                                <div class="col-sm-9">
                                  <input id="address" name="address" type="text" class="form-control" placeholder="Address" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Occupation</label>
                                <div class="col-sm-9">
                                  <select name="occupation" id="occupation" class="form-control custom-select" required>
                                    <option selected disabled>Select one</option>
                                    <option>Student</option>
                                    <option>Employee</option>
                                    <option>Non-Employee</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                  <input id="email" name="email" type="email" class="form-control" placeholder="Email" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                  <input id="password" name="password" type="password" class="form-control" placeholder="Password" required>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tenant Profile</label>
                                <div class="col-sm-9">
                                  <div class="custom-file">
                                    <?php
                                    if (!empty($profile)) { ?>
                                      <img src="../../image/<?php echo $profile; ?>" style="display:none" alt="">
                                    <?php } ?>
                                    <input id="profile" name="profile" type="file" accept=".jpeg, .jpg, .png" class="custom-file-input">
                                    <label class="custom-file-label" for="profile">Choose Profile</label>

                                    <input id="current_profile" name="current_profile" type="hidden" class="custom-file-input" value="<?php echo $profile ?>">
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Parent Name</label>
                                <div class="col-sm-9">
                                  <input id="parentname" name="parentname" type="text" class="form-control" placeholder="Parent Name">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Parent Contact</label>
                                <div class="col-sm-9">
                                  <input id="parentcontact" name="parentcontact" type="tel" placeholder="Enter Contact No." maxlength="12" pattern="\d{11}" title="Please enter a valid contact number." class="form-control">
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

      $('select[name="occupation"]').on('change', function() {
        const isStudent = $(this).val() === 'Student';
        $('input[name="parentname"], input[name="parentcontact"]').prop('required', isStudent);
        if (!isStudent) {
          $('input[name="parentname"], input[name="parentcontact"]').val('');
        }
      });
    });
  </script>

</body>

</html>