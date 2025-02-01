<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pegawai</title>
</head>

<body>
    <h2>Tambah Pegawai</h2>
    <form action="../actions/add_employee.php" method="post">
        <label>Nama:</label>
        <input type="text" name="name" required><br>

        <label>Posisi:</label>
        <input type="text" name="position" required><br>

        <label>No. Telepon:</label>
        <input type="text" name="phone"><br>

        <label>Email:</label>
        <input type="email" name="email"><br>

        <label>Tanggal Mulai Kerja:</label>
        <input type="date" name="hire_date" required><br>

        <button type="submit">Tambah</button>
    </form>
</body>

</html>