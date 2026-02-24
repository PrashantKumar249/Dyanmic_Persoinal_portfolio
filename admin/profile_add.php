<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

$resumeLink = "";

if (isset($_POST['add'])) {

    $name  = trim($_POST['name']);
    $title = trim($_POST['title']);
    $about = trim($_POST['about']);

    /* ===== TEXT VALIDATION (NO DIGITS) ===== */
    $textRegex = '/^[a-zA-Z .\-]{2,200}$/';

    if (!preg_match($textRegex, $name)) {
        echo "<script>alert('Name should contain only letters. Numbers not allowed.');history.back();</script>";
        exit;
    }

    if (!preg_match($textRegex, $title)) {
        echo "<script>alert('Title should contain only letters. Numbers not allowed.');history.back();</script>";
        exit;
    }

    if (!empty($about) && !preg_match($textRegex, $about)) {
        echo "<script>alert('About section should not contain numbers.');history.back();</script>";
        exit;
    }

    /* ===== PROFILE PHOTO (IMAGE ONLY) ===== */
    $profilePhoto = "";

    if (!empty($_FILES['profile_photo']['name'])) {

        $allowedExt  = ['jpg','jpeg','png','webp'];
        $allowedMime = ['image/jpeg','image/png','image/webp'];

        $fileName = $_FILES['profile_photo']['name'];
        $fileTmp  = $_FILES['profile_photo']['tmp_name'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExt)) {
            echo "<script>alert('Profile photo must be an image (JPG, PNG, WEBP)');history.back();</script>";
            exit;
        }

        $fileMime = mime_content_type($fileTmp);
        if (!in_array($fileMime, $allowedMime)) {
            echo "<script>alert('Invalid image file uploaded');history.back();</script>";
            exit;
        }

        $profilePhoto = time() . "_" . basename($fileName);
        move_uploaded_file($fileTmp, "admin/uploads/" . $profilePhoto);
    }

    /* ===== RESUME (PDF ONLY) ===== */
    $resumePDF = "";

    if (!empty($_FILES['resume_pdf']['name'])) {

        $fileName = $_FILES['resume_pdf']['name'];
        $fileTmp  = $_FILES['resume_pdf']['tmp_name'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExt !== 'pdf') {
            echo "<script>alert('Resume must be a PDF file only');history.back();</script>";
            exit;
        }

        $fileMime = mime_content_type($fileTmp);
        if ($fileMime !== 'application/pdf') {
            echo "<script>alert('Invalid PDF file');history.back();</script>";
            exit;
        }

        $resumePDF  = time() . "_" . basename($fileName);
        move_uploaded_file($fileTmp, "admin/uploads/" . $resumePDF);
        $resumeLink = "admin/uploads/" . $resumePDF;
    }

    /* ===== INSERT ===== */
    $stmt = $pdo->prepare(
        "INSERT INTO profile
        (name, title, about, profile_photo, resume_pdf)
        VALUES (?,?,?,?,?)"
    );

    $stmt->execute([
        $name,
        $title,
        $about,
        $profilePhoto,
        $resumePDF
    ]);

    header("Location: profile_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Profile</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
        }

        .box {
            width: 500px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input,
        textarea,
        button {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            background: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #0056b3;
        }

        label {
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .resume-link {
            margin-top: 10px;
            text-align: center;
        }

        .resume-link a {
            background: #17a2b8;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
        }

        .resume-link a:hover {
            background: #138496;
        }
    </style>
</head>

<body>

    <div class="box">
        <h2>Add Profile</h2>

        <form method="post" enctype="multipart/form-data">
            <label>Full Name</label>
            <input name="name" placeholder="Full Name" required>

            <label>Title / Role</label>
            <input name="title" placeholder="Title / Role" required>

            <label>About Yourself</label>
            <textarea name="about" placeholder="About Yourself"></textarea>

            <label>Profile Photo</label>
            <input type="file" name="profile_photo" accept="image/*">

            <label>Resume PDF</label>
            <input type="file" name="resume_pdf" accept="application/pdf">

            <button name="add">Add Profile</button>
        </form>

        <?php if($resumeLink): ?>
            <div class="resume-link">
                <a href="<?= $resumeLink ?>" target="_blank">View Uploaded Resume</a>
            </div>
        <?php endif; ?>

        <a href="profile_list.php">← Back to Profile List</a>
    </div>

</body>

</html>