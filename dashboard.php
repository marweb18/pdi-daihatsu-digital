<?php
// dashboard.php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['login_status'])) {
    header("Location: index.php");
    exit;
}

// Set Zona Waktu & Bahasa Indonesia untuk Tanggal
date_default_timezone_set('Asia/Jakarta');
function tanggal_indo($tanggal){
    $bulan = array (1 => 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}

// Ambil Statistik
$q_total = $conn->query("SELECT COUNT(*) as total FROM inspeksi");
$total_unit = $q_total->fetch_assoc()['total'];

$q_ok = $conn->query("SELECT COUNT(*) as total FROM inspeksi WHERE status_keputusan = 'DITERIMA'");
$total_ok = $q_ok->fetch_assoc()['total'];

$q_nok = $conn->query("SELECT COUNT(*) as total FROM inspeksi WHERE status_keputusan = 'DITOLAK'");
$total_nok = $q_nok->fetch_assoc()['total'];

$q_riwayat = $conn->query("SELECT * FROM inspeksi ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard PDI Checksheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #fcfdfe; font-family: 'Inter', sans-serif; color: #333; }
        .navbar { background: white; border-bottom: 1px solid #eee; padding: 15px 0; }
        .welcome-section { padding: 25px 0; }
        .welcome-section h4 { font-weight: 700; margin-bottom: 5px; }
        .welcome-section p { color: #888; font-size: 0.9rem; }
        
        /* Card Styles */
        .stat-card { border: none; border-radius: 16px; padding: 20px; height: 100%; transition: 0.3s; }
        .icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 15px; }
        
        /* Warna Custom Sesuai Gambar */
        .bg-blue-light { background-color: #f0f7ff; }
        .icon-blue { background-color: #e1effe; color: #3f83f8; }
        
        .bg-green-light { background-color: #f3faf7; }
        .icon-green { background-color: #def7ec; color: #31c48d; }
        
        .bg-red-light { background-color: #fdf2f2; }
        .icon-red { background-color: #fde2e2; color: #f98080; }
        
        .bg-yellow-light { background-color: #fdfdea; }
        .icon-yellow { background-color: #feecdc; color: #f6ad55; }

        .stat-title { font-size: 0.75rem; font-weight: 700; color: #666; text-transform: uppercase; line-height: 1.2; }
        .stat-value { font-size: 1.8rem; font-weight: 700; margin: 5px 0; }
        .stat-desc { font-size: 0.75rem; color: #999; }

        /* Button Baru */
        .btn-main { background-color: #cc0000; color: white; border-radius: 16px; padding: 18px; border: none; width: 100%; display: flex; align-items: center; justify-content: space-between; font-weight: 600; box-shadow: 0 4px 15px rgba(204, 0, 0, 0.2); }
        .btn-main:hover { background-color: #a30000; color: white; }
        
        .logout-link { color: #888; text-decoration: none; font-size: 1.2rem; }
    </style>
</head>
<body>

<nav class="navbar shadow-sm">
    <div class="container d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <i class="bi bi-list fs-3 me-3"></i>
            <span class="fw-bold small">Pre-Delivery Inspection Checksheet</span>
        </div>
        <a href="logout.php" class="logout-link"><i class="bi bi-box-arrow-right"></i></a>
    </div>
</nav>

<div class="container">
    <div class="welcome-section">
        <h4>Selamat datang, <?= $_SESSION['nama_lengkap']; ?> 👋</h4>
        <p><?= tanggal_indo(date('Y-m-d')); ?></p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6">
            <div class="card stat-card bg-blue-light">
                <div class="icon-box icon-blue"><i class="bi bi-calendar-event"></i></div>
                <div class="stat-title">TOTAL UNIT<br>HARI INI</div>
                <div class="stat-value text-primary"><?= $total_unit; ?></div>
                <div class="stat-desc">inspeksi hari ini</div>
            </div>
        </div>
        <div class="col-6">
            <div class="card stat-card bg-green-light">
                <div class="icon-box icon-green"><i class="bi bi-check-circle"></i></div>
                <div class="stat-title">UNIT OK</div>
                <div class="stat-value text-success"><?= $total_ok; ?></div>
                <div class="stat-desc">diterima</div>
            </div>
        </div>
        <div class="col-6">
            <div class="card stat-card bg-red-light">
                <div class="icon-box icon-red"><i class="bi bi-x-circle"></i></div>
                <div class="stat-title">UNIT NOK</div>
                <div class="stat-value text-danger"><?= $total_nok; ?></div>
                <div class="stat-desc">ditolak</div>
            </div>
        </div>
        <div class="col-6">
            <div class="card stat-card bg-yellow-light">
                <div class="icon-box icon-yellow"><i class="bi bi-clock"></i></div>
                <div class="stat-title">PENDING</div>
                <div class="stat-value text-warning">0</div>
                <div class="stat-desc">menunggu</div>
            </div>
        </div>
    </div>

    <a href="form_inspeksi.php" class="btn btn-main mb-5">
        <div class="d-flex align-items-center">
            <div class="bg-white bg-opacity-25 rounded p-2 me-3">
                <i class="bi bi-clipboard-check fs-4"></i>
            </div>
            <div class="text-start">
                <div class="mb-0">Mulai Inspeksi Baru</div>
                <div style="font-size: 0.7rem; font-weight: normal; opacity: 0.8;">Buat PDI checksheet baru</div>
            </div>
        </div>
        <i class="bi bi-arrow-right fs-4"></i>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>