<?php
require 'db.php';

// Fetch all contact messages
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Contact Messages | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial
        }

        body {
            background: #f4f6f9
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px
        }

        h2 {
            text-align: center;
            margin-bottom: 20px
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        table th {
            background: #2c3e50;
            color: #fff;
        }

        table tr:nth-child(even) {
            background: #f2f2f2;
        }

        .message-box {
            max-width: 300px;
            white-space: pre-wrap;
        }

        .date {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Contact Messages</h2>

        <?php if ($messages): ?>
            <table>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>

                <?php foreach ($messages as $msg): ?>
                    <tr>
                       <!-- <td><?= $msg['id'] ?></td> -->
                        <td><?= htmlspecialchars($msg['name']) ?></td>
                        <td><?= htmlspecialchars($msg['email']) ?></td>
                        <td class="message-box"><?= htmlspecialchars($msg['message']) ?></td>
                        <td class="date"><?= $msg['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>

            </table>
        <?php else: ?>
            <p style="text-align:center;">No contact messages found.</p>
        <?php endif; ?>

        // Back to dashboard link
        <a href="dashboard.php" style="display:block; text-align:center; margin-top:20px; text-decoration:none; color:#007bff;">← Back to Dashboard</a>
    </div>
</body>

</html>