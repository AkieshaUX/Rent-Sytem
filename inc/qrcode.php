<?php
$conn = new mysqli('localhost', 'root', '', 'bh');

$apiKey = '52eeae903efd299bee45a37f7f6f56d3';
$sender = 'BoardSpot';
date_default_timezone_set('Asia/Manila');

if (isset($_POST['qr_input'])) {
  $qrCodeData = $_POST['qr_input'];
  $tenants_id = trim($qrCodeData);
  $sql = "SELECT action, name, contact FROM tenants WHERE tenants_id='$tenants_id'";
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentStatus = $row['action'];
    $name = $row['name'];
    $contact = $row['contact'];
    if ($currentStatus == 'IN') {
      $sql_update = "UPDATE tenants SET action='OUT' WHERE tenants_id='$tenants_id'";
      $conn->query($sql_update);
      $log_sql = "INSERT INTO tenants_logs (tenants_id, action, timestamp) VALUES ('$tenants_id', 'OUT', NOW())";
      $conn->query($log_sql);
      echo json_encode(['status' => 'success', 'message' => 'Tenant status updated to OUT']);
    } else {
      $sql_update = "UPDATE tenants SET action='IN' WHERE tenants_id='$tenants_id'";
      $conn->query($sql_update);
      $log_sql = "INSERT INTO tenants_logs (tenants_id, action, timestamp) VALUES ('$tenants_id', 'IN', NOW())";
      $conn->query($log_sql);
      echo json_encode(['status' => 'success', 'message' => 'Tenant status updated to IN']);
    }
    if ($currentStatus == 'OUT') {
      $currentTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
      $admin_id = 1;
      $curfewResult = $conn->query("SELECT curfew FROM admin WHERE admin_id = $admin_id");
      if ($curfewResult && $curfewResult->num_rows > 0) {
        $curfewRow = $curfewResult->fetch_assoc();
        $curfewTime = new DateTime($curfewRow['curfew'], new DateTimeZone('Asia/Manila'));
        if ($currentTime > $curfewTime) {
          $message = "Hello $name, you have entered past the curfew hour ({$curfewTime->format('h:i A')}). Please be mindful of the curfew. Thank you!";
          $postData = array(
            'apikey'     => $apiKey,
            'number'     => $contact,
            'message'    => $message,
            'sendername' => $sender
          );
          $ch = curl_init('https://api.semaphore.co/api/v4/messages');
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
          $response = curl_exec($ch);
          if ($response === false) {
            echo json_encode(['status' => 'error', 'message' => 'SMS sending failed: ' . curl_error($ch)]);
          } else {
            $responseData = json_decode($response, true);
            if (isset($responseData['status']) && $responseData['status'] === 'success') {
              echo json_encode(['status' => 'success', 'message' => "Curfew warning SMS sent to $name successfully!"]);
            } else {
              echo json_encode(['status' => 'error', 'message' => "Failed to send SMS to $name."]);
            }
          }
          curl_close($ch);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: Could not retrieve curfew time.']);
        exit();
      }
    }
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Tenant not found or database query failed.']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'QR input not set.']);
}
$conn->close();
