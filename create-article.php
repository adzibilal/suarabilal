<?php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'models/Article.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->getConnection(); // Initialize database connection first

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $article = new Article($db);

    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $category_id = $_POST['category_id'] ?? '';

    if (empty($title) || empty($content) || empty($category_id)) {
        $error = 'All fields are required';
    } else {
        if ($article->create($title, $content, $_SESSION['user_id'], $category_id)) {
            $success = 'Article created successfully!';
        } else {
            $error = 'Failed to create article. Please try again.';
        }
    }
}

// Get categories for dropdown
$query = "SELECT id, category_name FROM Categories";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Create New Article</h2>
                
                <?php if($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" id="category" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Article</button>
                </form>
            </div>
        </div>
    </div>
</div>