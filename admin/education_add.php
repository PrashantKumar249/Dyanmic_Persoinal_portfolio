<?php
session_start();
require "db.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['add'])) {

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

    /* ===== INSTITUTE / COLLEGE NAME FORMAT ===== */
    $nameRegex = "/^[a-zA-Z .\-()']{3,100}$/";

    if (!preg_match($nameRegex, $institute)) {
        echo "<script>
                alert('Please enter a valid School / College name');
                history.back();
              </script>";
        exit;
    }

    if (!preg_match($nameRegex, $board)) {
        echo "<script>
                alert('Please enter a valid Board / University name');
                history.back();
              </script>";
        exit;
    }

    /* ===== YEAR CHECK ===== */
    $currentYear = date('Y') + 1;

    if ($start_year && (!preg_match('/^\d{4}$/', $start_year) || $start_year < 1900 || $start_year > $currentYear)) {
        echo "<script>alert('Invalid start year');history.back();</script>";
        exit;
    }

    if ($end_year && (!preg_match('/^\d{4}$/', $end_year) || $end_year < 1900 || $end_year > $currentYear)) {
        echo "<script>alert('Invalid end year');history.back();</script>";
        exit;
    }

    if ($start_year && $end_year && $end_year < $start_year) {
        echo "<script>alert('End year cannot be less than start year');history.back();</script>";
        exit;
    }

    /* ===== DESCRIPTION LENGTH CHECK ===== */
    if (!empty($description) && strlen($description) > 300) {
        echo "<script>
                alert('Description should not exceed 300 characters');
                history.back();
              </script>";
        exit;
    }

    /* ===== INSERT ===== */
    $stmt = $pdo->prepare(
        "INSERT INTO education 
        (level, institute, board_university, start_year, end_year, description)
        VALUES (?,?,?,?,?,?)"
    );

    $stmt->execute([
        $level,
        $institute,
        $board,
        $start_year ?: null,
        $end_year ?: null,
        $description
    ]);

    header("Location: education_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Education</title>
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
        }

        input,
        textarea,
        select,
        button {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
        }

        button {
            background: #007bff;
            color: #fff;
            border: none;
        }
    </style>
</head>

<body>

    <div class="box">
        <h2>Add Education</h2>

        <form method="post">
            <select name="level" required>
                <option value="">Select Level</option>
                <option>10th</option>
                <option>12th</option>
                <option>B.Tech</option>
                <option>Diploma</option>
            </select>

            <input name="institute" placeholder="School / College Name" required>

            <input name="board" placeholder="Board / University" required>

            <input type="number" name="start_year" placeholder="Start Year">

            <input type="number" name="end_year" placeholder="End Year">

            <textarea name="description" placeholder="Description"></textarea>

            <button name="add">Add</button>
        </form>

        <a href="education_list.php">← Back</a>
    </div>

</body>

</html>