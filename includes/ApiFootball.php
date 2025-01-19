<?php

class ApiFootball {
    private $apiKey;
    private $apiUrl;

    public function __construct() {
        $this->apiKey = API_FOOTBALL_KEY;
        $this->apiUrl = API_FOOTBALL_URL;
    }

    public function getUpcomingMatches($leagues = ['152', '175']) { // 152 = Premier League, 175 = La Liga
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
} 