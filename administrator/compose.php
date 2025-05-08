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
    .direct-chat-messages {
      -webkit-transform: translate(0, 0);
      transform: translate(0, 0);
      height: 420px !important;
      overflow: auto;
      padding: 10px;
    }
    .card-footer {
      padding: 1.75rem 1.25rem !important;
    }
    .select2-container .select2-selection--single {
      box-sizing: border-box;
      cursor: pointer;
      display: block;
      height: 40px !important;
      user-select: none;
      -webkit-user-select: none;
    }
  </style>
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
            <a href="inbox.php" class="btn btn-primary btn-block mb-3">Back to Inbox</a>
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
            <form id="send_smsForm" enctype="multipart/form-data">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Compose New Message</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <select id="selectName" class="form-control custom-select select2" required name="tenantID" style="width: 100%;">
                      <option selected disabled></option>
                      <?php
                      $query = mysqli_query($conn, "SELECT DISTINCT tenants.*, room.* FROM tenants JOIN rent ON tenants.tenants_id = rent.tenants_id JOIN room ON rent.room_id = room.room_id;");
                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                      ?>
                        <option value="<?php echo $tenants_id ?>">
                          <?php echo $name  ?>
                        </option>
                      <?php } ?>
                      <?php
                      $query = mysqli_query($conn, "SELECT * FROM `admin`");
                      while ($result = mysqli_fetch_array($query)) {
                        extract($result);
                      ?>
                        <input type="hidden" name="adminID" value="<?php echo $admin_id ?>">
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <textarea id="compose-textarea" placeholder="Type Message ..." name="content" required class="form-control" style="height: 200px"></textarea>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="float-right">
                    <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                  </div>
                  <div class="form-group">
                    <div class="btn btn-default btn-file">
                      <i class="fas fa-paperclip"></i> Attachment
                      <input type="file" name="attachment">
                    </div>
                    <p class="help-block">Max. 32MB</p>
                  </div>
                </div>
              </div>
            </form>
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
      $('#selectName').select2({
        placeholder: 'To:',
      });
      $('table').removeClass('dataTable ');
    });
  </script>
</body>
</html>