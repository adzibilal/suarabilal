<?php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'models/Article.php';

$database = new Database();
$db = $database->getConnection();
$article = new Article($db);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$articles = $article->getAll($limit, $offset);
?>

<h1 class="mb-4">Latest News</h1>

<?php foreach($articles as $article): ?>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h5>
            <h6 class="card-subtitle mb-2 text-muted">
                By <?php echo htmlspecialchars($article['author_name']); ?> in 
                <?php echo htmlspecialchars($article['category_name']); ?>
            </h6>
            <p class="card-text">
                <?php echo substr(htmlspecialchars($article['content']), 0, 200) . '...'; ?>
            </p>
            <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Read More</a>
        </div>
    </div>
<?php endforeach; ?>

<nav aria-label="Page navigation">
    <ul class="pagination">
        <?php if($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page-1; ?>">Previous</a>
            </li>
        <?php endif; ?>
        <li class="page-item">
            <a class="page-link" href="?page=<?php echo $page+1; ?>">Next</a>
        </li>
    </ul>
</nav>