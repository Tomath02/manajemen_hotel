<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Layanan</title>
</head>

<body>
    <h2>Tambah Layanan</h2>
    <form action="../actions/add_service.php" method="post">
        <label>Nama Layanan:</label>
        <input type="text" name="name" required><br>

        <label>Deskripsi:</label>
        <textarea name="description"></textarea><br>

        <label>Harga:</label>
        <input type="number" name="price" step="0.01" required><br>

        <button type="submit">Tambah</button>
    </form>
</body>

</html>