<?php
class Blog {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createBlog($titel, $content, $categorie_id, $gebruiker_id, $thumbnail = null) {
        $slug = $this->createSlug($titel);
        
        try {
            $stmt = $this->db->prepare("
                INSERT INTO blogs (titel, slug, content, categorie_id, gebruiker_id, thumbnail) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([$titel, $slug, $content, $categorie_id, $gebruiker_id, $thumbnail]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getBlog($slug) {
        try {
            $stmt = $this->db->prepare("
                SELECT b.*, c.naam as categorie_naam, u.naam as auteur_naam 
                FROM blogs b 
                LEFT JOIN categories c ON b.categorie_id = c.id 
                LEFT JOIN users u ON b.gebruiker_id = u.id 
                WHERE b.slug = ?
            ");
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getRecentBlogs($limit = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT b.*, c.naam as categorie_naam, u.naam as auteur_naam 
                FROM blogs b 
                INNER JOIN categories c ON b.categorie_id = c.id 
                INNER JOIN users u ON b.gebruiker_id = u.id 
                ORDER BY b.created_at DESC 
                LIMIT ?
            ");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function updateBlog($id, $titel, $content, $categorie_id, $thumbnail = null) {
        try {
            $sql = "UPDATE blogs SET titel = ?, content = ?, categorie_id = ?";
            $params = [$titel, $content, $categorie_id];

            if ($thumbnail) {
                $sql .= ", thumbnail = ?";
                $params[] = $thumbnail;
            }

            $sql .= " WHERE id = ?";
            $params[] = $id;

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteBlog($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM blogs WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    private function createSlug($string) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        return $slug;
    }
} 