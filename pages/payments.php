<?php
session_start();
include '../config.php';
include '../templates/header.php';

// Ambil daftar pembayaran
$query = "SELECT p.payment_id, r.reservation_id, g.name AS guest_name, p.payment_date, p.amount, p.payment_method, p.status
          FROM payments p
          JOIN reservations r ON p.reservation_id = r.reservation_id
          JOIN guests g ON r.guest_id = g.guest_id
          ORDER BY p.payment_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pembayaran</title>
</head>

<body>
    <h2>Daftar Pembayaran</h2>
    <a href="add_payment.php">Tambah Pembayaran</a>
    <table border="1">
        <tr>
            <th>ID Pembayaran</th>
            <th>Nama Tamu</th>
            <th>Tanggal Pembayaran</th>
            <th>Jumlah</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['payment_id']; ?></td>
                <td><?= $row['guest_name']; ?></td>
                <td><?= $row['payment_date']; ?></td>
                <td>Rp<?= number_format($row['amount'], 2, ',', '.'); ?></td>
                <td><?= $row['payment_method']; ?></td>
                <td><?= $row['status']; ?></td>
                <td>
                    <a href="edit_payment.php?payment_id=<?= $row['payment_id']; ?>">Edit</a> |
                    <a href="../actions/delete_payment.php?payment_id=<?= $row['payment_id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>