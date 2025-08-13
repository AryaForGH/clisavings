<?php
$host = "localhost";
$user = "root"; // default XAMPP
$pass = ""; // default XAMPP kosong
$db   = "clisavings"; // ganti sesuai nama database kamu

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
