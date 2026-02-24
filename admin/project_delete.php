<?php
session_start();
require "db.php";

// Login check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// ID check
if (!isset($_GET['id'])) {
    header("Location: projects.php");
    exit;
}

$id = $_GET['id'];

// Pehle project fetch karo (image ke liye)
$stmt = $pdo->prepare("SELECT project_image FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if ($project) {

    // Image delete (agar exist karti ho)
    if (!empty($project['project_image'])) {
        $imagePath = "../uploads/projects/" . $project['project_image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // DB se project delete
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
}

// Wapas projects page pe
header("Location: projects.php");
exit;