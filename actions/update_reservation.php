<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];
    $guest_id = $_POST['guest_id'];
    $room_id = $_POST['room_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];

    // Update query untuk mengubah data reservasi
    $query = "UPDATE reservations SET guest_id = ?, room_id = ?, check_in_date = ?, check_out_date = ?, total_price = ?, status = ? WHERE reservation_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissdsi", $guest_id, $room_id, $check_in_date, $check_out_date, $total_price, $status, $reservation_id);

    if ($stmt->execute()) {
        echo "<script>alert('Reservasi berhasil diperbarui!'); window.location.href = '../pages/reservations.php';</script>";
    } else {
        echo "Gagal memperbarui reservasi: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses ditolak!";
}
