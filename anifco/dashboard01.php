<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            --bg:        #f4f4f4;
            --white:     #ffffff;
            --text-dark: #1a1a1a;
            --text-mid:  #555555;
            --text-light:#888888;
            --shadow:    0 4px 20px rgba(0,0,0,0.08);
            --radius:    12px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .header {
            background: var(--header-bg);
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .header .logo-box {
            width: 36px;
            height: 36px;
            background: var(--accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            color: white;
        }

        .header .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
        }

        .header .logo-text span {
            color: var(--accent2);
        }

        .header-right {
            font-size: 13px;
            color: #ffffff60;
        }

        /* MAIN */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 20px 100px;
        }

        .welcome-box {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeDown 0.5s ease both;
        }

        .welcome-box h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .welcome-box p {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 300;
        }

        .divider {
            width: 48px;
            height: 3px;
            background: var(--accent);
            border-radius: 99px;
            margin: 12px auto 0;
        }

        /* CARDS */
        .cards {
            display: flex;
            flex-direction: column;
            gap: 14px;
            width: 100%;
            max-width: 460px;
        }

        .card {
            display: flex;
            align-items: center;
            gap: 18px;
            background: var(--white);
            border: 1px solid #e8e8e8;
            border-radius: var(--radius);
            padding: 20px 24px;
            text-decoration: none;
            color: var(--text-dark);
            box-shadow: var(--shadow);
            transition: all 0.25s ease;
            animation: fadeUp 0.5s ease both;
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        .card:nth-child(5) { animation-delay: 0.5s; }
        .card:nth-child(6) { animation-delay: 0.6s; }

        .card:hover {
            border-color: var(--accent);
            box-shadow: 0 6px 24px rgba(200,134,10,0.15);
            transform: translateY(-2px);
        }

        .card:active {
            transform: scale(0.99);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #fff6e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
            transition: background 0.25s;
        }

        .card:hover .icon-box {
            background: var(--accent);
        }

        .card-text h3 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .card-text p {
            font-size: 12px;
            color: var(--text-light);
            font-weight: 300;
        }

        .arrow {
            margin-left: auto;
            font-size: 20px;
            color: #cccccc;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .card:hover .arrow {
            transform: translateX(4px);
            color: var(--accent);
        }

        /* LOGOUT */
        .logout-wrap {
            position: fixed;
            bottom: 28px;
            right: 28px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--white);
            border: 1px solid #e0e0e0;
            color: var(--text-mid);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #fff0f0;
            border-color: #e05555;
            color: #e05555;
        }

        /* FOOTER */
        .footer {
            background: var(--header-bg);
            text-align: center;
            padding: 12px;
            font-size: 12px;
            color: #ffffff35;
            letter-spacing: 0.5px;
        }

        /* ANIMATIONS */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <a href="#" class="logo">
            <div class="logo-box">A</div>
            <span class="logo-text">ANIF<span>CO</span></span>
        </a>
        <div class="header-right">Dashboard</div>
    </div>

    <!-- Main -->
    <div class="main">

        <div class="welcome-box">
            <h2>What would you like to do?</h2>
            <p>Select an option below to continue</p>
            <div class="divider"></div>
        </div>

        <div class="cards">

            <!-- Message -->
            <a href="send_message.php" class="card">
                <div class="icon-box">💬</div>
                <div class="card-text">
                    <h3>Message</h3>
                    <p>View and send messages</p>
                </div>
                <span class="arrow">›</span>
            </a>

            <!-- Order Tracking -->
            <a href="user_tracking.php" class="card">
                <div class="icon-box">📦</div>
                <div class="card-text">
                    <h3>Order Tracking</h3>
                    <p>Track your order status live</p>
                </div>
                <span class="arrow">›</span>
            </a>

            <!-- Report Problem -->
            <a href="report_problem.php" class="card">
                <div class="icon-box">🚨</div>
                <div class="card-text">
                    <h3>Report a Problem</h3>
                    <p>Let us know if something's wrong</p>
                </div>
                <span class="arrow">›</span>
            </a>

            <!-- Request for Service -->
            <a href="servicee.php" class="card">
                <div class="icon-box">🔧</div>
                <div class="card-text">
                    <h3>Request for Service</h3>
                    <p>Check warranty & request service</p>
                </div>
                <span class="arrow">›</span>
            </a>

            <!-- My Service Requests -->
            <a href="request.php" class="card">
                <div class="icon-box">🛠️</div>
                <div class="card-text">
                    <h3>My Service Requests</h3>
                    <p>Track your service request status</p>
                </div>
                <span class="arrow">›</span>
            </a>

        </div>

    </div>

    <!-- Footer -->
    <div class="footer">© 2026 Anifco. All rights reserved.</div>

    <!-- Logout -->
    <div class="logout-wrap">
        <a href="login.php" class="logout-btn">
            ⎋ &nbsp;Logout
        </a>
    </div>

</body>
</html>