<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $hire_date = $_POST['hire_date'];

    $query = "INSERT INTO employees (name, position, phone, email, hire_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $position, $phone, $email, $hire_date);

    if ($stmt->execute()) {
        echo "<script>alert('Pegawai berhasil ditambahkan!'); window.location.href = '../pages/employees.php';</script>";
    } else {
        echo "Gagal menambahkan pegawai: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
