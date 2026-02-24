<?php
session_start();
require "db.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// ID check
if (!isset($_GET['id'])) {
    header("Location: education_list.php");
    exit;
}

$id = $_GET['id'];

// Fetch existing data
$stmt = $pdo->prepare("SELECT * FROM education WHERE id = ?");
$stmt->execute([$id]);
$edu = $stmt->fetch();

if (!$edu) {
    header("Location: education_list.php");
    exit;
}

// UPDATE with validation
if (isset($_POST['update'])) {

    $level       = trim($_POST['level']);
    $institute   = trim($_POST['institute']);
    $board       = trim($_POST['board']);
    $start_year  = trim($_POST['start_year']);
    $end_year    = trim($_POST['end_year']);
    $description = trim($_POST['description']);

    /* ===== LEVEL CHECK ===== */
    $allowedLevels = ['10th', '12th', 'B.Tech', 'Diploma'];
    if (!in_array($level, $allowedLevels)) {
        echo "<script>alert('Invalid education level');history.back();</script>";
        exit;
    }

    /* ===== COLLEGE / BOARD NAME FORMAT ===== */
    $nameRegex = "/^[a-zA-Z .\-()']{3,100}$/";

    if (!preg_match($nameRegex, $institute)) {
        echo "<script>alert('Invalid School / College name');history.back();</script>";
        exit;
    }

    if (!preg_match($nameRegex, $board)) {
        echo "<script>alert('Invalid Board / University name');history.back();</script>";
        exit;
    }

    /* ===== YEAR VALIDATION ===== */
    $currentYear = date('Y') + 1;

    if ($start_year && (!preg_match('/^\d{4}$/', $start_year) ||
        $start_year < 1900 || $start_year > $currentYear)) {
        echo "<script>alert('Invalid start year');history.back();</script>";
        exit;
    }

    if ($end_year && (!preg_match('/^\d{4}$/', $end_year) ||
        $end_year < 1900 || $end_year > $currentYear)) {
        echo "<script>alert('Invalid end year');history.back();</script>";
        exit;
    }

    if ($start_year && $end_year && $end_year < $start_year) {
        echo "<script>alert('End year cannot be less than start year');history.back();</script>";
        exit;
    }

    /* ===== DESCRIPTION LENGTH ===== */
    if (!empty($description) && strlen($description) > 300) {
        echo "<script>alert('Description should not exceed 300 characters');history.back();</script>";
        exit;
    }

    /* ===== UPDATE QUERY ===== */
    $stmt = $pdo->prepare(
        "UPDATE education SET
            level = ?,
            institute = ?,
            board_university = ?,
            start_year = ?,
            end_year = ?,
            description = ?
         WHERE id = ?"
    );

    $stmt->execute([
        $level,
        $institute,
        $board,
        $start_year ?: null,
        $end_year ?: null,
        $description,
        $id
    ]);

    header("Location: education_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Education</title>

    <!-- SIMPLE INTERNAL CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }
        .box {
            width: 500px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
        }
        button {
            background: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        a {
            display: block;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Edit Education</h2>

    <form method="post">

        <select name="level" required>
            <option <?= $edu['level']=="10th" ? "selected" : "" ?>>10th</option>
            <option <?= $edu['level']=="12th" ? "selected" : "" ?>>12th</option>
            <option <?= $edu['level']=="B.Tech" ? "selected" : "" ?>>B.Tech</option>
            <option <?= $edu['level']=="Diploma" ? "selected" : "" ?>>Diploma</option>
        </select>

        <input type="text" name="institute"
               value="<?= $edu['institute'] ?>" required>

        <input type="text" name="board"
               value="<?= $edu['board_university'] ?>" required>

        <input type="number" name="start_year"
               value="<?= $edu['start_year'] ?>">

        <input type="number" name="end_year"
               value="<?= $edu['end_year'] ?>">

        <textarea name="description"><?= $edu['description'] ?></textarea>

        <button type="submit" name="update">Update</button>
    </form>

    <a href="education_list.php">← Back to Education List</a>
</div>

</body>
</html>