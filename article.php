<?php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'models/Article.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$article = new Article($db);

$article_data = $article->getById($id);

if (!$article_data) {
    header('Location: index.php');
    exit;
}
?>

<div class="card">
    <div class="card-body">
        <h1 class="card-title"><?php echo htmlspecialchars($article_data['title']); ?></h1>
        <div class="text-muted mb-4">
            By <?php echo htmlspecialchars($article_data['author_name']); ?> in 
            <?php echo htmlspecialchars($article_data['category_name']); ?> | 
            <?php echo date('F j, Y', strtotime($article_data['created_at'])); ?>
        </div>
        <div class="article-content">
            <?php echo nl2br(htmlspecialchars($article_data['content'])); ?>
        </div>
    </div>
</div>

<div class="mt-4">
    <h3>Comments</h3>
    <?php if(isset($_SESSION['user_id'])): ?>
        <form method="POST" action="add-comment.php">
            <input type="hidden" name="article_id" value="<?php echo $id; ?>">
            <div class="mb-3">
                <textarea class="form-control" name="comment" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
    <?php else: ?>
        <p>Please <a href="login.php">login</a> to comment.</p>
    <?php endif; ?>

    <?php
    $query = "SELECT c.*, u.username 
              FROM Comments c 
              JOIN Users u ON c.user_id = u.id 
              WHERE c.article_id = ? 
              ORDER BY c.created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="comments-list mt-4">
        <?php foreach($comments as $comment): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                        <small class="text-muted">
                            <?php echo date('F j, Y g:i a', strtotime($comment['created_at'])); ?>
                        </small>
                    </div>
                    <p class="mt-2 mb-0"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>