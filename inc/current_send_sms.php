<?php
$conn = new mysqli('localhost', 'root', '', 'bh');

date_default_timezone_set('Asia/Manila');
$today = new DateTime();
$todayFormatted = $today->format('Y-m-d');
$query = mysqli_query($conn, "SELECT tenants.*, room.*, rent.* FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id JOIN room ON room.room_id = rent.room_id WHERE rent.status = 'Unpaid' AND DATE_FORMAT(rent.duedate, '%Y-%m-%d') = '$todayFormatted'");

while ($result = mysqli_fetch_array($query)) {
  extract($result);
  $checkSmsQuery = mysqli_query($conn, "SELECT * FROM sms_log WHERE tenants_id = $tenants_id AND sent_date = '$todayFormatted' AND `status` = 1");
  if (mysqli_num_rows($checkSmsQuery) == 0) {
    $apiKey  = '52eeae903efd299bee45a37f7f6f56d3';
    $sender = 'BoardSpot';
    $recipient = '63' . substr($contact, 1);
    $message = 'Hello ' . $name . ', Today is the due date for your rent. Please make sure to complete the payment ' . date('M d, Y', strtotime($duedate)) . '.';
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
    if (curl_errno($ch)) {
      echo 'Error: ' . curl_error($ch);
    } else {
      $responseDecoded = json_decode($response, true);
      echo "SMS sent to $recipient\n";
      $logSmsQuery = mysqli_query($conn, "INSERT INTO sms_log (tenants_id, sent_date, `status`) VALUES ($tenants_id, '$todayFormatted', 1)");
      if (!$logSmsQuery) {
        echo 'Error logging SMS: ' . mysqli_error($conn);
      }
    }
    curl_close($ch);
  } else {
    echo "SMS already sent to tenant $name.\n";
  }
}
