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
    .direct-chat-timestamp {
      color: #697582;
      font-size: 12px;
    }
    .card-footer {
      padding: 1.75rem 1.25rem !important;
    }
    .direct-chat-text {
      display: inline-block;
      border-radius: .3rem;
      background-color: #d2d6de;
      border: 1px solid #d2d6de;
      color: #444;
      margin: 5px 0 0 50px;
      padding: 5px 10px;
      position: relative;
      max-width: 75%;
      word-wrap: break-word;
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
      <?php
      if (isset($_GET['admin_id']) && $_GET['admin_id']) {
        $fetch_sms = mysqli_query($conn, "SELECT * FROM `inbox` WHERE `admin_id` = '$_GET[admin_id]' AND `tenants_id` = '$_GET[tenants_id]' ");
        while ($result = mysqli_fetch_array($fetch_sms)) {
          extract($result);
        }
      }
      ?>
      <section class="content" id="directchat_sms">
        <div class="row">
          <div class="col-md-3">
            <a href="compose.html" class="btn btn-primary btn-block mb-3">Compose</a>
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
            <div class="card direct-chat direct-chat-primary">
              <div class="card-header">
                <h3 class="card-title">Direct Chat</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="direct-chat-messages" id="chatMessages">
                  <?php
                  $display_sms = mysqli_query($conn, "SELECT inbox.*, tenants.*, inbox.inbox_status
                  FROM inbox 
                  INNER JOIN tenants ON inbox.tenants_id = tenants.tenants_id 
                  WHERE tenants.tenants_id = $tenants_id");
                  while ($result = mysqli_fetch_array($display_sms)) {
                    extract($result);
                    $isAdmin = ($admin_id == 1 && $inbox_status == 0);
                    if ($isAdmin) {
                  ?>
                      <div class="direct-chat-msg right">
                        <div class="direct-chat-infos clearfix">
                          <span class="direct-chat-timestamp float-right" style="margin-top: 1px;margin-right: 10px;"><?php echo date('D h:i A', strtotime($date)); ?></span>
                        </div>
                        <img class="direct-chat-img" src="../dist/img/user1-128x128.jpg" alt="message user image">
                        <?php if (!empty($content)) : ?>
                          <div class="direct-chat-text text-right right" style="float: right;margin-right: 30px !important;">
                            <?php echo htmlspecialchars($content); ?>
                          </div>
                        <?php endif; ?>
                        <?php if (!empty($attachment)) : ?>
                          <div class="attachment" style="margin-top: 10px; text-align: right;">
                            <a href="../image/<?php echo htmlspecialchars($attachment); ?>" class="gallery-item">
                              <img src="../image/<?php echo htmlspecialchars($attachment); ?>" alt="Attachment" style="max-width: 100%;max-height: 320px;margin-right: 30px">
                            </a>
                          </div>
                        <?php endif; ?>
                      </div>
                    <?php
                    } else {
                    ?>
                      <div class="direct-chat-msg">
                        <div class="direct-chat-infos clearfix">
                          <span class="direct-chat-name float-left"><a href="tenants-profile.php?tenants_id=<?php echo $tenants_id ?>" style="color: #000;"><?php echo htmlspecialchars($name); ?></a></span>
                          <span class="direct-chat-timestamp float-left" style="margin-top: 1px;margin-left: 10px;"><?php echo date('D h:i A', strtotime($date)); ?></span>
                        </div>
                        <img class="direct-chat-img" src="../image/<?php echo $profile ?>" alt="message user image">
                        <?php if (!empty($content)) : ?>
                          <div class="direct-chat-text" style="margin-left: 30px !important;">
                            <?php echo htmlspecialchars($content); ?>
                          </div>
                        <?php endif; ?>
                        <?php if (!empty($attachment)) : ?>
                          <div class="attachment" style="margin-top: 10px;">
                            <a href="../image/<?php echo htmlspecialchars($attachment); ?>" class="gallery-item">
                              <img src="../image/<?php echo htmlspecialchars($attachment); ?>" alt="Attachment" style="max-width: 100%;max-height: 320px;margin-left: 30px">
                            </a>
                          </div>
                        <?php endif; ?>
                      </div>
                  <?php
                    }
                  }
                  ?>
                </div>
              </div>
              <div class="card-footer">
                <form id="send_smsFormReplytoTenant" method="post">
                  <div class="input-group">
                    <input type="hidden" name="tenantID" value="<?php echo $tenants_id ?>">
                    <input type="hidden" name="adminID" value="<?php echo $admin_id ?>">
                    <input type="text" name="content" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                      <button type="submit" class="btn btn-primary">Send</button>
                    </span>
                  </div>
                  <div class="form-group pt-3">
                    <div class="btn btn-default btn-file">
                      <i class="fas fa-paperclip"></i> Attachment
                      <input type="file" name="attachment">
                    </div>
                    <p class="help-block">Max. 32MB</p>
                  </div>
                </form>
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
      function scrollToBottom() {
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
      }
      scrollToBottom();
      $('#chatMessages').on('DOMNodeInserted', function() {
        scrollToBottom();
      });
    });
  </script>
</body>
</html>