<?php
require __DIR__ . '/admin/db.php';

/* ===== INSERT MESSAGE (NO CHANGE) ===== */
if (isset($_POST['send_message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $messageText = trim($_POST['message']);

    if ($name && $email && $messageText) {
        $stmt = $pdo->prepare(
            "INSERT INTO contact_messages (name, email, message)
             VALUES (?, ?, ?)"
        );
        $stmt->execute([$name, $email, $messageText]);

        header("Location: index.php?msg=sent");
        exit;
    }
}

/* ===== FETCH ALL DATA (NO CHANGE) ===== */
$profile = $pdo->query("SELECT * FROM profile ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$skills = $pdo->query("SELECT * FROM skills ORDER BY skill_type, id ASC")->fetchAll(PDO::FETCH_ASSOC);
$education = $pdo->query("SELECT * FROM education ORDER BY start_year ASC")->fetchAll(PDO::FETCH_ASSOC);
$experience = $pdo->query("SELECT * FROM experience ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

/* ===== ADDED (ALREADY PRESENT IN YOUR CODE) ===== */
$travels = $pdo->query("SELECT * FROM travels ORDER BY travel_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$favorites = $pdo->query("SELECT * FROM favorites ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Personal Portfolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* === SAME CSS (NO CHANGE) === */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial
        }

        body {
            background: #f4f6f9;
            color: #333
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px
        }

        section {
            margin: 80px 0
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50
        }

        .grid {
            display: grid;
            gap: 25px
        }

        .grid-2 {
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr))
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr))
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1)
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 12px
        }

        .profile {
            text-align: center
        }

        .profile img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px
        }

        .skill {
            display: inline-block;
            background: #3498db;
            color: #fff;
            padding: 10px 18px;
            border-radius: 20px;
            margin: 6px
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- ================= PROFILE ================= -->
        <section class="profile">
            <?php if ($profile): ?>
                <img src="admin/uploads/<?= htmlspecialchars($profile['profile_photo']) ?>">
                <h1><?= htmlspecialchars($profile['name']) ?></h1>
                <h3><?= htmlspecialchars($profile['title']) ?></h3>
            <?php endif; ?>
        </section>

        <!-- ================= ABOUT ================= -->
        <section>
            <h2>About Me</h2>
            <p style="text-align:center;font-size:18px;max-width:900px;margin:auto;">
                <?= nl2br(htmlspecialchars($profile['about'] ?? '')) ?>
            </p>
        </section>

        <!-- ================= SKILLS ================= -->
        <section>
            <h2>Skills</h2>
            <div style="text-align:center">
                <?php foreach ($skills as $s): ?>
                    <span class="skill"><?= htmlspecialchars($s['skill_name']) ?></span>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ================= EDUCATION ================= -->
        <section>
            <h2>Education</h2>
            <div class="grid grid-2">
                <?php foreach ($education as $e): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($e['level']) ?></h3>
                        <p><?= htmlspecialchars($e['institute']) ?></p>
                        <small><?= $e['start_year'] ?> - <?= $e['end_year'] ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ================= EXPERIENCE ================= -->
        <section>
            <h2>Experience</h2>
            <div class="grid grid-2">
                <?php foreach ($experience as $ex): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($ex['role']) ?></h3>
                        <p><?= htmlspecialchars($ex['company_name']) ?></p>
                        <small><?= $ex['start_date'] ?> - <?= $ex['is_current'] ? 'Present' : $ex['end_date'] ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ================= PROJECTS ================= -->
        <section>
            <h2>Projects</h2>
            <div class="grid grid-3">
                <?php foreach ($projects as $p): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($p['project_name']) ?></h3>
                        <p><?= htmlspecialchars($p['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ================= TRAVELS (ADDED) ================= -->
        <section>
            <h2>Travels</h2>
            <div class="grid grid-3">
                <?php foreach ($travels as $t): ?>
                    <div class="card">
                        <?php if ($t['travel_image']): ?>
                            <img src="admin/uploads/<?= htmlspecialchars($t['travel_image']) ?>">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($t['place_name']) ?></h3>
                        <small><?= date('d M Y', strtotime($t['travel_date'])) ?></small>
                        <p><?= htmlspecialchars($t['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ================= FAVORITES (ADDED) ================= -->
        <section>
            <h2>Favorites</h2>
            <div style="text-align:center;font-size:20px;">
                <?php foreach ($favorites as $f): ?>
                    ⭐ <?= htmlspecialchars($f['category']) ?>&nbsp;&nbsp;
                <?php endforeach; ?>
            </div>
        </section>

        <!-- ================= CONTACT FORM ================= -->
        <section>
            <h2>Send Me a Message</h2>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'sent'): ?>
                <p style="text-align:center;color:green;font-weight:bold;">
                    Message sent successfully!
                </p>
            <?php endif; ?>

            <form method="post" style="max-width:600px;margin:auto;">
                <input type="text" name="name" placeholder="Your Name" required style="width:100%;padding:12px;margin-bottom:10px;">
                <input type="email" name="email" placeholder="Your Email" required style="width:100%;padding:12px;margin-bottom:10px;">
                <textarea name="message" placeholder="Your Message" required style="width:100%;padding:12px;height:120px;margin-bottom:10px;"></textarea>
                <button type="submit" name="send_message"
                    style="padding:12px 30px;background:#3498db;color:#fff;border:none;border-radius:5px;">
                    Send Message
                </button>
            </form>
        </section>

    </div>
</body>

</html>