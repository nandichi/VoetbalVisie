<?php 
$root_path = dirname(__DIR__);
require_once $root_path . '/includes/config.php';
require_once $root_path . '/includes/Auth.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$auth = new Auth();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="nav-container">
        <div class="nav-content">
            <a href="<?php echo SITE_URL; ?>" class="nav-brand">
                <i class="fas fa-futbol text-2xl mr-2"></i>
                <?php echo SITE_NAME; ?>
            </a>
            <div class="nav-links">
                <a href="<?php echo SITE_URL; ?>/blogs.php" class="nav-link">
                    <i class="fas fa-newspaper"></i> Blogs
                </a>
                <a href="<?php echo SITE_URL; ?>/wedstrijden.php" class="nav-link">
                    <i class="fas fa-futbol"></i> Wedstrijden
                </a>
                <?php if ($auth->isLoggedIn()): ?>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="nav-link flex items-center">
                            <i class="fas fa-user-shield mr-2"></i>
                            <span>Admin</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute right-0 mt-2 py-2 w-48 bg-white rounded-md shadow-xl z-50">
                            <a href="<?php echo SITE_URL; ?>/admin/create-blog.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-plus-circle mr-2"></i> Blog Toevoegen
                            </a>
                            <a href="<?php echo SITE_URL; ?>/admin/create-match.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-plus-circle mr-2"></i> Wedstrijd Toevoegen
                            </a>
                            <a href="<?php echo SITE_URL; ?>/admin/categories.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                                <i class="fas fa-tags mr-2"></i> Categorieën Beheren
                            </a>
                        </div>
                    </div>
                    <span class="nav-link">
                        <i class="fas fa-user mr-2"></i>
                        Welkom, <?php echo htmlspecialchars($_SESSION['user_naam']); ?>
                    </span>
                    <a href="<?php echo SITE_URL; ?>/logout.php" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt mr-2"></i> Uitloggen
                    </a>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/login.php" class="btn btn-secondary">
                        <i class="fas fa-sign-in-alt mr-2"></i> Inloggen
                    </a>
                    <a href="<?php echo SITE_URL; ?>/register.php" class="btn btn-primary">
                        <i class="fas fa-user-plus mr-2"></i> Registreren
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</body>
</html> 