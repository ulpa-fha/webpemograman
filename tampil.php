<?php
include 'koneksi.php';

// Ambil data dari tabel menu_makanan
$query = "SELECT * FROM menu_makanan ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
    <link rel="stylesheet" href="style.css">
<head>
    <title>Daftar Menu Makanan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color:rgb(134, 247, 140);
        }
        a {
            margin-right: 8px;
        }
    </style>
</head>
<body>

<h2>Daftar Menu Makanan</h2>
<a href="tambah.php">+ Tambah Menu</a>
<br><br>

<table>
    <tr>
        <th>No</th>
        <th>Nama Makanan</th>
        <th>Harga</th>
        <th>Kategori</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['nama_makanan']) . "</td>";
        echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
        echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
        echo "<td>" . htmlspecialchars($row['deskripsi']) . "</td>";
        echo "<td>
                <a href='edit.php?id=" . $row['id'] . "'>Edit</a>
                <a href='hapus.php?id=" . $row['id'] . "' onclick=\"return confirm('Yakin ingin menghapus menu ini?');\">Hapus</a>
              </td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>
