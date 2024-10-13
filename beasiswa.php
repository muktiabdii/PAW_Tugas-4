<?php
include 'db.php'; // Sertakan koneksi ke database
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Query untuk mengambil data beasiswa dari database
$sql = "SELECT id_beasiswa, nama_beasiswa, nama_penyelenggara FROM beasiswa";
$result = $conn->query($sql);

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsivitas -->
    <title>Data Beasiswa</title>
    <link rel="stylesheet" href="beasiswa.css"> <!-- Menghubungkan CSS -->
</head>
<body>
    <h1>Hello, <?php echo htmlspecialchars($username); ?>!</h1>

    <table>
        <tr>
            <th>ID Beasiswa</th>
            <th>Nama Beasiswa</th>
            <th>Nama Penyelenggara</th>
        </tr>
        <?php 
        // Menampilkan data beasiswa dalam tabel
        while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id_beasiswa']; ?></td>
            <td><?php echo htmlspecialchars($row['nama_beasiswa']); ?></td>
            <td><?php echo htmlspecialchars($row['nama_penyelenggara']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <form method="post" action="logout.php">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
