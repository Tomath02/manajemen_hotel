<?php
require '../vendor/autoload.php'; // Jika pakai Composer
require '../config.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil bulan & tahun dari input
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

// Panggil function `get_monthly_revenue` dari database
$query_total = "SELECT get_monthly_revenue(?, ?) AS total";
$stmt_total = $conn->prepare($query_total);
$stmt_total->bind_param("ii", $selected_year, $selected_month);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_revenue = $result_total->fetch_assoc()['total'] ?? 0;

// Buat tampilan HTML untuk PDF
$html = "
    <h2>Laporan Transaksi Bulanan</h2>
    <p>Bulan: " . date("F", mktime(0, 0, 0, $selected_month, 1)) . " $selected_year</p>
    <table border='1' cellpadding='5' cellspacing='0' width='100%'>
        <thead>
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
        <tbody>";

while ($row = $result->fetch_assoc()) {
    $html .= "
        <tr>
            <td>{$row['payment_id']}</td>
            <td>{$row['reservation_id']}</td>
            <td>{$row['guest_name']}</td>
            <td>" . number_format($row['amount'], 2, ',', '.') . "</td>
            <td>" . date('d M Y', strtotime($row['payment_date'])) . "</td>
            <td>{$row['payment_method']}</td>
            <td>{$row['status']}</td>
        </tr>";
}

$html .= "
        </tbody>
    </table>
    <h3>Total Pendapatan: Rp " . number_format($total_revenue, 2, ',', '.') . "</h3>";

// Konfigurasi DOMPDF
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Laporan_Transaksi_$selected_year-$selected_month.pdf", ["Attachment" => false]);
