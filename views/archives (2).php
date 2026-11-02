
<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once "../config/database.php";

$search = trim($_GET['search'] ?? '');
$category = $_GET['category'] ?? '';

$sql = "SELECT archives.archive_id,
               archives.title,
               archives.file_path,
               archives.file_size_kb,
               archives.created_at,
               categories.category_name
        FROM archives
        INNER JOIN categories
        ON archives.category_id = categories.category_id
        WHERE 1=1";

$params = [];

if ($search !== '') {
    $sql .= " AND archives.title LIKE ?";
    $params[] = "%" . $search . "%";
}

if ($category !== '') {
    $sql .= " AND archives.category_id = ?";
    $params[] = $category;
}

$sql .= " ORDER BY archives.created_at DESC";

$archiveQuery = $pdo->prepare($sql);
$archiveQuery->execute($params);

$archives = $archiveQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Archives | ARCHIVIO</title>
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
                <h1>My Archives</h1>
                <p>View and manage your stored documents.</p>
            </div>
            <div class="profile">Archive Manager</div>
        </div>

        <section class="content-box">

            <h2>All Archived Documents</h2>

            <p>Total Documents: <?= count($archives) ?></p>

            <form method="GET" action="archives.php" class="search-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Search documents..."
                    value="<?= htmlspecialchars($search) ?>"
                >

                <select name="category">
                    <option value="">All Categories</option>

                    <option value="1" <?= $category == '1' ? 'selected' : '' ?>>
                        School Documents
                    </option>

                    <option value="2" <?= $category == '2' ? 'selected' : '' ?>>
                        Personal Documents
                    </option>

                    <option value="3" <?= $category == '3' ? 'selected' : '' ?>>
                        Projects
                    </option>

                    <option value="4" <?= $category == '4' ? 'selected' : '' ?>>
                        Others
                    </option>
                </select>

                <button type="submit" class="upload-button">Search</button>

                <a href="archives.php">Clear</a>

            </form>

            <table>
                <thead>
                    <tr>
                        <th>Document Title</th>
                        <th>Category</th>
                        <th>File Size</th>
                        <th>Date Uploaded</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($archives) > 0): ?>

                        <?php foreach ($archives as $archive): ?>

                            <tr>
                                <td>
                                    <?= htmlspecialchars($archive['title']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($archive['category_name']) ?>
                                </td>

                                <td>
                                    <?= number_format((float)$archive['file_size_kb'], 2) ?> KB
                                </td>

                                <td>
                                    <?= htmlspecialchars($archive['created_at']) ?>
                                </td>

                                <td>
                                    <div class="action-buttons">

    <a href="../<?= htmlspecialchars($archive['file_path']) ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="view-button">
        View
    </a>

    <form action="../controllers/delete.php" method="POST"
          onsubmit="return confirm('Are you sure you want to delete this document?');">

        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <input type="hidden" name="archive_id"
               value="<?= (int)$archive['archive_id'] ?>">

        <button type="submit" class="delete-button">Delete</button>
    </form>

    <a href="edit.php?id=<?= (int)$archive['archive_id'] ?>"
       class="edit-button">
        Edit
    </a>

</div>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5">No archived documents found.</td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>

        </section>

    </main>

</div>

</body>
</html>
