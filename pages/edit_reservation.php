<?php
session_start();
include '../config.php';

// Ambil data reservasi berdasarkan ID yang dikirim melalui URL
if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    // Query untuk mengambil data reservasi
    $query = "SELECT r.reservation_id, g.guest_id, g.name AS guest_name, rm.room_id, rm.room_number, r.check_in_date, r.check_out_date, r.total_price, r.status
              FROM reservations r
              JOIN guests g ON r.guest_id = g.guest_id
              JOIN rooms rm ON r.room_id = rm.room_id
              WHERE r.reservation_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Reservasi tidak ditemukan!");
    }

    // Ambil data hasil query
    $reservation = $result->fetch_assoc();

    // Ambil daftar tamu
    $guests = $conn->query("SELECT guest_id, name FROM guests");

    // Ambil daftar kamar
    $rooms = $conn->query("SELECT room_id, room_number FROM rooms WHERE status = 'Available'");

    $stmt->close();
} else {
    die("ID reservasi tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservasi</title>
</head>

<body>
    <h2>Edit Reservasi</h2>
    <form action="../actions/update_reservation.php" method="post">
        <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id']; ?>">

        <label>Tamu:</label>
        <select name="guest_id" required>
            <option value="">Pilih Tamu</option>
            <?php while ($guest = $guests->fetch_assoc()): ?>
                <option value="<?= $guest['guest_id']; ?>" <?= $guest['guest_id'] == $reservation['guest_id'] ? 'selected' : ''; ?>>
                    <?= $guest['name']; ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <label>Kamar:</label>
        <select name="room_id" required>
            <option value="">Pilih Kamar</option>
            <?php while ($room = $rooms->fetch_assoc()): ?>
                <option value="<?= $room['room_id']; ?>" <?= $room['room_id'] == $reservation['room_id'] ? 'selected' : ''; ?>>
                    <?= $room['room_number']; ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <label>Check-in:</label>
        <input type="date" name="check_in_date" value="<?= $reservation['check_in_date']; ?>" required><br>

        <label>Check-out:</label>
        <input type="date" name="check_out_date" value="<?= $reservation['check_out_date']; ?>" required><br>

        <label>Total Harga:</label>
        <input type="number" name="total_price" value="<?= $reservation['total_price']; ?>" required><br>

        <label>Status:</label>
        <select name="status">
            <option value="Confirmed" <?= $reservation['status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
            <option value="Checked-in" <?= $reservation['status'] == 'Checked-in' ? 'selected' : ''; ?>>Checked-in</option>
            <option value="Completed" <?= $reservation['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
            <option value="Cancelled" <?= $reservation['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
        </select><br>

        <button type="submit">Perbarui</button>
    </form>
</body>

</html>