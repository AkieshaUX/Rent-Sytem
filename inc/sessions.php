<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bh');




if (!isset($_SESSION['tenants_id'])) {
  header("Location: ../index.php");
  exit();
}
$tenant_id = $_SESSION['tenants_id'];
$stmt = $conn->prepare("SELECT `status` FROM `tenants` WHERE `tenants_id` = ?");
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $tenant = $result->fetch_assoc();
  if ($tenant['status'] == 0) {
    session_destroy();
    header("Location: ../index.php?deactivated");
    exit();
  }
} else {
  session_destroy();
  header("Location: ../index.php");
  exit();
}
$stmt->close();
$query = mysqli_query($conn, "SELECT * FROM `tenants` WHERE `tenants_id` = '$tenant_id'");
while ($result = mysqli_fetch_array($query)) {
  extract($result);
}
$admin = mysqli_query($conn, "SELECT * FROM `admin`");
while ($result = mysqli_fetch_array($admin)) {
  extract($result);
}
