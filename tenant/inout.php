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
              <h1>In / Out History</h1>
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
                        <th>No.</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Date & Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      $tenants_id = $_SESSION['tenants_id'];
                      $query = mysqli_query($conn, " SELECT  * FROM tenants INNER JOIN tenants_logs ON tenants.tenants_id = tenants_logs.tenants_id WHERE tenants.tenants_id = $tenants_id ORDER BY tenants_logs_id DESC ");
                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                      ?>
                        <tr>
                          <td style="width:1%"><?php echo $no ?></td>
                          <td><?php echo $name ?></td>
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