<?php
session_start();
include("db.php");

// Status update
if (isset($_POST['update_status'])) {
    $id     = intval($_POST['report_id']);
    $status = mysqli_real_escape_string($conn, $_POST['new_status']);
    mysqli_query($conn, "UPDATE problem_reports SET status='$status' WHERE id=$id");
    header("Location: view_reports.php");
    exit();
}

$filter = $_GET['filter'] ?? 'All';
$where  = ($filter !== 'All') ? "WHERE status='" . mysqli_real_escape_string($conn, $filter) . "'" : "";
$reports = mysqli_query($conn, "SELECT * FROM problem_reports $where ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Problem Reports</title>
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

        .main { flex:1; padding:32px 40px 80px; width:100%; margin:0 auto; }

        .top-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:22px; flex-wrap:wrap; gap:12px; }
        .page-title { font-size:20px; font-weight:700; color:#1a1a1a; }
        .page-sub { font-size:13px; color:#999; margin-top:2px; }

        /* FILTER TABS */
        .filter-tabs { display:flex; gap:8px; flex-wrap:wrap; }
        .filter-tab {
            padding:7px 16px; border-radius:20px; font-size:12px; font-weight:600;
            text-decoration:none; font-family:'Poppins',sans-serif;
            border:1px solid #e0e0e0; color:#666; background:white;
            transition:all 0.2s;
        }
        .filter-tab:hover { border-color:var(--accent); color:var(--accent); }
        .filter-tab.active { background:var(--accent); color:white; border-color:var(--accent); }

        /* TABLE */
        .table-wrap { border-radius:var(--radius); box-shadow:var(--shadow); overflow:hidden; }
        table { width:100%; border-collapse:collapse; background:var(--white); table-layout:fixed; }
        thead { background:var(--header-bg); color:white; }
        thead th { padding:13px 16px; font-size:12px; text-align:left; font-weight:600; letter-spacing:0.5px; text-transform:uppercase; white-space:nowrap; }
        tbody tr { border-bottom:1px solid #f0f0f0; transition:background 0.15s; }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover { background:#fff8ee; }
        tbody td { padding:12px 16px; font-size:13px; color:#333; vertical-align:middle; word-wrap:break-word; }

        /* Column widths */
        table th:nth-child(1), table td:nth-child(1) { width:4%; }
        table th:nth-child(2), table td:nth-child(2) { width:12%; }
        table th:nth-child(3), table td:nth-child(3) { width:9%; }
        table th:nth-child(4), table td:nth-child(4) { width:10%; }
        table th:nth-child(5), table td:nth-child(5) { width:22%; }
        table th:nth-child(6), table td:nth-child(6) { width:11%; }
        table th:nth-child(7), table td:nth-child(7) { width:9%; }
        table th:nth-child(8), table td:nth-child(8) { width:9%; }
        table th:nth-child(9), table td:nth-child(9) { width:14%; }

        /* BADGES */
        .badge {
            padding:4px 10px; border-radius:20px; font-size:11px;
            font-weight:600; white-space:nowrap; display:inline-block;
        }
        .badge-pending    { background:#fff3e0; color:#e65100; }
        .badge-inprogress { background:#e3f2fd; color:#1565c0; }
        .badge-resolved   { background:#e8f5e9; color:#2e7d32; }
        .badge-closed     { background:#f5f5f5; color:#757575; }

        /* TYPE BADGE */
        .type-badge {
            background:#f5f5f5; color:#555; padding:3px 10px;
            border-radius:6px; font-size:12px; font-weight:500;
            display:inline-block; word-break:break-word;
        }

        /* STATUS FORM */
        .status-form { display:flex; gap:6px; align-items:center; flex-wrap:wrap; }
        .status-select {
            padding:5px 8px; border:1px solid #ddd; border-radius:6px;
            font-size:12px; font-family:'Poppins',sans-serif; outline:none;
            background:white; cursor:pointer; flex:1; min-width:90px;
        }
        .status-select:focus { border-color:var(--accent); }
        .update-btn {
            background:var(--accent); color:white; border:none;
            padding:5px 12px; border-radius:6px; font-size:12px;
            font-family:'Poppins',sans-serif; font-weight:600;
            cursor:pointer; transition:background 0.2s; white-space:nowrap;
        }
        .update-btn:hover { background:#b07408; }

        .desc-cell { white-space:normal; line-height:1.5; }

        .empty-state { text-align:center; padding:60px 20px; color:#bbb; font-size:15px; background:white; border-radius:var(--radius); }

        .footer { background:var(--header-bg); text-align:center; padding:11px; font-size:12px; color:#ffffff30; }

        @media(max-width:900px){
            .main { padding:20px 16px 80px; }
            table { font-size:12px; }
        }
    </style>
</head>
<body>

<div class="header">
    <a href="#" class="logo">ANIF<span>CO</span></a>
    <a href="dashboard.php" class="back-btn">← Back</a>
</div>

<div class="main">
    <div class="top-bar">
        <div>
            <div class="page-title">🚨 Problem Reports</div>
            <div class="page-sub">Customer reported issues</div>
        </div>
        <div class="filter-tabs">
            <?php foreach (['All','Pending','In Progress','Resolved','Closed'] as $f): ?>
            <a href="?filter=<?php echo urlencode($f); ?>"
               class="filter-tab <?php echo $filter === $f ? 'active' : ''; ?>">
                <?php echo $f; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (mysqli_num_rows($reports) === 0): ?>
        <div class="empty-state">📭 No reports found.</div>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Order ID</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Contact</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1; while ($row = mysqli_fetch_assoc($reports)): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['customer_name']); ?></strong></td>
                    <td><?php echo $row['order_id'] ? htmlspecialchars($row['order_id']) : '<span style="color:#bbb">—</span>'; ?></td>
                    <td><span class="type-badge"><?php echo htmlspecialchars($row['problem_type']); ?></span></td>
                    <td class="desc-cell"><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact']); ?></td>
                    <td style="white-space:nowrap"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php
                        $s = $row['status'];
                        $cls = match($s) {
                            'In Progress' => 'badge-inprogress',
                            'Resolved'    => 'badge-resolved',
                            'Closed'      => 'badge-closed',
                            default       => 'badge-pending'
                        };
                        ?>
                        <span class="badge <?php echo $cls; ?>"><?php echo $s; ?></span>
                    </td>
                    <td>
                        <form method="POST" class="status-form">
                            <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                            <select name="new_status" class="status-select">
                                <option value="Pending"     <?php echo $s==='Pending'     ?'selected':''; ?>>Pending</option>
                                <option value="In Progress" <?php echo $s==='In Progress' ?'selected':''; ?>>In Progress</option>
                                <option value="Resolved"    <?php echo $s==='Resolved'    ?'selected':''; ?>>Resolved</option>
                                <option value="Closed"      <?php echo $s==='Closed'      ?'selected':''; ?>>Closed</option>
                            </select>
                            <button type="submit" name="update_status" class="update-btn">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<div class="footer">© 2026 Anifco. All rights reserved.</div>
</body>
</html>