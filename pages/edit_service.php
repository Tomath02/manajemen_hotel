<?php
session_start();
include '../config.php';

if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    $query = "SELECT * FROM services WHERE service_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Layanan tidak ditemukan!");
    }

    $service = $result->fetch_assoc();
    $stmt->close();
} else {
    die("ID layanan tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Layanan</title>
</head>

<body>
    <h2>Edit Layanan</h2>
    <form action="../actions/update_service.php" method="post">
        <input type="hidden" name="service_id" value="<?= $service['service_id']; ?>">

        <label>Nama Layanan:</label>
        <input type="text" name="name" value="<?= $service['name']; ?>" required><br>

        <label>Deskripsi:</label>
        <textarea name="description"><?= $service['description']; ?></textarea><br>

        <label>Harga:</label>
        <input type="number" name="price" step="0.01" value="<?= $service['price']; ?>" required><br>

        <button type="submit">Perbarui</button>
    </form>
</body>

</html>