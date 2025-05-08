<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'bh');
$conn->set_charset("utf8");



$conn->close();
?>
