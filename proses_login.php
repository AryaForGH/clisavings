<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "clisavings";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, username, nama_lengkap, password FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['nama_lengkap'] = $row['nama_lengkap'];

        header("Location: dashboard.php");
        exit;
    } else {
        header("Location: login.php?error=Password salah");
        exit;
    }
} else {
    header("Location: login.php?error=Username tidak ditemukan");
    exit;
}

$stmt->close();
$conn->close();
?>
