<?php
header('Content-Type: application/json'); // Kasih tahu kalau ini API
require 'koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // API biasanya menerima data mentah (JSON)
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $no_rangka = $data['no_rangka'];
    $status = $data['status_keputusan'];

    $sql = "INSERT INTO inspeksi (no_rangka, status_keputusan) VALUES ('$no_rangka', '$status')";
    
    if($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "Data masuk via API"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>