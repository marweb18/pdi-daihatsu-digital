<?php
// simpan_inspeksi.php
require 'koneksi.php'; // Panggil koneksi database

// Cek apakah ada data yang di-POST dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Tangkap data unit
    $model = $_POST['model'];
    $warna = $_POST['warna'];
    $no_rangka = $_POST['no_rangka'];
    $no_mesin = $_POST['no_mesin'];
    $km_pdc = isset($_POST['km_pdc']) ? (int)$_POST['km_pdc'] : 0;
    
    // Tangkap hasil perhitungan (dari input hidden JS)
    $total_ok = (int)$_POST['total_ok'];
    $total_nok = (int)$_POST['total_nok'];
    $total_na = (int)$_POST['total_na'];
    $status_keputusan = $_POST['status_keputusan'];

    // Tangkap Array Ceklis (puluhan data OK/NOK) dan ubah ke format JSON
    // Ini cara efisien agar tidak perlu buat 40 kolom di database
    $hasil_ceklis = json_encode($_POST['ceklis']);

    // Persiapkan Query menggunakan Prepared Statement agar aman dari SQL Injection
    $stmt = $conn->prepare("INSERT INTO inspeksi (model, no_rangka, no_mesin, warna, km_pdc, hasil_ceklis, total_ok, total_nok, total_na, status_keputusan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssisiiis", $model, $no_rangka, $no_mesin, $warna, $km_pdc, $hasil_ceklis, $total_ok, $total_nok, $total_na, $status_keputusan);

    // Eksekusi dan cek hasilnya
    if ($stmt->execute()) {
        echo "<script>
                alert('Data inspeksi berhasil disimpan!');
                window.location.href='form_inspeksi.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>