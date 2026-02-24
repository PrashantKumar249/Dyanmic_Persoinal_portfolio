<?php
session_start();
require "db.php";

// Agar already login hai to dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // NORMAL LOGIN (NO HASH)
    $stmt = $pdo->prepare(
        "SELECT * FROM admin WHERE username = ? AND password = ?"
    );
    $stmt->execute([$username, $password]);
    $admin = $stmt->fetch();

    if ($admin) {

        // Login success
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>

<h2>Admin Login</h2>

<form method="post">
    <input type="text" name="username" placeholder="Username" required><br><br>

    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit">Login</button>
</form>

<p style="color:red;">
    <?= $error ?>
</p>

</body>
</html>