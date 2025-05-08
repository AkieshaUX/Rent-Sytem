<?php include '../inc/sessions.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'includes/link.php' ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
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
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Profile</h1>
            </div>
          </div>
        </div>
      </div>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3">
              <?php
              $tenants_id = $_SESSION['tenants_id'];
              $query = mysqli_query($conn, " SELECT tenants.*,room.*,rent.*  FROM rent 
              JOIN tenants ON rent.tenants_id = tenants.tenants_id 
              JOIN room ON room.room_id = rent.room_id 
               WHERE tenants.tenants_id = $tenants_id AND tenants.status = 1 AND rent.status = 'Unpaid' ");
              $result = mysqli_fetch_array($query);
              extract($result);
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
                    <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings </a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="settings">
                      <form id="edit_tenantsForm" method="POST" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" id="tenants_ids" name="haha" value="<?php echo $tenants_id ?>">
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Name</label>
                          <div class="col-sm-10">
                            <input id="name" type="text" class="form-control" name="name" placeholder="Name" required value="<?php echo $name ?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Gender</label>
                          <div class="col-sm-10">
                            <select id="gender" name="gender" class="form-control custom-select" required>
                              <option value="<?php echo $gender ?>"><?php echo $gender ?></option>
                              <option>Female</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Contact No:</label>
                          <div class="col-sm-10">
                            <input name="contact" id="contact" type="tel" placeholder="Enter Contact No." class="form-control" maxlength="11"
                              pattern="09\d{9}"
                              title="Please enter a valid 11-digit Philippine mobile number starting with 09." required value="<?php echo $contact ?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Address</label>
                          <div class="col-sm-10">
                            <input id="address" name="address" type="text" class="form-control" placeholder="Address" required value="<?php echo $address ?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Occupation</label>
                          <div class="col-sm-10">
                            <select name="occupation" id="occupation" class="form-control custom-select" required>
                              <option value="<?php echo $occupation ?>"><?php echo $occupation ?></option>
                              <option>Student</option>
                              <option>Employee</option>
                              <option>Non-Employee</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Email</label>
                          <div class="col-sm-10">
                            <input id="email" name="email" type="email" class="form-control" placeholder="Email" required value="<?php echo $email ?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Password</label>
                          <div class="col-sm-10">
                            <input style="position: relative;" id="password" name="password" type="password" class="form-control" placeholder="Password" required value="<?php echo $password ?>">
                            <a style="position: absolute;top: 10px; right: 20px;cursor: pointer;" id="toggleBtn" class="float-right  fas fa-eye-slash"></a>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Tenant Profile</label>
                          <div class="col-sm-10">
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
                          <label class="col-sm-2 col-form-label">Parent Name</label>
                          <div class="col-sm-10">
                            <input id="parentname" name="parentname" type="text" class="form-control" placeholder="Parent Name" value="<?php echo $parentname ?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label">Parent Contact</label>
                          <div class="col-sm-10">
                            <input id="parentcontact" name="parentcontact" type="tel" placeholder="Enter Contact No." maxlength="11"
                              pattern="09\d{9}"
                              title="Please enter a valid 11-digit Philippine mobile number starting with 09." class="form-control" value="<?php echo $parentcontact ?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-primary float-right">Save changes</button>
                          </div>
                        </div>
                      </form>
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
    $('#toggleBtn').click(function() {
      var passwordField = $('#password');
      var fieldType = passwordField.attr('type');
      if (fieldType === 'password') {
        passwordField.attr('type', 'text');
      } else {
        passwordField.attr('type', 'password');
      }
    });
  </script>
</body>

</html>