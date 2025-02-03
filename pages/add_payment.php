<?php
session_start();
include '../config.php';

// Ambil daftar reservasi
$reservations = $conn->query("SELECT r.reservation_id, g.name AS guest_name FROM reservations r JOIN guests g ON r.guest_id = g.guest_id");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Tambah Pembayaran</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <button class="btn btn-light close-btn" id="closeSidebar">←</button>
        <h5 class="text-center text-light mt-3">Menu</h5>
        <a href="dashboard.php">Dashboard</a>
        <a href="guests.php">Manajemen Tamu</a>
        <a href="rooms.php">Manajemen Kamar</a>
        <a href="reservations.php">Manajemen Reservasi</a>
        <a href="payments.php" class="active">Manajemen Pembayaran</a>
        <a href="services.php">Manajemen Layanan</a>
        <a href="employees.php">Manajemen Pegawai</a>
        <a href="../logout.php" class="text-danger">Logout</a>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="overlay"></div>

    <!-- Main Content -->
    <div class="content" id="mainContent">
        <h2 class="text-center text-primary my-4">Tambah Pembayaran</h2>

        <!-- Form to Add Payment -->
        <div class="table-container" style="width: 80%; margin: 0 auto;">
            <form action="../actions/add_payment.php" method="post">
                <div class="mb-3">
                    <label for="reservation_id" class="form-label">Reservasi:</label>
                    <select name="reservation_id" id="reservation_id" class="form-select" required>
                        <option value="">Pilih Reservasi</option>
                        <?php while ($row = $reservations->fetch_assoc()): ?>
                            <option value="<?= $row['reservation_id']; ?>"><?= $row['guest_name']; ?> (ID: <?= $row['reservation_id']; ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah:</label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Metode Pembayaran:</label>
                    <select name="payment_method" id="payment_method" class="form-select">
                        <option value="Credit Card">Credit Card</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select name="status" id="status" class="form-select">
                        <option value="Pending">Pending</option>
                        <option value="Paid">Paid</option>
                        <option value="Refunded">Refunded</option>
                    </select>
                </div>

                <!-- Buttons below the form (Tambah and Kembali) -->
                <div class="d-flex justify-content-between mb-3">
                    <a href="payments.php" class="btn btn-primary">Kembali</a>
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
