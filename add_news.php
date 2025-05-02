

<?php
require_once 'config.php';

// عند إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $image = !empty($_POST['image']) ? $_POST['image'] : null;
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    $author_id = !empty($_POST['author_id']) ? $_POST['author_id'] : null;
    $status = 'Inreview'; // الحالة الافتراضية

    $sql = "INSERT INTO news (title, body, image, category_id, author_id, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiss", $title, $body, $image, $category_id, $author_id, $status);
    $stmt->execute();

    echo "<p>تم إضافة الخبر بنجاح.</p>";
}

// جلب التصنيفات
$categories = $conn->query("SELECT id, name FROM category");

// جلب المؤلفين 
$authors = $conn->query("SELECT * FROM `user`");
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إضافة خبر جديد</title>
</head>
<body>

<h2>إضافة خبر جديد</h2>

<form method="POST">
    <label>العنوان:</label><br>
    <input type="text" name="title" required><br><br>

    <label>المحتوى:</label><br>
    <textarea name="body" rows="5" required></textarea><br><br>

    <label>رابط الصورة (اختياري):</label><br>
    <input type="text" name="image"><br><br>

    <label>التصنيف:</label><br>
    <select name="category_id">
        <option value="">-- اختر تصنيف --</option>
        <?php while ($row = $categories->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>المؤلف:</label><br>
    <select name="author_id">
        <option value="">-- اختر مؤلف --</option>
        <?php while ($row = $authors->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit">إرسال</button>
</form>

</body>
</html>

<?php $conn->close(); ?>
