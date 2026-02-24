<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM experience WHERE id=?");
$stmt->execute([$id]);

header("Location: experience_list.php");
exit;
