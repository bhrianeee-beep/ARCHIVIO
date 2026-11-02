
<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require_once "../config/database.php";

$archiveId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$archiveId) {
    die("Invalid archive ID.");
}

$archiveQuery = $pdo->prepare(
    "SELECT archive_id, title, category_id
     FROM archives
     WHERE archive_id = ?"
);

$archiveQuery->execute([$archiveId]);
$archive = $archiveQuery->fetch(PDO::FETCH_ASSOC);

if (!$archive) {
    die("Archive not found.");
}

$categoryQuery = $pdo->prepare(
    "SELECT category_id, category_name
     FROM categories
     ORDER BY category_name ASC"
);

$categoryQuery->execute();
$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Archive | ARCHIVIO</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">

    <aside class="sidebar">
        <div class="logo">ARCHIVIO.</div>
        <p>MAIN MENU</p>

        <a href="../index.php">Dashboard</a>
        <a href="archives.php" class="active">My Archives</a>
        <a href="categories.php">Categories</a>
        <a href="upload.php">Upload Files</a>
    </aside>

    <main class="main">

        <div class="header">
            <div>
                <h1>Edit Archive</h1>
                <p>Update your document information.</p>
            </div>
            <div class="profile">Archive Manager</div>
        </div>

        <section class="content-box">

            <h2>Edit Document Details</h2>

            <form action="../controllers/update.php" method="POST">
<input type="hidden" name="csrf_token"
       value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="archive_id"
                       value="<?= (int)$archive['archive_id'] ?>">

                <label for="title">Document Title</label><br><br>

                <input
                    type="text"
                    name="title"
                    id="title"
                    value="<?= htmlspecialchars($archive['title']) ?>"
                    required
                    maxlength="255"
                >

                <br><br>

                <label for="category">Category</label><br><br>

                <select name="category_id" id="category" required>

                    <?php foreach ($categories as $category): ?>

                        <option
                            value="<?= (int)$category['category_id'] ?>"
                            <?= $archive['category_id'] == $category['category_id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <br><br><br>

                <button type="submit" class="upload-button">
                    Save Changes
                </button>

                <a href="archives.php">Cancel</a>

            </form>

        </section>

    </main>

</div>

</body>
</html>
