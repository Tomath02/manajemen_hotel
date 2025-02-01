<?php
include '../config.php';
include '../templates/header.php';
$result = $conn->query("SELECT * FROM guests");
?>

<h2>Daftar Tamu</h2>
<a href="../actions/add_guest.php">Tambah Tamu</a>
<table border="1">
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Alamat</th>
        <th>Telepon</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td>
                <a href="../actions/edit_guest.php?guest_id=<?= $row['guest_id'] ?>">Edit</a>
                <a href="../actions/delete_guest.php?guest_id=<?= $row['guest_id'] ?>" onclick="return confirm('Hapus tamu ini?')">Hapus</a>
            </td>
        </tr>
    <?php } ?>
</table>