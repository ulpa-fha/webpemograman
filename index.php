<?php
include 'koneksi.php';

// Inisialisasi
$search = "";
$kategori_filter = "";
$whereParts = [];

// Tangkap input pencarian dan filter kategori
if (isset($_GET['cari'])) {
    $search = trim($_GET['cari']);
}
if (isset($_GET['kategori']) && $_GET['kategori'] !== "") {
    $kategori_filter = $_GET['kategori'];
}

// Bangun kondisi pencarian berdasarkan kata
if ($search !== "") {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $words = explode(" ", $searchEscaped);
    $searchConditions = [];

    foreach ($words as $word) {
        $word = trim($word);
        if ($word !== "") {
            $searchConditions[] = "(nama_makanan LIKE '%$word%' OR kategori LIKE '%$word%' OR deskripsi LIKE '%$word%')";
        }
    }

    if (count($searchConditions) > 0) {
        $whereParts[] = "(" . implode(" AND ", $searchConditions) . ")";
    }
}

// Tambahkan filter kategori ke WHERE clause
if ($kategori_filter !== "") {
    $kategoriEscaped = mysqli_real_escape_string($conn, $kategori_filter);
    $whereParts[] = "kategori = '$kategoriEscaped'";
}

// Gabungkan kondisi WHERE
$whereClause = count($whereParts) > 0 ? "WHERE " . implode(" AND ", $whereParts) : "";

// Eksekusi query utama
$query = "SELECT * FROM menu_makanan $whereClause ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Ambil data kategori untuk dropdown
$kategoriQuery = "SELECT DISTINCT kategori FROM menu_makanan";
$kategoriResult = mysqli_query($conn, $kategoriQuery);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Menu Makanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(163, 218, 250);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: black;
        }
        h2 {
            color: #4B0082;
        }
        th, td {
            color: black !important;
        }
        th {
            background-color: #4B0082;
            text-align: center;
        }
        .btn {
            border-radius: 8px;
        }
        .btn-warning {
            background-color: #ffc107;
            border: none;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-primary {
            background-color: #6f42c1;
            border: none;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color:rgb(192, 103, 103);
        }
        .search-box {
            max-width: 600px;
        }
    </style>
</head>
<body class="container py-4">

    <h2 class="mb-3 text-center">📋 Daftar Menu Makanan</h2>

    <div class="d-flex justify-content-between mb-3">
        <a href="tambah.php" class="btn btn-primary">+ Tambah Menu</a>

        <!-- Form pencarian -->
        <form method="GET" class="d-flex search-box" role="search">
            <input type="text" name="cari" class="form-control me-2" placeholder="Cari menu..." value="<?= htmlspecialchars($search) ?>">
            <select name="kategori" class="form-select me-2">
                <option value="">Semua Kategori</option>
                <?php while ($k = mysqli_fetch_assoc($kategoriResult)): ?>
                    <option value="<?= htmlspecialchars($k['kategori']) ?>" <?= $kategori_filter === $k['kategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-outline-secondary">Cari</button>
        </form>
    </div>

    <table class="table table-bordered table-striped shadow-sm">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Makanan</th>
                <th>Harga</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0): $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_makanan']) ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td class="text-center">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus menu ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center">Menu tidak ditemukan.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
