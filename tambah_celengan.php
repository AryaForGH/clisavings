<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('header.php');
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id            = $_SESSION['user_id'];
    $nama_tabungan      = $_POST['nama_tabungan'];
    $target_tabungan    = $_POST['target_tabungan'];
    $rencana_pengisian  = $_POST['rencana_pengisian'];
    $nominal_pengisian  = $_POST['nominal_pengisian'];

    // Hitung lama pencapaian (dalam hari)
    if ($rencana_pengisian === "Harian") {
        $lama_pencapaian = ceil($target_tabungan / $nominal_pengisian);
    } elseif ($rencana_pengisian === "Mingguan") {
        $lama_pencapaian = ceil($target_tabungan / $nominal_pengisian) * 7;
    } else { // Bulanan
        $lama_pencapaian = ceil($target_tabungan / $nominal_pengisian) * 30;
    }

    // Upload foto opsional
    $foto = NULL;
    if (!empty($_FILES['foto']['name'])) {
        $target_dir  = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name   = time() . "_" . basename($_FILES['foto']['name']);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $foto = $file_name;
        }
    }

    $query = "INSERT INTO celengan (user_id, foto, nama_tabungan, target_tabungan, rencana_pengisian, nominal_pengisian, lama_pencapaian) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issdsdi", $user_id, $foto, $nama_tabungan, $target_tabungan, $rencana_pengisian, $nominal_pengisian, $lama_pencapaian);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambah celengan.</div>";
    }
}
?>

<div class="container my-5">
    <h2>Tambah Celengan</h2>
    <form action="" method="POST" enctype="multipart/form-data" id="formCelengan">
        <div class="mb-3">
            <label for="foto" class="form-label">Tambah Foto (Opsional)</label>
            <input type="file" name="foto" id="foto" class="form-control">
        </div>

        <div class="mb-3">
            <label for="nama_tabungan" class="form-label">Nama Tabungan</label>
            <input type="text" name="nama_tabungan" id="nama_tabungan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="target_tabungan" class="form-label">Target Tabungan (Rp)</label>
            <input type="number" name="target_tabungan" id="target_tabungan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="rencana_pengisian" class="form-label">Rencana Pengisian</label>
            <select name="rencana_pengisian" id="rencana_pengisian" class="form-control" required>
                <option value="">-- Pilih --</option>
                <option value="Harian">Harian</option>
                <option value="Mingguan">Mingguan</option>
                <option value="Bulanan">Bulanan</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="nominal_pengisian" class="form-label">Nominal Pengisian (Rp)</label>
            <input type="number" name="nominal_pengisian" id="nominal_pengisian" class="form-control" required>
        </div>

        <div class="mb-3">
    <label class="form-label">Lama Pencapaian</label>
    <p id="lama_display" class="fw-bold text-success">-</p>
    <input type="hidden" name="lama_pencapaian" id="lama_pencapaian">
</div>


        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="dashboard.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
document.querySelectorAll("#target_tabungan, #nominal_pengisian, #rencana_pengisian").forEach(el => {
    el.addEventListener("input", hitungLamaPencapaian);
});

function hitungLamaPencapaian() {
    let target = parseFloat(document.getElementById("target_tabungan").value) || 0;
    let nominal = parseFloat(document.getElementById("nominal_pengisian").value) || 0;
    let rencana = document.getElementById("rencana_pengisian").value;
    let lama = 0;
    let displayText = "-";

    if (target > 0 && nominal > 0 && rencana) {
        let periode = "";
        if (rencana === "Harian") {
            lama = Math.ceil(target / nominal);
            periode = "Hari";
        } else if (rencana === "Mingguan") {
            lama = Math.ceil(target / nominal);
            periode = "Minggu";
        } else if (rencana === "Bulanan") {
            lama = Math.ceil(target / nominal);
            periode = "Bulan";
        }
        displayText = `${lama} ${periode}`;
    }

    document.getElementById("lama_display").textContent = displayText;
    document.getElementById("lama_pencapaian").value = lama > 0 ? lama : "";
}
</script>


<?php include('footer.php'); ?>
