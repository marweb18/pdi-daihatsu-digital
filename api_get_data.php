<?php
header('Content-Type: application/json');
require 'koneksi.php';

// Ambil statistik simpel
$q = $conn->query("SELECT 
    (SELECT COUNT(*) FROM inspeksi) as total,
    (SELECT COUNT(*) FROM inspeksi WHERE status_keputusan='DITERIMA') as ok,
    (SELECT COUNT(*) FROM inspeksi WHERE status_keputusan='DITOLAK') as nok
");

$data = $q->fetch_assoc();

// Keluarkan data dalam format JSON agar bisa dibaca aplikasi lain
echo json_encode([
    "status" => "success",
    "data_inspeksi" => $data,
    "source" => "PDI Digital System - Marhan Sharu Ridho"
]);