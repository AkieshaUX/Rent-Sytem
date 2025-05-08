<style>
  tr,
  td {
    text-transform: capitalize !important;
    white-space: nowrap !important;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  .fade-in {
    animation: fadeIn 1s ease-in-out;
  }

  .navbar-badge {
    font-size: .7rem;
    font-weight: 300;
    padding: 2px 4px;
    position: absolute;
    right: 118px;
    top: 11px;
  }

  #qr_input {
    background-color: rgba(255, 255, 255, 0);
  }

  #qr_input {
    opacity: 0;
  }
</style>
<div class="preloader flex-column justify-content-center align-items-center">
  <img class="animation__shake" src="../../dist/img/house_14860255.png" alt="AdminLTELogo" height="60" width="60">
</div>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="dashboard.php" class="nav-link">Home</a>
    </li>
    <form method="POST" id="scannerQR">
      <input type="text" name="qr_input" id="qr_input" style="height:0px; outline:0;width:0;" autofocus>
    </form>
  </ul>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link " data-admin-id="<?php echo $admin_id; ?>" data-toggle="modal" data-target="#curfewModal" id="timecurfew" style="cursor: pointer;color:#007bff">
        <i class="fa-solid fa-user"></i>
      </a>
    </li>
  </ul>
</nav>
<div class="modal fade" id="curfewModal" tabindex="-1" role="dialog" aria-labelledby="curfewModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width:440px" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="curfewModalLabel">Admin Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="curfewForm">
          <div class="form-group row">
            <input type="hidden" id="admin_id" name="admin_id">
            <label class="col-sm-3 col-form-label">User</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="adminuser" name="admin_user" required>
            </div>
          </div>
          <div class="form-group row">
            <input type="hidden" id="admin_id" name="admin_id">
            <label class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="adminpass" name="admin_pass">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Curfew Time</label>
            <div class="col-sm-9">
              <input type="time" class="form-control" id="curfew" name="curfews" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary" style="float: right;">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../../dist/img/house_14860255.png" alt="User Image" style="width: 2.6rem important;">
      </div>
      <div class="info">
        <a href="dashboard.php"" class=" d-block">ADMINISTRATOR</a>
      </div>
    </div>
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item menu-open" style="margin: 6px 0;">
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link active">
                <i class="fa-solid fa-chart-line nav-icon"></i>
                <p>Dashboard</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item " style="margin: 5px 0;">
          <a href="inbox.php" class="nav-link activeme">
            <?php
            $sql = "SELECT COUNT(*) AS total_sms FROM `inbox` WHERE `inbox_status` = 1";
            $query = $conn->query($sql);
            if ($query) {
              $result = $query->fetch_assoc();
              $total_sms = $result['total_sms'];
            ?>
              <i class="fa-solid fa-inbox nav-icon"></i>
              <p>
                Inbox <span class="badge badge-danger navbar-badge"><?php echo $total_sms ?></span>
              </p>
            <?php } ?>
          </a>
        </li>
        <li class="nav-item " style="margin: 5px 0;">
          <a href="tenants-list.php" class="nav-link activeme">
            <i class="fa-solid fa-user-group nav-icon"></i>
            <p>
              Tenants
            </p>
          </a>
        </li>
        <li class="nav-item " style="margin: 5px 0;">
          <a href="#" class="nav-link activeme">
            <i class="fa-solid fa-bed nav-icon"></i>
            <p>
              Room
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="room-list.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Room</p>
              </a>
            </li>
            <li class="nav-item " style="margin: 5px 0;">
              <a href="#" class="nav-link activeme">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Available Room
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="roommale.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Male</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="roomfemale.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Female</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="room-billing.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Room Billing </p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item" style="margin: 5px 0;">
          <a href="#" class="nav-link activeme">
            <i class="fa-solid fa-money-check-dollar nav-icon"></i>
            <p>
              Payment
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="payment-upcoming.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Payment</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="payment-pending.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pending Payment</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="payment-history.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Payment History</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item " style="margin: 5px 0;">
          <a href="In-Out.php" class="nav-link activeme">
            <i class="fa-solid fa-person-walking-arrow-loop-left nav-icon"></i>
            <p>
              In-Out Record
            </p>
          </a>
        </li>
        <li class="nav-item " style="margin: 5px 0;">
          <a href="withdraw-history.php" class="nav-link activeme">
            <i class="fa-solid fa-clock-rotate-left nav-icon"></i>
            <p>
              Withdraw History
            </p>
          </a>
        </li>
        <li class="nav-item" style="margin: 5px 0;">
          <a href="#" class="nav-link activeme">
            <i class="fa-solid fa-circle-info nav-icon"></i>
            <p>
              Reports
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="paymentreports.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Payment</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="billingreports.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Billing</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="maintenancereports.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Maintenance</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="withdrawreports.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Withdraw</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="inoutreports.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>In/Out</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>