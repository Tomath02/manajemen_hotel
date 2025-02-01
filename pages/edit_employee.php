<?php
session_start();
include '../config.php';

if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
    $query = "SELECT * FROM employees WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Pegawai tidak ditemukan!");
    }

    $employee = $result->fetch_assoc();
    $stmt->close();
} else {
    die("ID pegawai tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pegawai</title>
</head>

<body>
    <h2>Edit Pegawai</h2>
    <form action="../actions/update_employee.php" method="post">
        <input type="hidden" name="employee_id" value="<?= $employee['employee_id']; ?>">

        <label>Nama:</label>
        <input type="text" name="name" value="<?= $employee['name']; ?>" required><br>

        <label>Posisi:</label>
        <input type="text" name="position" value="<?= $employee['position']; ?>" required><br>

        <label>No. Telepon:</label>
        <input type="text" name="phone" value="<?= $employee['phone']; ?>"><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= $employee['email']; ?>"><br>

        <label>Tanggal Mulai Kerja:</label>
        <input type="date" name="hire_date" value="<?= $employee['hire_date']; ?>" required><br>

        <button type="submit">Perbarui</button>
    </form>
</body>

</html>