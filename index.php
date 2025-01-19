<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/Blog.php';
require_once 'includes/FootballMatch.php';
require_once 'includes/ApiFootball.php';

$blog = new Blog();
$match = new FootballMatch();
$apiFootball = new ApiFootball();

$recentBlogs = $blog->getRecentBlogs(3);
$apiMatches = $apiFootball->getUpcomingMatches();

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
<div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 overflow-hidden">
    <!-- Decorative background patterns -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\" fill=\"rgba(255,255,255,0.07)\"%3E%3C/path%3E%3C/svg%3E')] opacity-20"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center min-h-[600px] px-4 py-16 sm:px-6 lg:px-8">
            <!-- Left Column: Text Content -->
            <div class="space-y-8">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight">
                    <span class="block">Welkom bij</span>
                    <span class="block text-blue-400">VoetbalVisie</span>
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl">
                    Jouw ultieme bron voor voetbalanalyses, wedstrijdverslagen en diepgaande inzichten in het mooiste spel ter wereld.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="wedstrijden.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-500 hover:bg-blue-400 transition-colors duration-300">
                        Bekijk Wedstrijden
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="blogs.php" class="inline-flex items-center px-6 py-3 border-2 border-blue-400 text-base font-medium rounded-md text-blue-100 hover:bg-blue-800 transition-colors duration-300">
                        Lees Blogs
                    </a>
                </div>
            </div>
            
            <!-- Right Column: Animated Elements -->
            <div class="relative hidden lg:block">
                <!-- Soccer ball animation -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <div class="w-64 h-64 rounded-full bg-gradient-to-br from-white to-gray-200 shadow-2xl animate-float">
                        <div class="absolute inset-2 rounded-full bg-[url('data:image/svg+xml,%3Csvg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"%3E%3Cpath d=\"M50 0 L100 25 L100 75 L50 100 L0 75 L0 25 Z\" fill=\"%23000\" fill-opacity=\"0.1\"/%3E%3C/svg%3E')] bg-repeat-space"></div>
                    </div>
                </div>
                
                <!-- Floating stats cards -->
                <div class="absolute top-1/4 right-0 transform translate-x-1/2 animate-float-delay-1">
                    <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 shadow-xl">
                        <div class="text-white text-sm font-semibold">Laatste Analyses</div>
                        <div class="text-blue-300 text-2xl font-bold">24/7</div>
                    </div>
                </div>
                
                <div class="absolute bottom-1/4 left-0 transform -translate-x-1/2 animate-float-delay-2">
                    <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 shadow-xl">
                        <div class="text-white text-sm font-semibold">Live Updates</div>
                        <div class="text-blue-300 text-2xl font-bold">Real-time</div>
                    </div>
                </div>
            </div>
        </div>
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
</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Laatste Blogs -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
            <h2 class="text-2xl font-bold text-white">Laatste Blogs</h2>
        </div>
        <div class="p-6">
            <?php if (empty($recentBlogs)): ?>
                <p class="text-gray-600">Nog geen blogs beschikbaar.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($recentBlogs as $post): ?>
                        <div class="group">
                            <a href="blog.php?slug=<?php echo urlencode($post['slug']); ?>" 
                               class="block hover:bg-gray-50 rounded-lg p-4 transition duration-150 ease-in-out">
                                <h3 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600">
                                    <?php echo htmlspecialchars($post['titel']); ?>
                                </h3>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <svg class="mr-1.5 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <?php echo date('d-m-Y', strtotime($post['created_at'])); ?>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Aankomende Wedstrijden -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white">Aankomende Wedstrijden</h2>
        </div>
        <div class="p-6">
            <?php if (empty($matchesByLeague)): ?>
                <p class="text-gray-600">Geen aankomende wedstrijden gevonden.</p>
            <?php else: ?>
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-8" aria-label="Tabs">
                        <?php 
                        $firstLeague = true;
                        foreach ($matchesByLeague as $leagueName => $leagueMatches): 
                            $tabId = 'tab-' . strtolower(str_replace(' ', '-', $leagueName));
                        ?>
                            <button 
                                onclick="switchTab('<?php echo $tabId; ?>')"
                                class="<?php echo $firstLeague ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'; ?> py-4 px-2 text-sm font-medium"
                                role="tab"
                                aria-selected="<?php echo $firstLeague ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo $tabId; ?>">
                                <?php echo htmlspecialchars($leagueName); ?>
                            </button>
                        <?php 
                            $firstLeague = false;
                        endforeach; 
                        ?>
                    </nav>
                </div>

                <!-- Tab panels -->
                <?php 
                $firstLeague = true;
                foreach ($matchesByLeague as $leagueName => $leagueMatches): 
                    $tabId = 'tab-' . strtolower(str_replace(' ', '-', $leagueName));
                    $total_matches = count($leagueMatches);
                    $total_pages = ceil($total_matches / $matches_per_page);
                ?>
                    <div 
                        id="<?php echo $tabId; ?>" 
                        class="<?php echo $firstLeague ? 'block' : 'hidden'; ?>"
                        role="tabpanel"
                        data-matches='<?php echo htmlspecialchars(json_encode($leagueMatches)); ?>'>
                        <div class="space-y-4 matches-container">
                            <!-- Wedstrijden worden hier dynamisch ingeladen via JavaScript -->
                        </div>

                        <!-- Paginering -->
                        <?php if ($total_pages > 1): ?>
                            <div class="mt-6 flex justify-center space-x-2">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <button
                                        onclick="changePage('<?php echo $tabId; ?>', <?php echo $i; ?>)"
                                        class="pagination-button <?php echo $i === 1 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> px-4 py-2 rounded-md text-sm font-medium"
                                        data-page="<?php echo $i; ?>">
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
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Template voor een wedstrijd
function createMatchHTML(match) {
    return `
        <div class="bg-white border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition duration-150 ease-in-out">
            <div class="grid grid-cols-7 items-center gap-4">
                <!-- Team 1 -->
                <div class="col-span-2 flex items-center justify-end space-x-3">
                    <span class="font-medium text-gray-900">${match.team1}</span>
                    ${match.team1_logo ? `<img src="${match.team1_logo}" alt="${match.team1}" class="w-8 h-8 object-contain">` : ''}
                </div>
                
                <!-- VS -->
                <div class="col-span-1 text-center">
                    <span class="text-sm font-medium text-gray-500">VS</span>
                </div>
                
                <!-- Team 2 -->
                <div class="col-span-2 flex items-center space-x-3">
                    ${match.team2_logo ? `<img src="${match.team2_logo}" alt="${match.team2}" class="w-8 h-8 object-contain">` : ''}
                    <span class="font-medium text-gray-900">${match.team2}</span>
                </div>
                
                <!-- Datum en Tijd -->
                <div class="col-span-2 flex flex-col text-sm text-gray-500">
                    <div class="flex items-center">
                        <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        ${new Date(match.datum).toLocaleString('nl-NL', { 
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}
                    </div>
                    ${match.stadium ? `
                        <div class="flex items-center mt-1">
                            <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"/>
                            </svg>
                            ${match.stadium}
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

// Functie om wedstrijden voor een pagina te tonen
function showMatches(tabId, page) {
    const panel = document.getElementById(tabId);
    const matches = JSON.parse(panel.dataset.matches);
    const container = panel.querySelector('.matches-container');
    const matchesPerPage = 6;
    const start = (page - 1) * matchesPerPage;
    const end = start + matchesPerPage;
    const matchesToShow = matches.slice(start, end);
    
    container.innerHTML = matchesToShow.map(match => createMatchHTML(match)).join('');
}

// Functie om van pagina te wisselen
function changePage(tabId, page) {
    const panel = document.getElementById(tabId);
    
    // Update paginering knoppen
    panel.querySelectorAll('.pagination-button').forEach(button => {
        if (parseInt(button.dataset.page) === page) {
            button.classList.remove('bg-gray-100', 'text-gray-700');
            button.classList.add('bg-blue-600', 'text-white');
        } else {
            button.classList.remove('bg-blue-600', 'text-white');
            button.classList.add('bg-gray-100', 'text-gray-700');
        }
    });
    
    // Toon wedstrijden voor de geselecteerde pagina
    showMatches(tabId, page);
}

// Tab wissel functie
function switchTab(tabId) {
    // Verberg alle tab panels
    document.querySelectorAll('[role="tabpanel"]').forEach(panel => {
        panel.classList.add('hidden');
    });
    
    // Verwijder actieve status van alle tabs
    document.querySelectorAll('[role="tab"]').forEach(tab => {
        tab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        tab.classList.add('text-gray-500');
        tab.setAttribute('aria-selected', 'false');
    });
    
    // Toon het geselecteerde panel
    const selectedPanel = document.getElementById(tabId);
    selectedPanel.classList.remove('hidden');
    
    // Update de actieve tab
    const selectedTab = document.querySelector(`[aria-controls="${tabId}"]`);
    selectedTab.classList.remove('text-gray-500');
    selectedTab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    selectedTab.setAttribute('aria-selected', 'true');

    // Toon de eerste pagina van wedstrijden
    showMatches(tabId, 1);
}

// Initialiseer de eerste tab met wedstrijden
document.addEventListener('DOMContentLoaded', function() {
    const firstTab = document.querySelector('[role="tabpanel"]');
    if (firstTab) {
        showMatches(firstTab.id, 1);
    }
});
</script>

<?php include 'includes/footer.php'; ?> 