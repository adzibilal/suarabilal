<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['article_id']) || !isset($_POST['comment'])) {
    header('Location: index.php');
    exit;
}

$database = new Database();
$db = $database->getConnection();

$query = "INSERT INTO Comments (article_id, user_id, comment) VALUES (?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->execute([
    $_POST['article_id'],
    $_SESSION['user_id'],
    $_POST['comment']
]);

header('Location: article.php?id=' . $_POST['article_id']);
exit;
?>