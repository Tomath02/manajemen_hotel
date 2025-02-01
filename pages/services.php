<?php
session_start();
include '../config.php';
include '../templates/header.php';

// Ambil daftar layanan
$query = "SELECT * FROM services ORDER BY service_id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Layanan</title>
</head>

<body>
    <h2>Daftar Layanan Tambahan</h2>
    <a href="add_service.php">Tambah Layanan</a>
    <table border="1">
        <tr>
            <th>ID Layanan</th>
            <th>Nama Layanan</th>
            <th>Deskripsi</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['service_id']; ?></td>
                <td><?= $row['name']; ?></td>
                <td><?= $row['description']; ?></td>
                <td>Rp<?= number_format($row['price'], 2, ',', '.'); ?></td>
                <td>
                    <a href="edit_service.php?service_id=<?= $row['service_id']; ?>">Edit</a> |
                    <a href="../actions/delete_service.php?service_id=<?= $row['service_id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>