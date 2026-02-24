<?php
// Include DB connection
require 'db.php';

// Add favorite category
if (isset($_POST['add'])) {

    $category = trim($_POST['category']);

    //  letters + space only (no digits)
    $categoryRegex = '/^[a-zA-Z ]+$/';

    if (empty($category) || !preg_match($categoryRegex, $category)) {
        echo "<script>
                alert('Category must contain only letters. Numbers are not allowed!');
                history.back();
              </script>";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO favorites (category) VALUES (?)");
    $stmt->execute([strtolower($category)]);
}

// Fetch all favorites
$stmt = $pdo->query("SELECT * FROM favorites ORDER BY id DESC");
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Favorites</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 20px; }
        .container { max-width: 500px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        input, button { padding: 10px; margin: 5px 0; width: 100%; }
        ul { list-style: none; padding: 0; }
        li { padding: 8px 0; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
<div class="container">
    <h2>Favorites Section</h2>

    <!-- Add Category Form -->
    <form method="post">
        <input type="text" name="category" placeholder="Add category (food, movie, music)" required>
        <button type="submit" name="add">Add Favorite</button>
    </form>

    <!-- Display Favorites -->
    <h3>All Favorites:</h3>
    <ul>
        <?php foreach($favorites as $fav): ?>
            <li><?php echo htmlspecialchars($fav['category']); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>