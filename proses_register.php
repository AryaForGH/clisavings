<?php
session_start();

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "clisavings";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$username     = trim($_POST['username']);
$nama_lengkap = trim($_POST['nama_lengkap']);
$nomor_hp     = trim($_POST['nomor_hp']);
$email        = !empty($_POST['email']) ? trim($_POST['email']) : NULL;
$password     = $_POST['password'];
$confirm_pass = $_POST['confirm_password'];

// Validasi password
$pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
if (!preg_match($pattern, $password)) {
    $_SESSION['error'] = "Password harus mengandung huruf besar, huruf kecil, angka, simbol, dan minimal 8 karakter.";
    header("Location: register.php");
    exit;
}

// Cek konfirmasi password
if ($password !== $confirm_pass) {
    $_SESSION['error'] = "Konfirmasi password tidak cocok.";
    header("Location: register.php");
    exit;
}

// Cek apakah username sudah ada
$stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['error'] = "Username sudah digunakan, silakan pilih yang lain.";
    header("Location: register.php");
    exit;
}
$stmt->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Simpan data ke database
$stmt = $conn->prepare("INSERT INTO user (username, nama_lengkap, nomor_hp, email, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $nama_lengkap, $nomor_hp, $email, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
    header("Location: login.php");
} else {
    $_SESSION['error'] = "Terjadi kesalahan saat menyimpan data.";
    header("Location: register.php");
}

$stmt->close();
$conn->close();
?>
