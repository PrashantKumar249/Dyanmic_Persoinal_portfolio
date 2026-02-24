<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

$id = $_GET['id'] ?? 0;

// Fetch existing experience
$stmt = $pdo->prepare("SELECT * FROM experience WHERE id=?");
$stmt->execute([$id]);
$exp = $stmt->fetch();

if (!$exp) {
    die("Experience not found");
}

if (isset($_POST['update'])) {

    $company = trim($_POST['company_name']);
    $role    = trim($_POST['role']);

    /* ===== COMPANY NAME VALIDATION ===== */
    // Letters + digits + space + dot + hyphen allowed
    if (!preg_match('/^[a-zA-Z0-9 .\-]{2,100}$/', $company)) {
        echo "<script>
                alert('Company name can contain letters and numbers only!');
                history.back();
              </script>";
        exit;
    }

    /* ===== ROLE VALIDATION ===== */
    // ONLY letters and space
    if (!preg_match('/^[a-zA-Z ]{2,50}$/', $role)) {
        echo "<script>
                alert('Role should contain only letters. Digits or special characters are not allowed!');
                history.back();
              </script>";
        exit;
    }

    $isCurrent = isset($_POST['is_current']) ? 1 : 0;
    $endDate   = $isCurrent ? null : $_POST['end_date'];

    $stmt = $pdo->prepare(
        "UPDATE experience 
         SET company_name=?, role=?, start_date=?, end_date=?, is_current=? 
         WHERE id=?"
    );

    $stmt->execute([
        $company,
        $role,
        $_POST['start_date'],
        $endDate,
        $isCurrent,
        $id
    ]);

    header("Location: experience_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Experience</title>
    <style>
        body { font-family: Arial; background:#f4f6f8; }
        .box { width:500px; margin:30px auto; background:#fff; padding:20px; border:1px solid #ddd; border-radius:5px; }
        input, label, button { width:100%; padding:8px; margin-bottom:12px; font-size:14px; }
        button { background:#007bff; color:#fff; border:none; cursor:pointer; font-weight:bold; }
        button:hover { background:#0056b3; }
        label { font-weight:bold; margin-bottom:4px; display:block; }
        a { display:block; text-align:center; text-decoration:none; color:#007bff; margin-top:10px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Edit Experience</h2>

    <form method="post">
        <label>Company Name</label>
        <input name="company_name"
               value="<?= htmlspecialchars($exp['company_name']) ?>" required>

        <label>Role</label>
        <input name="role"
               value="<?= htmlspecialchars($exp['role']) ?>" required>

        <label>Start Date</label>
        <input type="date" name="start_date"
               value="<?= $exp['start_date'] ?>" required>

        <label>End Date</label>
        <input type="date" name="end_date"
               value="<?= $exp['end_date'] ?>">

        <label>
            <input type="checkbox" name="is_current"
                   <?= $exp['is_current'] ? "checked" : "" ?>>
            Currently Working Here
        </label>

        <button name="update">Update Experience</button>
    </form>

    <a href="experience_list.php">← Back to Experience List</a>
</div>

</body>
</html>