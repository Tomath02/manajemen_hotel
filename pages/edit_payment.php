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
</head>

<body>
    <h2>Edit Pembayaran</h2>
    <form action="../actions/update_payment.php" method="post">
        <input type="hidden" name="payment_id" value="<?= $payment['payment_id']; ?>">

        <label>Jumlah:</label>
        <input type="number" name="amount" value="<?= $payment['amount']; ?>" required><br>

        <label>Status:</label>
        <select name="status">
            <option value="Pending" <?= $payment['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Paid" <?= $payment['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
            <option value="Refunded" <?= $payment['status'] == 'Refunded' ? 'selected' : ''; ?>>Refunded</option>
        </select><br>

        <button type="submit">Perbarui</button>
    </form>
</body>

</html>