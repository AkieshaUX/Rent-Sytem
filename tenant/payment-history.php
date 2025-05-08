<?php include '../inc/sessions.php' ?>
<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'includes/link.php' ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <style>
    #modal-payment-gcash .modal-dialog {
      max-width: 40rem !important;
      margin: 1.75rem auto;
    }

    .modal-body img {
      max-height: 80vh;
    }
  </style>
  <div class="wrapper">
    <?php include 'includes/sidebar.php' ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-3">
            <div class="col-sm-6">
              <h1>Payment History</h1>
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="example1" class="table  table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Due Date</th>
                          <th>Date of Payment</th>
                          <th>Monthly</th>
                          <th>Payment Type</th>
                          <th>Reference No:</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $tenants_id = $_SESSION['tenants_id'];
                        if (isset($_SESSION['tenants_id'])) {
                          $query = mysqli_query($conn, "SELECT  tenants.*, room.*, rent.* FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE  rent.status = 'Paid' AND `confirmation`= 1 AND rent.tenants_id = $tenants_id ORDER BY datepayment DESC");
                        }
                        while ($result = mysqli_fetch_array($query)) {
                          extract($result);
                        ?>
                          <tr>
                            <td><?php echo date('M d, Y', strtotime($duedate)); ?></td>
                            <td><?php echo date('M d, Y', strtotime($datepayment)); ?></td>
                            <td><?php echo $roomsmonthly ?></td>
                            <td><?php echo $type ?></td>
                            <td style="height: 70px;"> <a class="image-popup-vertical-fit" href="../gcash/<?php echo $receipt ?>">
                                <img style="width: 100% !important; max-height: 70px; object-fit: cover;" src="../gcash/<?php echo $receipt ?>" alt="">
                              </a></td>
                            <td class="project-state">
                              <span class="badge badge-success"><?php echo $status ?></span>
                            </td>
                          </tr>
                        <?php
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
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "ordering": false,
        "searching": true
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
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
      $("#gcash-receipt").change(function(event) {
        var x = URL.createObjectURL(event.target.files[0]);
        $("#preview-gcash").attr("src", x);
        $("#preview-gcashs").attr("src", x);
        console.log(event)
      });
    });
  </script>
</body>

</html>