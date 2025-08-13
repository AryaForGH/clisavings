<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include('header.php');
?>

<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Kontak Developer</h3>
        </div>
        <div class="card-body text-center">
            <p class="mb-3">Punya pertanyaan, kritik, ataupun saran?</p>
            <p class="mb-3">Silakan hubungi saya melalui media berikut:</p>

            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Email</span>
                    <a href="mailto:cam.yak25@gmail.com">cam.yak25.com</a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>WhatsApp</span>
                    <a href="https://wa.me/6283857803520" target="_blank">+62 838-5780-3520</a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Instagram</span>
                    <a href="https://instagram.com/cliarym" target="_blank">@cliarym</a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>LinkedIn</span>
                    <a href="https://linkedin.com/in/username" target="_blank">LinkedIn Profile</a>
                </li>
            </ul>

            <p class="text-muted">Klik link untuk langsung menghubungi saya.</p>
        </div>
        <div class="card-footer text-center text-muted">
            &copy; <?php echo date("Y"); ?> Cli Savings
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
