
<?php

session_start();

require_once "../config/database.php";
require_once "../dsa/ArchiveQueue.php";

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/upload.php");
    exit;
}

// Get selected category
$categoryId = filter_input(INPUT_POST, "category_id", FILTER_VALIDATE_INT);

if (!$categoryId) {
    die("Please select a valid category.");
}

// Check if category exists
$categoryCheck = $pdo->prepare(
    "SELECT category_id FROM categories WHERE category_id = ?"
);
$categoryCheck->execute([$categoryId]);

if (!$categoryCheck->fetch()) {
    die("Selected category does not exist.");
}

// Check uploaded files
if (!isset($_FILES["archives"])) {
    die("Please select at least one file.");
}

// Get custom title
$customTitle = trim($_POST["title"] ?? "");

// Allowed file types
$allowedExtensions = ["pdf", "doc", "docx", "txt", "jpg", "jpeg", "png"];

// Maximum file size: 5 MB
$maxFileSize = 5 * 1024 * 1024;

// Create upload folder if it does not exist
$uploadDirectory = "../uploads/";

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}

// Initialize FIFO Queue
$archiveQueue = new ArchiveQueue();

// Initialize counters
$successCount = 0;
$failedCount = 0;
$invalidCount = 0;

$fileCount = count($_FILES["archives"]["name"]);

// Add valid files to the queue
for ($i = 0; $i < $fileCount; $i++) {

    // Check upload errors
    if ($_FILES["archives"]["error"][$i] !== UPLOAD_ERR_OK) {
        $failedCount++;
        continue;
    }

    $originalName = $_FILES["archives"]["name"][$i];
    $temporaryPath = $_FILES["archives"]["tmp_name"][$i];
    $fileSize = $_FILES["archives"]["size"][$i];

    $extension = strtolower(
        pathinfo($originalName, PATHINFO_EXTENSION)
    );

    // Validate file extension
    if (!in_array($extension, $allowedExtensions, true)) {
        $invalidCount++;
        continue;
    }

    // Validate file size
    if ($fileSize <= 0 || $fileSize > $maxFileSize) {
        $invalidCount++;
        continue;
    }

    // Verify actual file type
    $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($temporaryPath);

    $allowedMimeTypes = [
        "pdf" => ["application/pdf"],
        "doc" => ["application/msword"],
        "docx" => ["application/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        "txt" => ["text/plain"],
        "jpg" => ["image/jpeg"],
        "jpeg" => ["image/jpeg"],
        "png" => ["image/png"]
    ];

    // Validate MIME type
    if (!in_array($mimeType, $allowedMimeTypes[$extension] ?? [], true)) {
        $invalidCount++;
        continue;
    }

    // Add valid file to FIFO Queue
    $archiveQueue->enqueue([
        "original_name" => $originalName,
        "temporary_path" => $temporaryPath,
        "file_size" => $fileSize,
        "extension" => $extension
    ]);
}

// Process Queue in FIFO order
while (!$archiveQueue->isEmpty()) {

    // Get the first queued file
    $file = $archiveQueue->dequeue();

    // Generate a unique filename
    $newFileName = bin2hex(random_bytes(16)) . "." . $file["extension"];

    $destination = $uploadDirectory . $newFileName;

    // Move file into uploads folder
    if (move_uploaded_file($file["temporary_path"], $destination)) {

        try {

            $fileSizeKB = round($file["file_size"] / 1024, 2);

            // Use custom title for one file;
            // use original filenames for multiple files
            $title = ($fileCount === 1 && $customTitle !== "")
                ? $customTitle
                : $file["original_name"];

            // Save archive information into MySQL
            $insertQuery = $pdo->prepare(
                "INSERT INTO archives
                (title, file_path, file_size_kb, category_id)
                VALUES (?, ?, ?, ?)"
            );

            $insertQuery->execute([
                $title,
                "uploads/" . $newFileName,
                $fileSizeKB,
                $categoryId
            ]);

            $successCount++;

        } catch (PDOException $e) {

            // Remove stored file if database insertion fails
            if (file_exists($destination)) {
                unlink($destination);
            }

            $failedCount++;
        }

    } else {

        $failedCount++;
    }
}

// Save results for the next page
$_SESSION["upload_success"] = $successCount;
$_SESSION["upload_failed"] = $failedCount;
$_SESSION["upload_invalid"] = $invalidCount;

header("Location: ../views/upload.php");
exit;

?>

