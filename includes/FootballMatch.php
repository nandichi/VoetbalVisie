<?php
class FootballMatch {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createMatch($team1, $team2, $datum, $competitie_id, $uitslag = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO matches (team1, team2, datum, competitie_id, uitslag) 
                VALUES (?, ?, ?, ?, ?)
            ");
            return $stmt->execute([$team1, $team2, $datum, $competitie_id, $uitslag]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getMatch($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT m.*, c.naam as competitie_naam 
                FROM matches m 
                LEFT JOIN competitions c ON m.competitie_id = c.id 
                WHERE m.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getUpcomingMatches($limit = 5) {
        try {
            $stmt = $this->db->prepare("
                SELECT m.*, c.naam as competitie_naam 
                FROM matches m 
                LEFT JOIN competitions c ON m.competitie_id = c.id 
                WHERE m.datum >= CURRENT_DATE 
                ORDER BY m.datum ASC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getRecentResults($limit = 5) {
        try {
            $stmt = $this->db->prepare("
                SELECT m.*, c.naam as competitie_naam 
                FROM matches m 
                LEFT JOIN competitions c ON m.competitie_id = c.id 
                WHERE m.datum < CURRENT_DATE AND m.uitslag IS NOT NULL 
                ORDER BY m.datum DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function updateMatch($id, $team1, $team2, $datum, $competitie_id, $uitslag = null) {
        try {
            $stmt = $this->db->prepare("
                UPDATE matches 
                SET team1 = ?, team2 = ?, datum = ?, competitie_id = ?, uitslag = ? 
                WHERE id = ?
            ");
            return $stmt->execute([$team1, $team2, $datum, $competitie_id, $uitslag, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteMatch($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM matches WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllCompetitions() {
        try {
            return $this->db->query("SELECT * FROM competitions ORDER BY naam")->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getMatchesByCompetition($competitie_id, $limit = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT m.*, c.naam as competitie_naam 
                FROM matches m 
                LEFT JOIN competitions c ON m.competitie_id = c.id 
                WHERE m.competitie_id = ? 
                ORDER BY m.datum DESC 
                LIMIT ?
            ");
            $stmt->execute([$competitie_id, $limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
} 