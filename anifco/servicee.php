<?php
session_start();
include("db.php");

$order_data = null;
$searched = false;
$error = "";

if (isset($_GET['order_id']) && $_GET['order_id'] !== '') {
    $searched = true;
    $order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

    $query = "SELECT * FROM invoices WHERE order_id = '$order_id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $order_data = mysqli_fetch_assoc($result);
    } else {
        $error = "No order found with this ID.";
    }
}

if (isset($_POST['request_service'])) {
    $order_id   = mysqli_real_escape_string($conn, $_POST['order_id']);
    $cust_name  = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $item_name  = mysqli_real_escape_string($conn, $_POST['item_name']);
    $warranty   = mysqli_real_escape_string($conn, $_POST['warranty']);

    // Already requested check
    $check = mysqli_query($conn, "SELECT id FROM service_requests WHERE order_id='$order_id' AND request_status='Pending'");
    if (mysqli_num_rows($check) > 0) {
        $error = "You already have a pending request for this order.";
    } else {
        mysqli_query($conn, "INSERT INTO service_requests (order_id, customer_name, item_name, warranty)
            VALUES ('$order_id', '$cust_name', '$item_name', '$warranty')");
        $success = "✅ Service request submitted successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request for Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --header-bg: #2c1a0e;
            --accent: #c8860a;
            --accent2: #e6a820;
            --bg: #f4f4f4;
            --white: #ffffff;
            --radius: 12px;
            --shadow: 0 4px 20px rgba(0,0,0,0.08);
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
            font-size:13px; font-family:'Poppins',sans-serif; transition:background 0.2s;
        }
        .back-btn:hover { background:#ffffff25; }

        .main { flex:1; padding:40px 20px 80px; display:flex; flex-direction:column; align-items:center; }

        .page-title { font-size:22px; font-weight:700; color:#1a1a1a; margin-bottom:6px; text-align:center; }
        .page-sub { font-size:13px; color:#999; margin-bottom:30px; text-align:center; }

        /* Search */
        .search-box {
            background:var(--white); border-radius:var(--radius); padding:24px 28px;
            box-shadow:var(--shadow); width:100%; max-width:500px; margin-bottom:24px;
        }
        .search-box form { display:flex; gap:10px; }
        .search-box input {
            flex:1; padding:10px 14px; border:1px solid #ddd; border-radius:8px;
            font-size:14px; font-family:'Poppins',sans-serif; outline:none;
            transition:border-color 0.2s;
        }
        .search-box input:focus { border-color:var(--accent); }
        .search-box button {
            padding:10px 20px; background:var(--accent); color:white; border:none;
            border-radius:8px; font-size:14px; font-family:'Poppins',sans-serif;
            font-weight:600; cursor:pointer; transition:background 0.2s;
        }
        .search-box button:hover { background:#b07408; }

        /* Result Card */
        .result-card {
            background:var(--white); border-radius:var(--radius); padding:28px;
            box-shadow:var(--shadow); width:100%; max-width:500px;
            border-left:4px solid var(--accent);
        }
        .result-card h3 { font-size:16px; font-weight:700; margin-bottom:16px; color:#1a1a1a; }

        .info-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f0f0f0; font-size:13px; }
        .info-row:last-of-type { border-bottom:none; }
        .info-label { color:#999; font-weight:500; }
        .info-value { color:#1a1a1a; font-weight:600; text-align:right; }

        /* Warranty badge */
        .badge-yes {
            background:#e8f5e9; color:#2e7d32; padding:3px 10px;
            border-radius:20px; font-size:12px; font-weight:600;
        }
        .badge-no {
            background:#ffebee; color:#c62828; padding:3px 10px;
            border-radius:20px; font-size:12px; font-weight:600;
        }

        .service-btn {
            width:100%; padding:12px; margin-top:20px;
            background:var(--accent); color:white; border:none;
            border-radius:8px; font-size:15px; font-family:'Poppins',sans-serif;
            font-weight:600; cursor:pointer; transition:all 0.2s;
        }
        .service-btn:hover { background:#b07408; transform:translateY(-1px); }

        .alert-error { background:#ffebee; color:#c62828; padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:16px; max-width:500px; width:100%; }
        .alert-success { background:#e8f5e9; color:#2e7d32; padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:16px; max-width:500px; width:100%; }

        .footer { background:var(--header-bg); text-align:center; padding:11px; font-size:12px; color:#ffffff30; }
    </style>
</head>
<body>

<div class="header">
    <a href="#" class="logo">ANIF<span>CO</span></a>
    <a href="dashboard_user.php" class="back-btn">← Back</a>
</div>

<div class="main">
    <div class="page-title">🔧 Request for Service</div>
    <div class="page-sub">Enter your Order ID to check warranty & request service</div>

    <?php if ($error): ?>
        <div class="alert-error">⚠️ <?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <!-- Search -->
    <div class="search-box">
        <form method="GET">
            <input type="text" name="order_id" placeholder="Enter Order ID..." value="<?php echo htmlspecialchars($_GET['order_id'] ?? ''); ?>" required>
            <button type="submit">🔍 Search</button>
        </form>
    </div>

    <!-- Result -->
    <?php if ($order_data): ?>
    <div class="result-card">
        <h3>📋 Order Details</h3>

        <div class="info-row">
            <span class="info-label">Order ID</span>
            <span class="info-value"><?php echo htmlspecialchars($order_data['order_id']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Client Name</span>
            <span class="info-value"><?php echo htmlspecialchars($order_data['name']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Item</span>
            <span class="info-value"><?php echo htmlspecialchars($order_data['item_name']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Invoice Date</span>
            <span class="info-value"><?php echo htmlspecialchars($order_data['invoice_date']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Warranty</span>
            <span class="info-value">
                <?php if (!empty($order_data['warranty']) && $order_data['warranty'] !== 'No Warranty'): ?>
                    <span class="badge-yes">✅ <?php echo htmlspecialchars($order_data['warranty']); ?></span>
                <?php else: ?>
                    <span class="badge-no">❌ No Warranty</span>
                <?php endif; ?>
            </span>
        </div>

        <?php if (!empty($order_data['warranty']) && $order_data['warranty'] !== 'No Warranty'): ?>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_data['order_id']); ?>">
            <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($order_data['name']); ?>">
            <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($order_data['item_name']); ?>">
            <input type="hidden" name="warranty" value="<?php echo htmlspecialchars($order_data['warranty']); ?>">
            <button type="submit" name="request_service" class="service-btn">🔧 Request for Service</button>
        </form>
        <?php endif; ?>
    </div>
    <?php elseif ($searched && !$error): ?>
        <!-- handled by error above -->
    <?php endif; ?>
</div>

<div class="footer">© 2026 Anifco. All rights reserved.</div>
</body>
</html>