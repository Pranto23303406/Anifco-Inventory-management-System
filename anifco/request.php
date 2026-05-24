<?php
session_start();
include("db.php");

// Customer এর নাম দিয়ে বা session দিয়ে filter করো
$customer_name = $_SESSION['user_name']; // অথবা তোমার session variable
$customer_name = mysqli_real_escape_string($conn, $customer_name);

$requests = mysqli_query($conn, "SELECT * FROM service_requests WHERE customer_name='$customer_name' ORDER BY requested_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Service Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --header-bg:#2c1a0e; --accent:#c8860a; --accent2:#e6a820;
            --bg:#f4f4f4; --white:#fff; --radius:12px;
            --shadow:0 4px 16px rgba(0,0,0,0.08);
        }
        body { font-family:'Poppins',sans-serif; background:var(--bg); min-height:100vh; display:flex; flex-direction:column; }

        .header {
            background:var(--header-bg); padding:0 36px; height:62px;
            display:flex; align-items:center; justify-content:space-between;
            box-shadow:0 2px 12px rgba(0,0,0,0.25);
        }
        .logo { font-size:20px; font-weight:700; color:white; letter-spacing:1px; text-decoration:none; }
        .logo span { color:var(--accent2); }
        .back-btn {
            background:#ffffff15; border:1px solid #ffffff20; color:white;
            padding:7px 16px; border-radius:8px; text-decoration:none;
            font-size:13px; font-family:'Poppins',sans-serif;
        }

        .main { flex:1; padding:36px 20px 80px; max-width:700px; width:100%; margin:0 auto; }
        .page-title { font-size:20px; font-weight:700; color:#1a1a1a; margin-bottom:4px; }
        .page-sub { font-size:13px; color:#999; margin-bottom:24px; }

        .request-card {
            background:var(--white); border-radius:var(--radius); padding:20px 24px;
            box-shadow:var(--shadow); margin-bottom:14px;
            border-left:4px solid #ddd; transition:border-color 0.2s;
        }
        .request-card.accepted { border-left-color:#4caf50; }
        .request-card.pending  { border-left-color:var(--accent); }

        .rc-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
        .rc-order { font-size:15px; font-weight:700; color:#1a1a1a; }
        .badge-pending  { background:#fff3e0; color:#e65100; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-accepted { background:#e8f5e9; color:#2e7d32; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }

        .rc-row { display:flex; justify-content:space-between; font-size:13px; padding:6px 0; border-bottom:1px solid #f5f5f5; }
        .rc-row:last-child { border:none; }
        .rc-label { color:#999; }
        .rc-val { font-weight:600; color:#333; }

        .accepted-notice {
            margin-top:12px; background:#e8f5e9; border-radius:8px;
            padding:10px 14px; font-size:13px; color:#2e7d32; font-weight:500;
        }

        .empty-state { text-align:center; padding:60px 20px; color:#bbb; font-size:15px; }
        .footer { background:var(--header-bg); text-align:center; padding:11px; font-size:12px; color:#ffffff30; }
    </style>
</head>
<body>

<div class="header">
    <a href="#" class="logo">ANIF<span>CO</span></a>
    <a href="dashboard_user.php" class="back-btn">← Back</a>
</div>

<div class="main">
    <div class="page-title">🔧 My Service Requests</div>
    <div class="page-sub">Track your submitted service requests</div>

    <?php if (mysqli_num_rows($requests) === 0): ?>
        <div class="empty-state">📭 You haven't submitted any service requests yet.</div>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($requests)): ?>
        <div class="request-card <?php echo strtolower($row['request_status']); ?>">
            <div class="rc-top">
                <span class="rc-order">Order: <?php echo htmlspecialchars($row['order_id']); ?></span>
                <?php if ($row['request_status'] === 'Accepted'): ?>
                    <span class="badge-accepted">✅ Accepted</span>
                <?php else: ?>
                    <span class="badge-pending">⏳ Pending</span>
                <?php endif; ?>
            </div>
            <div class="rc-row"><span class="rc-label">Item</span><span class="rc-val"><?php echo htmlspecialchars($row['item_name']); ?></span></div>
            <div class="rc-row"><span class="rc-label">Warranty</span><span class="rc-val">🛡️ <?php echo htmlspecialchars($row['warranty']); ?></span></div>
            <div class="rc-row"><span class="rc-label">Requested</span><span class="rc-val"><?php echo date('d M Y, h:i A', strtotime($row['requested_at'])); ?></span></div>

            <?php if ($row['request_status'] === 'Accepted'): ?>
            <div class="accepted-notice">
                🎉 Your service request has been accepted! Our team will contact you soon.
                <?php if ($row['accepted_at']): ?>
                <br><small>Accepted on: <?php echo date('d M Y, h:i A', strtotime($row['accepted_at'])); ?></small>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<div class="footer">© 2026 Anifco. All rights reserved.</div>
</body>
</html>