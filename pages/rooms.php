<?php
session_start();
include '../config.php';
// include '../templates/header.php';

// Ambil data kamar dari database
$query = "SELECT * FROM rooms";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kamar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">
                <span class="sidebar-icon">&#9776;</span>
            </button>
            <a class="navbar-brand" href="#">Manajemen Kamar</a>
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
        <h2 class="text-center text-primary my-4">Daftar Kamar</h2>
        <!-- Button positioned  -->
        <div class="table-container" style="position: relative; width: 80%; margin: 0 auto;">
            <div class="d-flex justify-content-end mb-3" style="position: absolute; top: -40px; right: 20px;">
                <a href="../actions/add_room.php" class="btn btn-success">Tambah Kamar</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No Kamar</th>
                            <th>Tipe</th>
                            <th>Harga per Malam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['room_number']; ?></td>
                                <td><?= $row['type']; ?></td>
                                <td>Rp <?= number_format($row['price_per_night'], 2, ',', '.'); ?></td>
                                <td><?= $row['status']; ?></td>
                                <td>
                                    <a href="../actions/edit_room.php?room_id=<?= $row['room_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="../actions/delete_room.php?room_id=<?= $row['room_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kamar ini?');">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
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