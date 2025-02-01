<?php
session_start();
include '../config.php'; // Sesuaikan path jika perlu

// Pastikan ada guest_id di URL
if (!isset($_GET['guest_id'])) {
    echo "ID tamu tidak ditemukan!";
    exit();
}

$guest_id = $_GET['guest_id'];

// Ambil data tamu berdasarkan ID
$query = "SELECT * FROM guests WHERE guest_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $guest_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Tamu tidak ditemukan!";
    exit();
}

$guest = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tamu</title>
</head>

<body>
    <h2>Edit Data Tamu</h2>
    <form action="../actions/update_guest.php" method="POST">
        <input type="hidden" name="guest_id" value="<?= $guest['guest_id']; ?>">

        <label>Nama:</label>
        <input type="text" name="name" value="<?= $guest['name']; ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= $guest['email']; ?>" required><br>

        <label>Telepon:</label>
        <input type="text" name="phone" value="<?= $guest['phone']; ?>"><br>

        <label>Alamat:</label>
        <textarea name="address"><?= $guest['address']; ?></textarea><br>

        <button type="submit">Simpan Perubahan</button>
        <a href="../pages/guests.php">Batal</a>
    </form>
</body>

</html>