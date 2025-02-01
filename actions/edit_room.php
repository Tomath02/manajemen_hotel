<?php
session_start();
include '../config.php';

if (!isset($_GET['room_id'])) {
    echo "ID kamar tidak ditemukan!";
    exit();
}

$room_id = $_GET['room_id'];

$query = "SELECT * FROM rooms WHERE room_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Kamar tidak ditemukan!";
    exit();
}

$room = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kamar</title>
</head>

<body>
    <h2>Edit Kamar</h2>
    <form action="../actions/update_room.php" method="POST">
        <input type="hidden" name="room_id" value="<?= $room['room_id']; ?>">

        <label>Nomor Kamar:</label>
        <input type="text" name="room_number" value="<?= $room['room_number']; ?>" required><br>

        <label>Tipe:</label>
        <select name="type" required>
            <option value="Single" <?= ($room['type'] == 'Single') ? 'selected' : ''; ?>>Single</option>
            <option value="Double" <?= ($room['type'] == 'Double') ? 'selected' : ''; ?>>Double</option>
            <option value="Suite" <?= ($room['type'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
        </select><br>

        <label>Harga per Malam:</label>
        <input type="number" name="price_per_night" value="<?= $room['price_per_night']; ?>" required><br>

        <label>Status:</label>
        <select name="status">
            <option value="Available" <?= ($room['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
            <option value="Occupied" <?= ($room['status'] == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
            <option value="Maintenance" <?= ($room['status'] == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
        </select><br>

        <button type="submit">Simpan Perubahan</button>
        <a href="../pages/rooms.php">Batal</a>
    </form>
</body>

</html>