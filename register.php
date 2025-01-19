<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/Auth.php';

session_start();

$auth = new Auth();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'] ?? '';
    $email = $_POST['email'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    $wachtwoord_bevestig = $_POST['wachtwoord_bevestig'] ?? '';

    if (empty($naam) || empty($email) || empty($wachtwoord)) {
        $error = 'Alle velden zijn verplicht';
    } elseif ($wachtwoord !== $wachtwoord_bevestig) {
        $error = 'Wachtwoorden komen niet overeen';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ongeldig e-mailadres';
    } else {
        if ($auth->register($naam, $email, $wachtwoord)) {
            $success = 'Registratie succesvol! Je kunt nu inloggen.';
        } else {
            $error = 'Er ging iets mis bij het registreren';
        }
    }
}

include 'includes/header.php';
?>

<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-8">
    <h1 class="text-2xl font-bold mb-6">Registreren</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" class="space-y-4">
        <div>
            <label class="block text-gray-700">Naam</label>
            <input type="text" name="naam" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div>
            <label class="block text-gray-700">E-mail</label>
            <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div>
            <label class="block text-gray-700">Wachtwoord</label>
            <input type="password" name="wachtwoord" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div>
            <label class="block text-gray-700">Bevestig wachtwoord</label>
            <input type="password" name="wachtwoord_bevestig" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
            Registreren
        </button>
    </form>

    <p class="mt-4 text-center">
        Heb je al een account? <a href="login.php" class="text-blue-500 hover:text-blue-600">Log hier in</a>
    </p>
</div>

<?php include 'includes/footer.php'; ?> 