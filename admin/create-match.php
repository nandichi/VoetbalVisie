<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/Auth.php';
require_once '../includes/FootballMatch.php';

session_start();

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: ../login.php');
    exit;
}

$match = new FootballMatch();
$competitions = $match->getAllCompetitions();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team1 = $_POST['team1'] ?? '';
    $team2 = $_POST['team2'] ?? '';
    $datum = $_POST['datum'] ?? '';
    $competitie_id = $_POST['competitie_id'] ?? '';
    $uitslag = $_POST['uitslag'] ?? null;

    if (empty($team1) || empty($team2) || empty($datum) || empty($competitie_id)) {
        $error = 'Team 1, Team 2, datum en competitie zijn verplicht';
    } else {
        if ($match->createMatch($team1, $team2, $datum, $competitie_id, $uitslag)) {
            $success = 'Wedstrijd succesvol toegevoegd!';
        } else {
            $error = 'Er ging iets mis bij het toevoegen van de wedstrijd';
        }
    }
}

include '../includes/header.php';
?>

<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md mt-8">
    <h1 class="text-2xl font-bold mb-6">Nieuwe Wedstrijd Toevoegen</h1>

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
            <label class="block text-gray-700">Team 1</label>
            <input type="text" name="team1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div>
            <label class="block text-gray-700">Team 2</label>
            <input type="text" name="team2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div>
            <label class="block text-gray-700">Competitie</label>
            <select name="competitie_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                <option value="">Selecteer een competitie</option>
                <?php foreach ($competitions as $competition): ?>
                    <option value="<?php echo $competition['id']; ?>">
                        <?php echo htmlspecialchars($competition['naam']); ?>
                        <?php if ($competition['land']): ?>
                            (<?php echo htmlspecialchars($competition['land']); ?>)
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-gray-700">Datum en Tijd</label>
            <input type="datetime-local" name="datum" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
        </div>

        <div>
            <label class="block text-gray-700">Uitslag (optioneel)</label>
            <input type="text" name="uitslag" placeholder="bijv. 2-1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        </div>

        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
            Wedstrijd Toevoegen
        </button>
    </form>
</div>

<?php include '../includes/footer.php'; ?> 