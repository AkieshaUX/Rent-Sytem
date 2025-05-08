<?php include '../inc/sessions.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'includes/link.php' ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">ScanPay</h1>
            </div>
          </div>
        </div>
      </div>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="wrapme text-center">
                <?php
                $tenants_id = $tenants_id;
                $name = $name;
                $qrCodePath = "../dist/img/scanpay.png";
                $profileImagePath = "../dist/img/scanpay.png";
                ?>
                <img class="img-fluid" src="../gcash/<?php echo $qrCodePath ?>" alt="Photo" style="border-radius: 10px;">
                <div class="text-center">
                  <div class="downloadqrcode d-flex justify-content-center pt-3 pb-3">
                    <a href="<?php echo $qrCodePath; ?>" download="QRCODE_<?php echo $name; ?>.png">
                      <button class="btn btn-primary">Download QR Code</button>
                    </a>
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