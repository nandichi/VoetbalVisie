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

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-xl overflow-hidden">
        <!-- Header sectie -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
            <h1 class="text-3xl font-extrabold text-white">Nieuwe Blog Creëren</h1>
            <p class="mt-2 text-blue-100">Deel je voetbalkennis met de wereld</p>
        </div>

        <?php if ($error): ?>
            <div class="mx-8 mt-6">
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700"><?php echo htmlspecialchars($error); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="mx-8 mt-6">
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700"><?php echo htmlspecialchars($success); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" class="p-8">
            <div class="space-y-8 divide-y divide-gray-200">
                <div class="space-y-6">
                    <!-- Titel sectie -->
                    <div>
                        <label for="titel" class="block text-sm font-medium text-gray-700">Titel</label>
                        <div class="mt-1">
                            <input type="text" name="titel" id="titel" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="Voer een pakkende titel in">
                        </div>
                    </div>

                    <!-- Categorie sectie -->
                    <div>
                        <label for="categorie_id" class="block text-sm font-medium text-gray-700">Categorie</label>
                        <div class="mt-1">
                            <select name="categorie_id" id="categorie_id" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">Selecteer een categorie</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>">
                                        <?php echo htmlspecialchars($category['naam']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Thumbnail upload sectie -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Thumbnail</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="thumbnail" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload een bestand</span>
                                        <input id="thumbnail" name="thumbnail" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">of sleep en zet neer</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF tot 10MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Content sectie -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <div class="mt-1">
                            <textarea id="content" name="content" rows="10" required
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="Schrijf hier je blog content..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="button" onclick="window.history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                    Annuleren
                </button>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Blog Publiceren
                </button>
            </div>
        </form>
    </div>
</div>

<?php include $root_path . '/includes/footer.php'; ?> 