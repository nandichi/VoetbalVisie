<?php

class ApiFootball {
    private $apiKey;
    private $apiUrl;
    private $leagueIds = [
        'premier-league' => '152', // Premier League
        'la-liga' => '302',       // La Liga (was 175)
        'bundesliga' => '175',    // Bundesliga (was 195)
        'serie-a' => '207',       // Serie A
        'ligue-1' => '168'        // Ligue 1
    ];

    private $playerPhotos = [
        // Premier League
        'E. Haaland' => 'https://resources.premierleague.com/premierleague/photos/players/250x250/p223094.png',
        'M. Salah' => 'https://resources.premierleague.com/premierleague/photos/players/250x250/p118748.png',
        'O. Watkins' => 'https://resources.premierleague.com/premierleague/photos/players/250x250/p178301.png',
        'J. Bowen' => 'https://resources.premierleague.com/premierleague/photos/players/250x250/p178186.png',
        'H. Son' => 'https://resources.premierleague.com/premierleague/photos/players/250x250/p85971.png',
        
        // La Liga
        'J. Bellingham' => 'https://digitalhub.fifa.com/transform/40e6d6b5-9742-4123-8fb8-d69662c3b24a/1442141435',
        'A. Griezmann' => 'https://digitalhub.fifa.com/transform/e5c5ef06-d2f2-4230-96ae-2e1a3f0f99e4/Antoine-GRIEZMANN-France-2022',
        'Morata' => 'https://digitalhub.fifa.com/transform/08d01583-a0c6-4384-b4c1-c7691a34844e/Spain-Portraits-FIFA-World-Cup-Qatar-2022',
        'R. Lewandowski' => 'https://digitalhub.fifa.com/transform/2f08e4c5-c316-4534-9b6f-9c3eff0e9398/Robert-LEWANDOWSKI-Poland',
        'V. Guzmán' => 'https://assets.laliga.com/squad/2023/t188/p255937/512x512/p255937_t188_2023_1_003_000.png',
        
        // Bundesliga
        'H. Kane' => 'https://img.bundesliga.com/tachyon/sites/2/2023/08/Kane_Harry_FCB_0124-1.jpg',
        'S. Fullkrug' => 'https://img.bundesliga.com/tachyon/sites/2/2023/08/FCBD05-1.jpg',
        'L. Sane' => 'https://img.bundesliga.com/tachyon/sites/2/2023/07/FCBS19-1.jpg',
        'S. Tel' => 'https://img.bundesliga.com/tachyon/sites/2/2023/07/FCBT39.jpg',
        'J. Nmecha' => 'https://img.bundesliga.com/tachyon/sites/2/2023/07/BVBN8.jpg',
        
        // Serie A
        'L. Martinez' => 'https://assets.legaseriea.it/archive/legaseriea/jpg/MARTINEZ_LAUTARO_INTER_2324_C.jpg',
        'O. Giroud' => 'https://assets.legaseriea.it/archive/legaseriea/jpg/GIROUD_OLIVIER_MILAN_2324_C.jpg',
        'D. Vlahovic' => 'https://assets.legaseriea.it/archive/legaseriea/jpg/VLAHOVIC_DUSAN_JUVENTUS_2324_C.jpg',
        'R. Lukaku' => 'https://assets.legaseriea.it/archive/legaseriea/jpg/LUKAKU_ROMELU_ROMA_2324_C.jpg',
        'V. Osimhen' => 'https://assets.legaseriea.it/archive/legaseriea/jpg/OSIMHEN_VICTOR_NAPOLI_2324_C.jpg',
        
        // Ligue 1
        'K. Mbappe' => 'https://digitalhub.fifa.com/transform/fd1c1554-0043-4179-abe9-d152cf10e9dd/Kylian-MBAPPE-France-2022',
        'W. Ben Yedder' => 'https://www.ligue1.com/-/media/Project/LFP/Ligue1/Images/Players/2023-2024/00/51900.jpg',
        'A. Lacazette' => 'https://www.ligue1.com/-/media/Project/LFP/Ligue1/Images/Players/2023-2024/00/35300.jpg',
        'R. Openda' => 'https://www.ligue1.com/-/media/Project/LFP/Ligue1/Images/Players/2023-2024/00/99000.jpg',
        'P. Aubameyang' => 'https://www.ligue1.com/-/media/Project/LFP/Ligue1/Images/Players/2023-2024/00/51200.jpg'
    ];

    public function __construct() {
        $this->apiKey = API_FOOTBALL_KEY;
        $this->apiUrl = API_FOOTBALL_URL;
    }

    public function getUpcomingMatches($leagues = null) {
        if ($leagues === null) {
            $leagues = array_values($this->leagueIds);
        }
        
        $matches = [];
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d', strtotime('+7 days'));

        foreach ($leagues as $league) {
            $url = $this->apiUrl . "?action=get_events&from={$fromDate}&to={$toDate}&league_id={$league}&APIkey=" . $this->apiKey;
            
            // Debug: Print de URL (zonder API key voor veiligheid)
            $debugUrl = preg_replace('/APIkey=([^&]*)/', 'APIkey=HIDDEN', $url);
            error_log('Requesting URL: ' . $debugUrl);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            
            // Check voor curl errors
            if (curl_errno($ch)) {
                error_log('Curl error: ' . curl_error($ch));
                continue;
            }
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            error_log('HTTP Status Code: ' . $httpCode);
            
            curl_close($ch);

            // Debug informatie
            error_log('API Response for league ' . $league . ': ' . substr($response, 0, 500) . '...');

            $data = json_decode($response, true);

            // Check voor json decode errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log('JSON decode error: ' . json_last_error_msg());
                continue;
            }

            // Check of de data een array is en niet leeg
            if (!empty($data) && is_array($data)) {
                foreach ($data as $match) {
                    // Debug: Print match data
                    error_log('Processing match: ' . print_r($match, true));
                    
                    // Controleer of alle benodigde velden aanwezig zijn
                    if (!isset($match['match_hometeam_name']) || 
                        !isset($match['match_awayteam_name']) || 
                        !isset($match['match_date']) || 
                        !isset($match['match_time'])) {
                        error_log('Missing required fields in match data');
                        continue;
                    }

                    $matches[] = [
                        'team1' => $match['match_hometeam_name'],
                        'team2' => $match['match_awayteam_name'],
                        'datum' => $match['match_date'] . ' ' . $match['match_time'],
                        'league' => $match['league_name'] ?? 'Onbekende competitie',
                        'team1_logo' => $match['team_home_badge'] ?? '',
                        'team2_logo' => $match['team_away_badge'] ?? '',
                        'stadium' => $match['match_stadium'] ?? ''
                    ];
                }
            } else {
                error_log('Invalid or empty data received for league ' . $league . '. Response: ' . substr($response, 0, 500));
            }
        }

        // Sorteer wedstrijden op datum
        if (!empty($matches)) {
            usort($matches, function($a, $b) {
                return strtotime($a['datum']) - strtotime($b['datum']);
            });
        }

        return $matches;
    }

    /**
     * Haalt de top clubs op uit de opgegeven competities
     * 
     * @param array $leagues Array van competitie namen
     * @param int $limit Maximum aantal clubs per competitie
     * @return array Array van clubs met hun logo's en competitie
     */
    public function getTopClubs($leagues, $limit = 5) {
        $topClubs = [];
        
        // Voorgedefinieerde top clubs per competitie
        $clubsData = [
            'Premier League' => [
                ['name' => 'Manchester City', 'logo' => 'https://media.api-sports.io/football/teams/50.png'],
                ['name' => 'Arsenal', 'logo' => 'https://media.api-sports.io/football/teams/42.png'],
                ['name' => 'Liverpool', 'logo' => 'https://media.api-sports.io/football/teams/40.png'],
                ['name' => 'Manchester United', 'logo' => 'https://media.api-sports.io/football/teams/33.png'],
                ['name' => 'Chelsea', 'logo' => 'https://media.api-sports.io/football/teams/49.png']
            ],
            'La Liga' => [
                ['name' => 'Real Madrid', 'logo' => 'https://media.api-sports.io/football/teams/541.png'],
                ['name' => 'Barcelona', 'logo' => 'https://media.api-sports.io/football/teams/529.png'],
                ['name' => 'Atlético Madrid', 'logo' => 'https://media.api-sports.io/football/teams/530.png'],
                ['name' => 'Sevilla', 'logo' => 'https://media.api-sports.io/football/teams/536.png'],
                ['name' => 'Real Betis', 'logo' => 'https://media.api-sports.io/football/teams/543.png']
            ],
            'Eredivisie' => [
                ['name' => 'Ajax', 'logo' => 'https://media.api-sports.io/football/teams/194.png'],
                ['name' => 'PSV', 'logo' => 'https://media.api-sports.io/football/teams/197.png'],
                ['name' => 'Feyenoord', 'logo' => 'https://media.api-sports.io/football/teams/196.png'],
                ['name' => 'AZ Alkmaar', 'logo' => 'https://media.api-sports.io/football/teams/195.png'],
                ['name' => 'FC Twente', 'logo' => 'https://media.api-sports.io/football/teams/201.png']
            ]
        ];

        foreach ($leagues as $league) {
            if (isset($clubsData[$league])) {
                $clubs = array_slice($clubsData[$league], 0, $limit);
                foreach ($clubs as $club) {
                    $topClubs[] = [
                        'name' => $club['name'],
                        'logo' => $club['logo'],
                        'league' => $league
                    ];
                }
            }
        }

        return $topClubs;
    }

    /**
     * Haalt live wedstrijden op
     * @return array Array van live wedstrijden
     */
    public function getLiveMatches() {
        $url = $this->apiUrl . "?action=get_events&match_live=1&APIkey=" . $this->apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $liveMatches = [];

        if (!empty($data) && is_array($data)) {
            foreach ($data as $match) {
                $liveMatches[] = [
                    'team1' => $match['match_hometeam_name'],
                    'team2' => $match['match_awayteam_name'],
                    'score1' => $match['match_hometeam_score'],
                    'score2' => $match['match_awayteam_score'],
                    'time' => $match['match_status'] ?? 'Live',
                    'league' => $match['league_name'],
                    'team1_logo' => $match['team_home_badge'] ?? '',
                    'team2_logo' => $match['team_away_badge'] ?? ''
                ];
            }
        }

        return $liveMatches;
    }

    /**
     * Haalt de topscorers op van een competitie
     * @param string $leagueId ID van de competitie
     * @param int $limit Aantal spelers om op te halen
     * @return array Array van topscorers
     */
    public function getTopScorers($leagueId = null, $limit = 5) {
        if ($leagueId === null) {
            $leagueId = '152'; // Default naar Premier League
        }

        $topScorers = $this->getTopScorersFromMainApi($leagueId, $limit);
        
        // Als we geen resultaten krijgen van de hoofdAPI, probeer de backup
        if (empty($topScorers)) {
            $league = array_search($leagueId, $this->leagueIds);
            if ($league !== false) {
                $topScorers = $this->getFallbackTopScorers($league);
            }
        }

        return $topScorers;
    }

    private function getPlayerImageFromSofascore($playerName) {
        // Verwijder initialen en maak de naam URL-vriendelijk
        $name = strtolower(preg_replace('/[^a-zA-Z ]/', '', $playerName));
        $name = str_replace(' ', '-', trim($name));
        
        // Basis URL voor Sofascore spelersfoto's
        return "https://api.sofascore.app/api/v1/player/{$name}/image";
    }

    private function getPlayerImageFromFootapi($playerName) {
        // Verwijder initialen en maak de naam URL-vriendelijk
        $name = strtolower(preg_replace('/[^a-zA-Z ]/', '', $playerName));
        $name = str_replace(' ', '-', trim($name));
        
        // Basis URL voor Footapi spelersfoto's
        return "https://footapi7.p.rapidapi.com/api/player/{$name}/image";
    }

    private function getPlayerImageFromSportsdb($playerName) {
        // Verwijder initialen en maak de naam URL-vriendelijk
        $name = urlencode($playerName);
        $url = "https://www.thesportsdb.com/api/v1/json/3/searchplayers.php?p=" . $name;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        
        if (isset($data['player'][0]['strThumb']) && !empty($data['player'][0]['strThumb'])) {
            return $data['player'][0]['strThumb'];
        }
        
        return null;
    }

    private function getPlayerImage($playerName, $teamName) {
        // Verwijder speciale tekens en maak de naam URL-vriendelijk
        $cleanPlayerName = strtolower(preg_replace('/[^a-zA-Z ]/', '', $playerName));
        $cleanTeamName = strtolower(preg_replace('/[^a-zA-Z ]/', '', $teamName));
        
        // Basis URL voor spelersfoto's
        return "https://www.sportmonks.com/images/soccer/players/{$cleanPlayerName}.png";
    }

    private function getTopScorersFromMainApi($leagueId, $limit) {
        error_log("Ophalen topscorers voor league ID: " . $leagueId);
        $url = $this->apiUrl . "?action=get_topscorers&league_id={$leagueId}&APIkey=" . $this->apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log("Curl error bij ophalen topscorers: " . curl_error($ch));
            return [];
        }
        
        curl_close($ch);

        $data = json_decode($response, true);
        $topScorers = [];

        if (!empty($data) && is_array($data)) {
            $data = array_slice($data, 0, $limit);
            foreach ($data as $player) {
                $playerName = $player['player_name'];
                $teamName = $player['team_name'];
                
                // Gebruik een vaste URL structuur voor spelersfoto's
                $playerImage = "https://cdn.sportmonks.com/images/soccer/players/" . 
                             strtolower(str_replace(' ', '_', $playerName)) . ".png";

                // Fallback naar team logo als spelersfoto niet beschikbaar is
                $fallbackImage = $player['team_badge'] ?? 'https://www.svgrepo.com/show/5125/avatar.svg';
                
                $topScorers[] = [
                    'name' => $playerName,
                    'team' => $teamName,
                    'goals' => $player['goals'],
                    'team_logo' => $player['team_badge'] ?? '',
                    'player_image' => $playerImage,
                    'fallback_image' => $fallbackImage
                ];
            }
        }

        return $topScorers;
    }

    private function isImageValid($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode === 200;
    }

    private function getFallbackTopScorers($league) {
        // Gebruik de gratis API-Football-beta API
        $apiUrl = 'https://v3.football.api-sports.io';
        
        // League ID mapping voor de backup API
        $backupLeagueIds = [
            'premier-league' => '39',  // Premier League
            'la-liga' => '140',        // La Liga
            'bundesliga' => '78',      // Bundesliga
            'serie-a' => '135',        // Serie A
            'ligue-1' => '61'          // Ligue 1
        ];

        $leagueId = $backupLeagueIds[$league] ?? '39';
        $season = date('Y');

        $url = "{$apiUrl}/players/topscorers?league={$leagueId}&season={$season}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-rapidapi-host: v3.football.api-sports.io',
            'x-rapidapi-key: YOUR_API_KEY'  // Vervang dit met een echte API key als je die hebt
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $topScorers = [];

        if (isset($data['response']) && is_array($data['response'])) {
            foreach (array_slice($data['response'], 0, 5) as $player) {
                $playerName = $player['player']['name'];
                $topScorers[] = [
                    'name' => $playerName,
                    'team' => $player['statistics'][0]['team']['name'],
                    'goals' => $player['statistics'][0]['goals']['total'],
                    'team_logo' => $player['statistics'][0]['team']['logo'],
                    'player_image' => $player['player']['photo']
                ];
            }
        }

        return $topScorers;
    }

    /**
     * Haalt de spelersfoto op van WikiData
     * @param string $playerName Naam van de speler
     * @return string URL van de spelersfoto
     */
    private function getPlayerImageFromWikidata($playerName) {
        // Zoek eerst het Wikidata ID van de speler
        $searchUrl = 'https://www.wikidata.org/w/api.php?' . http_build_query([
            'action' => 'wbsearchentities',
            'search' => $playerName,
            'language' => 'en',
            'format' => 'json',
            'type' => 'item'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $searchUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'VoetbalVisie/1.0');
        
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        
        if (empty($data['search'])) {
            return 'https://www.svgrepo.com/show/5125/avatar.svg';
        }

        $entityId = $data['search'][0]['id'];

        // Haal nu de afbeelding op met het Wikidata ID
        $imageUrl = 'https://www.wikidata.org/w/api.php?' . http_build_query([
            'action' => 'wbgetclaims',
            'entity' => $entityId,
            'property' => 'P18', // P18 is de property voor afbeeldingen
            'format' => 'json'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'VoetbalVisie/1.0');
        
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['claims']['P18'][0]['mainsnak']['datavalue']['value'])) {
            $filename = $data['claims']['P18'][0]['mainsnak']['datavalue']['value'];
            $filename = str_replace(' ', '_', $filename);
            
            // Converteer de bestandsnaam naar een MD5 hash voor de URL structuur
            $hash = md5($filename);
            $prefix = substr($hash, 0, 1) . '/' . substr($hash, 0, 2);
            
            return "https://upload.wikimedia.org/wikipedia/commons/{$prefix}/{$filename}";
        }

        return 'https://www.svgrepo.com/show/5125/avatar.svg';
    }

    /**
     * Haalt de competitiestand op
     * @param string $league Sleutel van de competitie (bijv. 'premier-league')
     * @return array Array van teams met hun positie
     */
    public function getLeagueStandings($league = 'premier-league') {
        $leagueId = $this->leagueIds[$league] ?? '152'; // Fallback naar Premier League als de competitie niet bestaat
        $url = $this->apiUrl . "?action=get_standings&league_id={$leagueId}&APIkey=" . $this->apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        
        // Check voor curl errors
        if (curl_errno($ch)) {
            error_log('Curl error in getLeagueStandings: ' . curl_error($ch));
            return [];
        }
        
        curl_close($ch);

        $data = json_decode($response, true);
        
        // Debug informatie
        error_log('API Response for standings ' . $league . ': ' . substr($response, 0, 500));

        // Check voor json decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('JSON decode error in getLeagueStandings: ' . json_last_error_msg());
            return [];
        }

        $standings = [];

        // Controleer of de data geldig is
        if (!is_array($data)) {
            error_log('Invalid data format received for standings: ' . gettype($data));
            return [];
        }

        foreach ($data as $team) {
            // Controleer of team een array is en alle benodigde velden heeft
            if (!is_array($team) || 
                !isset($team['overall_league_position']) || 
                !isset($team['team_name']) ||
                !isset($team['overall_league_payed']) ||
                !isset($team['overall_league_W']) ||
                !isset($team['overall_league_D']) ||
                !isset($team['overall_league_L']) ||
                !isset($team['overall_league_GF']) ||
                !isset($team['overall_league_GA']) ||
                !isset($team['overall_league_PTS'])) {
                continue;
            }

            try {
                $standings[] = [
                    'position' => (int)$team['overall_league_position'],
                    'team' => (string)$team['team_name'],
                    'played' => (int)$team['overall_league_payed'],
                    'won' => (int)$team['overall_league_W'],
                    'drawn' => (int)$team['overall_league_D'],
                    'lost' => (int)$team['overall_league_L'],
                    'goals_for' => (int)$team['overall_league_GF'],
                    'goals_against' => (int)$team['overall_league_GA'],
                    'points' => (int)$team['overall_league_PTS'],
                    'team_logo' => $team['team_badge'] ?? ''
                ];
            } catch (Exception $e) {
                error_log('Error processing team data: ' . $e->getMessage());
                continue;
            }
        }

        // Sorteer op positie
        usort($standings, function($a, $b) {
            return $a['position'] - $b['position'];
        });

        return $standings;
    }

    public function getLeagueIds() {
        return $this->leagueIds;
    }
} 