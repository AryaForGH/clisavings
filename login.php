<?php include('header.php'); ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-lg" style="background-color: #2b2b2b; color: #fff; max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Login</h3>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger py-2 text-center">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
            <a href="index.php" class="btn btn-secondary w-100">Kembali</a>
        </form>
        <p class="mt-3 text-center">
            Belum punya akun? <a href="register.php" class="text-info">Daftar disini</a>
        </p>
    </div>
</div>

<?php include('footer.php'); ?>
