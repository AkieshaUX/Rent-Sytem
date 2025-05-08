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
              <h1>Remaining Payment</h1>
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
                  <table id="example1" class="table  table-striped">
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
                      $tenants_id = $_SESSION['tenants_id'];
                      $query = mysqli_query($conn, "SELECT  tenants.*, room.*, rent.* FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE  rent.status = 'Unpaid' AND rent.tenants_id = $tenants_id ORDER BY duedate ");
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