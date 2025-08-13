<?php include('header.php'); ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 85vh; padding-top: 40px; padding-bottom: 40px;">
    <div class="card p-4 shadow-lg" style="background-color: #2b2b2b; color: #fff; max-width: 500px; width: 100%;">
        <h3 class="text-center mb-4">Daftar Akun</h3>
        <form action="proses_register.php" method="POST" onsubmit="return validatePassword()">
            <div class="mb-3">
                <label for="username" class="form-label">Username *</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="mb-3">
                <label for="nomor_hp" class="form-label">Nomor HP *</label>
                <input type="text" name="nomor_hp" id="nomor_hp" class="form-control" placeholder="Masukkan nomor HP"
                    pattern="\d{10,15}" title="Nomor HP harus 10-15 digit angka" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email (Opsional)</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password *</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Minimal 8 karakter, ada huruf besar, kecil, angka & simbol"
                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}"
                    title="Minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol"
                    required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password *</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Daftar</button>
            <button type="button" class="btn btn-secondary w-100" onclick="history.back()">Kembali</button>
        </form>
        <p class="mt-3 text-center">
            Sudah punya akun? <a href="login.php" class="text-info">Login disini</a>
        </p>
    </div>
</div>

<script>
function validatePassword() {
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm_password").value;
    let pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (!pattern.test(password)) {
        alert("Password harus mengandung huruf besar, huruf kecil, angka, simbol, dan minimal 8 karakter.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Konfirmasi password tidak cocok.");
        return false;
    }
    return true;
}
</script>

<?php include('footer.php'); ?>
