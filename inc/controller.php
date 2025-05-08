<?php
require '../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

session_start();
$conn = new mysqli('localhost', 'root', '', 'bh');
$conn->set_charset("utf8");
extract($_POST);
extract($_GET);
extract($_SESSION);

if (isset($_POST['register_btn'])) {
  $checkAdminQuery = "SELECT COUNT(*) as count FROM `admin`";
  $result = $conn->query($checkAdminQuery);
  $row = $result->fetch_assoc();
  if ($row['count'] == 0) {
    $register = "INSERT INTO `admin`(`admin_user`, `admin_pass`,`curfew`) VALUES ('$admin_user', '$admin_pass','22:00:00')";
    $registerquery = $conn->query($register);
    if ($registerquery) {
      header('Location: ../administrator/index.php');
      exit();
    }
  } else {
    header('Location: ../administrator/index.php');
  }
}


if (isset($_POST['login_btn'])) {
  $admin_user = $_POST['admin_user'];
  $admin_pass = $_POST['admin_pass'];
  $stmt = $conn->prepare("SELECT * FROM `admin` WHERE `admin_user` = ?");
  $stmt->bind_param("s", $admin_user);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    if ($admin_pass == $admin['admin_pass']) {
      $_SESSION['admin_id'] = $admin['admin_id'];
      $_SESSION['admin_user'] = $admin['admin_user'];
      $_SESSION['curfew'] = $admin['curfew'];
      header('Location: ../administrator/dashboard.php');
      exit();
    } else {
      header('Location: ../administrator?invalid');
      exit();
    }
  } else {
    header('Location: ../administrator?invalid');
    exit();
  }
  $stmt->close();
}


if (isset($_GET['admin_id'])) {
  $admin_id = $_GET['admin_id'];
  $sql = "SELECT * FROM `admin` WHERE `admin_id` = $admin_id";
  $result = $conn->query($sql);
  echo json_encode($result->fetch_assoc());
}


if (isset($_GET['room_id'])) {
  $room_id = $_GET['room_id'];
  $sql = "SELECT * FROM `room` WHERE `room_id` = '$room_id'";
  $result = $conn->query($sql);
  echo json_encode($result->fetch_assoc());
}


if (isset($_GET['deleteroom'])) {
  $room_id = $_GET['room_id'];
  $sql = "UPDATE `room` SET `room_status`='Deleted' WHERE `room_id` = '$room_id'";
  $query = $conn->query($sql);
}


if (isset($curfewForm)) {
  $sql = "UPDATE `admin` SET `admin_user`='$admin_user',`admin_pass`='$admin_pass',`curfew`='$curfews' WHERE `admin_id` = $admin_id";
  $query = $conn->query($sql);
}

if (isset($register_tenants)) {
  $fileName = $_FILES['profile']['name'];
  $fileTemp = $_FILES['profile']['tmp_name'];
  $exp = explode(".", $fileName);
  $extension = end($exp);
  $newFileName = time() . "." . $extension;
  move_uploaded_file($fileTemp, "../image/" . $newFileName);
  $sql = "INSERT INTO `tenants`(`name`, `gender`, `contact`, `address`, `occupation`, `email`, `password`, `profile`, `parentname`, `parentcontact`,`status`,`action`) VALUES ('$name','$gender','$contact','$address','$occupation','$email','$password','$newFileName','$parentname','$parentcontact',0,'IN')";
  $query = $conn->query($sql);
  if ($query) {
    $tenants_id = $conn->insert_id;
    $qrData = $tenants_id;
    $qrCode = QrCode::create($qrData);
    $writer = new PngWriter();
    $qrCodePath = '../qrcodes/tenant_' . $tenants_id . '.png';
    $result = $writer->write($qrCode);
    $result->saveToFile($qrCodePath);
    echo "Registration successful. QR code generated.";
  } else {
    echo "Error: " . $conn->error;
  }
}



if (isset($_GET['tenants_id'])) {
  $tenants_id = $_GET['tenants_id'];
  $sql = "SELECT * FROM `tenants` WHERE `tenants_id` = $tenants_id";
  $result = $conn->query($sql);
  echo json_encode($result->fetch_assoc());
}


if (isset($edit_tenantsForm)) {
  if (!empty($_FILES['profile']['name'])) {
    $fileName = $_FILES['profile']['name'];
    $fileTemp = $_FILES['profile']['tmp_name'];
    $exp = explode(".", $fileName);
    $extension = end($exp);
    $newFileName = time() . "." . $extension;
    move_uploaded_file($fileTemp, "../image/" . $newFileName);
  } else {
    $newFileName = $_POST['current_profile'];
  }
  $sql = "UPDATE `tenants` SET `name`='$name',`gender`='$gender',`contact`='$contact',`address`='$address',`occupation`='$occupation',`email`='$email',`password`='$password',`profile`='$newFileName',`parentname`='$parentname',`parentcontact`='$parentcontact' WHERE `tenants_id` = '$haha'";
  $query = $conn->query($sql);
  if ($query) {
    $qrData = $tenants_id;
    $qrCode = QrCode::create($qrData);
    $writer = new PngWriter();
    $qrCodePath = '../qrcodes/tenant_' . $haha . '.png';
    $result = $writer->write($qrCode);
    $result->saveToFile($qrCodePath);
    echo "Tenant information updated and QR code regenerated.";
  } else {
    echo "Error: " . $conn->error;
  }
}

if (isset($_GET['removetenants'])) {
  $tenants_id = $_GET['tenants_id'];
  $room_id = $_GET['room_id'];
  $sql = "UPDATE `tenants` SET `room_id` = NULL, `status` = 0 WHERE `tenants_id` = '$tenants_id' AND `room_id` = '$room_id'";
  $query = $conn->query($sql);
  if ($query) {
    $delete = "DELETE FROM `rent` WHERE `tenants_id` = '$tenants_id' AND `room_id` = '$room_id' AND `status` = 'Unpaid'";
    $query = $conn->query($delete);
  }
}

// ==================================ROOM CONTROL===================================


if (isset($addroomForm)) {
  $fileName = $_FILES['roomimage']['name'];
  $fileTemp = $_FILES['roomimage']['tmp_name'];
  $exp = explode(".", $fileName);
  $extension = end($exp);
  $newFileName = time() . "." . $extension;
  move_uploaded_file($fileTemp, "../image/" . $newFileName);
  $sql = "INSERT INTO `room`(`roomnumber`, `roomdesciption`, `roomtype`,`roomgender`, `maximum`, `roomimage`, `roomsmonthly`)
   VALUES ('$roomnumber','$roomdesciption','$roomtype','$roomgender','$maximum','$newFileName','$roomsmonthly')";
  $query = $conn->query($sql);
}


if (isset($editroomForm)) {
  $tenantCount = 0;
  $queryCount = mysqli_query($conn, "SELECT COUNT(DISTINCT tenants.tenants_id) AS tenant_count FROM rent  INNER JOIN tenants ON tenants.tenants_id = rent.tenants_id   WHERE rent.room_id = $room_id AND tenants.status = 1 AND rent.status = 'Unpaid'");
  if ($countResult = mysqli_fetch_assoc($queryCount)) {
    $tenantCount = $countResult['tenant_count'];
  }

  if ($maximum < $tenantCount) {
    echo json_encode(["status" => "error", "message" => "Cannot reduce the room capacity below the current tenant count."]);
    return;
  }

  if (!empty($_FILES['roomimage']['name'])) {
    $fileName = $_FILES['roomimage']['name'];
    $fileTemp = $_FILES['roomimage']['tmp_name'];
    $exp = explode(".", $fileName);
    $extension = end($exp);
    $newFileName = time() . "." . $extension;
    move_uploaded_file($fileTemp, "../image/" . $newFileName);
  } else {
    $newFileName = $_POST['current_room'];
  }

  $sql = "UPDATE `room` SET `roomnumber`='$roomnumber',`roomgender` = '$roomgender',`roomtype`='$roomtype',`maximum`='$maximum',`roomimage`='$newFileName',`roomsmonthly`='$roomsmonthly' WHERE `room_id` = '$room_id'";
  $query = $conn->query($sql);
  $sql1 = "UPDATE `rent` SET `roomsmonthly`='$roomsmonthly' WHERE `room_id` = '$room_id'";
  $query1 = $conn->query($sql1);

  echo json_encode(["status" => "success", "message" => "Room data updated!"]);
}






if (isset($assigntenantForm)) {
  date_default_timezone_set('Asia/Manila');
  $today = date("Y-m-d");
  $todaynow = $today;
  $roomsmonthly = floatval($roomsmonthly);
  $deposit = floatval($deposit);
  $term = intval($term);

  $depositMonths = intval($deposit / $roomsmonthly);
  $depositPerMonth = $depositMonths > 0 ? $roomsmonthly : 0;

  $sqlAdvancePayment = "INSERT INTO `payment_income`(`amount`,`withdraw`,`status`) VALUES ('$roomsmonthly', '$roomsmonthly', 1)";
  $queryAdvancePayment = $conn->query($sqlAdvancePayment);

  if ($deposit > 0) {
    for ($i = 1; $i <= $depositMonths; $i++) {
      $sqlDepositPayment = "INSERT INTO `payment_income`(`amount`,`withdraw`,`status`) VALUES ('$depositPerMonth', '$depositPerMonth', 1)";
      $queryDepositPayment = $conn->query($sqlDepositPayment);
      if (!$queryDepositPayment) {
        echo "Error in Deposit SQL: " . $conn->error;
      }
    }
  }

  for ($i = 1; $i <= $term; $i++) {
    $started = date("Y-m-d", strtotime($today . " + $i months"));
    $duedate = date("Y-m-d", strtotime($started . " + 1 months"));
    if ($i == 1) {
      $status = 'Paid';
      $advancePaymentValue = $roomsmonthly;
      $depositValue = 0;
    } elseif ($i <= $depositMonths + 1) {
      $status = 'Paid';
      $advancePaymentValue = 0;
      $depositValue = $depositPerMonth;
    } else {
      $status = 'Unpaid';
      $advancePaymentValue = 0;
      $depositValue = 0;
    }
    $sql1 = "INSERT INTO `rent`(`tenants_id`, `room_id`, `roomsmonthly`, `term`, `payment`, `deposit`, `started`, `duedate`, `datepayment`, `type`, `status`, `confirmation`)  VALUES ('$tenanupdateID', '$room_id', '$roomsmonthly', '$term', '$advancePaymentValue', '$depositValue', '$todaynow', '$started', " .
      ($status === 'Unpaid' ? "NULL" : "'$todaynow'") . ", " .
      ($status === 'Paid' ? "'Cash'" : "NULL") . ", '$status', " .
      ($status === 'Paid' ? "1" : "NULL") . ")";
    $query1 = $conn->query($sql1);
    if (!$query1) {
      echo "Error in Rent SQL: " . $conn->error;
    }
  }

  $sql2 = "UPDATE `tenants` SET `room_id`='$room_id', `status`= 1 WHERE `tenants_id` = '$tenanupdateID'";
  $query2 = $conn->query($sql2);
  if (!$query2) {
    echo "Error in Tenant Update SQL: " . $conn->error;
  }
}
// ==================================TENANT DASHBOARD CONTROL===================================

if (isset($_POST['tenantLoginBtn'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM `tenants` WHERE `email` = ? AND `password` = ?");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $tenant = $result->fetch_assoc();

    if ($tenant['status'] == 0) {
      header('location: ../index.php?deactivated');
      exit();
    }

    $_SESSION['tenants_id'] = $tenant['tenants_id'];
    header('location: ../tenant/dashboard.php');
    exit();
  } else {

    header('location: ../index.php?invalid');
    exit();
  }
  $stmt->close();
}
// ==================================COMMUNICATION CONTROL===================================


if (isset($send_smsForm)) {
  date_default_timezone_set('Asia/Manila');
  $fileName = $_FILES['attachment']['name'];
  $fileTemp = $_FILES['attachment']['tmp_name'];
  move_uploaded_file($fileTemp, "../image/" . $fileName);
  $currentDateTime12 = date('Y-m-d h:i:s A');
  $sql = "INSERT INTO `inbox`(`admin_id`, `tenants_id`, `content`, `attachment`, `date`, `inbox_status`) VALUES ('$adminID','$tenantID','$content','$fileName','$currentDateTime12', 0)";
  $query = $conn->query($sql);
}



if (isset($send_smsFormReplytoTenant)) {
  date_default_timezone_set('Asia/Manila');
  $fileName = $_FILES['attachment']['name'];
  $fileTemp = $_FILES['attachment']['tmp_name'];
  move_uploaded_file($fileTemp, "../image/" . $fileName);
  $currentDateTime12 = date('Y-m-d h:i:s A');
  $sql = "INSERT INTO `inbox`(`admin_id`, `tenants_id`, `content`, `attachment`, `date`, `inbox_status`) VALUES ('$adminID','$tenantID','$content','$fileName','$currentDateTime12', 0)";
  $query = $conn->query($sql);
}

if (isset($send_smsFormReplytoAdmin)) {
  date_default_timezone_set('Asia/Manila');
  $fileName = $_FILES['attachment']['name'];
  $fileTemp = $_FILES['attachment']['tmp_name'];
  move_uploaded_file($fileTemp, "../image/" . $fileName);
  $currentDateTime12 = date('Y-m-d h:i:s A');
  $sql = "INSERT INTO `inbox`(`admin_id`, `tenants_id`, `content`, `attachment`, `date`, `inbox_status`) VALUES ('$adminID','$tenantID','$content','$fileName','$currentDateTime12', 1)";
  $query = $conn->query($sql);
}




if (isset($_GET['rent_id'])) {
  $rent_id = intval($_GET['rent_id']);
  $sql = "SELECT rent.*, tenants.name, tenants.contact, tenants.occupation, tenants.parentcontact, room.roomtype, room.roomnumber FROM rent JOIN tenants ON rent.tenants_id = tenants.tenants_id  JOIN room ON room.room_id = rent.room_id WHERE rent.rent_id = $rent_id";
  $result = $conn->query($sql);
  echo json_encode($result->fetch_assoc());
}



if (isset($GCASH_payment) || isset($CASH_payment)) {
  $paymentType = isset($GCASH_payment) ? 'Gcash' : 'Cash';
  $apiKey = '52eeae903efd299bee45a37f7f6f56d3';
  $sender = 'BoardSpot';
  $recipient = '63' . substr($contact, 1);
  $parentRecipient = isset($parentcontact) ? '63' . substr($parentcontact, 1) : null;

  if (isset($GCASH_payment)) {
    $fileName = $_FILES['gcash']['name'];
    $fileTemp = $_FILES['gcash']['tmp_name'];
    $exp = explode(".", $fileName);
    $extension = end($exp);
    $newFileName = time() . "." . $extension;
    move_uploaded_file($fileTemp, '../gcash/' . $newFileName);
  }
  $today = date("Y-m-d");

  $updateSql = "UPDATE `rent` SET `payment`='$amount', `datepayment`='$today', `type`='$paymentType', `status`='Paid', `confirmation`=1";
  $updateSql .= isset($GCASH_payment) ? ", `receipt`='$newFileName'" : "";
  $updateSql .= " WHERE `rent_id` = '$rentId'";
  $query = $conn->query($updateSql);

  $incomeSql = "INSERT INTO `payment_income`(`amount`, `withdraw`, `status`) VALUES ('$amount', '$amount', 1)";
  $query2 = $conn->query($incomeSql);
  if ($query && $query2) {
    $tenantIdQuery = "SELECT `tenants_id` FROM `rent` WHERE `rent_id` = '$rentId'";
    $tenantIdResult = $conn->query($tenantIdQuery);
    if ($tenantIdResult && $tenantIdResult->num_rows > 0) {
      $tenantId = $tenantIdResult->fetch_assoc()['tenants_id'];

      $countUnpaidQuery = "SELECT COUNT(*) as unpaid_count FROM `rent` WHERE `tenants_id` = '$tenantId' AND `status` = 'Unpaid'";
      $countResult = $conn->query($countUnpaidQuery);
      $unpaidCount = $countResult->fetch_assoc()['unpaid_count'];

      $tenantStatus = $unpaidCount > 0 ? 1 : 0;
      $tenantUpdateSql = "UPDATE `tenants` SET `status` = $tenantStatus";
      $tenantUpdateSql .= $tenantStatus == 0 ? ", `room_id` = NULL" : "";
      $tenantUpdateSql .= " WHERE `tenants_id` = '$tenantId'";
      $conn->query($tenantUpdateSql);

      $message = "Hello $name, your rent payment of PHP $amount has been successfully received via $paymentType. Thank you!";
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
        echo 'SMS sent successfully to tenant';
      }

      if ($occupation === 'Student' && $parentRecipient) {
        $parentMessage = "Dear Parent, we confirm the rent payment of PHP $amount for $name has been received. Thank you!";
        $postData['number'] = $parentRecipient;
        $postData['message'] = $parentMessage;
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $parentResponse = curl_exec($ch);
      }

      if ($parentResponse === false) {
        echo 'SMS Sending failed to parent: ' . curl_error($ch);
      } else {
        echo 'SMS sent successfully to parent';
      }
    }
    curl_close($ch);
  } else {
    echo "Error in updating payment status: " . $conn->error;
  }
}



if (isset($GCASH_payment_tenants)) {

  $apiKey = '52eeae903efd299bee45a37f7f6f56d3';
  $sender = 'BoardSpot';
  $recipient = '63' . substr($contact, 1);
  $parentRecipient = isset($parentcontact) ? '63' . substr($parentcontact, 1) : null;

  $fileName = $_FILES['gcash']['name'];
  $fileTemp = $_FILES['gcash']['tmp_name'];
  $exp = explode(".", $fileName);
  $extension = end($exp);
  $newFileName = time() . "." . $extension;
  move_uploaded_file($fileTemp, '../gcash/' . $newFileName);
  $today = date("Y-m-d");

  $sql = "UPDATE `rent` SET `payment`='$amount', `datepayment`='$today', `type`='Gcash', `receipt`='$newFileName', `status`='Paid', `confirmation`=0 WHERE `rent_id` = '$rentId'";
  $query = $conn->query($sql);

  $sql2 = "INSERT INTO `payment_income`(`rent_id`,`amount`, `withdraw`, `status`) VALUES ('$rentId','$amount', '$amount', 0)";
  $query2 = $conn->query($sql2);
  if ($query && $query2) {

    $message = 'Hello ' . $name . ', your rent payment of PHP ' . $amount . ' has been received and is awaiting admin approval. Thank you for your patience!';
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
      echo 'SMS sent successfully to tenant';
    }

    if ($occupation === 'Student' && $parentRecipient) {
      $parentMessage = 'Dear Parent, we confirm the rent payment of PHP ' . $amount . ' for ' . $name . ' has been received and is awaiting admin approval. Thank you for your patience!';
      $postData['number'] = $parentRecipient;
      $postData['message'] = $parentMessage;
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
      $parentResponse = curl_exec($ch);
      if ($parentResponse === false) {
        echo 'SMS Sending failed to parent: ' . curl_error($ch);
      } else {
        echo 'SMS sent successfully to parent';
      }
    }
    curl_close($ch);
  } else {
    echo "Error in updating payment status: " . $conn->error;
  }
}


if (isset($_GET['approve'])) {
  $rentId = $_GET['rent_id'];
  $apiKey = '52eeae903efd299bee45a37f7f6f56d3';
  $sender = 'BoardSpot';
  $query = mysqli_query($conn, "SELECT tenants.contact, tenants.name, tenants.occupation, tenants.parentcontact FROM tenants JOIN rent ON tenants.tenants_id = rent.tenants_id WHERE rent.rent_id = '$rentId'");
  $tenant = mysqli_fetch_assoc($query);
  $contact = $tenant['contact'];
  $name = $tenant['name'];
  $parentcontact = $tenant['parentcontact'];
  $occupation = $tenant['occupation'];
  $recipient = '63' . substr($contact, 1);
  $parentRecipient = isset($parentcontact) ? '63' . substr($parentcontact, 1) : null;
  $sql = "UPDATE `rent` SET `confirmation`= 1 WHERE `rent_id` = '$rentId'";
  $query = $conn->query($sql);
  $sql2 = "UPDATE `payment_income` SET `status`=1 WHERE `rent_id` = '$rentId'";
  $query2 = $conn->query($sql2);
  if ($query && $query2) {
    $tenantIdQuery = "SELECT `tenants_id` FROM `rent` WHERE `rent_id` = '$rentId'";
    $tenantIdResult = $conn->query($tenantIdQuery);
    $tenantId = $tenantIdResult->fetch_assoc()['tenants_id'];
    $countUnpaidQuery = "SELECT COUNT(*) as unpaid_count FROM `rent` WHERE `tenants_id` = '$tenantId' AND `status` = 'Unpaid'";
    $countResult = $conn->query($countUnpaidQuery);
    $unpaidCount = $countResult->fetch_assoc()['unpaid_count'];
    if ($unpaidCount > 0) {
      $updateTenantQuery = "UPDATE `tenants` SET `status` = 1 WHERE `tenants_id` = '$tenantId'";
    } else {
      $updateTenantQuery = "UPDATE `tenants` SET `room_id`= NULL, `status` = 0 WHERE `tenants_id` = '$tenantId'";
    }
    $conn->query($updateTenantQuery);
    $message = 'Hello ' . $name . ', your rent payment has been approved. Thank you!';
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
      echo 'SMS sent successfully to tenant';
    }
    if ($occupation === 'Student' && $parentRecipient) {
      $parentMessage = 'Dear Parent, we confirm the rent payment for ' . $name . ' has been approved. Thank you!';
      $postData['number'] = $parentRecipient;
      $postData['message'] = $parentMessage;
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
      $parentResponse = curl_exec($ch);
      if ($parentResponse === false) {
        echo 'SMS Sending failed to parent: ' . curl_error($ch);
      } else {
        echo 'SMS sent successfully to parent';
      }
    }
    if ($parentResponse === false) {
      echo 'SMS Sending failed to parent: ' . curl_error($ch);
    } else {
      echo 'SMS sent successfully to parent';
    }
    curl_close($ch);
  } else {
    echo "Error in updating payment status: " . $conn->error;
  }
}


if (isset($_GET['rentdecline'])) {
  $rentId = $_GET['rent_id'];
  $apiKey = '52eeae903efd299bee45a37f7f6f56d3';
  $sender = 'BoardSpot';
  $query = mysqli_query($conn, "SELECT tenants.contact, tenants.name, tenants.occupation, tenants.parentcontact FROM tenants JOIN rent ON tenants.tenants_id = rent.tenants_id WHERE rent.rent_id = '$rentId'");
  $tenant = mysqli_fetch_assoc($query);
  if ($tenant) {
    $contact = $tenant['contact'];
    $name = $tenant['name'];
    $occupation = $tenant['occupation'];
    $parentcontact = $tenant['parentcontact'];
    $recipient = '63' . substr($contact, 1);
    $parentRecipient = isset($parentcontact) ? '63' . substr($parentcontact, 1) : null;
    $messages = 'Hello ' . $name . ', your rent payment has been declined due to an invalid reference number. Thank you!';
    $postData = array(
      'apikey'     => $apiKey,
      'number'     => $recipient,
      'message'    => $messages,
      'sendername' => $sender
    );
    $ch = curl_init('https://api.semaphore.co/api/v4/messages');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    if ($response === false) {
      echo 'SMS Sending failed: ' . curl_error($ch);
    } else {
      echo 'SMS sent successfully to tenant';
    }
    if ($occupation === 'Student' && $parentRecipient) {
      $parentMessage = 'Dear Parent, we inform you that the rent payment for ' . $name . ' has been declined. Please check for further details.';
      $postData['number'] = $parentRecipient;
      $postData['message'] = $parentMessage;
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
      $parentResponse = curl_exec($ch);
    }
    if ($parentResponse === false) {
      echo 'SMS Sending failed to parent: ' . curl_error($ch);
    } else {
      echo 'SMS sent successfully to parent';
    }
    curl_close($ch);
  } else {
    echo "Tenant details not found.";
  }
}



if (isset($_GET['maintenance_id'])) {
  $maintenance_id = intval($_GET['maintenance_id']);
  $sql = "SELECT room.*, maintenance.* FROM `maintenance` INNER JOIN room ON maintenance.room_id = room.room_id WHERE maintenance.maintenance_id = $maintenance_id";
  $result = $conn->query($sql);
  echo json_encode($result->fetch_assoc());
}



if (isset($roomMaintenance)) {
  $sql = "SELECT SUM(`withdraw`) as totalAvailable FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  $totalAvailable = $row['totalAvailable'];
  if ($amount > $totalAvailable) {
    echo json_encode(['status' => 'error', 'message' => 'Not enough available funds to complete the room maintenance process.']);
    return;
  }
  $insertmaintenance = "INSERT INTO `maintenance` (`room_id`, `issue`, `amount`, `date`) VALUES ('$room_id', '$issue', '$amount','$date')";
  $query = $conn->query($insertmaintenance);
  $last_id = mysqli_insert_id($conn);
  $totalWithdrawn = 0;
  do {
    $sql = "SELECT * FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1 LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_array();
    $availableAmount = $row['withdraw'];
    if ($amount <= $availableAmount) {
      $totalWithdrawn += $amount;
      $totalamount = $availableAmount - $amount;
      $update = "UPDATE `payment_income` SET `withdraw`='$totalamount' WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = 0;
    } else {
      $totalWithdrawn += $availableAmount;
      $newtotalamount = $amount - $availableAmount;
      $update = "UPDATE `payment_income` SET `withdraw`= 0 WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = $newtotalamount;
    }
  } while ($amount != 0);
  if ($totalWithdrawn > 0) {
    $insert = "INSERT INTO `withdraw`(`maintenance_id`, `amount`, `type`, `date`) VALUES ('$last_id', '$totalWithdrawn', 'Room Maintenance', NOW())";
    $inquery = $conn->query($insert);
  }
  echo json_encode(['status' => 'success', 'message' => 'Room maintenance request submitted successfully!']);
}



if (isset($EditroomMaintenance)) {
  $sql = "SELECT SUM(`withdraw`) as totalAvailable FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  $totalAvailable = $row['totalAvailable'];
  if ($amount > $totalAvailable) {
    echo json_encode(['status' => 'error', 'message' => 'Not enough available funds to complete the room maintenance update.']);
    return;
  }
  $sql1 = "UPDATE `maintenance` SET `room_id`='$room_id', `issue`='$issue', `amount`='$amount',`date`='$date' WHERE `maintenance_id` = '$maintenance_id'";
  $query = $conn->query($sql1);
  $sql2 = "INSERT INTO `payment_income`(`amount`, `withdraw`, `status`) VALUES (0, '$oldamount', 1)";
  $query = $conn->query($sql2);
  $totalWithdrawn = 0;
  do {
    $sql = "SELECT * FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1 LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_array();
    $availableAmount = $row['withdraw'];
    if ($amount <= $availableAmount) {
      $totalWithdrawn += $amount;
      $totalamount = $availableAmount - $amount;
      $update = "UPDATE `payment_income` SET `withdraw`='$totalamount' WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = 0;
    } else {
      $totalWithdrawn += $availableAmount;
      $newtotalamount = $amount - $availableAmount;
      $update = "UPDATE `payment_income` SET `withdraw`= 0 WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = $newtotalamount;
    }
  } while ($amount != 0);
  if ($totalWithdrawn > 0) {
    $insert = "UPDATE `withdraw` SET `amount`='$totalWithdrawn', `type`='Room Maintenance' WHERE `maintenance_id` = '$maintenance_id'";
    $inquery = $conn->query($insert);
  }
  echo json_encode(['status' => 'success', 'message' => 'Room maintenance processed successfully!']);
}




if (isset($_GET['room_bill_id'])) {
  $room_bill_id = intval($_GET['room_bill_id']);
  $sql = "SELECT  room.*, room_bill.* FROM `room_bill` INNER JOIN room ON room_bill.room_id =  room.room_id WHERE room_bill.room_bill_id = $room_bill_id";
  $result = $conn->query($sql);
  echo json_encode($result->fetch_assoc());
}


if (isset($roombillingFORM)) {
  $sql = "SELECT SUM(`withdraw`) as totalAvailable FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  $totalAvailable = $row['totalAvailable'];
  if ($amount > $totalAvailable) {
    echo json_encode(['status' => 'error', 'message' => 'Not enough available funds to complete the room billing process.']);
    return;
  }
  $insertmaintenance = "INSERT INTO `room_bill` (`room_id`, `billtype`, `amount`, `date`) VALUES ('$room_id', '$billtype', '$amount','$date')";
  $query = $conn->query($insertmaintenance);
  $last_id = mysqli_insert_id($conn);
  $totalWithdrawn = 0;
  do {
    $sql = "SELECT * FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1 LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_array();
    $availableAmount = $row['withdraw'];
    if ($amount <= $availableAmount) {
      $totalWithdrawn += $amount;
      $totalamount = $availableAmount - $amount;
      $update = "UPDATE `payment_income` SET `withdraw`='$totalamount' WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = 0;
    } else {
      $totalWithdrawn += $availableAmount;
      $newtotalamount = $amount - $availableAmount;
      $update = "UPDATE `payment_income` SET `withdraw`= 0 WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = $newtotalamount;
    }
  } while ($amount != 0);
  if ($totalWithdrawn > 0) {
    $insert = "INSERT INTO `withdraw`(`room_bill_id`, `amount`, `type`, `date`) VALUES ('$last_id', '$totalWithdrawn', '$billtype', NOW())";
    $inquery = $conn->query($insert);
  }
  echo json_encode(['status' => 'success', 'message' => 'Room bill processed successfully!']);
}


if (isset($EditroombillingFORM)) {
  $sql = "SELECT SUM(`withdraw`) as totalAvailable FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  $totalAvailable = $row['totalAvailable'];
  if ($amount > $totalAvailable) {
    echo json_encode(['status' => 'error', 'message' => 'Not enough available funds to complete the room billing update.']);
    return;
  }
  $sql1 = "UPDATE `room_bill` SET `room_id`='$room_id', `billtype`='$billtype', `amount`='$amount' WHERE `room_bill_id` = '$room_bill_id'";
  $query = $conn->query($sql1);
  $sql2 = "INSERT INTO `payment_income`(`amount`, `withdraw`, `status`) VALUES (0, '$oldamount', 1)";
  $query = $conn->query($sql2);
  $totalWithdrawn = 0;
  do {
    $sql = "SELECT * FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1 LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_array();
    $availableAmount = $row['withdraw'];
    if ($amount <= $availableAmount) {
      $totalWithdrawn += $amount;
      $totalamount = $availableAmount - $amount;
      $update = "UPDATE `payment_income` SET `withdraw`='$totalamount' WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = 0;
    } else {
      $totalWithdrawn += $availableAmount;
      $newtotalamount = $amount - $availableAmount;
      $update = "UPDATE `payment_income` SET `withdraw`= 0 WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = $newtotalamount;
    }
  } while ($amount != 0);
  if ($totalWithdrawn > 0) {
    $insert = "UPDATE `withdraw` SET `amount`='$totalWithdrawn',`type`='$billtype' WHERE `room_bill_id` = '$room_bill_id'";
    $inquery = $conn->query($insert);
  }
  echo json_encode(['status' => 'success', 'message' => 'Room bill processed successfully!']);
}





if (isset($adminwithdraw)) {
  $sql = "SELECT SUM(`withdraw`) as totalAvailable FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  $totalAvailable = $row['totalAvailable'];
  if ($amount > $totalAvailable) {
    echo json_encode(['status' => 'error', 'message' => 'Not enough available funds to complete the  Withdrawal process.']);
    return;
  }

  $totalWithdrawn = 0;
  do {
    $sql = "SELECT * FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1 LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_array();
    $availableAmount = $row['withdraw'];
    if ($amount <= $availableAmount) {
      $totalWithdrawn += $amount;
      $totalamount = $availableAmount - $amount;
      $update = "UPDATE `payment_income` SET `withdraw`='$totalamount' WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = 0;
    } else {
      $totalWithdrawn += $availableAmount;
      $newtotalamount = $amount - $availableAmount;
      $update = "UPDATE `payment_income` SET `withdraw`= 0 WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = $newtotalamount;
    }
  } while ($amount != 0);
  if ($totalWithdrawn > 0) {
    $insert = "INSERT INTO `withdraw`(`admin`, `amount`, `type`, `date`) VALUES ('admin', '$totalWithdrawn', '$purpose', NOW())";
    $inquery = $conn->query($insert);
  }
  echo json_encode(['status' => 'success', 'message' => 'Admin Withdrawal processed successfully!']);
}

if (isset($_GET['withdraw_id'])) {
  $withdraw_id = intval($_GET['withdraw_id']);
  $sql = "SELECT  * FROM `withdraw` WHERE `admin` = 'admin' AND `withdraw_id` = '$withdraw_id' ";
  $result = $conn->query($sql);
  echo json_encode($result->fetch_assoc());
}


if (isset($EDITadminwithdraw)) {
  $sql = "SELECT SUM(`withdraw`) as totalAvailable FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1";
  $result = $conn->query($sql);
  $row = $result->fetch_array();
  $totalAvailable = $row['totalAvailable'];
  if ($amount > $totalAvailable) {
    echo json_encode(['status' => 'error', 'message' => 'Not enough available funds to complete the  Withdrawal process.']);
    return;
  }
  $sql2 = "INSERT INTO `payment_income`(`amount`, `withdraw`, `status`) VALUES (0, '$oldamount', 1)";
  $query = $conn->query($sql2);
  $totalWithdrawn = 0;
  do {
    $sql = "SELECT * FROM `payment_income` WHERE `withdraw` != 0 AND `status`=1 LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_array();
    $availableAmount = $row['withdraw'];
    if ($amount <= $availableAmount) {
      $totalWithdrawn += $amount;
      $totalamount = $availableAmount - $amount;
      $update = "UPDATE `payment_income` SET `withdraw`='$totalamount' WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = 0;
    } else {
      $totalWithdrawn += $availableAmount;
      $newtotalamount = $amount - $availableAmount;
      $update = "UPDATE `payment_income` SET `withdraw`= 0 WHERE `payment_income_id` = '$row[0]' AND `status`=1";
      $upquery = $conn->query($update);
      $amount = $newtotalamount;
    }
  } while ($amount != 0);
  if ($totalWithdrawn > 0) {
    $insert = "UPDATE `withdraw` SET `amount`='$totalWithdrawn', `type`='$purpose' WHERE `withdraw_id` = '$withdraw_id'";
    $inquery = $conn->query($insert);
  }
  echo json_encode(['status' => 'success', 'message' => 'Admin Withdrawal processed successfully!']);
}


if (isset($switchroomform)) {
  $sql = "UPDATE `rent` SET `room_id`='$room_id', `roomsmonthly`='$roomsmonthly' WHERE `tenants_id` = '$tenants_id' AND `status` = 'Unpaid'";
  $query = $conn->query($sql);
  if ($query) {
    $sql1 = "UPDATE `tenants` SET `room_id`='$room_id' WHERE `tenants_id` = '$tenants_id'";
    $query = $conn->query($sql1);
  }
}


if (isset($_GET['gender'])) {
  $gender = $_GET['gender'];

  $query = mysqli_query($conn, "SELECT room.*, (room.maximum - COALESCE((SELECT COUNT(DISTINCT tenants.tenants_id) FROM rent  INNER JOIN tenants ON tenants.tenants_id = rent.tenants_id  WHERE rent.room_id = room.room_id), 0)) AS free_beds FROM `room` WHERE room_status IS NULL  AND roomgender = '$gender'  HAVING free_beds > 0");
  $rooms = [];
  while ($result = mysqli_fetch_array($query)) {
    $rooms[] = $result;
  }
  echo json_encode($rooms);
  exit;
}
