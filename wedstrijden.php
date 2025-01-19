<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/FootballMatch.php';

$match = new FootballMatch();
$competitions = $match->getAllCompetitions();

// Filter op competitie als er een is geselecteerd
$selected_competition = isset($_GET['competitie']) ? (int)$_GET['competitie'] : null;

if ($selected_competition) {
    $matches = $match->getMatchesByCompetition($selected_competition);
    // Haal de competitienaam op voor de titel
    foreach ($competitions as $comp) {
        if ($comp['id'] == $selected_competition) {
            $competition_name = $comp['naam'];
            break;
        }
    }
} else {
    $upcomingMatches = $match->getUpcomingMatches(10);
    $recentResults = $match->getRecentResults(10);
}

include 'includes/header.php';
?>

<div class="max-w-6xl mx-auto mt-8">
    <!-- Competitie filter -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Competities</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($competitions as $competition): ?>
                <a href="?competitie=<?php echo $competition['id']; ?>" 
                   class="block p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow <?php echo ($selected_competition == $competition['id']) ? 'ring-2 ring-blue-500' : ''; ?>">
                    <h3 class="font-semibold"><?php echo htmlspecialchars($competition['naam']); ?></h3>
                    <p class="text-sm text-gray-600">
                        <?php echo $competition['land'] ? htmlspecialchars($competition['land']) : ($competition['type'] == 'CUP' ? 'Internationaal' : ''); ?>
                    </p>
                </a>
            <?php endforeach; ?>
            <?php if ($selected_competition): ?>
                <a href="?" class="block p-4 bg-gray-100 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
                    <span class="font-semibold">Toon Alles</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($selected_competition): ?>
        <!-- Wedstrijden voor geselecteerde competitie -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-4">Wedstrijden <?php echo htmlspecialchars($competition_name); ?></h2>
            
            <?php if (empty($matches)): ?>
                <p class="text-gray-600">Geen wedstrijden gevonden voor deze competitie.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($matches as $match): ?>
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-center">
                                <div class="flex-1 text-right"><?php echo htmlspecialchars($match['team1']); ?></div>
                                <div class="mx-4 font-bold">
                                    <?php echo $match['uitslag'] ? htmlspecialchars($match['uitslag']) : 'vs'; ?>
                                </div>
                                <div class="flex-1 text-left"><?php echo htmlspecialchars($match['team2']); ?></div>
                            </div>
                            <div class="text-center text-sm text-gray-600 mt-2">
                                <?php echo date('d-m-Y H:i', strtotime($match['datum'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Aankomende Wedstrijden -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-4">Aankomende Wedstrijden</h2>
                
                <?php if (empty($upcomingMatches)): ?>
                    <p class="text-gray-600">Geen aankomende wedstrijden gepland.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($upcomingMatches as $match): ?>
                            <div class="border-b pb-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1 text-right"><?php echo htmlspecialchars($match['team1']); ?></div>
                                    <div class="mx-4 font-bold">vs</div>
                                    <div class="flex-1 text-left"><?php echo htmlspecialchars($match['team2']); ?></div>
                                </div>
                                <div class="text-center text-sm text-gray-600 mt-2">
                                    <?php echo date('d-m-Y H:i', strtotime($match['datum'])); ?>
                                    <?php if ($match['competitie_naam']): ?>
                                        <br>
                                        <span class="text-blue-600"><?php echo htmlspecialchars($match['competitie_naam']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recente Uitslagen -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-4">Recente Uitslagen</h2>
                
                <?php if (empty($recentResults)): ?>
                    <p class="text-gray-600">Geen recente uitslagen beschikbaar.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($recentResults as $result): ?>
                            <div class="border-b pb-4">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1 text-right"><?php echo htmlspecialchars($result['team1']); ?></div>
                                    <div class="mx-4 font-bold"><?php echo htmlspecialchars($result['uitslag']); ?></div>
                                    <div class="flex-1 text-left"><?php echo htmlspecialchars($result['team2']); ?></div>
                                </div>
                                <div class="text-center text-sm text-gray-600 mt-2">
                                    <?php echo date('d-m-Y', strtotime($result['datum'])); ?>
                                    <?php if ($result['competitie_naam']): ?>
                                        <br>
                                        <span class="text-blue-600"><?php echo htmlspecialchars($result['competitie_naam']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?> 