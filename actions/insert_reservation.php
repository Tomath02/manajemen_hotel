<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guest_id = $_POST['guest_id'];
    $room_id = $_POST['room_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];

    $query = "INSERT INTO reservations (guest_id, room_id, check_in_date, check_out_date, total_price, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissds", $guest_id, $room_id, $check_in_date, $check_out_date, $total_price, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Reservasi berhasil ditambahkan!'); window.location.href = '../pages/reservations.php';</script>";
    } else {
        echo "Gagal menambahkan reservasi: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses ditolak!";
}
