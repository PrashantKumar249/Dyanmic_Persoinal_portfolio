<?php
session_start();
require "db.php";

// Login check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['add'])) {

    $skillsInput = trim($_POST['name']);   // php mysql,html
    $type  = $_POST['type'];
    $level = $_POST['level'];

    // comma ya space se split
    $skillsArray = preg_split('/[\s,]+/', $skillsInput);

    // ✅ letters-only regex
    $skillRegex = '/^[a-zA-Z]+$/';

    $stmt = $pdo->prepare(
        "INSERT INTO skills (skill_name, skill_type, skill_level)
         VALUES (?,?,?)"
    );

    foreach ($skillsArray as $skill) {

        $skill = trim($skill);

        if (empty($skill)) {
            continue;
        }

        // ❌ agar digit ya symbol ho
        if (!preg_match($skillRegex, $skill)) {
            echo "<script>
                    alert('Skills must contain only letters (A-Z). Numbers or symbols not allowed!');
                    history.back();
                  </script>";
            exit;
        }

        $stmt->execute([
            strtolower($skill),  // php, mysql
            $type,
            $level
        ]);
    }

    header("Location: skills.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Skills</title>

    <!-- BASIC INTERNAL CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }
        .container {
            width: 400px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        input, select, button {
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
            background: #1e7e34;
        }
        .note {
            font-size: 13px;
            color: #555;
            margin-bottom: 10px;
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

<div class="container">
    <h2>Add Skills</h2>

    <form method="post">
        <input type="text" name="name" placeholder="Enter skills" required>

        <select name="type">
            <option value="Technical">Technical</option>
            <option value="Soft">Soft</option>
        </select>

        <input type="number" name="level" placeholder="Skill Level (0-100)" required>

        <button type="submit" name="add">Add Skills</button>
    </form>

    <a href="skills.php">← Back to Skills</a>
</div>

</body>
</html>