<?php
session_start();
include '../config.php';

// Ambil daftar tamu
$guests = $conn->query("SELECT guest_id, name FROM guests");

// Ambil daftar kamar yang tersedia
$rooms = $conn->query("SELECT room_id, room_number FROM rooms WHERE status = 'Available'");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Reservasi</title>
</head>

<body>
    <h2>Tambah Reservasi</h2>
    <form action="../actions/insert_reservation.php" method="post">
        <label>Tamu:</label>
        <select name="guest_id" required>
            <option value="">Pilih Tamu</option>
            <?php while ($guest = $guests->fetch_assoc()): ?>
                <option value="<?= $guest['guest_id']; ?>"><?= $guest['name']; ?></option>
            <?php endwhile; ?>
        </select><br>

        <label>Kamar:</label>
        <select name="room_id" required>
            <option value="">Pilih Kamar</option>
            <?php while ($room = $rooms->fetch_assoc()): ?>
                <option value="<?= $room['room_id']; ?>"><?= $room['room_number']; ?></option>
            <?php endwhile; ?>
        </select><br>

        <label>Check-in:</label>
        <input type="date" name="check_in_date" required><br>

        <label>Check-out:</label>
        <input type="date" name="check_out_date" required><br>

        <label>Total Harga:</label>
        <input type="number" name="total_price" required><br>

        <label>Status:</label>
        <select name="status">
            <option value="Confirmed">Confirmed</option>
            <option value="Checked-in">Checked-in</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
        </select><br>

        <button type="submit">Simpan</button>
    </form>
</body>

</html>