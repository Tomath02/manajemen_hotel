<?php
include '../config.php';

// Ambil bulan & tahun dari input, default ke bulan & tahun saat ini
$selected_month = isset($_GET['month']) ? (int) $_GET['month'] : date('m');
$selected_year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');

// Ambil semua transaksi di bulan & tahun yang dipilih
$query = "SELECT p.payment_id, r.reservation_id, g.name AS guest_name, p.amount, p.payment_date, p.payment_method, p.status
          FROM payments p
          JOIN reservations r ON p.reservation_id = r.reservation_id
          JOIN guests g ON r.guest_id = g.guest_id
          WHERE YEAR(p.payment_date) = ? AND MONTH(p.payment_date) = ?
          ORDER BY p.payment_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $selected_year, $selected_month);
$stmt->execute();
$result = $stmt->get_result();

// Ambil total pendapatan dari function `get_monthly_revenue`
$query_total = "SELECT get_monthly_revenue(?, ?) AS total";
$stmt_total = $conn->prepare($query_total);
$stmt_total->bind_param("ii", $selected_year, $selected_month);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_revenue = $result_total->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center text-primary">Laporan Transaksi Bulanan</h2>

        <!-- Form Pilih Bulan & Tahun -->
        <div class="card p-3 mb-4">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pilih Tahun:</label>
                    <select name="year" class="form-select">
                        <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++) { ?>
                            <option value="<?= $y; ?>" <?= ($y == $selected_year) ? 'selected' : ''; ?>><?= $y; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Pilih Bulan:</label>
                    <select name="month" class="form-select">
                        <?php for ($i = 1; $i <= 12; $i++) { ?>
                            <option value="<?= $i; ?>" <?= ($i == $selected_month) ? 'selected' : ''; ?>>
                                <?= date("F", mktime(0, 0, 0, $i, 1)); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                </div>
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-primary">
                    <tr>
                        <th>ID Pembayaran</th>
                        <th>ID Reservasi</th>
                        <th>Nama Tamu</th>
                        <th>Jumlah (Rp)</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['payment_id']; ?></td>
                            <td><?= $row['reservation_id']; ?></td>
                            <td><?= $row['guest_name']; ?></td>
                            <td><?= number_format($row['amount'], 2, ',', '.'); ?></td>
                            <td><?= date('d M Y', strtotime($row['payment_date'])); ?></td>
                            <td><?= $row['payment_method']; ?></td>
                            <td>
                                <span class="badge bg-<?= ($row['status'] == 'Paid') ? 'success' : 'warning'; ?>">
                                    <?= $row['status']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Total Pendapatan -->
        <div class="alert alert-info text-center mt-4">
            <h4>Total Pendapatan Bulan <?= date("F", mktime(0, 0, 0, $selected_month, 1)); ?> <?= $selected_year; ?>:</h4>
            <h3 class="fw-bold">Rp <?= number_format($total_revenue, 2, ',', '.'); ?></h3>
        </div>

        <!-- Tombol Cetak PDF & Kembali -->
        <div class="d-flex justify-content-center gap-3">
            <form method="GET" action="print_report.php" target="_blank">
                <input type="hidden" name="year" value="<?= $selected_year; ?>">
                <input type="hidden" name="month" value="<?= $selected_month; ?>">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
                </button>
            </form>

            <!-- Tombol Kembali ke Dashboard -->
            <a href="dashboard.php" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
