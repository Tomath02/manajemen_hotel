<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO guests (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$alamat')";
    if ($conn->query($sql)) {
        header("Location: ../pages/guests.php");
    } else {
        echo "Gagal menambah tamu!";
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Nama" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="alamat" placeholder="Alamat" required>
    <input type="text" name="phone" placeholder="Telepon">
    <button type="submit">Tambah</button>
</form>