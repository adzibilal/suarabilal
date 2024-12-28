<?php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'models/User.php';

// Check if user is admin
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    header('Location: index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

// Get all users with their roles
$query = "SELECT u.*, r.role_name 
          FROM Users u 
          JOIN Roles r ON u.role_id = r.id 
          ORDER BY u.username";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all roles for the dropdown
$query = "SELECT * FROM Roles ORDER BY role_name";
$stmt = $db->prepare($query);
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'update_role' && !empty($_POST['user_id']) && !empty($_POST['role_id'])) {
            $query = "UPDATE Users SET role_id = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            if ($stmt->execute([$_POST['role_id'], $_POST['user_id']])) {
                $success = 'User role updated successfully!';
            } else {
                $error = 'Failed to update user role.';
            }
        }
    }
}
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Manage Users</h2>

        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Change Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-2">
                                <input type="hidden" name="action" value="update_role">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <select name="role_id" class="form-select form-select-sm" style="width: auto;">
                                    <?php foreach($roles as $role): ?>
                                        <option value="<?php echo $role['id']; ?>" <?php echo $user['role_id'] == $role['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($role['role_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
