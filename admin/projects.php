<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
require "db.php";

// Fetch projects
$projects = $pdo->query("SELECT * FROM projects ORDER BY id DESC")->fetchAll();
$adminName = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects | Admin</title>
    <style>
        /* --- CSS VARIABLES (Same as Dashboard) --- */
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --bg-main: #f1f5f9;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --white: #ffffff;
            --danger: #ef4444;
            --success: #10b981;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
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
        .nav-list { flex: 1; padding: 20px 12px; list-style: none; }
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
            margin-bottom: 4px;
        }
        .nav-link:hover { background-color: var(--sidebar-hover); color: var(--white); }
        .nav-link.active { background-color: var(--primary); color: var(--white); }

        /* --- MAIN CONTENT --- */
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        header {
            height: 64px;
            background: var(--white);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
        }

        .content-inner { padding: 32px; max-width: 1100px; width: 100%; margin: 0 auto; }

        /* --- PAGE HEADER --- */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-back { color: var(--text-muted); }
        .btn-back:hover { color: var(--text-main); }

        /* --- DATA TABLE --- */
        .table-container {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th {
            background: #f8fafc;
            padding: 16px 24px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid #e2e8f0;
        }
        td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; font-size: 0.95rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f8fafc; }

        .btn-delete {
            color: var(--danger);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 6px;
        }
        .btn-delete:hover { background: #fef2f2; }

        .empty-state {
            padding: 48px;
            text-align: center;
            color: var(--text-muted);
        }

        /* --- BREADCRUMB --- */
        .breadcrumb { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px; }
        .breadcrumb a { color: var(--primary); text-decoration: none; }
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
            <a href="dashboard.php" class="nav-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Dashboard
            </a>
            <a href="projects.php" class="nav-link active">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                Manage Projects
            </a>
            <!-- Baki links sidebar ke dashboard se match kar sakte hain -->
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="breadcrumb">
                <a href="dashboard.php">Dashboard</a> / Projects
            </div>
            <div style="font-size: 0.9rem; font-weight: 500;">
                Logged in as: <span style="color: var(--primary);"><?= ucfirst($adminName) ?></span>
            </div>
        </header>

        <div class="content-inner">
            <div class="page-header">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700;">Projects</h1>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Manage and showcase your best work.</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <a href="dashboard.php" class="btn btn-back">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        Back to Home
                    </a>
                    <a href="project_add.php" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add New Project
                    </a>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Project Name</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($projects) > 0): ?>
                            <?php foreach($projects as $p): ?>
                            <tr>
                                <td style="color: var(--text-muted); font-weight: 500;">#<?= $p['id'] ?></td>
                                <td style="font-weight: 600;"><?= htmlspecialchars($p['project_name']) ?></td>
                                <td style="text-align: right;">
                                    <a href="project_edit.php?id=<?= $p['id'] ?>" style="color: var(--primary); font-size: 0.85rem; text-decoration: none; margin-right: 15px;">Edit</a>
                                    <a href="project_delete.php?id=<?= $p['id'] ?>" 
                                       class="btn-delete" 
                                       onclick="return confirm('Are you sure you want to delete this project?')">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="empty-state">
                                    <p>No projects found. Start by adding one!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>