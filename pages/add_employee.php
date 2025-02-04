<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Tambah Pegawai</a>
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
        <h2 class="text-center text-primary my-4">Tambah Pegawai</h2>

        <!-- Form to Add Employee -->
        <div class="table-container" style="position: relative; width: 80%; margin: 0 auto;">
            <form action="../actions/add_employee.php" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="position" class="form-label">Posisi:</label>
                    <input type="text" class="form-control" id="position" name="position" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">No. Telepon:</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>

                <div class="mb-3">
                    <label for="hire_date" class="form-label">Tanggal Mulai Kerja:</label>
                    <input type="date" class="form-control" id="hire_date" name="hire_date" required>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <a href="employees.php" class="btn btn-primary">Kembali</a>
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