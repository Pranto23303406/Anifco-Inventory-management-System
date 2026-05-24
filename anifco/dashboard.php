<?php
session_start();

if(!isset($_SESSION['user_name'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Anifco</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --header-bg: #2c1a0e;
            --accent:    #c8860a;
            --accent2:   #e6a820;
            --bg:        #f0f0f0;
            --white:     #ffffff;
            --text-dark: #1a1a1a;
            --text-mid:  #555;
            --text-light:#999;
            --shadow:    0 4px 16px rgba(0,0,0,0.08);
            --radius:    14px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: var(--header-bg);
            padding: 0 36px;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(0,0,0,0.25);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 20px;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
            text-decoration: none;
        }

        .logo span { color: var(--accent2); }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .welcome-text {
            font-size: 13px;
            color: #ffffff70;
        }

        .welcome-text strong {
            color: var(--accent2);
            font-weight: 600;
        }

        .notif-wrap {
            position: relative;
        }

        .notif-btn {
            background: #ffffff10;
            border: 1px solid #ffffff15;
            color: white;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: background 0.2s;
        }

        .notif-btn:hover { background: #ffffff20; }

        .notif-dropdown {
            display: none;
            position: absolute;
            top: 42px;
            right: 0;
            width: 260px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 28px rgba(0,0,0,0.15);
            z-index: 200;
            border: 1px solid #eee;
        }

        .notif-header {
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f0f0f0;
        }

        .notif-item {
            padding: 11px 16px;
            font-size: 13px;
            color: var(--text-dark);
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.15s;
            cursor: pointer;
        }

        .notif-item:hover { background: #fff8ee; }
        .notif-item:last-child { border-bottom: none; }

        .notif-dot {
            width: 7px;
            height: 7px;
            background: var(--accent);
            border-radius: 50%;
            flex-shrink: 0;
        }

        .notif-wrap:hover .notif-dropdown { display: block; }

        .main {
            flex: 1;
            padding: 36px 40px 100px;
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
        }

        .section-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 18px;
            padding-left: 4px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .menu-card {
            background: var(--white);
            border: 1px solid #e8e8e8;
            border-radius: var(--radius);
            padding: 22px 20px;
            text-decoration: none;
            color: var(--text-dark);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.22s ease;
            animation: fadeUp 0.4s ease both;
        }

        .menu-card:nth-child(1)  { animation-delay: 0.05s; }
        .menu-card:nth-child(2)  { animation-delay: 0.10s; }
        .menu-card:nth-child(3)  { animation-delay: 0.15s; }
        .menu-card:nth-child(4)  { animation-delay: 0.20s; }
        .menu-card:nth-child(5)  { animation-delay: 0.25s; }
        .menu-card:nth-child(6)  { animation-delay: 0.30s; }
        .menu-card:nth-child(7)  { animation-delay: 0.35s; }
        .menu-card:nth-child(8)  { animation-delay: 0.40s; }

        .menu-card:hover {
            border-color: var(--accent);
            box-shadow: 0 6px 22px rgba(200,134,10,0.14);
            transform: translateY(-2px);
        }

        .menu-card:active { transform: scale(0.98); }

        .card-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #fff6e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            transition: background 0.22s;
        }

        .menu-card:hover .card-icon { background: var(--accent); }

        .card-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .card-sub {
            font-size: 11px;
            color: var(--text-light);
            font-weight: 300;
            margin-top: 2px;
        }

        .card-arrow {
            margin-left: auto;
            font-size: 18px;
            color: #ddd;
            transition: transform 0.2s, color 0.2s;
        }

        .menu-card:hover .card-arrow {
            transform: translateX(3px);
            color: var(--accent);
        }

        .logout-wrap {
            position: fixed;
            bottom: 26px;
            right: 26px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #e0e0e0;
            color: var(--text-mid);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: #fff0f0;
            border-color: #e05555;
            color: #e05555;
        }

        .footer {
            background: var(--header-bg);
            text-align: center;
            padding: 11px;
            font-size: 12px;
            color: #ffffff30;
            letter-spacing: 0.5px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media(max-width: 800px){
            .menu-grid { grid-template-columns: repeat(2, 1fr); }
            .main { padding: 24px 20px 100px; }
        }

        @media(max-width: 500px){
            .menu-grid { grid-template-columns: 1fr; }
            .header { padding: 0 20px; }
        }

    </style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <a href="#" class="logo">ANIF<span>CO</span></a>

    <div class="header-right">

        <div class="welcome-text">
            Working Hard Is The Key Of Success
        </div>

        <div class="notif-wrap">
            <button class="notif-btn">🔔 Notifications</button>
            <div class="notif-dropdown">
                <div class="notif-header">Recent</div>
                <div class="notif-item"><span class="notif-dot"></span>Stock update available</div>
                <div class="notif-item"><span class="notif-dot"></span>New invoice created</div>
                <div class="notif-item"><span class="notif-dot"></span>Message from admin</div>
                <div class="notif-item"><span class="notif-dot"></span>Import update completed</div>
            </div>
        </div>

    </div>
</div>

<!-- MAIN -->
<div class="main">

    <div class="section-title">Main Menu</div>

    <div class="menu-grid">

        <a href="equipments.php" class="menu-card">
            <div class="card-icon">📦</div>
            <div>
                <div class="card-label">Stock</div>
                <div class="card-sub">Manage inventory</div>
            </div>
            <span class="card-arrow">›</span>
        </a>

        <a href="accounts.php" class="menu-card">
            <div class="card-icon">💰</div>
            <div>
                <div class="card-label">Accounts</div>
                <div class="card-sub">View financials</div>
            </div>
            <span class="card-arrow">›</span>
        </a>

        <a href="invoice.php" class="menu-card">
            <div class="card-icon">🧾</div>
            <div>
                <div class="card-label">Invoice</div>
                <div class="card-sub">Create & manage invoices</div>
            </div>
            <span class="card-arrow">›</span>
        </a>

        <a href="messages.php" class="menu-card">
            <div class="card-icon">💬</div>
            <div>
                <div class="card-label">Messages</div>
                <div class="card-sub">User conversations</div>
            </div>
            <span class="card-arrow">›</span>
        </a>

        <a href="delivery_updates.php" class="menu-card">
            <div class="card-icon">🚚</div>
            <div>
                <div class="card-label">Delivery Updates</div>
                <div class="card-sub">Track deliveries</div>
            </div>
            <span class="card-arrow">›</span>
        </a>

        <a href="import_update.php" class="menu-card">
            <div class="card-icon">📥</div>
            <div>
                <div class="card-label">Import Updates</div>
                <div class="card-sub">Import records</div>
            </div>
            <span class="card-arrow">›</span>
        </a>

        <a href="view_reports.php" class="menu-card">
            <div class="card-icon">🚨</div>
            <div>
                <div class="card-label">Problem Reports</div>
                <div class="card-sub">Customer reported issues</div>
            </div>
            <span class="card-arrow">›</span>
        </a>

        <a href="requested_problems.php" class="menu-card">
            <div class="card-icon">🛠️</div>
            <div>
                <div class="card-label">Service Request</div>
                <div class="card-sub">Customer service requests</div>
            </div>
            <span class="card-arrow">›</span>
        </a>

    </div>

</div>

<!-- FOOTER -->
<div class="footer">© 2026 Anifco. All rights reserved.</div>

<!-- LOGOUT -->
<div class="logout-wrap">
    <a href="login.php" class="logout-btn">⎋ &nbsp;Logout</a>
</div>

</body>
</html>