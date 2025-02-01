<?php
session_start();
include '../config.php';

if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];

    $query = "DELETE FROM payments WHERE payment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $payment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Pembayaran berhasil dihapus!'); window.location.href = '../pages/payments.php';</script>";
    } else {
        echo "Gagal menghapus pembayaran: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("ID pembayaran tidak ditemukan!");
}
