<?php
require_once 'config.php';

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// الاستعلام لجلب كل الأعمدة
$sql = "SELECT `id`, `title`, `body`, `image`, `dateposted`, `category_id`, `author_id` FROM `news`";

$result = $conn->query($sql);

// التحقق من وجود خطأ في الاستعلام
if (!$result) {
    die("Query failed: " . $conn->error);
}

// جلب البيانات كمصفوفة
$news_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All News</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 95%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            vertical-align: top;
        }
        th {
            background-color: #ddd;
        }
        img {
            max-width: 120px;
            max-height: 100px;
        }
    </style>
</head>
<body>

<h1>All News Items</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Body</th>
            <th>Image Path</th>
            <th>Image</th>
            <th>Date Posted</th>
            <th>Category ID</th>
            <th>Author ID</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($news_items)): ?>
        <?php foreach ($news_items as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($row['body'])); ?></td>
                <td><?php echo htmlspecialchars($row['image']); ?></td>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="News Image">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['dateposted']); ?></td>
                <td><?php echo htmlspecialchars($row['category_id']); ?></td>
                <td><?php echo htmlspecialchars($row['author_id']); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" style="text-align: center;">No news items found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
