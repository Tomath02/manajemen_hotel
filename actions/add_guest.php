<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO guests (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$alamat')";
    if ($conn->query($sql)) {
        header("Location: ../pages/guests.php");
    } else {
        echo "Gagal menambah tamu!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tamu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Tambah Tamu</a>
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
        <h2 class="text-center text-primary my-4">Tambah Tamu</h2>

        <!-- Form to Add Guest -->
        <div class="table-container" style="width: 80%; margin: 0 auto;">
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama:</label>
                    <input type="text" name="name" class="form-control" placeholder="Nama" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat:</label>
                    <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Telepon:</label>
                    <input type="text" name="phone" class="form-control" placeholder="Telepon">
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mb-3">
                    <a href="../pages/guests.php" class="btn btn-primary">Kembali</a>
                    <button type="submit" class="btn btn-success">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript untuk Sidebar -->
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