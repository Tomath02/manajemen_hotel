<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $query = "INSERT INTO services (name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssd", $name, $description, $price);

    if ($stmt->execute()) {
        echo "<script>alert('Layanan berhasil ditambahkan!'); window.location.href = '../pages/services.php';</script>";
    } else {
        echo "Gagal menambahkan layanan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
