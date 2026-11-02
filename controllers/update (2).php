
<?php

session_start();

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/archives.php");
    exit;
}

$archiveId = filter_input(INPUT_POST, "archive_id", FILTER_VALIDATE_INT);
$title = trim($_POST["title"] ?? "");
$categoryId = filter_input(INPUT_POST, "category_id", FILTER_VALIDATE_INT);

if (!$archiveId || $title === "" || !$categoryId || strlen($title) > 255) {
    die("Invalid document information.");
}
if (
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
) {
    die("Invalid security token. Please try again.");
}
// Check if the selected category exists
$categoryCheck = $pdo->prepare(
    "SELECT category_id FROM categories WHERE category_id = ?"
);
$categoryCheck->execute([$categoryId]);

if (!$categoryCheck->fetch()) {
    die("Selected category does not exist.");
}

// Update archive information
$updateQuery = $pdo->prepare(
    "UPDATE archives
     SET title = ?, category_id = ?
     WHERE archive_id = ?"
);

$updateQuery->execute([
    $title,
    $categoryId,
    $archiveId
]);

header("Location: ../views/archives.php");
exit;
?>
