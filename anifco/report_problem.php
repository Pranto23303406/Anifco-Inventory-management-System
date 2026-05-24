<?php
session_start();
include("db.php");

$success = "";
$error = "";

if (isset($_POST['submit'])) {
    $order_id     = mysqli_real_escape_string($conn, $_POST['order_id']);
    $problem_type = mysqli_real_escape_string($conn, $_POST['problem_type']);
    $description  = mysqli_real_escape_string($conn, $_POST['description']);
    $contact      = mysqli_real_escape_string($conn, $_POST['contact']);
    $customer_name = mysqli_real_escape_string($conn, $_SESSION['user_name'] ?? 'Unknown');

    if (empty($problem_type) || empty($description) || empty($contact)) {
        $error = "Please fill in all required fields.";
    } else {
        $sql = "INSERT INTO problem_reports (order_id, problem_type, description, contact, customer_name)
                VALUES ('$order_id', '$problem_type', '$description', '$contact', '$customer_name')";
        if (mysqli_query($conn, $sql)) {
            $success = "✅ Your problem has been reported successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report a Problem</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --header-bg: #2c1a0e;
            --accent:    #c8860a;
            --accent2:   #e6a820;
            --bg:        #f4f4f4;
            --white:     #ffffff;
            --radius:    12px;
            --shadow:    0 4px 20px rgba(0,0,0,0.08);
        }
        body { font-family:'Poppins',sans-serif; background:var(--bg); min-height:100vh; display:flex; flex-direction:column; }

        /* HEADER */
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

        /* MAIN */
        .main {
            flex:1; display:flex; flex-direction:column;
            align-items:center; padding:40px 20px 80px;
        }

        .page-title { font-size:22px; font-weight:700; color:#1a1a1a; margin-bottom:4px; text-align:center; }
        .page-sub   { font-size:13px; color:#999; margin-bottom:30px; text-align:center; font-weight:300; }

        /* FORM CARD */
        .form-card {
            background:var(--white); border-radius:var(--radius);
            padding:32px 28px; box-shadow:var(--shadow);
            width:100%; max-width:480px;
        }

        label {
            display:block; font-size:13px; font-weight:500;
            color:#444; margin-bottom:6px; margin-top:18px;
        }
        label:first-of-type { margin-top:0; }

        .optional { font-size:11px; color:#bbb; font-weight:300; margin-left:4px; }

        input, select, textarea {
            width:100%; padding:10px 14px;
            border:1px solid #e0e0e0; border-radius:8px;
            font-size:13px; font-family:'Poppins',sans-serif;
            color:#333; outline:none; transition:border-color 0.2s;
            background:#fafafa;
        }
        input:focus, select:focus, textarea:focus {
            border-color:var(--accent); background:white;
        }
        textarea { resize:none; height:100px; }

        .submit-btn {
            width:100%; padding:12px; margin-top:24px;
            background:var(--accent); color:white; border:none;
            border-radius:8px; font-size:15px; font-family:'Poppins',sans-serif;
            font-weight:600; cursor:pointer; transition:all 0.2s;
        }
        .submit-btn:hover { background:#b07408; transform:translateY(-1px); }
        .submit-btn:active { transform:scale(0.99); }

        /* ALERTS */
        .alert-success {
            background:#e8f5e9; color:#2e7d32; border:1px solid #c8e6c9;
            padding:12px 16px; border-radius:8px; font-size:13px;
            margin-bottom:20px; width:100%; max-width:480px; text-align:center;
        }
        .alert-error {
            background:#ffebee; color:#c62828; border:1px solid #ffcdd2;
            padding:12px 16px; border-radius:8px; font-size:13px;
            margin-bottom:20px; width:100%; max-width:480px; text-align:center;
        }

        /* FOOTER */
        .footer {
            background:var(--header-bg); text-align:center;
            padding:11px; font-size:12px; color:#ffffff30; letter-spacing:0.5px;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="#" class="logo">ANIF<span>CO</span></a>
    <a href="dashboard_user.php" class="back-btn">← Back</a>
</div>

<div class="main">

    <div class="page-title">🚨 Report a Problem</div>
    <div class="page-sub">Let us know what went wrong and we'll look into it</div>

    <?php if ($success): ?>
        <div class="alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert-error">⚠️ <?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!$success): ?>
    <div class="form-card">
        <form method="POST">

            <label>Order ID <span class="optional">(optional)</span></label>
            <input type="text" name="order_id" placeholder="e.g. ORD-1023"
                   value="<?php echo htmlspecialchars($_POST['order_id'] ?? ''); ?>">

            <label>Problem Type <span style="color:red">*</span></label>
            <select name="problem_type" required>
                <option value="">— Select a problem type —</option>
                <option value="Delivery Issue"     <?php echo (($_POST['problem_type'] ?? '') === 'Delivery Issue')     ? 'selected' : ''; ?>>🚚 Delivery Issue</option>
                <option value="Product Issue"      <?php echo (($_POST['problem_type'] ?? '') === 'Product Issue')      ? 'selected' : ''; ?>>📦 Product Issue</option>
                <option value="Payment Issue"      <?php echo (($_POST['problem_type'] ?? '') === 'Payment Issue')      ? 'selected' : ''; ?>>💳 Payment Issue</option>
                <option value="Warranty Issue"     <?php echo (($_POST['problem_type'] ?? '') === 'Warranty Issue')     ? 'selected' : ''; ?>>🛡️ Warranty Issue</option>
                <option value="Service Issue"      <?php echo (($_POST['problem_type'] ?? '') === 'Service Issue')      ? 'selected' : ''; ?>>🔧 Service Issue</option>
                <option value="Other"              <?php echo (($_POST['problem_type'] ?? '') === 'Other')              ? 'selected' : ''; ?>>❓ Other</option>
            </select>

            <label>Problem Description <span style="color:red">*</span></label>
            <textarea name="description" placeholder="Describe your problem in detail..." required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

            <label>Contact Number <span style="color:red">*</span></label>
            <input type="text" name="contact" placeholder="e.g. 01XXXXXXXXX" required
                   value="<?php echo htmlspecialchars($_POST['contact'] ?? ''); ?>">

            <button type="submit" name="submit" class="submit-btn">🚨 Submit Report</button>

        </form>
    </div>
    <?php endif; ?>

</div>

<div class="footer">© 2026 Anifco. All rights reserved.</div>
</body>
</html>