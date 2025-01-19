<?php
$root_path = dirname(__DIR__);
require_once $root_path . '/includes/config.php';
require_once $root_path . '/includes/db.php';
require_once $root_path . '/includes/Auth.php';
require_once $root_path . '/includes/Blog.php';

session_start();

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: ' . SITE_URL . '/login.php');
    exit;
}

$blog = new Blog();
$error = '';
$success = '';

// Haal categorieën op
$db = Database::getInstance()->getConnection();
$categories = $db->query("SELECT * FROM categories ORDER BY naam")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titel = $_POST['titel'] ?? '';
    $content = $_POST['content'] ?? '';
    $categorie_id = $_POST['categorie_id'] ?? '';
    $thumbnail = null;

    // Thumbnail upload verwerking
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['thumbnail']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $upload_dir = $root_path . '/uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $new_filename = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $new_filename)) {
                $thumbnail = $new_filename;
            }
        }
    }

    if (empty($titel) || empty($content) || empty($categorie_id)) {
        $error = 'Alle velden zijn verplicht';
    } else {
        if ($blog->createBlog($titel, $content, $categorie_id, $_SESSION['user_id'], $thumbnail)) {
            $success = 'Blog succesvol toegevoegd!';
        } else {
            $error = 'Er ging iets mis bij het toevoegen van de blog';
        }
    }
}

include $root_path . '/includes/header.php';
?>

<div class="card max-w-4xl mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-6">Nieuwe Blog Toevoegen</h1>

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

    <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
        <div class="form-group">
            <label class="form-label">Titel</label>
            <input type="text" name="titel" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Categorie</label>
            <select name="categorie_id" class="form-input" required>
                <option value="">Selecteer een categorie</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['naam']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Thumbnail</label>
            <input type="file" name="thumbnail" accept="image/*" class="form-input">
            <p class="text-sm text-gray-500 mt-1">Toegestane formaten: JPG, JPEG, PNG, GIF</p>
        </div>

        <div class="form-group">
            <label class="form-label">Content</label>
            <textarea name="content" rows="10" class="form-input" required></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn btn-primary">
                Blog Toevoegen
            </button>
        </div>
    </form>
</div>

<?php include $root_path . '/includes/footer.php'; ?> 