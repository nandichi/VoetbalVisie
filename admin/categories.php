<?php
$root_path = dirname(__DIR__);
require_once $root_path . '/includes/config.php';
require_once $root_path . '/includes/db.php';
require_once $root_path . '/includes/Auth.php';

session_start();

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

$db = Database::getInstance()->getConnection();
$error = '';
$success = '';

// Helper functie om slug te genereren
function generateSlug($text) {
    // Vervang niet-letters/cijfers met een streepje
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Translitereer
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Verwijder ongewenste karakters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim streepjes
    $text = trim($text, '-');
    // Verwijder dubbele streepjes
    $text = preg_replace('~-+~', '-', $text);
    // Kleine letters
    $text = strtolower($text);
    
    return empty($text) ? 'n-a' : $text;
}

// Categorie toevoegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $naam = trim($_POST['naam'] ?? '');
    
    if (empty($naam)) {
        $error = 'Voer een categorienaam in';
    } else {
        $slug = generateSlug($naam);
        
        // Controleer of de slug al bestaat
        $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        $slugExists = $stmt->fetchColumn() > 0;
        
        if ($slugExists) {
            // Voeg een nummer toe aan de slug
            $i = 1;
            do {
                $newSlug = $slug . '-' . $i;
                $stmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
                $stmt->execute([$newSlug]);
                $slugExists = $stmt->fetchColumn() > 0;
                $i++;
            } while ($slugExists);
            $slug = $newSlug;
        }
        
        $stmt = $db->prepare("INSERT INTO categories (naam, slug) VALUES (?, ?)");
        if ($stmt->execute([$naam, $slug])) {
            $success = 'Categorie succesvol toegevoegd';
        } else {
            $error = 'Er ging iets mis bij het toevoegen van de categorie';
        }
    }
}

// Categorie verwijderen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? '';
    
    if (!empty($id)) {
        // Controleer eerst of er blogs zijn in deze categorie
        $stmt = $db->prepare("SELECT COUNT(*) FROM blogs WHERE categorie_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            $error = 'Deze categorie kan niet worden verwijderd omdat er nog blogs aan gekoppeld zijn';
        } else {
            $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
            if ($stmt->execute([$id])) {
                $success = 'Categorie succesvol verwijderd';
            } else {
                $error = 'Er ging iets mis bij het verwijderen van de categorie';
            }
        }
    }
}

// Alle categorieën ophalen
$categories = $db->query("SELECT id, naam, slug, (SELECT COUNT(*) FROM blogs WHERE categorie_id = categories.id) as blog_count FROM categories ORDER BY naam")->fetchAll();

include $root_path . '/includes/header.php';
?>

<div class="card max-w-4xl mx-auto mt-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Categorieën Beheren</h1>
    </div>

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

    <!-- Nieuwe categorie toevoegen -->
    <form method="POST" action="" class="mb-8">
        <input type="hidden" name="action" value="add">
        <div class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="naam" placeholder="Nieuwe categorie naam" class="form-input" required>
            </div>
            <button type="submit" class="btn btn-primary">Toevoegen</button>
        </div>
    </form>

    <!-- Lijst van bestaande categorieën -->
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Slug</th>
                    <th>Aantal Blogs</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['naam']); ?></td>
                        <td><code class="bg-gray-100 px-2 py-1 rounded"><?php echo htmlspecialchars($category['slug']); ?></code></td>
                        <td><?php echo $category['blog_count']; ?></td>
                        <td>
                            <form method="POST" action="" class="inline">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                <button type="submit" class="text-red-600 hover:text-red-800" 
                                        onclick="return confirm('Weet je zeker dat je deze categorie wilt verwijderen?')"
                                        <?php echo $category['blog_count'] > 0 ? 'disabled' : ''; ?>>
                                    Verwijderen
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include $root_path . '/includes/footer.php'; ?> 