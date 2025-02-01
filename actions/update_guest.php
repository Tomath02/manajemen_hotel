<?php
session_start();
include '../config.php';

// Pastikan data dikirim melalui POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guest_id = $_POST['guest_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update data tamu
    $query = "UPDATE guests SET name = ?, email = ?, phone = ?, address = ? WHERE guest_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $guest_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data tamu berhasil diperbarui!'); window.location.href = '../pages/guests.php';</script>";
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses ditolak!";
}
