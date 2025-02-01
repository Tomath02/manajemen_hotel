<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = $_POST['payment_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    $query = "UPDATE payments SET amount = ?, status = ? WHERE payment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dsi", $amount, $status, $payment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Pembayaran berhasil diperbarui!'); window.location.href = '../pages/payments.php';</script>";
    } else {
        echo "Gagal memperbarui pembayaran: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
