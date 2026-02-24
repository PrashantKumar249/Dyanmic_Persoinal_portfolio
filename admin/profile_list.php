<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

$profiles = $pdo->query("SELECT * FROM profile ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Profile List</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f6f8;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        a.delete {
            color: red;
            text-decoration: none;
        }

        a.edit {
            color: white;
            text-decoration: none;
            background: #28a745;
            padding: 4px 8px;
            border-radius: 4px;
        }

        a.edit:hover {
            background: #218838;
        }

        a.add {
            display: inline-block;
            margin: 10px auto;
            text-align: center;
            text-decoration: none;
            background: #007bff;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
        }

        a.add:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>

    <h2 style="text-align:center;">Profiles</h2>
    <p style="text-align:center;"><a href="profile_add.php" class="add">+ Add Profile</a></p>

    <table>
        <tr>
            <th>Name</th>
            <th>Title</th>
            <th>Photo</th>
            <th>Resume</th>
            <th>Action</th>
        </tr>

        <?php foreach ($profiles as $p): ?>
            <tr>
                <td><?= $p['name'] ?></td>
                <td><?= $p['title'] ?></td>
                <td>
                    <?php if ($p['profile_photo']): ?>
                        <img src="uploads/<?= $p['profile_photo'] ?>" alt="Photo">
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($p['resume_pdf']): ?>
                        <a href="uploads/<?= $p['resume_pdf'] ?>" target="_blank">View PDF</a>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="profile_edit.php?id=<?= $p['id'] ?>" class="edit">Edit</a>
                    &nbsp;
                    <a href="profile_delete.php?id=<?= $p['id'] ?>" class="delete"
                        onclick="return confirm('Delete this profile?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php" style="display:block; text-align:center; margin-top:20px; text-decoration:none; color:#007bff;">← Back to Dashboard</a> 
</body>

</html>