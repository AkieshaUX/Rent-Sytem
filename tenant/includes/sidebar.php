<style>
  tr,
  td {
    text-transform: capitalize;
    white-space: nowrap;
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
      <a href="dashboard.php"" class=" nav-link">Home</a>
    </li>
  </ul>
  <ul class="navbar-nav ml-auto">
</nav>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <div class="sidebar">
    <div class="form-inline pt-3">
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
        <li class="nav-item" style="margin: 5px 0;">
          <a href="profile.php" class="nav-link">
            <i class="fa-solid fa-user nav-icon"></i>
            <p>
              Profile
            </p>
          </a>
        </li>
        <li class="nav-item" style="margin: 5px 0;">
          <a href="communication.php" class="nav-link">
            <i class="fa-solid fa-inbox nav-icon"></i>
            <p>
              Inbox
            </p>
          </a>
        </li>
        <li class="nav-item" style="margin: 5px 0;">
          <a href="upcoming.php" class="nav-link">
            <i class="fa-regular fa-credit-card nav-icon"></i>
            <p>
              Payment
            </p>
          </a>
        </li>
        <li class="nav-item" style="margin: 5px 0;">
          <a href="unpaid.php" class="nav-link">
            <i class="fa-solid fa-credit-card nav-icon"></i>
            <p>
              Remaining
            </p>
          </a>
        </li>
        <li class="nav-item" style="margin: 5px 0;">
          <a href="payment-history.php" class="nav-link">
            <i class="fa-solid fa-circle-check nav-icon"></i>
            <p>
              Payment History
            </p>
          </a>
        </li>
        <li class="nav-item" style="margin: 5px 0;">
          <a href="payment-pending.php" class="nav-link">
            <i class="fa-solid fa-hourglass-start nav-icon"></i>
            <p>
              Pending Payment
            </p>
          </a>
        </li>
        <li class="nav-item" style="margin: 5px 0;">
          <a href="scanpay.php" class="nav-link">
            <i class="fa-solid fa-barcode nav-icon"></i>
            <p>
              ScanPay
            </p>
          </a>
        </li>
        <li class="nav-item" style="margin: 5px 0;">
          <a href="inout.php" class="nav-link">
            <i class="fa-solid fa-person-walking-arrow-right nav-icon"></i>
            <p>
              In/Out
            </p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>