<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $query = "UPDATE services SET name = ?, description = ?, price = ? WHERE service_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdi", $name, $description, $price, $service_id);

    if ($stmt->execute()) {
        echo "<script>alert('Layanan berhasil diperbarui!'); window.location.href = '../pages/services.php';</script>";
    } else {
        echo "Gagal memperbarui layanan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
