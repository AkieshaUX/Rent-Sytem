<?php include '../inc/sessions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'includes/link.php'; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php include 'includes/sidebar.php'; ?>
    <div class="content-wrapper">
      <?php
      $tenants_id = $_SESSION['tenants_id'];
      $query = mysqli_query($conn, "SELECT tenants.*, room.*, rent.* FROM rent  JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE tenants.tenants_id = $tenants_id AND tenants.status = 1");
      $result = mysqli_fetch_array($query);
      extract($result);
      ?>
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div>
          </div>
        </div>
      </div>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <h3 class="mb-4">Welcome to Our Boarding House!</h3>
                  <p class="lead">Dear <?php echo $name ?>,</p>
                  <p>We are thrilled to have you here! To ensure a comfortable and harmonious living environment for everyone, we have established some important rules. Please take a moment to read through these guidelines.</p>
                  <h4 class="mt-4">Boarding House Rules</h4>
                  <ul class="list-unstyled">
                    <li class="mb-3">
                      <h5 class="font-weight-bold">1. Respect Quiet Hours:</h5>
                      <p>Maintain a quiet environment from 10 PM to 7 AM.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">2. Keep Common Areas Clean:</h5>
                      <p>Always clean up after yourself in shared spaces.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">3. No Overnight Guests:</h5>
                      <p>Overnight guests are not permitted without prior approval.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">4. Limit Noise Levels:</h5>
                      <p>Use headphones when listening to music or watching videos.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">5. Follow Kitchen Rules:</h5>
                      <p>Clean dishes and utensils immediately after use.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">6. Smoking Policy:</h5>
                      <p>Smoking is strictly prohibited inside the premises.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">7. Report Maintenance Issues:</h5>
                      <p>Notify management immediately of any maintenance problems.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">8. Respect Personal Property:</h5>
                      <p>Do not use or borrow others' belongings without permission.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">9. Pay Rent on Time:</h5>
                      <p>Ensure that rent payments are made by the due date.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">10. Follow Emergency Procedures:</h5>
                      <p>Familiarize yourself with the emergency exits and procedures.</p>
                    </li>
                    <li class="mb-3">
                      <h5 class="font-weight-bold">11. Curfew Policy:</h5>
                      <?php
                      $curfew_time = $curfew;
                      $curfewnow = date("h: i A", strtotime($curfew_time));
                      ?>
                      <p>All tenants are required to be inside the boarding house by <?php echo $curfewnow ?> . Late arrivals must notify management in advance.</p>
                    </li>
                  </ul>
                  <p class="mt-4">Thank you for your cooperation, and we hope you enjoy your stay!</p>
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
  <?php include 'includes/script.php'; ?>
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