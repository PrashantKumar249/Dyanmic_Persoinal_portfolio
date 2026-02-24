<?php
require "db.php";

$pdo->prepare("DELETE FROM skills WHERE id=?")
    ->execute([$_GET['id']]);

header("Location: skills.php");
?>