<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/ApiFootball.php';

$apiFootball = new ApiFootball();
$liveMatches = $apiFootball->getLiveMatches();

if (empty($liveMatches)): ?>
    <div class="text-center text-white text-lg">
        <p>Er zijn momenteel geen live wedstrijden.</p>
    </div>
<?php else:
    // Groepeer live wedstrijden per competitie
    $liveMatchesByLeague = [];
    foreach ($liveMatches as $match) {
        $league = $match['league'];
        if (!isset($liveMatchesByLeague[$league])) {
            $liveMatchesByLeague[$league] = [];
        }
        $liveMatchesByLeague[$league][] = $match;
    }
    ?>

    <div class="grid grid-cols-1 gap-8">
        <?php foreach ($liveMatchesByLeague as $league => $matches): ?>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                <h3 class="text-xl font-semibold text-white mb-4"><?php echo htmlspecialchars($league); ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($matches as $match): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2 flex-1">
                                        <img src="<?php echo htmlspecialchars($match['team1_logo']); ?>" alt="" class="w-6 h-6 object-contain">
                                        <span class="font-medium text-sm"><?php echo htmlspecialchars($match['team1']); ?></span>
                                    </div>
                                    <div class="px-2 py-1 bg-blue-100 rounded text-sm">
                                        <span class="font-bold text-blue-800"><?php echo $match['score1']; ?> - <?php echo $match['score2']; ?></span>
                                    </div>
                                    <div class="flex items-center space-x-2 flex-1 justify-end">
                                        <span class="font-medium text-sm"><?php echo htmlspecialchars($match['team2']); ?></span>
                                        <img src="<?php echo htmlspecialchars($match['team2_logo']); ?>" alt="" class="w-6 h-6 object-contain">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">
                                        <?php echo htmlspecialchars($match['time']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?> 