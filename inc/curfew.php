<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'bh');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
date_default_timezone_set('Asia/Manila');
$today = new DateTime();
$todayFormatted = $today->format('Y-m-d');
$admin_id = 1;
$curfewResult = mysqli_query($conn, "SELECT curfew FROM admin WHERE admin_id = $admin_id");

if ($curfewResult && mysqli_num_rows($curfewResult) > 0) {
  $curfewRow = mysqli_fetch_assoc($curfewResult);
  $curfewTime = $curfewRow['curfew'];
  $curfewStart = DateTime::createFromFormat('H:i:s', $curfewTime);
  if ($curfewStart === false) {
    echo 'Curfew time format error.';
    exit();
  }
  $currentTime = new DateTime('now');
  $isCurfew = $currentTime >= $curfewStart;
  if ($isCurfew) {
    $query = mysqli_query($conn, "SELECT * FROM `tenants` WHERE `action` = 'OUT'");
    while ($result = mysqli_fetch_array($query)) {
      extract($result);
      $checkSmsQuery = mysqli_query($conn, "SELECT * FROM sms_log WHERE tenants_id = $tenants_id AND sent_date = '$todayFormatted' AND `status` = 2");
      if (mysqli_num_rows($checkSmsQuery) == 0) {
        $apiKey = '52eeae903efd299bee45a37f7f6f56d3';
        $sender = 'BoardSpot';
        $recipient = '63' . substr($contact, 1);
        $message = 'Hello ' . $name . ', you are currently OUT past curfew hour (' . date('g:i A', strtotime($curfewTime)) . '). Please be mindful of the curfew. Thank you!';
        $postData = array(
          'apikey'     => $apiKey,
          'number'     => $recipient,
          'message'    => $message,
          'sendername' => $sender
        );
        $ch = curl_init('https://api.semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $response = curl_exec($ch);
        if ($response === false) {
          echo 'SMS Sending failed: ' . curl_error($ch);
        } else {
          echo "API Response: $response\n";
          echo 'SMS sent successfully to tenant';
        }
        if (curl_errno($ch)) {
          echo 'CURL Error: ' . curl_error($ch);
        } else {
          $logSmsQuery = mysqli_query($conn, "INSERT INTO sms_log (tenants_id, sent_date, `status`) VALUES ($tenants_id, '$todayFormatted', 2)");
          if (!$logSmsQuery) {
            echo 'Error logging SMS: ' . mysqli_error($conn);
          } else {
            echo 'SMS log entry created successfully.';
          }
        }
        curl_close($ch);
      } else {
        echo "SMS already sent to tenant $name.\n";
      }
    }
  } else {
    echo "Current time is before curfew start time.";
  }
}
$conn->close();
