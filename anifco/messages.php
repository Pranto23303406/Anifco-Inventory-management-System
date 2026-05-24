<?php
session_start();
include("db.php");

// Employee বা Admin যেকেউ দেখতে পারবে
if(!isset($_SESSION['user_name'])){
    header("Location: login.php");
    exit();
}

// Get all users who have sent messages + unread count
$result = $conn->query("
    SELECT 
        u.id,
        u.full_name,
        u.email,
        u.company_name,
        COUNT(m.id) AS total_msg,
        SUM(CASE WHEN m.is_read = 0 AND m.sender_type = 'user' THEN 1 ELSE 0 END) AS unread
    FROM users u
    JOIN messages m ON u.id = m.user_id
    GROUP BY u.id
    ORDER BY unread DESC, total_msg DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages - Anifco</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #2c1a0e;
            padding: 0 30px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header .logo {
            font-size: 18px;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
        }

        .header .logo span { color: #e6a820; }

        .header .badge {
            font-size: 13px;
            background: #e6a820;
            color: #2c1a0e;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .main {
            max-width: 700px;
            width: 100%;
            margin: 30px auto;
            padding: 0 16px;
        }

        .page-title {
            font-size: 18px;
            font-weight: 700;
            color: #2c1a0e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e6a820;
        }

        .user-card {
            background: white;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            text-decoration: none;
            color: inherit;
            border: 1px solid #eee;
            transition: all 0.2s ease;
        }

        .user-card:hover {
            border-color: #c8860a;
            box-shadow: 0 4px 20px rgba(200,134,10,0.15);
            transform: translateY(-1px);
        }

        .avatar {
            width: 46px;
            height: 46px;
            background: #2c1a0e;
            color: #e6a820;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-info h3 {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 3px;
        }

        .user-info p {
            font-size: 12px;
            color: #888;
        }

        .msg-count {
            margin-left: auto;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .unread-badge {
            background: #e6a820;
            color: #2c1a0e;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .total-badge {
            font-size: 11px;
            color: #aaa;
        }

        .arrow {
            font-size: 18px;
            color: #ccc;
            margin-left: 8px;
        }

        .no-msg {
            text-align: center;
            color: #aaa;
            font-size: 14px;
            padding: 40px;
            background: white;
            border-radius: 12px;
        }

        .back-link {
            font-size: 13px;
            color: #888;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 16px;
        }

        .back-link:hover { color: #c8860a; }
    </style>
</head>
<body>

<div class="header">
    <div class="logo">ANIF<span>CO</span></div>
    <div class="badge"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
</div>

<div class="main">

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>

    <div class="page-title">📬 User Messages</div>

    <?php
    if($result->num_rows == 0){
        echo '<div class="no-msg">No messages from users yet.</div>';
    } else {
        while($row = $result->fetch_assoc()){
            $initial = strtoupper(substr($row['full_name'], 0, 1));
            $unread = $row['unread'];
            $total = $row['total_msg'];
            echo "
            <a href='view_message.php?user_id={$row['id']}' class='user-card'>
                <div class='avatar'>$initial</div>
                <div class='user-info'>
                    <h3>" . htmlspecialchars($row['full_name']) . "</h3>
                    <p>" . htmlspecialchars($row['company_name']) . " • " . htmlspecialchars($row['email']) . "</p>
                </div>
                <div class='msg-count'>
                    " . ($unread > 0 ? "<span class='unread-badge'>$unread new</span>" : "") . "
                    <span class='total-badge'>$total messages</span>
                </div>
                <span class='arrow'>›</span>
            </a>";
        }
    }
    ?>
</div>

</body>
</html>