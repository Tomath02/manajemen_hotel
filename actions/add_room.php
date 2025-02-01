<?php
session_start();
include '../config.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kamar</title>
</head>

<body>
    <h2>Tambah Kamar</h2>
    <form action="../actions/save_room.php" method="POST">
        <label>Nomor Kamar:</label>
        <input type="text" name="room_number" required><br>

        <label>Tipe:</label>
        <select name="type" required>
            <option value="Single">Single</option>
            <option value="Double">Double</option>
            <option value="Suite">Suite</option>
        </select><br>

        <label>Harga per Malam:</label>
        <input type="number" name="price_per_night" required><br>

        <label>Status:</label>
        <select name="status">
            <option value="Available">Available</option>
            <option value="Occupied">Occupied</option>
            <option value="Maintenance">Maintenance</option>
        </select><br>

        <button type="submit">Simpan</button>
        <a href="rooms.php">Batal</a>
    </form>
</body>

</html>