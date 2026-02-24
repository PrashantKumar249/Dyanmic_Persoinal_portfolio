<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

$id = $_GET['id'];

// Fetch files to delete
$stmt = $pdo->prepare("SELECT profile_photo,resume_pdf FROM profile WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if ($p) {
    if ($p['profile_photo'] && file_exists("../uploads/profile/".$p['profile_photo'])) {
        unlink("../uploads/profile/".$p['profile_photo']);
    }
    if ($p['resume_pdf'] && file_exists("../uploads/profile/".$p['resume_pdf'])) {
        unlink("../uploads/profile/".$p['resume_pdf']);
    }

    $stmt = $pdo->prepare("DELETE FROM profile WHERE id=?");
    $stmt->execute([$id]);
}

header("Location: profile_list.php");
exit;