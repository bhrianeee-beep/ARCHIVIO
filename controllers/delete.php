
<?php

session_start();

require_once "../config/database.php";

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/archives.php");
    exit;
}

// Validate CSRF token
if (
    empty($_SESSION['csrf_token']) ||
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    die("Invalid security token. Please go back and try again.");
}

// Validate archive ID
$archiveId = filter_input(INPUT_POST, "archive_id", FILTER_VALIDATE_INT);

if (!$archiveId) {
    header("Location: ../views/archives.php");
    exit;
}

// Find the archive record
$findQuery = $pdo->prepare(
    "SELECT file_path FROM archives WHERE archive_id = ?"
);
$findQuery->execute([$archiveId]);

$archive = $findQuery->fetch(PDO::FETCH_ASSOC);

if (!$archive) {
    header("Location: ../views/archives.php");
    exit;
}

// Delete the database record
$deleteQuery = $pdo->prepare(
    "DELETE FROM archives WHERE archive_id = ?"
);
$deleteQuery->execute([$archiveId]);

// Delete the physical file
$filePath = realpath(__DIR__ . "/../" . $archive["file_path"]);
$uploadFolder = realpath(__DIR__ . "/../uploads");

if (
    $filePath !== false &&
    $uploadFolder !== false &&
    strpos($filePath, $uploadFolder . DIRECTORY_SEPARATOR) === 0 &&
    is_file($filePath)
) {
    unlink($filePath);
}

// Return to My Archives
header("Location: ../views/archives.php");
exit;

?>
