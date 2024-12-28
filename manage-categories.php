<?php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'models/Category.php';

// Check if user is admin
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header('Location: index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add' && !empty($_POST['category_name'])) {
            if ($category->create($_POST['category_name'])) {
                $success = 'Category added successfully!';
            } else {
                $error = 'Failed to add category.';
            }
        } elseif ($_POST['action'] == 'delete' && !empty($_POST['category_id'])) {
            if ($category->delete($_POST['category_id'])) {
                $success = 'Category deleted successfully!';
            } else {
                $error = 'Failed to delete category.';
            }
        }
    }
}

$categories = $category->getAll();
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Manage Categories</h2>

        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Add Category Form -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="add">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" name="category_name" class="form-control" placeholder="New Category Name" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </div>
        </form>

        <!-- Categories List -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $cat): ?>
                    <tr>
                        <td><?php echo $cat['id']; ?></td>
                        <td><?php echo htmlspecialchars($cat['category_name']); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="category_id" value="<?php echo $cat['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
