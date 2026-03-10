<?php
// koneksi.php
$host = "localhost";
$user = "root"; // Sesuaikan dengan user database kamu
$pass = "";     // Sesuaikan dengan password database kamu
$db   = "db_daihatsu_pdi";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>