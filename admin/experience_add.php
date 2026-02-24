<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

if (isset($_POST['add'])) {

    $company     = trim($_POST['company']);
    $role        = trim($_POST['role']);
    $start_date  = $_POST['start_date'];
    $isCurrent   = isset($_POST['is_current']) ? 1 : 0;
    $endDate     = $isCurrent ? null : $_POST['end_date'];
    $description = trim($_POST['description']);

    /* ===== ROLE VALIDATION (NO DIGITS / SPECIAL CHARS) ===== */
    $roleRegex = '/^[a-zA-Z .\-]{2,100}$/';

    if (!preg_match($roleRegex, $role)) {
        echo "<script>
                alert('Role should contain only letters. Numbers or special characters are not allowed!');
                history.back();
              </script>";
        exit;
    }

    /* ===== DATE LOGIC ===== */
    if (!$isCurrent && !empty($endDate) && $endDate < $start_date) {
        echo "<script>
                alert('End date cannot be earlier than start date!');
                history.back();
              </script>";
        exit;
    }

    /* ===== INSERT ===== */
    $stmt = $pdo->prepare(
        "INSERT INTO experience 
        (company_name, role, start_date, end_date, description, is_current)
        VALUES (?,?,?,?,?,?)"
    );

    $stmt->execute([
        $company,
        $role,
        $start_date,
        $endDate,
        $description,
        $isCurrent
    ]);

    header("Location: experience_list.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Experience</title>
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
        button {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
        }

        label {
            font-size: 14px;
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
        <h2>Add Experience</h2>

        <form method="post">
            <input name="company" placeholder="Company Name" required>
            <input name="role" placeholder="Role / Position" required>

            <label>Start Date</label>
            <input type="date" name="start_date" required>

            <label>End Date</label>
            <input type="date" name="end_date">

            <label>
                <input type="checkbox" name="is_current"> Currently Working
            </label>

            <textarea name="description" placeholder="Work Description"></textarea>

            <button name="add">Add</button>
        </form>

        <a href="experience_list.php">← Back</a>
    </div>

</body>

</html>