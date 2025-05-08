<?php
$conn = new mysqli('localhost', 'root', '', 'bh');

$conn->set_charset("utf8");
extract($_POST);
extract($_GET);



function display_tenants($conn)
{
  $query = mysqli_query($conn, "SELECT t.*, r.* FROM tenants t INNER JOIN room r ON t.room_id = r.room_id WHERE t.status = 1 ORDER BY t.tenants_id DESC");
  $tenantsWithStatus = [];
  while ($tenant = mysqli_fetch_array($query)) {
    $tenant_id = $tenant['tenants_id'];
    $payment_query = mysqli_query($conn, "SELECT * FROM rent WHERE tenants_id = '$tenant_id' AND status = 'Paid' AND confirmation = 0");
    $hasPending = mysqli_num_rows($payment_query) > 0 ? true : false;
    $tenant['hasPending'] = $hasPending;
    $tenantsWithStatus[] = $tenant;
  }
  return $tenantsWithStatus;
}


function view_details_tenants($conn, $tenants_id)
{
  $query = mysqli_query($conn, "SELECT tenants.*,room.* FROM tenants INNER JOIN rent ON tenants.tenants_id = rent.tenants_id INNER JOIN room ON rent.room_id = room.room_id WHERE tenants.tenants_id = '$tenants_id' AND tenants.status = 1  AND tenants.status NOT IN (3) ");
  $result = mysqli_fetch_array($query, MYSQLI_ASSOC);
  return $result;
}



function view_sms_header($conn)
{
  $query = mysqli_query($conn, " SELECT tenants.tenants_id, tenants.name, tenants.profile, inbox.admin_id, inbox.content, inbox.attachment, inbox.date FROM tenants INNER JOIN inbox ON tenants.tenants_id = inbox.tenants_id INNER JOIN room ON tenants.room_id = room.room_id INNER JOIN (SELECT tenants_id, MAX(date) AS max_date FROM inbox  WHERE inbox_status = 1 GROUP BY tenants_id) AS latest_inbox ON inbox.tenants_id = latest_inbox.tenants_id AND inbox.date = latest_inbox.max_date WHERE inbox.inbox_status = 1 ORDER BY inbox.date");
  return $query;
}

function view_sms_inbox($conn)
{
  $query = mysqli_query($conn, "SELECT tenants.tenants_id, tenants.name, tenants.profile, inbox.admin_id, inbox.content, inbox.attachment, inbox.date FROM tenants  INNER JOIN inbox ON tenants.tenants_id = inbox.tenants_id  INNER JOIN (SELECT tenants_id, MAX(date) AS max_date FROM inbox  WHERE inbox_status IN (1, 0)   GROUP BY tenants_id  ) AS latest_inbox ON inbox.tenants_id = latest_inbox.tenants_id AND inbox.date = latest_inbox.max_date   ORDER BY inbox.date");
  return $query;
}



function fetchup_coming_payment($conn)
{
  date_default_timezone_set('Asia/Manila');
  $today = new DateTime();
  $todayFormatted = $today->format('Y-m-d');
  $query = mysqli_query($conn, "SELECT tenants.*, room.*, rent.* FROM rent JOIN  tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id  WHERE rent.status = 'Unpaid' AND  DATE_FORMAT(rent.duedate, '%Y-%m-%d') = DATE_ADD('$todayFormatted', INTERVAL 3 DAY) ");
  return $query;
}



function fetch_duetoday_payment($conn)
{
  date_default_timezone_set('Asia/Manila');
  $today = new DateTime();
  $todayFormatted = $today->format('Y-m-d');
  $query = mysqli_query($conn, "SELECT tenants.*, room.*, rent.* FROM rent  JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id  WHERE rent.status = 'Unpaid' AND DATE_FORMAT(rent.duedate, '%Y-%m-%d') = '$todayFormatted' ");
  return $query;
}


function fetch_overdue_payment($conn)
{
  $query = mysqli_query($conn, "SELECT  tenants.*, room.*, rent.*  FROM rent  JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE rent.status = 'Unpaid' ORDER BY duedate  ");
  return $query;
}


function fetch_room_billing($conn)
{
  $query = mysqli_query($conn, "SELECT * FROM `room`");
  return $query;
}


function InOut($conn)
{
  $query = mysqli_query($conn, " SELECT  * FROM tenants INNER JOIN tenants_logs ON tenants.tenants_id = tenants_logs.tenants_id ORDER BY tenants_logs_id DESC ");
  return $query;
}
function InOut_tenants($conn)
{
  $query = mysqli_query($conn, " SELECT  * FROM tenants INNER JOIN tenants_logs ON tenants.tenants_id = tenants_logs.tenants_id WHERE tenants.tenants_id = $_GET[tenants_id] ORDER BY tenants_logs_id DESC ");
  return $query;
}
