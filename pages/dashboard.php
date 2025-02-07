<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';
// include '../templates/header.php';

// Hitung total tamu
$result = $conn->query("SELECT COUNT(*) AS total FROM guests");
$total_guests = $result->fetch_assoc()['total'];

// Hitung total kamar
$result = $conn->query("SELECT COUNT(*) AS total FROM rooms");
$total_rooms = $result->fetch_assoc()['total'];

// Hitung kamar berdasarkan status
$available_rooms = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE status='Available'")->fetch_assoc()['total'];
$occupied_rooms = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE status='Occupied'")->fetch_assoc()['total'];

// Hitung total reservasi
$result = $conn->query("SELECT COUNT(*) AS total FROM reservations");
$total_reservations = $result->fetch_assoc()['total'];

// Hitung total pembayaran
$result = $conn->query("SELECT COUNT(*) AS total FROM payments");
$total_payments = $result->fetch_assoc()['total'];

// Hitung total layanan tambahan
$result = $conn->query("SELECT COUNT(*) AS total FROM services");
$total_services = $result->fetch_assoc()['total'];

// Hitung total pegawai
$result = $conn->query("SELECT COUNT(*) AS total FROM employees");
$total_employees = $result->fetch_assoc()['total'];


// Ambil data pendapatan dan jumlah reservasi per bulan
$query1 = "SELECT 
              YEAR(r.check_in_date) AS year, 
              MONTH(r.check_in_date) AS month, 
              COUNT(r.reservation_id) AS total_reservations, 
              COALESCE(SUM(p.amount), 0) AS total_revenue
           FROM reservations r
           LEFT JOIN payments p ON r.reservation_id = p.reservation_id AND p.status = 'Paid'
           GROUP BY YEAR(r.check_in_date), MONTH(r.check_in_date)
           ORDER BY YEAR(r.check_in_date), MONTH(r.check_in_date)";
$result1 = $conn->query($query1);

$months = [];
$reservations = [];
$revenues = [];

while ($row = $result1->fetch_assoc()) {
    $months[] = date("F Y", mktime(0, 0, 0, $row['month'], 1, $row['year']));
    $reservations[] = $row['total_reservations'];
    $revenues[] = $row['total_revenue'];
}

$months_json = json_encode($months);
$reservations_json = json_encode($reservations);
$revenues_json = json_encode($revenues);

// Ambil data tipe kamar favorit
$query2 = "SELECT rm.type, COUNT(r.room_id) AS total_bookings 
           FROM rooms rm 
           LEFT JOIN reservations r ON rm.room_id = r.room_id 
           GROUP BY rm.type 
           ORDER BY total_bookings DESC";
$result2 = $conn->query($query2);

$room_types = [];
$room_bookings = [];

while ($row = $result2->fetch_assoc()) {
    $room_types[] = $row['type'];
    $room_bookings[] = $row['total_bookings'];
}

$room_types_json = json_encode($room_types);
$room_bookings_json = json_encode($room_bookings);

// Ambil data dari view untuk notifikasi
$sql_occupied_rooms = "SELECT * FROM view_occupied_rooms";
$result_occupied_rooms = $conn->query($sql_occupied_rooms);

$sql_unpaid_reservations = "SELECT * FROM view_unpaid_reservations";
$result_unpaid_reservations = $conn->query($sql_unpaid_reservations);

// Ambil tingkat hunian kamar
$query3 = "SELECT (COUNT(DISTINCT room_id) / (SELECT COUNT(*) FROM rooms)) * 100 AS occupancy_rate 
           FROM reservations 
           WHERE status IN ('Confirmed', 'Checked-in')";
$result3 = $conn->query($query3);
$occupancy_rate = $result3->fetch_assoc()['occupancy_rate'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .chart-container {
            width: 80%;
            /* Sesuaikan lebar */
            max-width: 600px;
            /* Batasi ukuran maksimal */
            margin: 20px auto;
            /* Pusatkan grafik */
        }

        .notifications {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .alert-warning {
            background: #fff3cd;
            border-left: 5px solid #ff9800;
            color: #856404;
        }

        .alert-danger {
            background: #f8d7da;
            border-left: 5px solid #dc3545;
            color: #721c24;
        }

        .alert ul {
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Hotel Dashboard</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <button class="btn btn-light close-btn" id="closeSidebar">←</button>
        <h5 class="text-center text-light mt-3">Menu</h5>
        <a href="guests.php">Manajemen Tamu</a>
        <a href="rooms.php">Manajemen Kamar</a>
        <a href="reservations.php">Manajemen Reservasi</a>
        <a href="payments.php">Manajemen Pembayaran</a>
        <a href="services.php">Manajemen Layanan</a>
        <a href="employees.php">Manajemen Pegawai</a>
        <a href="../logout.php" class="text-danger">Logout</a>
    </div>

    <!-- Overlay (hanya menutupi main content) -->
    <div id="overlay" class="overlay"></div>

    <!-- Main Content -->
    <div class="container-fluid content" id="main-content">
        <h2 class="text-center text-primary my-4">Dashboard</h2>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card card-custom">
                    <div class="card-header">Statistik Hotel</div>
                    <div class="card-body text-center">
                        <p><strong>Tamu:</strong> <?= $total_guests; ?></p>
                        <p><strong>Total Kamar:</strong> <?= $total_rooms; ?> (Available: <?= $available_rooms; ?>, Occupied: <?= $occupied_rooms; ?>)</p>
                        <p><strong>Total Reservasi:</strong> <?= $total_reservations; ?></p>
                        <p><strong>Total Pembayaran:</strong> <?= $total_payments; ?></p>
                        <p><strong>Total Layanan Tambahan:</strong> <?= $total_services; ?></p>
                        <p><strong>Total Pegawai:</strong> <?= $total_employees; ?></p>
                        <!-- Tombol ke Report -->
                        <a href="report.php" class="btn btn-primary mt-3">Lihat Laporan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom">
                    <div class="card-header">Kamar & Reservasi</div>
                    <div class="card-body text-center">
                        <p><strong>Kamar Tersedia:</strong> <?= $available_rooms; ?></p>
                        <p><strong>Kamar Terisi:</strong> <?= $occupied_rooms; ?></p>
                        <p><strong>Total Reservasi:</strong> <?= $total_reservations; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom">
                    <div class="card-header">Keuangan</div>
                    <div class="card-body text-center">
                        <p><strong>Total Pembayaran:</strong> <?= $total_payments; ?></p>
                        <p><strong>Total Layanan Tambahan:</strong> <?= $total_services; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="notifications">
        <h3>🚨 Notifikasi</h3>

        <?php if ($result_occupied_rooms->num_rows > 0) : ?>
            <div class="alert alert-warning">
                <strong>⚠️ Kamar yang sudah ditempati lebih dari 7 hari:</strong>
                <ul>
                    <?php while ($row = $result_occupied_rooms->fetch_assoc()) : ?>
                        <li>Kamar <?= htmlspecialchars($row['room_number']); ?> - Check-in sejak <?= htmlspecialchars($row['check_in_date']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($result_unpaid_reservations->num_rows > 0) : ?>
            <div class="alert alert-danger">
                <strong>❗ Reservasi belum dibayar setelah check-in:</strong>
                <ul>
                    <?php while ($row = $result_unpaid_reservations->fetch_assoc()) : ?>
                        <li>Reservasi ID <?= htmlspecialchars($row['reservation_id']); ?> oleh Tamu ID <?= htmlspecialchars($row['guest_id']); ?> - Check-in <?= htmlspecialchars($row['check_in_date']); ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($result_occupied_rooms->num_rows == 0 && $result_unpaid_reservations->num_rows == 0) : ?>
            <p>Tidak ada notifikasi saat ini. 🎉</p>
        <?php endif; ?>
    </div>


    <h3>📊 Analisis Tren Pendapatan dan Pemesanan</h3>
    <div class="chart-container">
        <canvas id="revenueChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="reservationChart"></canvas>
    </div>

    <h3>🏨 Analisis Hunian Kamar</h3>
    <div class="chart-container">
        <canvas id="roomTypeChart"></canvas>
    </div>
    <div class="chart-container">
        <h4 style="text-align: center;">Tingkat Hunian Kamar</h4>
        <canvas id="occupancyChart"></canvas>
    </div>



    <!-- JavaScript untuk Sidebar -->
    <script>
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik Pendapatan per Bulan
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: <?= $months_json; ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= $revenues_json; ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik Jumlah Pemesanan per Bulan
        const reservationCtx = document.getElementById('reservationChart').getContext('2d');
        new Chart(reservationCtx, {
            type: 'line',
            data: {
                labels: <?= $months_json; ?>,
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: <?= $reservations_json; ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik Tipe Kamar Favorit
        const roomTypeCtx = document.getElementById('roomTypeChart').getContext('2d');
        new Chart(roomTypeCtx, {
            type: 'pie',
            data: {
                labels: <?= $room_types_json; ?>,
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: <?= $room_bookings_json; ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });

        // Grafik Tingkat Hunian Kamar
        const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
        new Chart(occupancyCtx, {
            type: 'doughnut',
            data: {
                labels: ['Terisi', 'Kosong'],
                datasets: [{
                    data: [<?= $occupancy_rate; ?>, 100 - <?= $occupancy_rate; ?>],
                    backgroundColor: ['#4CAF50', '#E0E0E0']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

</body>

</html>