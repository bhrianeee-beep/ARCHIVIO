<?php
require_once "../config/database.php";

$categoryQuery = $pdo->prepare(
    "SELECT categories.category_id,
            categories.category_name,
            COUNT(archives.archive_id) AS total_documents
     FROM categories
     LEFT JOIN archives
     ON categories.category_id = archives.category_id
     GROUP BY categories.category_id, categories.category_name
     ORDER BY categories.category_name ASC"
);

$categoryQuery->execute();
$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories | ARCHIVIO</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">

    <aside class="sidebar">
        <div class="logo">ARCHIVIO.</div>
        <p>MAIN MENU</p>

        <a href="../index.php">Dashboard</a>
        <a href="archives.php">My Archives</a>
        <a href="categories.php" class="active">Categories</a>
        <a href="upload.php">Upload Files</a>
    </aside>

    <main class="main">

        <div class="header">
            <div>
                <h1>Categories</h1>
                <p>Organize and view your archived documents.</p>
            </div>
            <div class="profile">Archive Manager</div>
        </div>

        <section class="content-box">

            <h2>Archive Categories</h2>

            <table>
                <thead>
                    <tr>
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Total Documents</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($categories as $category): ?>

                        <tr>
                            <td><?= htmlspecialchars($category['category_id']) ?></td>

                            <td><?= htmlspecialchars($category['category_name']) ?></td>

                            <td><?= (int)$category['total_documents'] ?></td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>

        </section>

    </main>

</div>

</body>
</html>
