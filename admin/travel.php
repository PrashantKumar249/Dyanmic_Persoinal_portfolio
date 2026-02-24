<?php
require 'db.php';
$message = '';

if (isset($_POST['submit'])) {

    $place_name  = trim($_POST['place_name']);
    $travel_date = $_POST['travel_date'];
    $description = trim($_POST['description']);

    /* ===== PLACE NAME VALIDATION (NO DIGITS) ===== */
    $placeRegex = '/^[a-zA-Z .\-]{2,100}$/';

    if (!preg_match($placeRegex, $place_name)) {
        echo "<script>
                alert('Place name should contain only letters. Numbers are not allowed!');
                history.back();
              </script>";
        exit;
    }

    /* ===== IMAGE VALIDATION ===== */
    $image_name = null;

    if (isset($_FILES['travel_image']) && $_FILES['travel_image']['error'] == 0) {

        $allowedExt  = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];

        $fileName = $_FILES['travel_image']['name'];
        $fileTmp  = $_FILES['travel_image']['tmp_name'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Extension check
        if (!in_array($fileExt, $allowedExt)) {
            echo "<script>
                    alert('Only image files (JPG, PNG, WEBP) are allowed!');
                    history.back();
                  </script>";
            exit;
        }

        // MIME type check (blocks PDF renamed as image)
        $fileMime = mime_content_type($fileTmp);
        if (!in_array($fileMime, $allowedMime)) {
            echo "<script>
                    alert('Invalid image file!');
                    history.back();
                  </script>";
            exit;
        }

        // Upload
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($fileName);
        move_uploaded_file($fileTmp, $upload_dir . $image_name);
    }

    /* ===== INSERT ===== */
    $stmt = $pdo->prepare(
        "INSERT INTO travels (place_name, travel_date, description, travel_image)
         VALUES (?, ?, ?, ?)"
    );

    if ($stmt->execute([$place_name, $travel_date, $description, $image_name])) {
        $message = "Travel added successfully!";
    } else {
        $message = "Failed to add travel.";
    }
}

// Fetch all travels
$stmt = $pdo->query("SELECT * FROM travels ORDER BY travel_date DESC");
$travels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travels</title>
    <style>
        body {
            font-family: Arial;
            margin: 20px;
            background: #f0f2f5;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form input,
        form textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        form button {
            padding: 10px 20px;
        }

        .travel-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background: #fafafa;
        }

        .travel-card img {
            max-width: 200px;
            margin-top: 10px;
        }

        .message {
            padding: 10px;
            background: #d4edda;
            color: #155724;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add Travel</h2>
        <?php if ($message) echo "<div class='message'>$message</div>"; ?>
        <form method="post" enctype="multipart/form-data">
            <label>Place Name:</label>
            <input type="text" name="place_name" required>

            <label>Travel Date:</label>
            <input type="date" name="travel_date" required>

            <label>Description:</label>
            <textarea name="description"></textarea>

            <label>Travel Image:</label>
            <input type="file" name="travel_image" accept="image/*">

            <button type="submit" name="submit">Add Travel</button>
        </form>

        <hr>
        <h2>All Travels</h2>
        <?php
        if ($travels) {
            foreach ($travels as $travel) {
                echo "<div class='travel-card'>";
                echo "<h3>" . htmlspecialchars($travel['place_name']) . "</h3>";
                echo "<p><strong>Date:</strong> " . $travel['travel_date'] . "</p>";
                echo "<p>" . htmlspecialchars($travel['description']) . "</p>";
                if ($travel['travel_image']) {
                    echo "<img src='uploads/" . htmlspecialchars($travel['travel_image']) . "'>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No travels added yet.</p>";
        }
        ?>
        
        <a href="dashboard.php" style="display:block; text-align:center; margin-top:20px; text-decoration:none; color:#007bff;">← Back to Dashboard</a>
    </div>
</body>

</html>