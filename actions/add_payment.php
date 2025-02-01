<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];

    $query = "INSERT INTO payments (reservation_id, amount, payment_method, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("idss", $reservation_id, $amount, $payment_method, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Pembayaran berhasil ditambahkan!'); window.location.href = '../pages/payments.php';</script>";
    } else {
        echo "Gagal menambahkan pembayaran: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
