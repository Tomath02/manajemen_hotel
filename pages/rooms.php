<?php
session_start();
include '../config.php';
include '../templates/header.php';

// Ambil data kamar dari database
$query = "SELECT * FROM rooms";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kamar</title>
</head>

<body>
    <h2>Daftar Kamar</h2>
    <a href="../actions/add_room.php">Tambah Kamar</a>
    <table border="1">
        <tr>
            <th>No Kamar</th>
            <th>Tipe</th>
            <th>Harga per Malam</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['room_number']; ?></td>
                <td><?= $row['type']; ?></td>
                <td><?= $row['price_per_night']; ?></td>
                <td><?= $row['status']; ?></td>
                <td>
                    <a href="../actions/edit_room.php?room_id=<?= $row['room_id']; ?>">Edit</a>
                    <a href="../actions/delete_room.php?room_id=<?= $row['room_id']; ?>" onclick="return confirm('Yakin ingin menghapus kamar ini?');">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>