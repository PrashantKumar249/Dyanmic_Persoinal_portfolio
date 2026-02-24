<?php
require __DIR__ . '/admin/db.php';

//  INSERT MESSAGE (NO CHANGE)
if (isset($_POST['send_message'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $messageText = trim($_POST['message']);

    //Empty check
    if (!$name || !$email || !$messageText) {
        header("Location: index.php?msg=empty#contact");
        exit;
    }

    //Email format validation (IMPORTANT FIX)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?msg=invalid_email#contact");
        exit;
    }

    //Insert only if everything is valid
    $stmt = $pdo->prepare(
        "INSERT INTO contact_messages (name, email, message)
         VALUES (?, ?, ?)"
    );
    $stmt->execute([$name, $email, $messageText]);

    header("Location: index.php?msg=sent#contact");
    exit;
}

//FETCH DATA (NO CHANGE) 
$profile = $pdo->query("SELECT * FROM profile ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$skills = $pdo->query("SELECT * FROM skills ORDER BY skill_type, id ASC")->fetchAll(PDO::FETCH_ASSOC);
$education = $pdo->query("SELECT * FROM education ORDER BY start_year ASC")->fetchAll(PDO::FETCH_ASSOC);
$experience = $pdo->query("SELECT * FROM experience ORDER BY start_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$travels = $pdo->query("SELECT * FROM travels ORDER BY travel_date DESC")->fetchAll(PDO::FETCH_ASSOC);
$favorites = $pdo->query("SELECT * FROM favorites ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

// Extract First Name for Logo
$fullName = $profile['name'] ?? 'Portfolio';
$firstName = explode(' ', trim($fullName))[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($profile['name']) ?> | Portfolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #00d2ff;
            --secondary: #3a7bd5;
            --bg-dark: #0a0a0c;
            --card-bg: #16161a;
            --text-main: #ffffff;
            --text-dim: #a0a0ab;
            --accent-gradient: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            line-height: 1.6;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* HEADER & NAV */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(10, 10, 12, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 800;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: capitalize;
            letter-spacing: -0.5px;
        }

        /* DESKTOP NAV */
        nav {
            display: flex;
            align-items: center;
        }

        nav a {
            color: var(--text-dim);
            text-decoration: none;
            margin-left: 2rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: 0.3s;
        }

        nav a:hover {
            color: var(--primary);
        }

        /* MOBILE MENU TOGGLE */
        .menu-btn {
            display: none;
            cursor: pointer;
            font-size: 1.8rem;
            color: white;
            z-index: 1100;
        }

        /* CONTAINER */
        .container {
            max-width: 1100px;
            margin: auto;
            padding: 0 20px;
        }

        section {
            padding: 50px 0;
        }

        h2.section-title {
            font-size: clamp(1.5rem, 4vw, 2.2rem);
            margin-bottom: 2rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        h2.section-title::after {
            content: '';
            height: 2px;
            flex-grow: 1;
            background: rgba(255, 255, 255, 0.05);
        }

        /* HERO SECTION */
        .hero {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            padding-top: 100px;
        }

        .hero-content {
            flex: 1;
        }

        .hero-content h1 {
            font-size: clamp(2.5rem, 8vw, 4.5rem);
            line-height: 1.1;
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: var(--text-dim);
            max-width: 500px;
            margin-bottom: 2rem;
        }

        .hero-image {
            flex: 0 0 auto;
        }

        .hero-image img {
            width: clamp(220px, 30vw, 320px);
            height: clamp(220px, 30vw, 320px);
            border-radius: 30px;
            object-fit: cover;
            border: 2px solid rgba(0, 210, 255, 0.3);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            transition: 0.4s ease;
        }

        .hero-image:hover img {
            border-color: var(--primary);
            transform: scale(1.02);
        }

        /* GRID SYSTEM */
        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-2 {
            grid-template-columns: 1.4fr 1fr;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }

        /* CARDS */
        .card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: 0.3s;
        }

        .card:hover {
            background: #1c1c21;
        }

        /* TRAVEL ITEMS */
        .travel-item {
            display: flex;
            gap: 25px;
            align-items: flex-start;
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 1.5rem;
            transition: 0.3s;
        }

        .travel-img-box {
            width: 200px;
            height: 130px;
            flex-shrink: 0;
            overflow: hidden;
            border-radius: 12px;
        }

        .travel-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .travel-info {
            flex: 1;
        }

        .travel-info h4 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .travel-info p {
            color: var(--text-dim);
            font-size: 0.95rem;
        }

        /* TECH & SKILL TAGS */
        .tech-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 15px 0;
        }

        .tech-pill {
            font-size: 0.75rem;
            background: rgba(0, 210, 255, 0.08);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: 600;
        }

        .skill-tag {
            background: rgba(255, 255, 255, 0.04);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-right: 8px;
            margin-bottom: 8px;
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* FAVORITES */
        .fav-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .fav-pill {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(0, 210, 255, 0.15);
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .fav-pill:hover {
            background: rgba(0, 210, 255, 0.05);
            border-color: var(--primary);
        }

        /* CONTACT FORM */
        .form-group {
            margin-bottom: 1.2rem;
        }

        input,
        textarea {
            width: 100%;
            padding: 14px;
            background: #111;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            color: white;
            outline: none;
            font-size: 1rem;
            transition: 0.3s;
        }

        input:focus,
        textarea:focus {
            border-color: var(--primary);
            background: #16161a;
        }

        button.btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--accent-gradient);
            border: none;
            color: white;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            transition: 0.3s;
        }

        button.btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* MOBILE MENU SIDEBAR */
        @media (max-width: 900px) {
            header {
                padding: 1rem 1.5rem;
            }

            .menu-btn {
                display: block;
            }

            nav {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                background: #0d0d10;
                flex-direction: column;
                justify-content: center;
                transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1050;
                padding: 40px;
                border-left: 1px solid rgba(255, 255, 255, 0.05);
            }

            nav.active {
                right: 0;
            }

            nav a {
                margin: 1.5rem 0;
                font-size: 1.2rem;
                margin-left: 0;
            }

            .hero {
                flex-direction: column-reverse;
                text-align: center;
                justify-content: center;
                min-height: 90vh;
            }

            .hero-content p {
                margin: 0 auto 2rem auto;
            }

            .hero-image img {
                width: 220px;
                height: 220px;
                border-radius: 20px;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }

            .travel-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .travel-img-box {
                width: 100%;
                height: 180px;
            }

            h2.section-title {
                text-align: center;
                flex-direction: column;
            }

            h2.section-title::after {
                width: 50px;
                flex-grow: 0;
                height: 3px;
                margin: 0 auto;
            }
        }
    </style>
</head>

<body>

    <!--  HEADER  -->
    <header>
        <div class="logo"><?= htmlspecialchars($firstName) ?></div>
        <div class="menu-btn" onclick="toggleMenu()">☰</div>
        <nav id="navbar">
            <a href="#home" onclick="toggleMenu()">Home</a>
            <a href="#about" onclick="toggleMenu()">About</a>
            <a href="#projects" onclick="toggleMenu()">Projects</a>
            <a href="#travels" onclick="toggleMenu()">Travels</a>
            <a href="#favorites" onclick="toggleMenu()">Favorites</a>
            <a href="#contact" onclick="toggleMenu()">Contact</a>
        </nav>
    </header>

    <!--  HERO  -->
    <section id="home">
        <div class="container hero">
            <div class="hero-content">
                <h3 style="color: var(--primary); font-size: 1rem; letter-spacing: 3px; font-weight: 600; margin-bottom: 10px;">
                    Hello 🙏
                </h3>

                <h1>I'm <?= htmlspecialchars($profile['name']) ?></h1>

                <p>
                    <?= htmlspecialchars($profile['title']) ?>.
                    I craft high-quality digital experiences that combine performance with aesthetics.
                </p>

                <div style="display: flex; gap: 15px; justify-content: inherit; flex-wrap: wrap;">

                    <!-- Contact Button -->
                    <a href="#contact"
                        style="background: var(--accent-gradient); padding: 12px 30px; border-radius: 12px; color: white; text-decoration: none; font-weight: 600; font-size: 0.95rem; box-shadow: 0 10px 20px rgba(0, 210, 255, 0.2);">
                        Let's Talk
                    </a>

                    <!-- Projects Button -->
                    <a href="#projects"
                        style="border: 1px solid rgba(255, 255, 255, 0.1); padding: 12px 30px; border-radius: 12px; color: white; text-decoration: none; font-weight: 600; font-size: 0.95rem;">
                        View Work
                    </a>

                    <!-- Resume Button -->
                    <?php if (!empty($profile['resume_pdf'])): ?>
                        <a href="admin/uploads/<?= htmlspecialchars($profile['resume_pdf']) ?>"
                            target="_blank"
                            style="border: 1px solid var(--primary); padding: 12px 30px; border-radius: 12px; color: var(--primary); text-decoration: none; font-weight: 600; font-size: 0.95rem;">
                            View Resume
                        </a>
                    <?php endif; ?>

                </div>
            </div>

            <div class="hero-image">
                <img src="admin/uploads/<?= htmlspecialchars($profile['profile_photo']) ?>" alt="Profile">
            </div>
        </div>
    </section>

    <!--  ABOUT & EDUCATION -->
    <section id="about">
        <div class="container grid grid-2">
            <div class="card">
                <h2 class="section-title">About Me</h2>
                <p style="color: var(--text-dim); font-size: 1rem;"><?= nl2br(htmlspecialchars($profile['about'])) ?></p>
            </div>
            <div class="card">
                <h2 class="section-title">Expertise & Education</h2>
                <div style="margin-bottom: 2rem;">
                    <?php foreach ($skills as $s): ?>
                        <span class="skill-tag"><?= htmlspecialchars($s['skill_name']) ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="grid" style="gap: 15px;">
                    <?php foreach ($education as $e): ?>
                        <div style="border-left: 3px solid var(--primary); padding: 5px 15px; background: rgba(255,255,255,0.02); border-radius: 0 12px 12px 0;">
                            <p style="font-weight: 700; font-size: 1rem;"><?= $e['level'] ?></p>
                            <p style="font-size: 0.85rem; color: var(--text-dim);"><?= $e['institute'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- PROJECTS  -->
    <section id="projects" style="background: rgba(255,255,255,0.01);">
        <div class="container">
            <h2 class="section-title">Featured Projects</h2>
            <div class="grid grid-3">
                <?php foreach ($projects as $p): ?>
                    <div class="card">
                        <?php if (!empty($p['project_image'])): ?>
                            <img src="admin/uploads/<?= htmlspecialchars($p['project_image']) ?>" style="width:100%; height:180px; object-fit:cover; border-radius:12px; margin-bottom:15px;">
                        <?php endif; ?>

                        <h3 style="font-size: 1.25rem; margin-bottom: 8px;"><?= htmlspecialchars($p['project_name']) ?></h3>

                        <p style="font-size: 0.9rem; color: var(--text-dim); flex-grow: 1;">
                            <?= htmlspecialchars($p['description']) ?>
                        </p>

                        <?php if (!empty($p['technologies'])): ?>
                            <div class="tech-tags">
                                <?php
                                $techs = explode(',', $p['technologies']);
                                foreach ($techs as $t):
                                ?>
                                    <span class="tech-pill"><?= htmlspecialchars(trim($t)) ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div style="margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 15px;">
                            <a href="<?= htmlspecialchars($p['github_link'] ?? '#') ?>" style="color:var(--primary); text-decoration:none; font-size:0.9rem; font-weight:700;">View Repository →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!--  TRAVELS  -->
    <section id="travels">
        <div class="container">
            <h2 class="section-title">My Journeys</h2>
            <div>
                <?php foreach ($travels as $t): ?>
                    <div class="travel-item">
                        <div class="travel-img-box">
                            <img src="admin/uploads/<?= $t['travel_image'] ?>" alt="Travel">
                        </div>
                        <div class="travel-info">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 10px;">
                                <h4><?= htmlspecialchars($t['place_name']) ?></h4>
                                <span style="font-size: 0.8rem; font-weight: 700; color: var(--primary); background: rgba(0, 210, 255, 0.1); padding: 4px 12px; border-radius: 50px;"><?= date('M Y', strtotime($t['travel_date'])) ?></span>
                            </div>
                            <p><?= htmlspecialchars($t['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!--  FAVORITES  -->
    <section id="favorites" style="background: rgba(255,255,255,0.01);">
        <div class="container">
            <h2 class="section-title">Things I Love</h2>
            <div class="fav-container">
                <?php foreach ($favorites as $f): ?>
                    <div class="fav-pill">⭐ <?= htmlspecialchars($f['category']) ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!--  CONTACT -->
    <section id="contact">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <div class="card" style="max-width: 650px; margin: 0 auto;">
                <?php if (isset($_GET['msg']) && $_GET['msg'] === 'invalid_email'): ?>
                    <div style="background: rgba(255,0,0,0.1); color:#ff6b6b; padding:12px; border-radius:8px; margin-bottom:20px; text-align:center; font-weight:600;">
                        ❌ Please enter a valid email address
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['msg']) && $_GET['msg'] === 'empty'): ?>
                    <div style="background: rgba(255,165,0,0.1); color:#ffb347; padding:12px; border-radius:8px; margin-bottom:20px; text-align:center; font-weight:600;">
                        ⚠️ All fields are required
                    </div>
                <?php endif; ?>
                <form method="post">
                    <div class="grid grid-2" style="gap: 1rem;">
                        <input type="text" name="name" placeholder="Full Name" required>
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div style="margin-top: 1rem;">
                        <textarea name="message" rows="5" placeholder="How can I help you?" required></textarea>
                    </div>
                    <button name="send_message" class="btn-submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <footer style="text-align: center; padding: 40px 20px; border-top: 1px solid rgba(255,255,255,0.05); color: var(--text-dim); font-size: 0.9rem;">
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($profile['name']) ?>. Built with passion and code.</p>
    </footer>

    <script>
        function toggleMenu() {
            const nav = document.getElementById('navbar');
            nav.classList.toggle('active');
        }

        // Close menu when clicking outside (on overlay effect)
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('navbar');
            const btn = document.querySelector('.menu-btn');
            if (!nav.contains(event.target) && !btn.contains(event.target)) {
                nav.classList.remove('active');
            }
        });
    </script>

</body>

</html>