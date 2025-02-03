<?php
session_start();
include '../config.php';

if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
    $query = "SELECT * FROM payments WHERE payment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Pembayaran tidak ditemukan!");
    }

    $payment = $result->fetch_assoc();
    $stmt->close();
} else {
    die("ID pembayaran tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Edit Pembayaran</a>
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
        <h2 class="text-center text-primary my-4">Edit Pembayaran</h2>

        <!-- Form to Edit Payment -->
        <div class="table-container" style="width: 80%; margin: 0 auto;">
            <form action="../actions/update_payment.php" method="POST">
                <input type="hidden" name="payment_id" value="<?= $payment['payment_id']; ?>">

                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah:</label>
                    <input type="number" name="amount" class="form-control" value="<?= $payment['amount']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="Pending" <?= $payment['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Paid" <?= $payment['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                        <option value="Refunded" <?= $payment['status'] == 'Refunded' ? 'selected' : ''; ?>>Refunded</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mb-3">
                    <a href="payments.php" class="btn btn-primary">Kembali</a>
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
