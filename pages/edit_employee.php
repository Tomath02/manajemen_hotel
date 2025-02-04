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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Edit Pegawai</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <button class="btn btn-light close-btn" id="closeSidebar">←</button>
        <h5 class="text-center text-light mt-3">Menu</h5>
        <a href="../pages/dashboard.php">Dashboard</a>
        <a href="../pages/guests.php" class="active">Manajemen Tamu</a>
        <a href="../pages/rooms.php">Manajemen Kamar</a>
        <a href="../pages/reservations.php">Manajemen Reservasi</a>
        <a href="../pages/payments.php">Manajemen Pembayaran</a>
        <a href="../pages/services.php">Manajemen Layanan</a>
        <a href="../pages/employees.php">Manajemen Pegawai</a>
        <a href="../logout.php" class="text-danger">Logout</a>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="overlay"></div>

    <!-- Main Content -->
    <div class="content" id="mainContent">
        <h2 class="text-center text-primary my-4">Edit Data Pegawai</h2>

        <!-- Form to Edit Employee -->
        <div class="table-container" style="width: 80%; margin: 0 auto;">
            <form action="../actions/update_employee.php" method="POST">
                <input type="hidden" name="employee_id" value="<?= $employee['employee_id']; ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Nama:</label>
                    <input type="text" name="name" class="form-control" value="<?= $employee['name']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="position" class="form-label">Posisi:</label>
                    <input type="text" name="position" class="form-control" value="<?= $employee['position']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">No. Telepon:</label>
                    <input type="text" name="phone" class="form-control" value="<?= $employee['phone']; ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" value="<?= $employee['email']; ?>">
                </div>

                <div class="mb-3">
                    <label for="hire_date" class="form-label">Tanggal Mulai Kerja:</label>
                    <input type="date" name="hire_date" class="form-control" value="<?= $employee['hire_date']; ?>" required>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mb-3">
                    <a href="employees.php" class="btn btn-primary">Kembali</a>
                    <button type="submit" class="btn btn-success">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Sidebar -->
    <script>
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const openBtn = document.getElementById("toggleSidebar");
        const closeBtn = document.getElementById("closeSidebar");

        openBtn.addEventListener("click", function() {
            sidebar.classList.add("sidebar-show");
            overlay.classList.add("overlay-active");
        });

        closeBtn.addEventListener("click", function() {
            sidebar.classList.remove("sidebar-show");
            overlay.classList.remove("overlay-active");
        });

        overlay.addEventListener("click", function() {
            sidebar.classList.remove("sidebar-show");
            overlay.classList.remove("overlay-active");
        });
    </script>

</body>

</html>