<?php
// register.php
require 'koneksi.php';

$pesan = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama_lengkap'];
    $nik = $_POST['nik'];
    $jabatan = $_POST['jabatan'];
    $password = $_POST['password'];

    // Cek apakah NIK sudah terdaftar
    $cek_nik = $conn->query("SELECT * FROM inspektor WHERE nik = '$nik'");
    
    if ($cek_nik->num_rows > 0) {
        $pesan = "<div class='alert alert-danger'>Gagal daftar: NIK already registered</div>";
    } else {
        // Enkripsi password untuk keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO inspektor (nama_lengkap, nik, jabatan, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $nik, $jabatan, $hashed_password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location.href='index.php';</script>";
        } else {
            $pesan = "<div class='alert alert-danger'>Terjadi kesalahan: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Daihatsu PDI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .header-bg { background-color: #dc3545; height: 150px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; }
        .form-card { margin-top: -100px; border-radius: 15px; border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="header-bg d-flex justify-content-center pt-4">
    <h4 class="text-white fw-bold">DAIHATSU</h4>
</div>

<div class="container">
    <div class="card form-card p-4">
        <h5 class="fw-bold mb-1">Daftar</h5>
        <p class="text-muted small mb-4">Lengkapi data untuk membuat akun</p>
        
        <?= $pesan; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label small fw-bold">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control bg-light" placeholder="Nama lengkap Anda" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">NIK</label>
                <input type="number" name="nik" class="form-control bg-light" placeholder="Nomor Induk Karyawan" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Jabatan</label>
                <select name="jabatan" class="form-select bg-light" required>
                    <option value="Inspektor">Inspektor</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="password" class="form-control bg-light" placeholder="Minimal 6 karakter" required minlength="6">
            </div>
            <button type="submit" class="btn btn-danger w-100 fw-bold py-2 mb-3">Daftar</button>
            <div class="text-center small">
                Sudah punya akun? <a href="index.php" class="text-danger fw-bold text-decoration-none">Masuk di sini</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>