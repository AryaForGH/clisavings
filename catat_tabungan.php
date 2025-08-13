<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit;
}

$user_id     = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$celengan_id = isset($_POST['celengan_id']) ? (int)$_POST['celengan_id'] : 0;
$aksi        = isset($_POST['aksi']) ? $_POST['aksi'] : '';
$nominal     = isset($_POST['nominal']) ? (float)$_POST['nominal'] : 0;

if ($user_id <= 0 || $celengan_id <= 0) {
    $_SESSION['error'] = "Data tidak valid.";
    header("Location: detail_celengan.php?id=" . $celengan_id);
    exit;
}

if ($nominal <= 0) {
    $_SESSION['error'] = "Nominal harus lebih dari 0.";
    header("Location: detail_celengan.php?id=" . $celengan_id);
    exit;
}

if ($aksi !== 'tambah' && $aksi !== 'kurangi') {
    $_SESSION['error'] = "Jenis aksi tidak valid.";
    header("Location: detail_celengan.php?id=" . $celengan_id);
    exit;
}

// Ambil data celengan milik user
$sql = "SELECT terkumpul, target_tabungan, histori FROM celengan WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $celengan_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    $_SESSION['error'] = "Celengan tidak ditemukan.";
    header("Location: detail_celengan.php?id=" . $celengan_id);
    exit;
}

$data = $res->fetch_assoc();
$terkumpul_sekarang = (float)$data['terkumpul'];
$target             = (float)$data['target_tabungan'];
$histori_lama       = !empty($data['histori']) ? json_decode($data['histori'], true) : [];

// Hitung nilai baru
if ($aksi === 'tambah') {
    $terkumpul_baru = $terkumpul_sekarang + $nominal;
    if ($terkumpul_baru > $target) $terkumpul_baru = $target;
} else {
    $terkumpul_baru = $terkumpul_sekarang - $nominal;
    if ($terkumpul_baru < 0) $terkumpul_baru = 0;
}

// Tambahkan histori baru
$histori_lama[] = [
    'tanggal' => date('Y-m-d H:i:s'),
    'aksi' => $aksi,
    'nominal' => $nominal
];
$histori_json = json_encode($histori_lama);

// Update database
$upd = $conn->prepare("UPDATE celengan SET terkumpul = ?, histori = ? WHERE id = ? AND user_id = ?");
if (!$upd) {
    $_SESSION['error'] = "Gagal menyiapkan update: " . $conn->error;
    header("Location: detail_celengan.php?id=" . $celengan_id);
    exit;
}

$upd->bind_param("dsii", $terkumpul_baru, $histori_json, $celengan_id, $user_id);
if ($upd->execute()) {
    $_SESSION['success'] = "Catatan tabungan berhasil disimpan.";
} else {
    $_SESSION['error'] = "Gagal menyimpan catatan tabungan: " . $upd->error;
}

header("Location: detail_celengan.php?id=" . $celengan_id);
exit;
