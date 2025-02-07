<?php
session_start();
include '../config.php';

// Ambil data reservasi berdasarkan ID yang dikirim melalui URL
if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    // Query untuk mengambil data reservasi
    $query = "SELECT r.reservation_id, g.guest_id, g.name AS guest_name, rm.room_id, rm.room_number, r.check_in_date, r.check_out_date, r.total_price, r.status
              FROM reservations r
              JOIN guests g ON r.guest_id = g.guest_id
              JOIN rooms rm ON r.room_id = rm.room_id
              WHERE r.reservation_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Reservasi tidak ditemukan!");
    }

    // Ambil data hasil query
    $reservation = $result->fetch_assoc();

    // Ambil daftar tamu
    $guests = $conn->query("SELECT guest_id, name FROM guests");

    // Ambil daftar kamar
    $rooms = $conn->query("SELECT room_id, room_number FROM rooms");

    $stmt->close();
} else {
    die("ID reservasi tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Edit Reservasi</a>
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
        <h2 class="text-center text-primary my-4">Edit Reservasi</h2>

        <!-- Form to Edit Reservation -->
        <div class="table-container" style="width: 80%; margin: 0 auto;">
            <form action="../actions/update_reservation.php" method="POST">
                <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id']; ?>">

                <div class="mb-3">
                    <label for="guest_id" class="form-label">Tamu:</label>
                    <select name="guest_id" class="form-select" required>
                        <option value="">Pilih Tamu</option>
                        <?php while ($guest = $guests->fetch_assoc()): ?>
                            <option value="<?= $guest['guest_id']; ?>" <?= $guest['guest_id'] == $reservation['guest_id'] ? 'selected' : ''; ?>>
                                <?= $guest['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="room_id" class="form-label">Kamar:</label>
                    <select name="room_id" class="form-select" required>
                        <option value="<?= $reservation['room_id']; ?>">Pilih Kamar</option>
                        <?php while ($room = $rooms->fetch_assoc()): ?>
                            <option value="<?= $room['room_id']; ?>" <?= $room['room_id'] == $reservation['room_id'] ? 'selected' : ''; ?>>
                                <?= $room['room_number']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="check_in_date" class="form-label">Check-in:</label>
                    <input type="date" name="check_in_date" class="form-control" value="<?= $reservation['check_in_date']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="check_out_date" class="form-label">Check-out:</label>
                    <input type="date" name="check_out_date" class="form-control" value="<?= $reservation['check_out_date']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="total_price" class="form-label">Total Harga:</label>
                    <input type="number" name="total_price" class="form-control" value="<?= $reservation['total_price']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status:</label>
                    <select name="status" class="form-select">
                        <option value="Confirmed" <?= $reservation['status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="Checked-in" <?= $reservation['status'] == 'Checked-in' ? 'selected' : ''; ?>>Checked-in</option>
                        <option value="Completed" <?= $reservation['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="Cancelled" <?= $reservation['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mb-3">
                    <a href="reservations.php" class="btn btn-primary">Kembali</a>
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