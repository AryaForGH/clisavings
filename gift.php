<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('header.php');
?>

<div class="container my-5">
    <div class="card shadow-lg text-center">
        <div class="card-header bg-primary text-white">
            <h3>Gift untuk Developer</h3>
        </div>
        <div class="card-body">
            <p>Dukung Developer agar terus berkembang! 💖</p>
            <p>Scan QRIS berikut untuk memberikan gift/donasi:</p>
            
            <!-- QRIS Image -->
            <div class="my-3">
                <img src="QRIS.jpeg" alt="QRIS Developer" class="img-fluid" style="max-width: 300px;">
            </div>

            
        </div>
        <div class="card-footer">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Kembali ke Beranda</a>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
