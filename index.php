<?php
// index.php
session_start();
require 'koneksi.php';

if (isset($_SESSION['login_status']) && $_SESSION['login_status'] === true) {
    header("Location: dashboard.php");
    exit;
}

$pesan = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = $_POST['nik'];
    $password = $_POST['password'];

    $query = $conn->query("SELECT * FROM inspektor WHERE nik = '$nik'");
    
    if ($query->num_rows > 0) {
        $data = $query->fetch_assoc();
        if (password_verify($password, $data['password'])) {
            $_SESSION['login_status'] = true;
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['nik'] = $data['nik'];
            header("Location: dashboard.php");
            exit;
        } else {
            $pesan = "<div class='alert alert-danger'>Password salah!</div>";
        }
    } else {
        $pesan = "<div class='alert alert-danger'>NIK tidak ditemukan!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Daihatsu PDI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .header-bg { 
            background-color: #dc3545; 
            height: 220px; /* Ditambah tingginya */
            border-bottom-left-radius: 30px; 
            border-bottom-right-radius: 30px; 
        }
        .form-card { 
            margin-top: -60px; /* Dikurangi negatifnya supaya turun */
            border-radius: 15px; 
            border: none; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
        }
        .logo-circle {
            width: 60px; 
            height: 60px; 
            background: white; 
            color: #dc3545; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: bold; 
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="header-bg d-flex flex-column align-items-center justify-content-center text-white pt-2">
    <div class="logo-circle shadow">D</div>
    <h3 class="fw-bold mb-0" style="letter-spacing: 2px;">DAIHATSU</h3>
    <small class="opacity-75">PDI SYSTEM</small>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card form-card p-4">
                <h5 class="fw-bold mb-1">Masuk</h5>
                <p class="text-muted small mb-4">Masuk dengan akun NIK Anda</p>
                
                <?= $pesan; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">NIK</label>
                        <input type="number" name="nik" class="form-control py-2" placeholder="Masukkan NIK Anda" required style="background-color: #f1f3f5; border: none;">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control py-2" placeholder="Masukkan password" required style="background-color: #f1f3f5; border: none;">
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2 mb-3 shadow-sm">Masuk</button>
                    <div class="text-center small">
                        Belum punya akun? <a href="register.php" class="text-danger fw-bold text-decoration-none">Daftar sekarang</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>