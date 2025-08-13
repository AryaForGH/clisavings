<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('header.php');
include('koneksi.php');

// Ambil data celengan user
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, nama_tabungan, target_tabungan, terkumpul, foto FROM celengan WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container my-5">
    <h2>Halo, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>! 👋</h2>
    <p>Selamat datang di Cli Savings.</p>
    <p>Kejar targetmu!</p>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-warning text-center p-4 mt-4">
            <h5>Belum ada celengan dibuat, kuy nyeleng sekarang 🐷</h5>
            <a href="tambah_celengan.php" class="btn btn-primary mt-3">Tambah Celengan</a>
        </div>
    <?php else: ?>
        <h4 class="mb-4">Daftar Celengan Kamu</h4>

        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                    // Ambil & sanitasi nilai
                    $id        = isset($row['id']) ? (int)$row['id'] : 0;
                    $nama      = isset($row['nama_tabungan']) ? $row['nama_tabungan'] : '-';
                    $target    = isset($row['target_tabungan']) ? (float)$row['target_tabungan'] : 0.0;
                    $foto      = (!empty($row['foto'])) ? $row['foto'] : 'default.jpg';

                    // Kalau kamu sudah punya kolom `terkumpul` di tabel, pakai ini:
                    // $terkumpul = isset($row['terkumpul']) ? (float)$row['terkumpul'] : 0.0;

                    // Karena di skema yang kamu kirim belum ada kolom `terkumpul`,
                    // kita default-kan 0 dulu biar tidak error.
                    $terkumpul = isset($row['terkumpul']) ? (float)$row['terkumpul'] : 0.0;

                    $persen = ($target > 0) ? ($terkumpul / $target) * 100 : 0;
                    if ($persen < 0) $persen = 0;
                    if ($persen > 100) $persen = 100;

                    // Pastikan path foto
                    $imgPath = 'uploads/' . $foto;
                ?>
                <div class="col-md-4 mb-4">
                    <a href="detail_celengan.php?id=<?php echo $id; ?>" class="text-decoration-none">
                        <div class="card h-100 bg-dark text-light shadow-sm border-0">
                            <img src="<?php echo htmlspecialchars($imgPath); ?>"
                                 onerror="this.src='uploads/default.jpg';"
                                 class="card-img-top"
                                 alt="<?php echo htmlspecialchars($nama); ?>"
                                 style="height:200px; object-fit:cover;">
                            <div class="card-body">
                                <h5 class="card-title mb-2"><?php echo htmlspecialchars($nama); ?></h5>
                                <p class="card-text mb-2">
                                    Target: Rp<?php echo number_format($target, 0, ',', '.'); ?>
                                </p>
                                <div class="progress" style="height:20px;">
                                    <div class="progress-bar bg-success"
                                         role="progressbar"
                                         style="width: <?php echo number_format($persen, 2, '.', ''); ?>%;"
                                         aria-valuenow="<?php echo (int)$persen; ?>"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?php echo number_format($persen, 2, ',', '.'); ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>

        <a href="tambah_celengan.php" class="btn btn-success mt-3">Tambah Celengan</a>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>
