
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../config/database.php";

require_once "../config/database.php";

// Get all available categories
$categoryQuery = $pdo->prepare(
    "SELECT category_id, category_name
     FROM categories
     ORDER BY category_name ASC"
);

$categoryQuery->execute();

$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);

?>

<?php
session_start();

$successCount = $_SESSION["upload_success"] ?? null;
$failedCount = $_SESSION["upload_failed"] ?? null;

unset($_SESSION["upload_success"], $_SESSION["upload_failed"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload Archives | ARCHIVIO</title>

    <link rel="stylesheet" href="../assets/style.css">

</head>

<body>

<div class="container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="logo">ARCHIVIO.</div>

        <p>MAIN MENU</p>

        <a href="../index.php">Dashboard</a>
        <a href="#">My Archives</a>
        <a href="#">Categories</a>
        <a href="upload.php" class="active">Upload Files</a>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main">

        <div class="header">

            <div>

                <h1>Upload Archives</h1>

                <p>
                    Add your documents to the archive system.
                </p>

            </div>

            <div class="profile">
                Archive Manager
            </div>

        </div>


        <!-- UPLOAD FORM -->

        <section class="content-box">

            <h2>Upload New Documents</h2>
<?php if ($successCount !== null): ?>
    <p style="color: green; font-weight: bold;">
        Successfully uploaded <?= (int)$successCount ?> file(s).
    </p>
<?php endif; ?>

<?php if ($failedCount !== null && $failedCount > 0): ?>
    <p style="color: red; font-weight: bold;">
        Failed to upload <?= (int)$failedCount ?> file(s).
    </p>
<?php endif; ?>
            <form
                action="../controllers/upload.php"
                method="POST"
                enctype="multipart/form-data"
            >

                <label for="category">
                    Select Category
                </label>

                <br><br>

                <select name="category_id" id="category" required>

                    <option value="">
                        Choose a category
                    </option>

                    <?php foreach ($categories as $category): ?>

                        <option value="<?= htmlspecialchars($category['category_id']) ?>">

                            <?= htmlspecialchars($category['category_name']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <br><br><br>


                <label for="files">
                    Select Documents
                </label>

                <br><br>

                <input
                    type="file"
                    name="archives[]"
                    id="files"
                    multiple
                    required
                >

                <br><br>

                <p>
                    You can select multiple files for batch uploading.
                </p>

                <br>

                <button type="submit" class="upload-button">
                    Upload Documents
                </button>

            </form>

        </section>

    </main>

</div>

</body>

</html>
