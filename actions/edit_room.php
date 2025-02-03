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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Edit Kamar</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <button class="btn btn-light close-btn" id="closeSidebar">←</button>
        <h5 class="text-center text-light mt-3">Menu</h5>
        <a href="dashboard.php">Dashboard</a>
        <a href="guests.php">Manajemen Tamu</a>
        <a href="rooms.php" class="active">Manajemen Kamar</a>
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
        <h2 class="text-center text-primary my-4">Edit Data Kamar</h2>

        <!-- Form to Edit Room -->
        <div class="table-container" style="width: 80%; margin: 0 auto;">
            <form action="../actions/update_room.php" method="POST">
                <input type="hidden" name="room_id" value="<?= $room['room_id']; ?>">

                <div class="mb-3">
                    <label for="room_number" class="form-label">Nomor Kamar:</label>
                    <input type="text" name="room_number" class="form-control" value="<?= $room['room_number']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Tipe:</label>
                    <select name="type" class="form-select" required>
                        <option value="Single" <?= ($room['type'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                        <option value="Double" <?= ($room['type'] == 'Double') ? 'selected' : ''; ?>>Double</option>
                        <option value="Suite" <?= ($room['type'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="price_per_night" class="form-label">Harga per Malam:</label>
                    <input type="number" name="price_per_night" class="form-control" value="<?= $room['price_per_night']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select name="status" class="form-select">
                        <option value="Available" <?= ($room['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                        <option value="Occupied" <?= ($room['status'] == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
                        <option value="Maintenance" <?= ($room['status'] == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mb-3">
                    <a href="../pages/rooms.php" class="btn btn-primary">Kembali</a>
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
