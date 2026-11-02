
<?php

require_once "config/database.php";

// Get total number of archives
$archiveQuery = $pdo->prepare(
    "SELECT COUNT(*) FROM archives"
);
$archiveQuery->execute();
$totalArchives = $archiveQuery->fetchColumn();

// Get total number of categories
$categoryQuery = $pdo->prepare(
    "SELECT COUNT(*) FROM categories"
);
$categoryQuery->execute();
$totalCategories = $categoryQuery->fetchColumn();

// Get recently added archives
$recentQuery = $pdo->prepare(
    "SELECT
        archives.title,
        categories.category_name,
        archives.file_size_kb,
        archives.created_at
     FROM archives
     INNER JOIN categories
     ON archives.category_id = categories.category_id
     ORDER BY archives.created_at DESC
     LIMIT 5"
);

$recentQuery->execute();
$recentArchives = $recentQuery->fetchAll(PDO::FETCH_ASSOC);

// Get most recent archive count
$recentCount = count($recentArchives);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ARCHIVIO | Archive Management</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<div class="container">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="logo">ARCHIVIO.</div>

        <p>MAIN MENU</p>

       <a href="index.php" class="active">Dashboard</a>
<a href="views/archives.php">My Archives</a>
<a href="views/categories.php">Categories</a>
<a href="views/upload.php">Upload Files</a>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main">

        <div class="header">

            <div>

                <h1>Dashboard</h1>

                <p>
                    Welcome back! Manage your archived documents.
                </p>

            </div>

            <div class="profile">
                Archive Manager
            </div>

        </div>


        <!-- DASHBOARD CARDS -->

        <section class="cards">

            <div class="card">

                <h3>Total Archives</h3>

                <h2>
                    <?= $totalArchives ?>
                </h2>

            </div>


            <div class="card">

                <h3>Total Categories</h3>

                <h2>
                    <?= $totalCategories ?>
                </h2>

            </div>


            <div class="card">

                <h3>Recently Added</h3>

                <h2>
                    <?= $recentCount ?>
                </h2>

            </div>

        </section>


        <!-- RECENT ARCHIVES TABLE -->

        <section class="content-box">

            <h2>Recent Archives</h2>

            <table>

                <thead>

                    <tr>

                        <th>Archive Title</th>
                        <th>Category</th>
                        <th>File Size</th>
                        <th>Date Added</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (count($recentArchives) > 0): ?>

                        <?php foreach ($recentArchives as $archive): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($archive['title']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($archive['category_name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($archive['file_size_kb']) ?> KB
                                </td>

                                <td>
                                    <?= htmlspecialchars($archive['created_at']) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="4" class="empty">
                                No archives found. Start by uploading a file!
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </section>

    </main>

</div>

</body>

</html>
