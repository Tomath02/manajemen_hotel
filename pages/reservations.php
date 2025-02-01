<?php
session_start();
include '../config.php';
include '../templates/header.php';

// Ambil data reservasi dari database
$query = "SELECT r.reservation_id, g.name AS guest_name, rm.room_number, r.check_in_date, r.check_out_date, r.total_price, r.status 
          FROM reservations r
          JOIN guests g ON r.guest_id = g.guest_id
          JOIN rooms rm ON r.room_id = rm.room_id
          ORDER BY r.reservation_id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Reservasi</title>
</head>

<body>
    <h2>Daftar Reservasi</h2>
    <a href="add_reservation.php">Tambah Reservasi</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Tamu</th>
            <th>Kamar</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['reservation_id']; ?></td>
                <td><?= $row['guest_name']; ?></td>
                <td><?= $row['room_number']; ?></td>
                <td><?= $row['check_in_date']; ?></td>
                <td><?= $row['check_out_date']; ?></td>
                <td>Rp <?= number_format($row['total_price'], 2); ?></td>
                <td><?= $row['status']; ?></td>
                <td>
                    <a href="edit_reservation.php?reservation_id=<?= $row['reservation_id']; ?>">Edit</a> |
                    <a href="../actions/delete_reservation.php?reservation_id=<?= $row['reservation_id']; ?>" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>