<?php
// index.php - Main Navigation Page
// ANIFCO - Navigation Portal
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANIFCO | Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --primary:       #186ee7;   /* deep navy */
            --primary-light: #24426a;
            --accent:        #c9a84c;   /* warm gold */
            --accent-light:  #e2c068;
            --surface:       #f5f3ee;   /* off-white/cream */
            --surface-2:     #eceae3;
            --text-dark:     #0f1e30;
            --text-mid:      #4a5c70;
            --text-light:    #8496a8;
            --white:         #ffffff;
            --shadow-sm:     0 2px 12px rgba(26,46,74,0.08);
            --shadow-md:     0 8px 32px rgba(26,46,74,0.14);
            --shadow-lg:     0 20px 60px rgba(26,46,74,0.22);
            --radius:        14px;
            --radius-lg:     24px;
        }

        /* ===== RESET ===== */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--surface);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ===== BACKGROUND PATTERN ===== */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 15% 10%, rgba(201,168,76,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 85% 90%, rgba(26,46,74,0.07) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* ===== HEADER ===== */
        header {
            position: relative;
            z-index: 10;
            background: var(--primary);
            padding: 0 48px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-md);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-mark {
            width: 38px;
            height: 38px;
            background: var(--accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 18px;
            color: var(--primary);
            letter-spacing: -0.5px;
            flex-shrink: 0;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 1.5px;
        }

        .logo-text span {
            color: var(--accent);
        }

        .header-tagline {
            font-size: 12px;
            color: var(--text-light);
            letter-spacing: 0.5px;
        }

        /* ===== MAIN ===== */
        main {
            flex: 1;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 24px;
        }

        /* ===== HERO TEXT ===== */
        .portal-label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 14px;
        }

        .portal-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(32px, 5vw, 52px);
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .portal-subtitle {
            font-size: 15px;
            color: var(--text-mid);
            text-align: center;
            max-width: 420px;
            line-height: 1.7;
            margin-bottom: 60px;
        }

        /* ===== DIVIDER ===== */
        .divider {
            width: 48px;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-light));
            border-radius: 2px;
            margin: 0 auto 60px;
        }

        /* ===== CARDS GRID ===== */
        .cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            width: 100%;
            max-width: 760px;
        }

        /* ===== CARD ===== */
        .card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 44px 36px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(26,46,74,0.07);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }

        .card:hover::before {
            opacity: 1;
        }

        /* Active card (Accounts) */
        .card--active {
            border-color: rgba(201,168,76,0.3);
        }

        .card--active::before {
            background: linear-gradient(90deg, var(--accent), var(--accent-light));
            opacity: 1;
        }

        /* Disabled card (Others) */
        .card--disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .card--disabled:hover {
            transform: none;
            box-shadow: var(--shadow-sm);
        }

        .card--disabled::before {
            display: none;
        }

        /* ===== CARD ICON ===== */
        .card-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card--active .card-icon {
            background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(226,192,104,0.25));
        }

        .card--disabled .card-icon {
            background: var(--surface-2);
        }

        .card-icon svg {
            width: 28px;
            height: 28px;
        }

        /* ===== CARD CONTENT ===== */
        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .card-desc {
            font-size: 13.5px;
            color: var(--text-mid);
            line-height: 1.65;
            margin-bottom: 32px;
            min-height: 42px;
        }

        /* ===== BUTTON ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 32px;
            border-radius: 50px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.3px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.25s ease;
        }

        .btn--primary {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 16px rgba(26,46,74,0.25);
        }

        .btn--primary:hover {
            background: var(--primary-light);
            box-shadow: 0 6px 24px rgba(26,46,74,0.35);
            transform: translateX(2px);
        }

        .btn--primary:active {
            transform: scale(0.97);
        }

        .btn--ghost {
            background: var(--surface-2);
            color: var(--text-light);
            cursor: not-allowed;
        }

        .btn svg {
            width: 16px;
            height: 16px;
            transition: transform 0.25s ease;
            flex-shrink: 0;
        }

        .btn--primary:hover svg {
            transform: translateX(3px);
        }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }

        .badge--available {
            background: rgba(34,197,94,0.1);
            color: #16a34a;
        }

        .badge--soon {
            background: rgba(148,163,184,0.12);
            color: var(--text-light);
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .badge--available .badge-dot { background: #22c55e; }
        .badge--soon .badge-dot { background: var(--text-light); }

        /* ===== FOOTER ===== */
        footer {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 24px;
            font-size: 12px;
            color: var(--text-light);
            letter-spacing: 0.3px;
        }

        footer strong {
            color: var(--primary);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            header {
                padding: 0 20px;
            }

            .header-tagline {
                display: none;
            }

            .cards {
                grid-template-columns: 1fr;
                max-width: 380px;
            }

            .card {
                padding: 36px 28px;
            }
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .portal-label   { animation: fadeUp 0.5s ease both; animation-delay: 0.1s; }
        .portal-title   { animation: fadeUp 0.5s ease both; animation-delay: 0.2s; }
        .portal-subtitle { animation: fadeUp 0.5s ease both; animation-delay: 0.28s; }
        .divider        { animation: fadeUp 0.5s ease both; animation-delay: 0.32s; }
        .cards          { animation: fadeUp 0.55s ease both; animation-delay: 0.4s; }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <header>
        <a href="#" class="logo">
            <div class="logo-mark">A</div>
            <span class="logo-text">ANI<span>FCO</span></span>
        </a>
        <span class="header-tagline">Management Portal</span>
    </header>

    <!-- ===== MAIN ===== -->
    <main>
        <p class="portal-label">Welcome to</p>
        <h1 class="portal-title">ANIFCO Portal</h1>
        <p class="portal-subtitle">
            A Perfect Healthcare Partner.
        </p>
        <div class="divider"></div>

        <div class="cards">

            <!-- ACCOUNTS CARD -->
            <div class="card card--active">
                <div class="card-icon">
                    <!-- Ledger / accounts icon -->
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="18" height="18" rx="3" stroke="#c9a84c" stroke-width="1.8"/>
                        <line x1="7" y1="8"  x2="17" y2="8"  stroke="#c9a84c" stroke-width="1.6" stroke-linecap="round"/>
                        <line x1="7" y1="12" x2="17" y2="12" stroke="#c9a84c" stroke-width="1.6" stroke-linecap="round"/>
                        <line x1="7" y1="16" x2="13" y2="16" stroke="#c9a84c" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                </div>

                <span class="badge badge--available">
                    <span class="badge-dot"></span> Available
                </span>

                <h2 class="card-title">Accounts</h2>
                <p class="card-desc">Manage financial records, transactions, and account statements.</p>

                <a href="accounts.php" class="btn btn--primary">
                    Open Accounts
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            <!-- OTHERS CARD -->
            <div class="card card--disabled">
                <div class="card-icon">
                    <!-- Grid / modules icon -->
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3"  y="3"  width="7" height="7" rx="2" stroke="#8496a8" stroke-width="1.8"/>
                        <rect x="14" y="3"  width="7" height="7" rx="2" stroke="#8496a8" stroke-width="1.8"/>
                        <rect x="3"  y="14" width="7" height="7" rx="2" stroke="#8496a8" stroke-width="1.8"/>
                        <rect x="14" y="14" width="7" height="7" rx="2" stroke="#8496a8" stroke-width="1.8"/>
                    </svg>
                </div>

                <span class="badge badge--soon">
                    <span class="badge-dot"></span> Coming Soon
                </span>

                <h2 class="card-title">Others</h2>
                <p class="card-desc">Additional modules and features are being prepared for you.</p>

                <button class="btn btn--ghost" disabled>
                    Coming Soon
                </button>
            </div>

        </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer>
        &copy; <?php echo date('Y'); ?> <strong>ANIFCO</strong>. All rights reserved.
    </footer>

</body>
</html>