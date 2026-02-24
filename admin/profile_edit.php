<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM profile WHERE id=?");
$stmt->execute([$id]);
$profile = $stmt->fetch();

if (!$profile) {
    die("Profile not found");
}

if (isset($_POST['update'])) {

    $profilePhoto = $profile['profile_photo'];
    $resumePDF = $profile['resume_pdf'];

    // Update photo if new uploaded
    if (!empty($_FILES['profile_photo']['name'])) {
        $profilePhoto = time() . "_" . $_FILES['profile_photo']['name'];
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], "../uploads/" . $profilePhoto);
    }

    // Update resume if new uploaded
    if (!empty($_FILES['resume_pdf']['name'])) {
        $resumePDF = time() . "_" . $_FILES['resume_pdf']['name'];
        move_uploaded_file($_FILES['resume_pdf']['tmp_name'], "../uploads/" . $resumePDF);
    }

    $stmt = $pdo->prepare("UPDATE profile SET name=?, title=?, about=?, profile_photo=?, resume_pdf=? WHERE id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['title'],
        $_POST['about'],
        $profilePhoto,
        $resumePDF,
        $id
    ]);

    header("Location: profile_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial; background:#f4f6f8; }
        .box { width:500px; margin:30px auto; background:#fff; padding:20px; border:1px solid #ddd; border-radius:5px; }
        input, textarea, button { width:100%; padding:8px; margin-bottom:12px; border-radius:4px; border:1px solid #ccc; font-size:14px; }
        button { background:#007bff; color:#fff; border:none; font-weight:bold; cursor:pointer; }
        button:hover { background:#0056b3; }
        label { font-weight:bold; display:block; margin-bottom:4px; }
        a { text-decoration:none; color:#007bff; display:block; text-align:center; margin-top:10px; }
        img { width:50px; height:50px; border-radius:50%; object-fit:cover; margin-bottom:10px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Edit Profile</h2>

    <form method="post" enctype="multipart/form-data">
        <label>Full Name</label>
        <input name="name" value="<?= htmlspecialchars($profile['name']) ?>" required>

        <label>Title / Role</label>
        <input name="title" value="<?= htmlspecialchars($profile['title']) ?>" required>

        <label>About Yourself</label>
        <textarea name="about"><?= htmlspecialchars($profile['about']) ?></textarea>

        <label>Profile Photo</label>
        <?php if($profile['profile_photo']): ?>
            <img src="uploads/<?= $profile['profile_photo'] ?>" alt="Photo">
        <?php endif; ?>
        <input type="file" name="profile_photo" accept="image/*">

        <label>Resume PDF</label>
        <?php if($profile['resume_pdf']): ?>
            <a href="uploads/<?= $profile['resume_pdf'] ?>" target="_blank">View Current Resume</a>
        <?php endif; ?>
        <input type="file" name="resume_pdf" accept="application/pdf">

        <button name="update">Update Profile</button>
    </form>

    <a href="profile_list.php">← Back to Profile List</a>
</div>

</body>
</html>