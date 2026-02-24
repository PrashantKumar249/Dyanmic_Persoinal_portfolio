<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

// Fetch username
$stmt = $pdo->query("SELECT username FROM admin LIMIT 1");
$admin = $stmt->fetch();
$adminName = $admin['username'] ?? 'Admin';

// Stats Data (Aap inhe real database queries se replace kar sakte hain)
$stats = [
    ['label' => 'Total Projects', 'count' => 3, 'color' => '#3b82f6'],
    ['label' => 'Skills Added', 'count' => 12, 'color' => '#f59e0b'],
    ['label' => 'New Messages', 'count' => 5, 'color' => '#10b981'],
    ['label' => 'Countries Traveled', 'count' => 8, 'color' => '#8b5cf6'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Portfolio</title>
    <style>
        /* --- CSS VARIABLES --- */
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --bg-main: #f1f5f9;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --radius: 12px;
        }

        /* --- GLOBAL STYLES --- */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            background-color: var(--bg-main); 
            color: var(--text-main);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: #cbd5e1;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .sidebar-header {
            padding: 24px;
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--sidebar-hover);
        }
        .nav-list {
            flex: 1;
            padding: 20px 12px;
            list-style: none;
            overflow-y: auto;
        }
        .nav-item { margin-bottom: 4px; }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            color: inherit;
            border-radius: 8px;
            transition: 0.2s;
            font-size: 0.95rem;
        }
        .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: var(--white);
        }
        .nav-link.active {
            background-color: var(--primary);
            color: var(--white);
        }
        .nav-section-title {
            padding: 20px 16px 8px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
        }
        .logout-btn {
            padding: 20px;
            border-top: 1px solid var(--sidebar-hover);
        }
        .logout-link {
            color: #f87171;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-weight: 500;
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        header {
            height: 64px;
            background: var(--white);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .avatar {
            width: 36px;
            height: 36px;
            background: #dbeafe;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.85rem;
        }

        /* --- DASHBOARD GRID --- */
        .content-inner {
            padding: 32px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: var(--radius);
            padding: 32px;
            color: var(--white);
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
        }
        .btn-view {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
            backdrop-filter: blur(4px);
        }
        .btn-view:hover { background: white; color: var(--primary); }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: var(--white);
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid #f1f5f9;
        }
        .stat-value { font-size: 1.8rem; font-weight: 700; margin-top: 8px; }
        .stat-label { color: var(--text-muted); font-size: 0.9rem; font-weight: 500; }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        .action-card {
            background: var(--white);
            padding: 20px;
            border-radius: var(--radius);
            text-align: center;
            text-decoration: none;
            color: var(--text-main);
            border: 1px solid #e2e8f0;
            transition: 0.2s;
            font-weight: 500;
        }
        .action-card:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        .action-card svg { margin-bottom: 12px; color: var(--text-muted); transition: 0.2s; }
        .action-card:hover svg { color: var(--primary); }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .welcome-banner { flex-direction: column; text-align: center; gap: 20px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #60a5fa;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
            PortfoAdmin
        </div>
        
        <nav class="nav-list">
            <div class="nav-item">
                <a href="dashboard.php" class="nav-link active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="profile_list.php" class="nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Profile Page
                </a>
            </div>

            <div class="nav-section-title">Content Management</div>
            <div class="nav-item"><a href="skills.php" class="nav-link">Skills</a></div>
            <div class="nav-item"><a href="projects.php" class="nav-link">Projects</a></div>
            <div class="nav-item"><a href="education_list.php" class="nav-link">Education</a></div>
            <div class="nav-item"><a href="experience_list.php" class="nav-link">Experience</a></div>
            <div class="nav-item"><a href="travel.php" class="nav-link">Travels</a></div>

            <div class="nav-section-title">Inbox</div>
            <div class="nav-item">
                <a href="contact_messages.php" class="nav-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"></path><path d="M22 2 11 13"></path></svg>
                    Messages
                </a>
            </div>
        </nav>

        <div class="logout-btn">
            <a href="logout.php" class="logout-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h2 style="font-size: 1.1rem; font-weight: 600;">Overview</h2>
            <div class="user-profile">
                <div style="text-align: right;">
                    <div style="font-size: 0.85rem; font-weight: 600;"><?= ucfirst($adminName) ?></div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">Admin Account</div>
                </div>
                <div class="avatar"><?= strtoupper(substr($adminName, 0, 1)) ?></div>
            </div>
        </header>

        <div class="content-inner">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div>
                    <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 8px;">Welcome Back, <?= ucfirst($adminName) ?>!</h1>
                    <p style="opacity: 0.9;">Your portfolio is live and looking great. Here's what's happening today.</p>
                </div>
                <a href="../index.php" target="_blank" class="btn-view">Visit Live Site</a>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <?php foreach ($stats as $stat): ?>
                <div class="stat-card">
                    <div class="stat-label"><?= $stat['label'] ?></div>
                    <div class="stat-value" style="color: <?= $stat['color'] ?>"><?= $stat['count'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Quick Actions -->
            <h3 style="margin-bottom: 20px; font-size: 1.1rem;">Quick Management</h3>
            <div class="quick-actions">
                <a href="projects.php" class="action-card">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    <div>New Project</div>
                </a>
                <a href="skills.php" class="action-card">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    <div>Add Skill</div>
                </a>
                <a href="contact_messages.php" class="action-card">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <div>Messages</div>
                </a>
                <a href="profile_list.php" class="action-card">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    <div>Settings</div>
                </a>
            </div>
        </div>
    </div>

</body>
</html>