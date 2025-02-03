<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Manajemen Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="card p-4 shadow-lg" style="width: 350px;">
        <h3 class="text-center">Daftar</h3>
        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="email" class="form-label">Username</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
            <p class="mt-3 text-center">Sudah punya akun? <a href="login.php">Login</a></p>
        </form>
    </div>

    <?php
    session_start();
    include 'config.php'; // Pastikan koneksi database di sini

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ambil data dari form
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Enkripsi password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Masukkan data pengguna baru ke dalam tabel employees tanpa validasi apapun
        $insert_query = "INSERT INTO employees (email, password) VALUES ('$email', '$hashed_password')";

        if ($conn->query($insert_query) === TRUE) {
            echo "<div class='alert alert-success text-center mt-3'>Pendaftaran berhasil! <a href='login.php'>Login</a></div>";
        } else {
            echo "<div class='alert alert-danger text-center mt-3'>Terjadi kesalahan. Silakan coba lagi.</div>";
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
