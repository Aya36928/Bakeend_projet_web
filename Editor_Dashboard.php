<?php
require_once 'config.php';

// Handle approve, deny, or delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $news_id = $_POST['news_id'];
        $status = 'accept';
        $sql = "UPDATE news SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $status, $news_id);
        $stmt->execute();
        header('Location: editor_dashboard.php');
        exit();
    } elseif (isset($_POST['deny'])) {
        $news_id = $_POST['news_id'];
        $status = 'reject';
        $sql = "UPDATE news SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $status, $news_id);
        $stmt->execute();
        header('Location: editor_dashboard.php');
        exit();
    } elseif (isset($_POST['delete'])) {
        $news_id = $_POST['news_id'];
        $sql = "DELETE FROM news WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $news_id);
        $stmt->execute();
        header('Location: editor_dashboard.php');
        exit();
    }
}

// Fetch all news items with category and author
$sql = "SELECT news.id, news.title, category.name AS category_name, user.name AS author_name, 
               news.dateposted, news.status
        FROM news
        JOIN category ON news.category_id = category.id
        JOIN user ON news.author_id = user.id
        ORDER BY news.dateposted DESC";

$result = $conn->query($sql);
$news_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editor Dashboard</title>
</head>
<body>

<h1>Editor Dashboard</h1>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Author</th>
            <th>Date Posted</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($news_items as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
            <td><?php echo htmlspecialchars($row['author_name']); ?></td>
            <td><?php echo htmlspecialchars($row['dateposted']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td>
                <?php if ($row['status'] !== 'accept'): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="news_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="approve">Approve</button>
                    </form>
                <?php endif; ?>

                <?php if ($row['status'] !== 'reject'): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="news_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="deny">Deny</button>
                    </form>
                <?php endif; ?>

                <form method="POST" style="display:inline;">
                    <input type="hidden" name="news_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
                    
</body>
</html>

<?php
$conn->close();
?>
