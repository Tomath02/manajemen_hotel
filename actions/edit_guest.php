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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Edit Tamu</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <button class="btn btn-light close-btn" id="closeSidebar">←</button>
        <h5 class="text-center text-light mt-3">Menu</h5>
        <a href="dashboard.php">Dashboard</a>
        <a href="guests.php" class="active">Manajemen Tamu</a>
        <a href="rooms.php">Manajemen Kamar</a>
        <a href="reservations.php">Manajemen Reservasi</a>
        <a href="payments.php">Manajemen Pembayaran</a>
        <a href="services.php">Manajemen Layanan</a>
        <a href="employees.php">Manajemen Pegawai</a>
        <a href="../logout.php" class="text-danger">Logout</a>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="overlay"></div>

    <!-- Main Content -->
    <div class="content" id="mainContent">
        <h2 class="text-center text-primary my-4">Edit Data Tamu</h2>

        <!-- Form to Edit Guest -->
        <div class="table-container" style="width: 80%; margin: 0 auto;">
            <form action="../actions/update_guest.php" method="POST">
                <input type="hidden" name="guest_id" value="<?= $guest['guest_id']; ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Nama:</label>
                    <input type="text" name="name" class="form-control" value="<?= $guest['name']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" value="<?= $guest['email']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Telepon:</label>
                    <input type="text" name="phone" class="form-control" value="<?= $guest['phone']; ?>">
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Alamat:</label>
                    <textarea name="address" class="form-control"><?= $guest['address']; ?></textarea>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mb-3">
                    <a href="../pages/guests.php" class="btn btn-primary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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

        openBtn.addEventListener("click", function () {
            sidebar.classList.add("sidebar-show");
            overlay.classList.add("overlay-active");
        });

        closeBtn.addEventListener("click", function () {
            sidebar.classList.remove("sidebar-show");
            overlay.classList.remove("overlay-active");
        });

        overlay.addEventListener("click", function () {
            sidebar.classList.remove("sidebar-show");
            overlay.classList.remove("overlay-active");
        });
    </script>

</body>

</html>
