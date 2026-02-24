<?php
session_start();
require "db.php";

// Login check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['add'])) {

    $name   = trim($_POST['name']);
    $desc   = trim($_POST['desc']);
    $tech   = trim($_POST['tech']);
    $github = trim($_POST['github']);

    /* ===== PROJECT NAME VALIDATION (NO DIGITS) ===== */
    $projectNameRegex = '/^[a-zA-Z .\-]{2,100}$/';
    if (!preg_match($projectNameRegex, $name)) {
        echo "<script>
                alert('Project name should contain only letters. Numbers are not allowed!');
                history.back();
              </script>";
        exit;
    }

    /* ===== TECHNOLOGY USED VALIDATION ===== */
    // ❌ Pure digits not allowed (123)
    // ✅ HTML5, CSS3, PHP8 allowed
    if (ctype_digit($tech)) {
        echo "<script>
                alert('Technologies field cannot contain only numbers!');
                history.back();
              </script>";
        exit;
    }

    /* ===== DESCRIPTION VALIDATION (NO DIGITS) ===== */
    if (preg_match('/\d/', $desc)) {
        echo "<script>
                alert('Description should not contain numbers!');
                history.back();
              </script>";
        exit;
    }

    /* ===== GITHUB REPO LINK VALIDATION ===== */
    $githubRegex = '/^https?:\/\/(www\.)?github\.com\/[A-Za-z0-9_-]+\/[A-Za-z0-9_.-]+\/?$/';

    if (!empty($github) && !preg_match($githubRegex, $github)) {
        echo "<script>
                alert('Please enter a valid GitHub repository link!');
                history.back();
              </script>";
        exit;
    }

    /* ===== SECURE IMAGE UPLOAD ===== */
    $img = "";

    if (!empty($_FILES['image']['name'])) {

        $allowedExt  = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];

        $fileName = $_FILES['image']['name'];
        $fileTmp  = $_FILES['image']['tmp_name'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Extension check
        if (!in_array($fileExt, $allowedExt)) {
            echo "<script>
                    alert('Only JPG, JPEG, PNG, WEBP images are allowed!');
                    history.back();
                  </script>";
            exit;
        }

        // MIME check
        $fileMime = mime_content_type($fileTmp);
        if (!in_array($fileMime, $allowedMime)) {
            echo "<script>
                    alert('Invalid image file. Please upload a real image!');
                    history.back();
                  </script>";
            exit;
        }

        $img = time() . "_" . basename($fileName);
        move_uploaded_file($fileTmp, "admin/uploads/" . $img);
    }

    /* ===== INSERT QUERY ===== */
    $stmt = $pdo->prepare(
        "INSERT INTO projects
        (project_name, description, technologies, project_image, github_link)
        VALUES (?,?,?,?,?)"
    );

    $stmt->execute([
        $name,
        $desc,
        $tech,
        $img,
        $github
    ]);

    header("Location: projects.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Project</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }
        .container {
            width: 450px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input, textarea, button {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
        }
        textarea {
            resize: vertical;
            height: 80px;
        }
        button {
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Project</h2>

    <form method="post" enctype="multipart/form-data">

        <input type="text" name="name" placeholder="Project Name" required>

        <textarea name="desc" placeholder="Project Description" required></textarea>

        <input type="text" name="tech" placeholder="Technologies Used" required>

        <input type="url" name="github" placeholder="GitHub Link">

        <input type="file" name="image" accept="image/*">

        <button type="submit" name="add">Add Project</button>
    </form>

    <a href="projects.php">← Back to Projects</a>
</div>

</body>
</html>