<?php
require "db.php";

$skills = $pdo->query("SELECT * FROM skills")->fetchAll();
?>
<h2>Skills</h2>
<a href="skill_add.php">Add Skill</a>
<ul>
<?php foreach($skills as $s): ?>
<li>
<?= $s['skill_name'] ?> (<?= $s['skill_level'] ?>%)
<a href="skill_delete.php?id=<?= $s['id'] ?>">Delete</a>
</li>
<?php endforeach; ?>
</ul>


<a href="dashboard.php" style="display:block; margin-top:20px; text-decoration:none; color:#007bff;">← Back to Dashboard</a>