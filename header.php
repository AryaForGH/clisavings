<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cli Savings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            background-color: #1c1c1c;
            color: #e0e0e0;
        }
        .navbar {
            background-color: #474545ff;
        }
        .navbar-brand, .nav-link, .dropdown-item {
            color: #e0e0e0 !important;
        }
        .btn-primary {
            background-color: #555;
            border: none;
        }
        .btn-primary:hover {
            background-color: #777;
        }
        main {
            flex: 1;
        }
        .dropdown-menu {
            background-color: #2b2b2b;
        }
        .dropdown-item:hover {
            background-color: #444;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">💰 Cli Savings</a>

        <?php if (isset($_SESSION['username'])): ?>
            <!-- Tombol Beranda -->
            

            <div class="dropdown ms-auto">
                
                <button class="btn btn-secondary dropdown-toggle" type="button" id="menuUser" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuUser">
                    <li><a class="dropdown-item" href="profil.php">Profil</a></li>
                    <li><a class="dropdown-item" href="gift.php">Gift untuk Developer</a></li>
                    <li><a class="dropdown-item" href="kontak.php">Kontak</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>


<main>
