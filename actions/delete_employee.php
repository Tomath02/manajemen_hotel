<?php
session_start();
include '../config.php';

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    $query = "DELETE FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employee_id);

    if ($stmt->execute()) {
        echo "<script>alert('Pegawai berhasil dihapus!'); window.location.href = '../pages/employees.php';</script>";
    } else {
        echo "Gagal menghapus pegawai: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("ID pegawai tidak ditemukan!");
}
