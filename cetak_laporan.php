<?php
// cetak_laporan.php
require 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Cari data inspeksi berdasarkan ID
$query = $conn->query("SELECT * FROM inspeksi WHERE id = $id");
$data = $query->fetch_assoc();

if (!$data) {
    die("Data tidak ditemukan!");
}

// Bongkar data ceklis yang berformat JSON menjadi Array PHP
$ceklis = json_decode($data['hasil_ceklis'], true);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - <?= $data['no_rangka']; ?></title>
    <style>
        /* Styling khusus agar rapi saat dicetak ke PDF */
        body { font-family: Arial, sans-serif; padding: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #dc3545; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #dc3545; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .ceklis-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .ceklis-table th, .ceklis-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .ceklis-table th { background-color: #f8f9fa; }
        .status-ok { color: green; font-weight: bold; }
        .status-nok { color: red; font-weight: bold; }
        .summary { margin-top: 20px; font-weight: bold; text-align: right; }
        
        /* Menyembunyikan elemen tertentu saat mode print */
        @media print {
            @page { margin: 1cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>DAIHATSU PDI CHECKSHEET</h2>
        <p>Pre-Delivery Inspection Report</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Model</strong></td>
            <td width="35%">: <?= strtoupper($data['model']); ?></td>
            <td width="15%"><strong>Warna</strong></td>
            <td width="35%">: <?= strtoupper($data['warna']); ?></td>
        </tr>
        <tr>
            <td><strong>No. Rangka</strong></td>
            <td>: <?= strtoupper($data['no_rangka']); ?></td>
            <td><strong>No. Mesin</strong></td>
            <td>: <?= strtoupper($data['no_mesin']); ?></td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>: <?= date('d F Y', strtotime($data['created_at'])); ?></td>
            <td><strong>Keputusan</strong></td>
            <td>: <span class="<?= $data['status_keputusan'] == 'DITERIMA' ? 'status-ok' : 'status-nok'; ?>"><?= $data['status_keputusan']; ?></span></td>
        </tr>
    </table>

    <table class="ceklis-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="75%">Item Pengecekan</th>
                <th width="20%">Hasil</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Looping array ceklis yang dibongkar dari JSON
            if(is_array($ceklis)): 
                foreach($ceklis as $item => $hasil): 
                    // Merapikan nama variabel (contoh: all_body menjadi All Body)
                    $nama_item = ucwords(str_replace('_', ' ', $item));
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $nama_item; ?></td>
                <td class="<?= $hasil == 'OK' ? 'status-ok' : ($hasil == 'NOK' ? 'status-nok' : ''); ?>">
                    <?= $hasil; ?>
                </td>
            </tr>
            <?php 
                endforeach; 
            endif; 
            ?>
        </tbody>
    </table>

    <div class="summary">
        Total OK: <?= $data['total_ok']; ?> | Total NOK: <?= $data['total_nok']; ?>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>