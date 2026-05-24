<?php
session_start();
include("db.php");

// Accept করলে update করো
if (isset($_POST['accept_id'])) {
    $id = intval($_POST['accept_id']);
    mysqli_query($conn, "UPDATE service_requests SET request_status='Accepted', accepted_at=NOW() WHERE id=$id");
    header("Location: requested_problems.php");
    exit();
}

$requests = mysqli_query($conn, "SELECT * FROM service_requests ORDER BY requested_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Requested Problems</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --header-bg:#2c1a0e; --accent:#c8860a; --accent2:#e6a820;
            --bg:#f0f0f0; --white:#fff; --radius:12px;
            --shadow:0 4px 16px rgba(0,0,0,0.08);
        }
        body { font-family:'Poppins',sans-serif; background:var(--bg); min-height:100vh; display:flex; flex-direction:column; }

        .header {
            background:var(--header-bg); padding:0 36px; height:62px;
            display:flex; align-items:center; justify-content:space-between;
            box-shadow:0 2px 12px rgba(0,0,0,0.25); position:sticky; top:0; z-index:100;
        }
        .logo { font-size:20px; font-weight:700; color:white; letter-spacing:1px; text-decoration:none; }
        .logo span { color:var(--accent2); }
        .back-btn {
            background:#ffffff15; border:1px solid #ffffff20; color:white;
            padding:7px 16px; border-radius:8px; text-decoration:none;
            font-size:13px; font-family:'Poppins',sans-serif;
        }
        .back-btn:hover { background:#ffffff25; }

        .main { flex:1; padding:36px 40px 80px; max-width:900px; width:100%; margin:0 auto; }
        .page-title { font-size:20px; font-weight:700; color:#1a1a1a; margin-bottom:4px; }
        .page-sub { font-size:13px; color:#999; margin-bottom:28px; }

        table { width:100%; border-collapse:collapse; background:var(--white); border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow); }
        thead { background:var(--header-bg); color:white; }
        thead th { padding:14px 16px; font-size:12px; text-align:left; font-weight:600; letter-spacing:0.5px; text-transform:uppercase; }
        tbody tr { border-bottom:1px solid #f0f0f0; transition:background 0.15s; }
        tbody tr:hover { background:#fff8ee; }
        tbody td { padding:13px 16px; font-size:13px; color:#333; vertical-align:middle; }

        .badge-pending { background:#fff3e0; color:#e65100; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .badge-accepted { background:#e8f5e9; color:#2e7d32; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600; }

        .warranty-badge { background:#e3f2fd; color:#1565c0; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600; }

        .accept-btn {
            background:var(--accent); color:white; border:none;
            padding:7px 16px; border-radius:7px; font-size:12px;
            font-family:'Poppins',sans-serif; font-weight:600; cursor:pointer; transition:all 0.2s;
        }
        .accept-btn:hover { background:#b07408; transform:translateY(-1px); }

        .empty-state { text-align:center; padding:60px 20px; color:#bbb; font-size:15px; }

        .footer { background:var(--header-bg); text-align:center; padding:11px; font-size:12px; color:#ffffff30; }

        @media(max-width:700px){
            .main { padding:20px 16px 80px; }
            thead th, tbody td { padding:10px 10px; font-size:12px; }
        }
    </style>
</head>
<body>

<div class="header">
    <a href="#" class="logo">ANIF<span>CO</span></a>
    <a href="dashboard.php" class="back-btn">← Back</a>
</div>

<div class="main">
    <div class="page-title">🛠️ Requested Problems</div>
    <div class="page-sub">Customer service requests with warranty information</div>

    <?php if (mysqli_num_rows($requests) === 0): ?>
        <div class="empty-state">📭 No service requests yet.</div>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Item</th>
                <th>Warranty</th>
                <th>Status</th>
                <th>Requested At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 1; while ($row = mysqli_fetch_assoc($requests)): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><strong><?php echo htmlspecialchars($row['order_id']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><span class="warranty-badge">🛡️ <?php echo htmlspecialchars($row['warranty']); ?></span></td>
                <td>
                    <?php if ($row['request_status'] === 'Accepted'): ?>
                        <span class="badge-accepted">✅ Accepted</span>
                    <?php else: ?>
                        <span class="badge-pending">⏳ Pending</span>
                    <?php endif; ?>
                </td>
                <td><?php echo date('d M Y, h:i A', strtotime($row['requested_at'])); ?></td>
                <td>
                    <?php if ($row['request_status'] !== 'Accepted'): ?>
                    <form method="POST">
                        <input type="hidden" name="accept_id" value="<?php echo $row['id']; ?>">
                        <button class="accept-btn" type="submit">✔ Accept</button>
                    </form>
                    <?php else: ?>
                        <span style="color:#bbb;font-size:12px;">Done</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<div class="footer">© 2026 Anifco. All rights reserved.</div>
</body>
</html>