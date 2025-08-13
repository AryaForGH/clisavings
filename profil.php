<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('header.php');
include('koneksi.php');

$user_id = (int)$_SESSION['user_id'];

// Ambil data user
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Data pengguna tidak ditemukan.</div></div>";
    include('footer.php');
    exit;
}

$user = $res->fetch_assoc();
$foto = !empty($user['foto']) ? $user['foto'] : 'default_profile.png';
?>

<div class="container my-5">
    <div class="card shadow-lg border-0" style="border-radius: 15px; overflow: hidden;">
        <div class="row g-0">
            <!-- Foto Profil -->
            <div class="col-md-4 bg-primary d-flex align-items-center justify-content-center p-4">
                <img src="uploads/<?php echo htmlspecialchars($foto); ?>" 
                     class="rounded-circle img-fluid" 
                     style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #fff;"
                     alt="Foto Profil"
                     onerror="this.src='uploads/default_profile.png';">
            </div>

            <!-- Info Profil -->
            <div class="col-md-8">
                <div class="card-body">
                    <h2 class="card-title fw-bold mb-3">
                        <?php echo htmlspecialchars($user['nama_lengkap']); ?> 
                        <button class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#editNamaModal">Edit Nama</button>
                    </h2>

                    <p class="mb-2">
                        <i class="bi bi-person-fill me-2"></i> <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?>
                        <button class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#editUsernameModal">Edit Username</button>
                    </p>

                    <p class="mb-2">
                        <i class="bi bi-lock-fill me-2"></i> <strong>Password:</strong> ********
                        <!-- <button class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#editPasswordModal">Edit Password</button> -->
                    </p>

                    <p class="mb-2"><i class="bi bi-envelope-fill me-2"></i> <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p class="mb-2"><i class="bi bi-calendar-fill me-2"></i> <strong>Tanggal Daftar:</strong> <?php echo date('d M Y H:i', strtotime($user['created_at'])); ?></p>
                </div>
                <div class="card-footer bg-transparent d-flex justify-content-end">
                    <a href="dashboard.php" class="btn btn-secondary me-2">⬅ Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Nama -->
<div class="modal fade" id="editNamaModal" tabindex="-1" aria-labelledby="editNamaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="update_profil.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editNamaModalLabel">Edit Nama Lengkap</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="field" value="nama_lengkap">
        <input type="text" name="value" class="form-control" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Username -->
<div class="modal fade" id="editUsernameModal" tabindex="-1" aria-labelledby="editUsernameModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="update_profil.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUsernameModalLabel">Edit Username</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="field" value="username">
        <input type="text" name="value" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Password -->
<div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="update_profil.php" method="POST" class="modal-content" onsubmit="return validatePassword()">
      <div class="modal-header">
        <h5 class="modal-title" id="editPasswordModalLabel">Edit Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="field" value="password">
        <div class="mb-3">
          <label for="newPassword" class="form-label">Password Baru</label>
          <input type="password" id="newPassword" name="value" class="form-control" required>
          <div class="form-text">Minimal 8 karakter, huruf besar, huruf kecil, angka, simbol</div>
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
          <input type="password" id="confirmPassword" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
function validatePassword() {
    const pwd = document.getElementById('newPassword').value;
    const confirm = document.getElementById('confirmPassword').value;
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (!regex.test(pwd)) {
        alert('Password tidak sesuai ketentuan!');
        return false;
    }
    if (pwd !== confirm) {
        alert('Konfirmasi password tidak sama!');
        return false;
    }
    return true;
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<?php include('footer.php'); ?>
