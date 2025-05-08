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
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Inbox</h1>
            </div>
          </div>
        </div>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-3">
            <a href="compose.php" class="btn btn-primary btn-block mb-3">Compose</a>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Folders</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item active">
                    <a href="inbox.php" class="nav-link">
                      <?php
                      $countQuery = "SELECT COUNT(DISTINCT tenants_id) AS message_count FROM inbox";
                      $result = mysqli_query($conn, $countQuery);
                      $count = mysqli_fetch_assoc($result);
                      $ako = $count['message_count'];
                      ?>
                      <i class="fas fa-inbox"></i> Inbox
                      <span class="badge bg-primary float-right"><?php echo $ako ?></span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Inbox</h3>
              </div>
              <div class="card-body p-0">
                <div class="mailbox-controls">
                </div>
                <div class="table-responsive mailbox-messages">
                  <table class="table table-hover table-striped" id="inboxsection">
                    <tbody>
                      <?php
                      $view_sms_inbox = view_sms_inbox($conn);
                      while ($result = mysqli_fetch_array($view_sms_inbox)) {
                        extract($result);
                        date_default_timezone_set('Asia/Manila');
                        $hehe = $date;
                        $timestamp = strtotime($hehe);
                        $formattedDate = date('l g:i A', $timestamp);
                      ?>
                        <tr>
                          <td class="mailbox-name" style="width: 25%;">
                            <a href="view_compose.php?tenants_id=<?php echo $tenants_id ?>&admin_id=<?php echo $admin_id ?>">
                              <?php echo $name ?>
                            </a>
                          </td>
                          <td class="mailbox-subject">
                            <!-- <b><?php echo 'Room' . ' ' . $roomnumber . ' | ' . $roomtype ?></b> - -->
                            <?php
                            if (!empty($attachment)) {
                              echo "Sending Photo";
                            } else {
                              echo substr($content, 0, 50) . '...';
                            }
                            ?>
                          </td>
                          <td class="mailbox-attachment"></td>
                          <td style="width: 20%;" class="mailbox-date"><?php echo $formattedDate; ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="card-footer p-0">
                <div class="mailbox-controls">
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
        "autoWidth": false
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('.image-popup-vertical-fit').magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        mainClass: 'mfp-img-mobile',
        image: {
          verticalFit: true
        }
      });
      $('#selectName').select2({
        placeholder: 'Fullname',
      });
      $('#selectRoom').select2({
        placeholder: 'Room(#)',
      });
      $('#selectRoom').on('change', function() {
        var roomType = $(this).find(':selected').data('roomsmonthly');
        $('input[name="roomsmonthly"]').val(roomType);
      });
      $('table').removeClass('dataTable ');
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
  </script>
</body>

</html>