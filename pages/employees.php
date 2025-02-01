<?php
session_start();
include '../config.php';
include '../templates/header.php';

// Ambil daftar pegawai
$query = "SELECT * FROM employees ORDER BY employee_id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pegawai</title>
</head>

<body>
    <h2>Daftar Pegawai</h2>
    <a href="add_employee.php">Tambah Pegawai</a>
    <table border="1">
        <tr>
            <th>ID Pegawai</th>
            <th>Nama</th>
            <th>Posisi</th>
            <th>No. Telepon</th>
            <th>Email</th>
            <th>Tanggal Mulai Kerja</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['employee_id']; ?></td>
                <td><?= $row['name']; ?></td>
                <td><?= $row['position']; ?></td>
                <td><?= $row['phone']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['hire_date']; ?></td>
                <td>
                    <a href="edit_employee.php?employee_id=<?= $row['employee_id']; ?>">Edit</a> |
                    <a href="../actions/delete_employee.php?employee_id=<?= $row['employee_id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>