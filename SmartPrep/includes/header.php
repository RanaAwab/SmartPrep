<?php
if (!isset($title)) {
    $title = defined('APP_NAME') ? APP_NAME : "SmartPrep";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= $title ?></title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

/* ===== GLOBAL ===== */
body {
    margin: 0;
    background: #f8fafc; /* Keep content area clean */
    font-family: 'Outfit', sans-serif;
    color: #1e293b;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* ===== LAYOUT ===== */
.layout {
    display: flex;
    flex: 1; 
}

/* ===== MAIN CONTENT ===== */
.main-content {
    flex: 1;
    padding: 35px 40px;
    overflow-x: hidden;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 270px;
    background: #0f172a; /* Premium dark blue */
    color: #f8fafc;
    flex-shrink: 0;
    padding: 30px 20px;
    border-right: 1px solid #1e293b;
    display: flex;
    flex-direction: column;
}

/* Sidebar Branding */
.sidebar h4 {
    font-weight: 800;
    letter-spacing: 0.5px;
    color: #ffffff;
    margin-bottom: 2.5rem !important;
    text-align: center;
}

/* Sidebar links */
.sidebar a {
    color: #94a3b8;
    display: flex;
    align-items: center;
    padding: 14px 18px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 500;
    font-size: 1.05rem;
    transition: all 0.2s ease;
    margin-bottom: 6px;
}

.sidebar a:hover {
    background: rgba(255, 255, 255, 0.05); /* Slight highlight */
    color: #ffffff;
    transform: translateX(5px);
}

/* Section titles */
.sidebar .section {
    font-size: 0.8rem;
    font-weight: 700;
    color: #475569;
    margin: 25px 0 12px 10px;
    text-transform: uppercase;
    letter-spacing: 1.2px;
}

/* ===== CARDS (Global Default for legacy cards) ===== */
.card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.03);
    box-shadow: 0 10px 25px rgba(0,0,0,0.02);
    background: #ffffff;
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 15px 35px rgba(0,0,0,0.05);
}

/* ===== TABLE ===== */
.table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}
.table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
    padding: 16px;
    border-top: none;
}
.table tbody td {
    padding: 16px;
    vertical-align: middle;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
}
.table tbody tr:hover td {
    background-color: #f8fafc;
}

/* ===== BUTTON ===== */
.btn {
    border-radius: 10px;
    font-weight: 500;
    padding: 10px 20px;
    transition: all 0.2s ease;
}

/* ===== CUSTOM TOP NAVBAR (DARK THEME) ===== */
.top-navbar {
    background: #0f172a; /* Deep obsidian matching the sidebar */
    box-shadow: 0 4px 25px rgba(0,0,0,0.2);
    padding: 14px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 10; /* Above sidebar flow */
    position: relative;
    border-bottom: 1px solid #1e293b;
}

.top-navbar .navbar-brand {
    font-weight: 800;
    font-size: 1.6rem;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    letter-spacing: -0.5px;
}

.top-navbar .navbar-brand i {
    color: #3b82f6 !important;
}

.top-navbar .user-pnl {
    display: flex;
    align-items: center;
    gap: 20px;
}

.role-badge {
    background: rgba(255, 255, 255, 0.05);
    color: #e2e8f0;
    padding: 8px 18px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    gap: 8px;
}

.role-label {
    background: rgba(59, 130, 246, 0.25);
    color: #93c5fd;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-logout {
    background: rgba(239, 68, 68, 0.15);
    color: #fca5a5;
    border: 1px solid rgba(239, 68, 68, 0.2);
    padding: 9px 18px;
    border-radius: 10px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-logout:hover {
    background: rgba(239, 68, 68, 0.3);
    color: #fef2f2;
    transform: translateY(-1px);
    border-color: rgba(239, 68, 68, 0.4);
}

</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="top-navbar">
    <a href="<?= defined('BASE_URL') ? base_url() : '/' ?>" class="navbar-brand">
        <i class="bi bi-mortarboard-fill text-primary"></i>
        <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?>
    </a>

    <div class="user-pnl">
        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="role-badge shadow-sm">
                <i class="bi bi-person-circle"></i>
                <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>
                <?php if (isset($_SESSION['role'])): ?>
                    <span class="role-label"><?= htmlspecialchars($_SESSION['role']) ?></span>
                <?php endif; ?>
            </span>
            <a href="<?= defined('BASE_URL') ? base_url('logout.php') : 'logout.php' ?>" class="btn-logout shadow-sm">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        <?php endif; ?>
    </div>
</nav>

<!-- MAIN LAYOUT -->
<div class="layout">