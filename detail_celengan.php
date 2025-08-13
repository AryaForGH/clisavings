<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('header.php');
include('koneksi.php');

if (!isset($_GET['id'])) {
    echo "<div class='container my-5'><div class='alert alert-danger'>ID celengan tidak ditemukan.</div></div>";
    include('footer.php');
    exit;
}

$id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user_id'];

// Ambil data celengan
$sql = "SELECT id, foto, nama_tabungan, target_tabungan, rencana_pengisian, nominal_pengisian, lama_pencapaian, tanggal_dibuat, terkumpul, histori
        FROM celengan
        WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Data celengan tidak ditemukan.</div></div>";
    include('footer.php');
    exit;
}

$row = $res->fetch_assoc();

$nama       = $row['nama_tabungan'];
$target     = (float)$row['target_tabungan'];
$rencana    = $row['rencana_pengisian'];
$nominal    = (float)$row['nominal_pengisian'];
$lamaPlan   = (int)$row['lama_pencapaian'];
$foto       = !empty($row['foto']) ? $row['foto'] : 'default.jpg';
$tglBuat    = $row['tanggal_dibuat'];
$terkumpul  = (float)$row['terkumpul'];
$histori    = !empty($row['histori']) ? json_decode($row['histori'], true) : [];

$kekurangan = max(0, $target - $terkumpul);
$persen     = ($target > 0) ? min(100, ($terkumpul / $target) * 100) : 0;
$imgPath    = 'uploads/' . $foto;

// Estimasi tanggal tercapai
$estimasiTercapai = null;
if ($nominal > 0) {
    $sisaPeriode = ceil($kekurangan / $nominal);
    $estimasiTercapai = date('d M Y', strtotime("+{$sisaPeriode} days"));
}

// Flash message
$flashMessage = '';
if (isset($_SESSION['success'])) {
    $flashMessage = "<div class='alert alert-success'>{$_SESSION['success']}</div>";
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error'])) {
    $flashMessage = "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}
?>

<div class="container my-5">
    <?php echo $flashMessage; ?>

    <div class="card shadow-lg bg-dark text-light border-0">
        <div class="row g-0">
            <div class="col-md-5">
                <img src="<?php echo htmlspecialchars($imgPath); ?>"
                     class="img-fluid rounded-start h-100"
                     style="object-fit: cover;"
                     alt="<?php echo htmlspecialchars($nama); ?>"
                     onerror="this.src='uploads/default.jpg';">
            </div>
            <div class="col-md-7">
                <div class="card-body">
                    <h3 class="card-title mb-3"><?php echo htmlspecialchars($nama); ?></h3>

                    <p><strong>Target Tabungan:</strong> Rp<?php echo number_format($target, 0, ',', '.'); ?></p>
                    <p><strong>Terkumpul:</strong> Rp<?php echo number_format($terkumpul, 0, ',', '.'); ?></p>
                    <p><strong>Kekurangan:</strong> Rp<?php echo number_format($kekurangan, 0, ',', '.'); ?></p>
                    <p><strong>Rencana Pengisian:</strong> <?php echo htmlspecialchars($rencana); ?> (Rp<?php echo number_format($nominal, 0, ',', '.'); ?> / periode)</p>
                    <p><strong>Tanggal Dibuat:</strong> <?php echo date('d M Y H:i', strtotime($tglBuat)); ?></p>
                    <!-- <?php if ($estimasiTercapai): ?>
                        <p><strong>Estimasi Tercapai:</strong> <?php echo $estimasiTercapai; ?></p>
                    <?php endif; ?> -->

                    <div class="progress mb-3" style="height: 26px;">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: <?php echo number_format($persen, 2, '.', ''); ?>%;"
                             aria-valuenow="<?php echo (int)$persen; ?>"
                             aria-valuemin="0" aria-valuemax="100">
                             <?php echo number_format($persen, 2, ',', '.'); ?>%
                        </div>
                    </div>

                    <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali</a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#catatModal">📝 Catat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Histori Tabungan -->
    <div class="card mt-4 shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Histori Tabungan</h5>
        </div>
        <div class="card-body bg-light">
            <?php if (!empty($histori)): ?>
                <ul class="list-group">
                    <?php foreach (array_reverse($histori) as $h): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>
                                <?php echo date('d M Y H:i', strtotime($h['tanggal'])); ?> - 
                                <?php echo ucfirst($h['aksi']); ?> 
                                Rp<?php echo number_format($h['nominal'], 0, ',', '.'); ?>
                            </span>
                            <span class="badge bg-<?php echo ($h['aksi'] == 'tambah') ? 'success' : 'danger'; ?>">
                                <?php echo ($h['aksi'] == 'tambah') ? '+' : '-'; ?>Rp<?php echo number_format($h['nominal'], 0, ',', '.'); ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Belum ada histori tabungan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Catat -->
<div class="modal fade" id="catatModal" tabindex="-1" aria-labelledby="catatModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="catat_tabungan.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="catatModalLabel">Catat Tabungan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="celengan_id" value="<?php echo $id; ?>">
        <div class="mb-3">
          <label for="aksi" class="form-label">Jenis Aksi</label>
          <select name="aksi" id="aksi" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="tambah">Tambah</option>
            <option value="kurangi">Kurangi</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="nominal" class="form-label">Nominal</label>
          <input type="number" class="form-control" id="nominal" name="nominal" min="1" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?php include('footer.php'); ?>
