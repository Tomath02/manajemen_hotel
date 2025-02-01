<?php
session_start();
include '../config.php';

// Ambil daftar reservasi
$reservations = $conn->query("SELECT r.reservation_id, g.name AS guest_name FROM reservations r JOIN guests g ON r.guest_id = g.guest_id");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pembayaran</title>
</head>

<body>
    <h2>Tambah Pembayaran</h2>
    <form action="../actions/add_payment.php" method="post">
        <label>Reservasi:</label>
        <select name="reservation_id" required>
            <option value="">Pilih Reservasi</option>
            <?php while ($row = $reservations->fetch_assoc()): ?>
                <option value="<?= $row['reservation_id']; ?>"><?= $row['guest_name']; ?> (ID: <?= $row['reservation_id']; ?>)</option>
            <?php endwhile; ?>
        </select><br>

        <label>Jumlah:</label>
        <input type="number" name="amount" step="0.01" required><br>

        <label>Metode Pembayaran:</label>
        <select name="payment_method">
            <option value="Credit Card">Credit Card</option>
            <option value="Cash">Cash</option>
            <option value="Bank Transfer">Bank Transfer</option>
        </select><br>

        <label>Status:</label>
        <select name="status">
            <option value="Pending">Pending</option>
            <option value="Paid">Paid</option>
            <option value="Refunded">Refunded</option>
        </select><br>

        <button type="submit">Tambah</button>
    </form>
</body>

</html>