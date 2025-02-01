<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $hire_date = $_POST['hire_date'];

    $query = "UPDATE employees SET name = ?, position = ?, phone = ?, email = ?, hire_date = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $name, $position, $phone, $email, $hire_date, $employee_id);

    if ($stmt->execute()) {
        echo "<script>alert('Pegawai berhasil diperbarui!'); window.location.href = '../pages/employees.php';</script>";
    } else {
        echo "Gagal memperbarui pegawai: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
