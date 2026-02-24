<?php
session_start();
require "db.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM education WHERE id=?");
$stmt->execute([$id]);

header("Location: education_list.php");
exit;