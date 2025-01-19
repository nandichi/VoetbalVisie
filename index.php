<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/Blog.php';
require_once 'includes/FootballMatch.php';
require_once 'includes/ApiFootball.php';

$match = new FootballMatch();
$apiFootball = new ApiFootball();

$apiMatches = $apiFootball->getUpcomingMatches();
$topScorers = $apiFootball->getTopScorers();

// Haal standen op voor de top 5 competities
$leagues = [
    'premier-league' => 'Premier League',
    'la-liga' => 'La Liga',
    'bundesliga' => 'Bundesliga',
    'serie-a' => 'Serie A',
    'ligue-1' => 'Ligue 1'
];
$allStandings = [];
foreach ($leagues as $key => $name) {
    $allStandings[$key] = $apiFootball->getLeagueStandings($key);
}

// Groepeer wedstrijden per competitie
$matchesByLeague = [];
foreach ($apiMatches as $match) {
    $leagueName = $match['league'];
    if (!isset($matchesByLeague[$leagueName])) {
        $matchesByLeague[$leagueName] = [];
    }
    $matchesByLeague[$leagueName][] = $match;
}

// Paginering instellingen
$matches_per_page = 6;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-[#0B132B] via-[#1C2541] to-[#3A506B] overflow-hidden min-h-[80vh]">
    <!-- Animated background patterns -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M54.627 16.072c1.941 3.359 2.373 6.709 2.373 10.928 0 4.219-0.432 7.569-2.373 10.928-1.94 3.359-4.145 5.858-7.504 7.799-3.359 1.941-6.709 2.373-10.928 2.373-4.219 0-7.569-0.432-10.928-2.373-3.359-1.94-5.858-4.145-7.799-7.504-1.941-3.359-2.373-6.709-2.373-10.928 0-4.219 0.432-7.569 2.373-10.928 1.94-3.359 4.145-5.858 7.504-7.799 3.359-1.941 6.709-2.373 10.928-2.373 4.219 0 7.569 0.432 10.928 2.373 3.359 1.94 5.858 4.145 7.799 7.504z\" fill=\"rgba(255,255,255,0.03)\"%3E%3C/path%3E%3C/svg%3E')] opacity-20 animate-spin-slow"></div>
    </div>
    
    <!-- Soccer field lines overlay -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"100\" height=\"100\" viewBox=\"0 0 100 100\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Crect width=\"100\" height=\"100\" fill=\"none\" stroke=\"rgba(255,255,255,0.05)\" stroke-width=\"2\"/%3E%3Ccircle cx=\"50\" cy=\"50\" r=\"30\" stroke=\"rgba(255,255,255,0.05)\" stroke-width=\"2\" fill=\"none\"/%3E%3Cline x1=\"0\" y1=\"50\" x2=\"100\" y2=\"50\" stroke=\"rgba(255,255,255,0.05)\" stroke-width=\"2\"/%3E%3C/svg%3E')] opacity-10"></div>

    <div class="relative max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center min-h-[80vh] px-4 py-16 sm:px-6 lg:px-8">
            <!-- Left Column: Text Content -->
            <div class="space-y-8 relative">
                <div class="relative">
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-tight">
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">VoetbalVisie</span>
                        <span class="block mt-2 text-3xl sm:text-4xl lg:text-5xl text-gray-300">Jouw Bron voor</span>
                        <span class="block text-3xl sm:text-4xl lg:text-5xl text-white">Voetbal Expertise</span>
                    </h1>
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-blue-500 rounded-full filter blur-[100px] opacity-30"></div>
                </div>
                
                <p class="text-xl text-gray-300 max-w-3xl leading-relaxed">
                    Duik diep in de wereld van voetbal met onze expert analyses, real-time wedstrijdverslagen en diepgaande tactische inzichten.
                </p>
                
                <div class="flex flex-wrap gap-6">
                    <a href="wedstrijden.php" class="group inline-flex items-center px-8 py-4 text-lg font-semibold rounded-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-blue-500/25">
                        Bekijk Wedstrijden
                        <svg class="ml-3 w-6 h-6 transition-transform duration-300 group-hover:translate-x-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="blogs.php" class="inline-flex items-center px-8 py-4 text-lg font-semibold rounded-lg text-white border-2 border-blue-400 hover:bg-blue-400/10 transition-all duration-300 transform hover:scale-105">
                        Ontdek Blogs
                    </a>
                </div>
            </div>
            
            <!-- Right Column: Animated Elements -->
            <div class="relative hidden lg:block">
                <!-- Club Slideshow -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px]">
                    <!-- Slideshow Container -->
                    <div class="relative aspect-square">
                        <!-- Background Layers -->
                        <div class="absolute inset-0 bg-gradient-to-br from-[#1e3a8a] via-[#1e40af] to-[#2563eb] rounded-3xl shadow-2xl overflow-hidden">
                            <!-- Animated gradient overlay -->
                            <div class="absolute inset-0 opacity-75 mix-blend-overlay animate-gradient">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent transform -skew-x-12"></div>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"100\" height=\"100\" viewBox=\"0 0 100 100\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath fill=\"%23ffffff\" fill-opacity=\"0.05\" d=\"M0 0h100v100H0z\" /%3E%3Cpath stroke=\"%23ffffff\" stroke-opacity=\"0.1\" stroke-width=\"0.5\" d=\"M0 20h100M0 40h100M0 60h100M0 80h100M20 0v100M40 0v100M60 0v100M80 0v100\" /%3E%3C/svg%3E')] opacity-20 rounded-3xl"></div>
                        
                        <!-- Slides -->
                        <div class="relative h-full club-slideshow overflow-hidden rounded-3xl">
                            <?php
                            $topClubs = $apiFootball->getTopClubs(array_values($leagues), 5);
                            
                            foreach ($topClubs as $index => $club):
                            ?>
                                <div class="club-slide absolute inset-0 flex flex-col items-center justify-center p-8 transition-all duration-700 ease-in-out <?php echo $index === 0 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full'; ?>">
                                    <!-- Club Logo Container -->
                                    <div class="relative mb-8 transform transition-transform duration-700 hover:scale-110">
                                        <!-- Glow Effect -->
                                        <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-xl"></div>
                                        <!-- Logo -->
                                        <div class="relative bg-gradient-to-br from-white/10 to-white/5 rounded-full p-8 backdrop-blur-sm border border-white/10">
                                            <img src="<?php echo htmlspecialchars($club['logo']); ?>" 
                                                 alt="<?php echo htmlspecialchars($club['name']); ?>"
                                                 class="w-48 h-48 object-contain drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]">
                                        </div>
                                    </div>
                                    
                                    <!-- Club Info -->
                                    <div class="text-center transform transition-all duration-700 translate-y-0">
                                        <h3 class="text-3xl font-bold text-white mb-3 tracking-tight">
                                            <?php echo htmlspecialchars($club['name']); ?>
                                        </h3>
                                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/10">
                                            <span class="text-blue-400 font-medium">
                                                <?php echo htmlspecialchars($club['league']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Navigation Dots -->
                        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2">
                            <?php for ($i = 0; $i < count($topClubs); $i++): ?>
                                <button class="club-dot w-2.5 h-2.5 rounded-full transition-all duration-300 <?php echo $i === 0 ? 'bg-blue-500 w-8' : 'bg-white/30 hover:bg-white/50'; ?>"
                                        data-index="<?php echo $i; ?>">
                                </button>
                            <?php endfor; ?>
                        </div>

                        <!-- Navigation Arrows -->
                        <button class="club-prev absolute left-4 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/10 transition-all duration-300 flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button class="club-next absolute right-4 top-1/2 transform -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/10 transition-all duration-300 flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Wedstrijden Overzicht -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Aankomende Wedstrijden</h2>

        <!-- Competitie Tabs -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 justify-center" aria-label="Tabs">
                    <?php 
                    $firstLeague = true;
                    foreach ($matchesByLeague as $leagueName => $matches): 
                        $tabId = strtolower(str_replace(' ', '-', $leagueName));
                    ?>
                        <button 
                            onclick="switchLeagueTab('<?php echo $tabId; ?>')"
                            class="league-tab <?php echo $firstLeague ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="<?php echo $tabId; ?>">
                            <?php echo htmlspecialchars($leagueName); ?>
                        </button>
                    <?php 
                        $firstLeague = false;
                    endforeach; 
                    ?>
                </nav>
            </div>
        </div>

        <!-- Wedstrijden per Competitie -->
        <?php 
        $firstLeague = true;
        foreach ($matchesByLeague as $leagueName => $matches):
            $tabId = strtolower(str_replace(' ', '-', $leagueName));
            $totalMatches = count($matches);
            $totalPages = ceil($totalMatches / $matches_per_page);
        ?>
            <div id="league-<?php echo $tabId; ?>" class="league-content <?php echo $firstLeague ? '' : 'hidden'; ?>">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 matches-container">
                    <?php 
                    $pageMatches = array_slice($matches, 0, $matches_per_page);
                    foreach ($pageMatches as $match): 
                    ?>
                        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 flex-1">
                                    <img src="<?php echo htmlspecialchars($match['team1_logo']); ?>" alt="" class="w-12 h-12 object-contain">
                                    <span class="font-semibold"><?php echo htmlspecialchars($match['team1']); ?></span>
                                </div>
                                <div class="px-4 py-2 bg-gray-50 rounded-lg">
                                    <span class="text-lg font-bold text-gray-700">VS</span>
                                </div>
                                <div class="flex items-center space-x-4 flex-1 justify-end">
                                    <span class="font-semibold"><?php echo htmlspecialchars($match['team2']); ?></span>
                                    <img src="<?php echo htmlspecialchars($match['team2_logo']); ?>" alt="" class="w-12 h-12 object-contain">
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between text-sm text-gray-500 border-t pt-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="font-medium"><?php echo date('d-m-Y H:i', strtotime($match['datum'])); ?></span>
                                </div>
                                <?php if (!empty($match['stadium'])): ?>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="font-medium"><?php echo htmlspecialchars($match['stadium']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginering -->
                <?php if ($totalPages > 1): ?>
                    <div class="mt-8 flex justify-center space-x-2">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <button 
                                onclick="changePage('<?php echo $tabId; ?>', <?php echo $i; ?>, <?php echo $matches_per_page; ?>)"
                                class="pagination-btn <?php echo $i === 1 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'; ?> px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-500 hover:text-white transition-colors"
                                data-page="<?php echo $i; ?>"
                                data-league="<?php echo $tabId; ?>">
                                <?php echo $i; ?>
                            </button>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php 
            $firstLeague = false;
        endforeach; 
        ?>
    </div>
</div>

<!-- Competitiestand Sectie -->
<div class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Competitie Standen</h2>
        
        <!-- Competitie Tabs -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 justify-center" aria-label="Tabs">
                    <?php 
                    $firstLeague = true;
                    foreach ($leagues as $key => $name): 
                    ?>
                        <button 
                            onclick="switchStandingsTab('<?php echo $key; ?>')"
                            class="standings-tab <?php echo $firstLeague ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="<?php echo $key; ?>">
                            <?php echo htmlspecialchars($name); ?>
                        </button>
                    <?php 
                        $firstLeague = false;
                    endforeach; 
                    ?>
                </nav>
            </div>
        </div>

        <!-- Standen per Competitie -->
        <?php 
        $firstLeague = true;
        foreach ($leagues as $key => $name):
            $standings = $allStandings[$key];
        ?>
            <div id="standings-<?php echo $key; ?>" class="standings-content <?php echo $firstLeague ? '' : 'hidden'; ?>">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h3 class="text-2xl font-bold text-white"><?php echo htmlspecialchars($name); ?></h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Pos</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Club</th>
                                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-600">GS</th>
                                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-600">W</th>
                                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-600">G</th>
                                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-600">V</th>
                                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-600">DV</th>
                                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-600">DT</th>
                                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-600">Pnt</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($standings as $index => $team): ?>
                                        <tr class="<?php echo $index % 2 === 0 ? 'bg-white' : 'bg-gray-50'; ?> hover:bg-blue-50 transition-colors">
                                            <td class="px-4 py-3 text-sm">
                                                <?php echo $team['position']; ?>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center space-x-3">
                                                    <img src="<?php echo htmlspecialchars($team['team_logo']); ?>" alt="" class="w-6 h-6 object-contain">
                                                    <span class="font-medium"><?php echo htmlspecialchars($team['team']); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm"><?php echo $team['played']; ?></td>
                                            <td class="px-4 py-3 text-center text-sm"><?php echo $team['won']; ?></td>
                                            <td class="px-4 py-3 text-center text-sm"><?php echo $team['drawn']; ?></td>
                                            <td class="px-4 py-3 text-center text-sm"><?php echo $team['lost']; ?></td>
                                            <td class="px-4 py-3 text-center text-sm"><?php echo $team['goals_for']; ?></td>
                                            <td class="px-4 py-3 text-center text-sm"><?php echo $team['goals_against']; ?></td>
                                            <td class="px-4 py-3 text-center font-bold"><?php echo $team['points']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            $firstLeague = false;
        endforeach; 
        ?>
    </div>
</div>

<!-- Topscorers Sectie -->
<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Topscorers</h2>
        
        <!-- Competitie Tabs -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 justify-center" aria-label="Tabs">
                    <?php 
                    $firstLeague = true;
                    foreach ($leagues as $key => $name): 
                        $leagueId = $apiFootball->getLeagueIds()[$key];
                        $leagueTopScorers[$key] = $apiFootball->getTopScorers($leagueId);
                    ?>
                        <button 
                            onclick="switchTopScorersTab('<?php echo $key; ?>')"
                            class="topscorers-tab <?php echo $firstLeague ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="<?php echo $key; ?>">
                            <?php echo htmlspecialchars($name); ?>
                        </button>
                    <?php 
                        $firstLeague = false;
                    endforeach; 
                    ?>
                </nav>
            </div>
        </div>

        <!-- Topscorers per League -->
        <?php 
        $firstLeague = true;
        foreach ($leagues as $key => $name):
        ?>
            <div id="topscorers-<?php echo $key; ?>" class="topscorers-content <?php echo $firstLeague ? '' : 'hidden'; ?>">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h3 class="text-2xl font-bold text-white"><?php echo htmlspecialchars($name); ?> Topscorers</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($leagueTopScorers[$key] as $index => $scorer): ?>
                                <div class="flex items-center justify-between p-4 <?php echo $index % 2 === 0 ? 'bg-gray-50' : 'bg-white'; ?> rounded-lg hover:bg-blue-50 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <!-- Ranking Number -->
                                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full <?php echo $index === 0 ? 'bg-yellow-400' : ($index === 1 ? 'bg-gray-300' : ($index === 2 ? 'bg-amber-600' : 'bg-blue-600')); ?> text-white font-bold text-xl">
                                            <?php echo $index + 1; ?>
                                        </div>
                                        
                                        <!-- Player Info -->
                                        <div>
                                            <h4 class="font-bold text-lg text-gray-900"><?php echo htmlspecialchars($scorer['name']); ?></h4>
                                            <span class="text-sm text-gray-600"><?php echo htmlspecialchars($scorer['team']); ?></span>
                                        </div>
                                    </div>

                                    <!-- Goals Badge -->
                                    <div class="bg-gray-100 rounded-full px-6 py-2 text-center">
                                        <div class="text-2xl font-bold <?php echo $index === 0 ? 'text-yellow-500' : ($index === 1 ? 'text-gray-500' : ($index === 2 ? 'text-amber-600' : 'text-blue-600')); ?>">
                                            <?php echo $scorer['goals']; ?>
                                        </div>
                                        <div class="text-xs text-gray-500">doelpunten</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            $firstLeague = false;
        endforeach; 
        ?>
    </div>
</div>

<!-- Populaire Clubs Sectie -->
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Populaire Clubs</h2>
    
    <!-- Competitie Tabs -->
    <div class="flex justify-center mb-8">
        <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1">
            <button onclick="switchLeague('premier-league')" class="league-tab active px-4 py-2 text-sm font-medium rounded-md" data-league="premier-league">
                Premier League
            </button>
            <button onclick="switchLeague('la-liga')" class="league-tab px-4 py-2 text-sm font-medium rounded-md" data-league="la-liga">
                La Liga
            </button>
        </div>
    </div>

    <!-- Premier League Clubs -->
    <div id="premier-league-clubs" class="league-content grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
        <a href="https://www.mancity.com" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/50.png" alt="Manchester City" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Manchester City</span>
            </div>
        </a>
        <a href="https://www.arsenal.com" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/42.png" alt="Arsenal" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Arsenal</span>
            </div>
        </a>
        <a href="https://www.liverpoolfc.com" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/40.png" alt="Liverpool" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Liverpool</span>
            </div>
        </a>
        <a href="https://www.manutd.com" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/33.png" alt="Manchester United" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Manchester United</span>
            </div>
        </a>
        <a href="https://www.chelseafc.com" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/49.png" alt="Chelsea" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Chelsea</span>
            </div>
        </a>
        <a href="https://www.tottenhamhotspur.com" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/47.png" alt="Tottenham" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Tottenham</span>
            </div>
        </a>
    </div>

    <!-- La Liga Clubs -->
    <div id="la-liga-clubs" class="league-content hidden grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
        <a href="https://www.realmadrid.com" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/541.png" alt="Real Madrid" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Real Madrid</span>
            </div>
        </a>
        <a href="https://www.fcbarcelona.com" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/529.png" alt="Barcelona" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Barcelona</span>
            </div>
        </a>
        <a href="https://www.atleticodemadrid.com" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/530.png" alt="Atlético Madrid" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Atlético Madrid</span>
            </div>
        </a>
        <a href="https://sevillafc.es" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/536.png" alt="Sevilla" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Sevilla</span>
            </div>
        </a>
        <a href="https://www.realbetisbalompie.es" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/543.png" alt="Real Betis" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Real Betis</span>
            </div>
        </a>
        <a href="https://www.realsociedad.eus" target="_blank" class="club-card">
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="relative w-20 h-20 mb-3">
                    <img src="https://media.api-sports.io/football/teams/548.png" alt="Real Sociedad" class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-300">
                </div>
                <span class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors text-center">Real Sociedad</span>
            </div>
        </a>
    </div>
</div>

<style>
    .league-tab {
        transition: all 0.3s ease;
    }
    .league-tab.active {
        background-color: #1a237e;
        color: white;
    }
    .league-tab:not(.active):hover {
        background-color: #f3f4f6;
    }
    .club-card {
        transition: transform 0.3s ease;
    }
    .club-card:hover {
        transform: translateY(-5px);
    }
</style>

<script>
    function switchLeague(league) {
        // Verberg alle league content
        document.querySelectorAll('.league-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Toon de geselecteerde league content
        document.getElementById(`${league}-clubs`).classList.remove('hidden');
        
        // Update active states van tabs
        document.querySelectorAll('.league-tab').forEach(tab => {
            if (tab.dataset.league === league) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });
    }

    function switchStandingsTab(leagueId) {
        // Update tab styling
        document.querySelectorAll('.standings-tab').forEach(tab => {
            const isActive = tab.dataset.tab === leagueId;
            tab.classList.toggle('border-blue-500', isActive);
            tab.classList.toggle('text-blue-600', isActive);
            tab.classList.toggle('border-transparent', !isActive);
            tab.classList.toggle('text-gray-500', !isActive);
        });

        // Toon/verberg content
        document.querySelectorAll('.standings-content').forEach(content => {
            content.classList.toggle('hidden', content.id !== `standings-${leagueId}`);
        });
    }

    function switchTopScorersTab(leagueId) {
        // Update tab styling
        document.querySelectorAll('.topscorers-tab').forEach(tab => {
            const isActive = tab.dataset.tab === leagueId;
            tab.classList.toggle('border-blue-500', isActive);
            tab.classList.toggle('text-blue-600', isActive);
            tab.classList.toggle('border-transparent', !isActive);
            tab.classList.toggle('text-gray-500', !isActive);
        });

        // Toon/verberg content
        document.querySelectorAll('.topscorers-content').forEach(content => {
            content.classList.toggle('hidden', content.id !== `topscorers-${leagueId}`);
        });
    }
</script>

<script>
// Functie om van competitie te wisselen
function switchLeagueTab(leagueId) {
    // Update tab styling
    document.querySelectorAll('.league-tab').forEach(tab => {
        const isActive = tab.dataset.tab === leagueId;
        tab.classList.toggle('border-blue-500', isActive);
        tab.classList.toggle('text-blue-600', isActive);
        tab.classList.toggle('border-transparent', !isActive);
        tab.classList.toggle('text-gray-500', !isActive);
    });

    // Toon/verberg content
    document.querySelectorAll('.league-content').forEach(content => {
        content.classList.toggle('hidden', content.id !== `league-${leagueId}`);
    });
}

// Functie om van pagina te wisselen
function changePage(leagueId, page, perPage) {
    const leagueData = <?php echo json_encode($matchesByLeague); ?>;
    const matches = leagueData[Object.keys(leagueData).find(key => 
        leagueId === key.toLowerCase().replace(/ /g, '-')
    )];
    
    const start = (page - 1) * perPage;
    const end = start + perPage;
    const pageMatches = matches.slice(start, end);
    
    // Update paginering buttons
    const container = document.querySelector(`#league-${leagueId} .matches-container`);
    container.innerHTML = pageMatches.map(match => `
        <div class="bg-white rounded-lg shadow p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 flex-1">
                    <img src="${match.team1_logo}" alt="" class="w-8 h-8 object-contain">
                    <span class="font-medium text-sm">${match.team1}</span>
                </div>
                <div class="px-3 py-1">
                    <span class="text-sm font-medium text-gray-600">VS</span>
                </div>
                <div class="flex items-center space-x-3 flex-1 justify-end">
                    <span class="font-medium text-sm">${match.team2}</span>
                    <img src="${match.team2_logo}" alt="" class="w-8 h-8 object-contain">
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                <span>${new Date(match.datum).toLocaleString('nl-NL', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })}</span>
                ${match.stadium ? `<span class="text-gray-400">${match.stadium}</span>` : ''}
            </div>
        </div>
    `).join('');

    // Update active state van paginering buttons
    document.querySelectorAll(`#league-${leagueId} .pagination-btn`).forEach(btn => {
        const isActive = parseInt(btn.dataset.page) === page;
        btn.classList.toggle('bg-blue-600', isActive);
        btn.classList.toggle('text-white', isActive);
        btn.classList.toggle('bg-gray-100', !isActive);
        btn.classList.toggle('text-gray-700', !isActive);
    });
}
</script>

<!-- Vervang de oude slideshow JavaScript met deze nieuwe versie -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slideshow = document.querySelector('.club-slideshow');
    const slides = document.querySelectorAll('.club-slide');
    const dots = document.querySelectorAll('.club-dot');
    const prevButton = document.querySelector('.club-prev');
    const nextButton = document.querySelector('.club-next');
    let currentSlide = 0;
    let slideInterval;

    function updateSlides(newIndex) {
        slides.forEach((slide, index) => {
            if (index === newIndex) {
                slide.classList.remove('opacity-0', 'translate-x-full', '-translate-x-full');
                slide.classList.add('opacity-100', 'translate-x-0');
            } else if (index < newIndex) {
                slide.classList.remove('opacity-100', 'translate-x-0', 'translate-x-full');
                slide.classList.add('opacity-0', '-translate-x-full');
            } else {
                slide.classList.remove('opacity-100', 'translate-x-0', '-translate-x-full');
                slide.classList.add('opacity-0', 'translate-x-full');
            }
        });

        dots.forEach((dot, index) => {
            if (index === newIndex) {
                dot.classList.remove('bg-white/30', 'w-2.5');
                dot.classList.add('bg-blue-500', 'w-8');
            } else {
                dot.classList.remove('bg-blue-500', 'w-8');
                dot.classList.add('bg-white/30', 'w-2.5');
            }
        });

        currentSlide = newIndex;
    }

    function nextSlide() {
        updateSlides((currentSlide + 1) % slides.length);
    }

    function prevSlide() {
        updateSlides((currentSlide - 1 + slides.length) % slides.length);
    }

    // Event Listeners
    prevButton.addEventListener('click', () => {
        clearInterval(slideInterval);
        prevSlide();
        startSlideshow();
    });

    nextButton.addEventListener('click', () => {
        clearInterval(slideInterval);
        nextSlide();
        startSlideshow();
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            clearInterval(slideInterval);
            updateSlides(index);
            startSlideshow();
        });
    });

    // Hover pause
    slideshow.addEventListener('mouseenter', () => clearInterval(slideInterval));
    slideshow.addEventListener('mouseleave', startSlideshow);

    // Start automatische slideshow
    function startSlideshow() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
    }

    startSlideshow();
});
</script>

<?php include 'includes/footer.php'; ?> 