<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: profil.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$field   = isset($_POST['field']) ? $_POST['field'] : '';
$value   = isset($_POST['value']) ? trim($_POST['value']) : '';

$allowed_fields = ['username', 'nama_lengkap', 'password'];
if (!in_array($field, $allowed_fields)) {
    $_SESSION['error'] = "Field tidak valid.";
    header("Location: profil.php");
    exit;
}

if (empty($value)) {
    $_SESSION['error'] = "Nilai tidak boleh kosong.";
    header("Location: profil.php");
    exit;
}

// Jika password, lakukan validasi dan hash
if ($field === 'password') {
    $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    if ($value !== $confirm) {
        $_SESSION['error'] = "Konfirmasi password tidak sama.";
        header("Location: profil.php");
        exit;
    }

    // Validasi kekuatan password
    $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    if (!preg_match($regex, $value)) {
        $_SESSION['error'] = "Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol.";
        header("Location: profil.php");
        exit;
    }

    // Hash password
    $value = password_hash($value, PASSWORD_DEFAULT);
}

// Update database
$sql = "UPDATE user SET $field = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['error'] = "Gagal menyiapkan query: " . $conn->error;
    header("Location: profil.php");
    exit;
}

// selalu "si": string untuk password/username/nama, integer untuk id
$stmt->bind_param("si", $value, $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Profil berhasil diperbarui.";
} else {
    $_SESSION['error'] = "Gagal memperbarui profil: " . $stmt->error;
}

header("Location: profil.php");
exit;
?>
