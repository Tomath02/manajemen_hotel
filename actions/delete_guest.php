<?php
include '../config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM guests WHERE guest_id = $id");
header("Location: ../pages/guests.php");
