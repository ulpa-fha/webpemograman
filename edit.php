<?php include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM menu_makanan WHERE id=$id");
$row = mysqli_fetch_assoc($data);

$error = "";

if (isset($_POST['submit'])) {
    $nama = trim($_POST['nama_makanan']);
    $harga = trim($_POST['harga']);
    $kategori = trim($_POST['kategori']);
    $deskripsi = trim($_POST['deskripsi']);

    // Validasi input
    if (empty($nama) || empty($harga) || empty($kategori) || empty($deskripsi)) {
        $error = "Semua field harus diisi!";
    } elseif (!is_numeric($harga) || $harga <= 0) {
        $error = "Harga harus berupa angka dan lebih dari 0!";
    } else {
        mysqli_query($conn, "UPDATE menu_makanan SET 
            nama_makanan='$nama', 
            harga='$harga', 
            kategori='$kategori', 
            deskripsi='$deskripsi' 
            WHERE id=$id");

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Menu</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="post" id="editForm">
                <div class="mb-3">
                    <label class="form-label">Nama Makanan</label>
                    <input type="text" name="nama_makanan" class="form-control"
                           value="<?= isset($nama) ? htmlspecialchars($nama) : $row['nama_makanan'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control"
                           value="<?= isset($harga) ? htmlspecialchars($harga) : $row['harga'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control"
                           value="<?= isset($kategori) ? htmlspecialchars($kategori) : $row['kategori'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"><?= isset($deskripsi) ? htmlspecialchars($deskripsi) : $row['deskripsi'] ?></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" name="submit" class="btn btn-success">Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Optional JavaScript validation -->
<script>
document.getElementById("editForm").addEventListener("submit", function(e) {
    const nama = document.querySelector("[name='nama_makanan']").value.trim();
    const harga = document.querySelector("[name='harga']").value.trim();
    const kategori = document.querySelector("[name='kategori']").value.trim();
    const deskripsi = document.querySelector("[name='deskripsi']").value.trim();

    if (!nama || !harga || !kategori || !deskripsi) {
        alert("Semua field harus diisi!");
        e.preventDefault();
    } else if (isNaN(harga) || Number(harga) <= 0) {
        alert("Harga harus berupa angka dan lebih dari 0!");
        e.preventDefault();
    }
});
</script>

</body>
</html>
