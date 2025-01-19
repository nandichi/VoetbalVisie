<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/Auth.php';

session_start();

$auth = new Auth();
$error = '';

if ($auth->isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    if (empty($email) || empty($wachtwoord)) {
        $error = 'Vul alle velden in';
    } else {
        if ($auth->login($email, $wachtwoord)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Ongeldige inloggegevens';
        }
    }
}

include 'includes/header.php';
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-8">
    <h1 class="text-2xl font-bold mb-6">Inloggen</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="space-y-4">
        <div>
            <label class="block text-gray-700">E-mail</label>
            <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div>
            <label class="block text-gray-700">Wachtwoord</label>
            <input type="password" name="wachtwoord" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
            Inloggen
        </button>
    </form>

    <p class="mt-4 text-center">
        Nog geen account? <a href="register.php" class="text-blue-500 hover:text-blue-600">Registreer hier</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?> 