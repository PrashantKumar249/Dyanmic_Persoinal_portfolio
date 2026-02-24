<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

$data = $pdo->query(
    "SELECT * FROM experience ORDER BY start_date DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Experience</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        a.delete {
            color: red;
            text-decoration: none;
        }

        a.edit {
            color: white;
            background: #28a745;
            padding: 4px 8px;
            border-radius: 4px;
            text-decoration: none;
        }

        a.edit:hover {
            background: #218838;
        }

        a.add {
            display: inline-block;
            text-decoration: none;
            background: #007bff;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
        }

        a.add:hover {
            background: #0056b3;
        }

        a.dashboard {
            display: inline-block;
            margin: 10px auto;
            text-decoration: none;
            background: #6c757d;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
        }

        a.dashboard:hover {
            background: #5a6268;
        }

        .center {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h2 style="text-align:center;">Experience</h2>

    <div class="center">
        <a href="experience_add.php" class="add">+ Add Experience</a>
        <a href="dashboard.php" class="dashboard">← Back to Dashboard</a>
    </div>

    <table>
        <tr>
            <th>Company</th>
            <th>Role</th>
            <th>Duration</th>
            <th>Action</th>
        </tr>

        <?php foreach ($data as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['company_name']) ?></td>
                <td><?= htmlspecialchars($e['role']) ?></td>
                <td>
                    <?= $e['start_date'] ?> - <?= $e['is_current'] ? "Present" : $e['end_date'] ?>
                </td>
                <td>
                    <a href="experience_edit.php?id=<?= $e['id'] ?>" class="edit">Edit</a>
                    &nbsp;
                    <a href="experience_delete.php?id=<?= $e['id'] ?>" class="delete"
                        onclick="return confirm('Delete this experience?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

</body>

</html>