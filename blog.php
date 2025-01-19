<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/Auth.php';
require_once 'includes/Blog.php';

$blog = new Blog();
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header('Location: index.php');
    exit;
}

$blogPost = $blog->getBlog($slug);

if (!$blogPost) {
    header('Location: index.php');
    exit;
}

include 'includes/header.php';
?>

<article class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md mt-8">
    <?php if ($blogPost['thumbnail']): ?>
        <img src="uploads/<?php echo htmlspecialchars($blogPost['thumbnail']); ?>" 
             alt="<?php echo htmlspecialchars($blogPost['titel']); ?>"
             class="w-full h-64 object-cover rounded-lg mb-6">
    <?php endif; ?>

    <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($blogPost['titel']); ?></h1>
    
    <div class="text-gray-600 mb-4">
        <span>Door <?php echo htmlspecialchars($blogPost['auteur_naam']); ?></span>
        <span class="mx-2">|</span>
        <span><?php echo date('d-m-Y', strtotime($blogPost['created_at'])); ?></span>
        <span class="mx-2">|</span>
        <span>Categorie: <?php echo htmlspecialchars($blogPost['categorie_naam']); ?></span>
    </div>

    <div class="prose max-w-none">
        <?php echo nl2br(htmlspecialchars($blogPost['content'])); ?>
    </div>
</article>

<?php include 'includes/footer.php'; ?> 