<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/Blog.php';

$blog = new Blog();
$recentBlogs = $blog->getRecentBlogs(20);

include 'includes/header.php';
?>

<div class="max-w-6xl mx-auto mt-8">
    <h1 class="text-3xl font-bold mb-8">Alle Blogs</h1>

    <?php if (empty($recentBlogs)): ?>
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            Er zijn nog geen blogs beschikbaar.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($recentBlogs as $post): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if ($post['thumbnail']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($post['thumbnail']); ?>" 
                             alt="<?php echo htmlspecialchars($post['titel']); ?>"
                             class="w-full h-48 object-cover">
                    <?php endif; ?>

                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-2">
                            <a href="blog.php?slug=<?php echo urlencode($post['slug']); ?>" 
                               class="text-blue-600 hover:text-blue-800">
                                <?php echo htmlspecialchars($post['titel']); ?>
                            </a>
                        </h2>

                        <div class="text-gray-600 text-sm mb-4">
                            <span><?php echo date('d-m-Y', strtotime($post['created_at'])); ?></span>
                            <span class="mx-2">|</span>
                            <span><?php echo htmlspecialchars($post['categorie_naam']); ?></span>
                        </div>

                        <p class="text-gray-700">
                            <?php echo htmlspecialchars(substr($post['content'], 0, 150)) . '...'; ?>
                        </p>

                        <a href="blog.php?slug=<?php echo urlencode($post['slug']); ?>" 
                           class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                            Lees meer →
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?> 