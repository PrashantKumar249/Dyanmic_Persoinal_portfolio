<?php
session_start();
require "db.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$rows = $pdo->query("SELECT * FROM education ORDER BY start_year DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Education List</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
        }

        .table {
            width: 90%;
            margin: 30px auto;
            background: #fff;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        a {
            color: red;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <h2 style="text-align:center;">Education</h2>
    <p style="text-align:center;"><a href="education_add.php">+ Add Education</a></p>

    <table class="table">
        <tr>
            <th>Level</th>
            <th>Institute</th>
            <th>Board</th>
            <th>Years</th>
            <th>Action</th>
        </tr>

        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= $r['level'] ?></td>
                <td><?= $r['institute'] ?></td>
                <td><?= $r['board_university'] ?></td>
                <td><?= $r['start_year'] ?> - <?= $r['end_year'] ?></td>
                <td>
                    <a href="education_edit.php?id=<?= $r['id'] ?>">Edit</a> |
                    <a href="education_delete.php?id=<?= $r['id'] ?>"
                        onclick="return confirm('Delete this record?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

</body>

</html>