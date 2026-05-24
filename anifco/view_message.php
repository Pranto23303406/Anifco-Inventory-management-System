<?php
session_start();
include("db.php");

// Employee বা Admin যেকেউ দেখতে পারবে
if(!isset($_SESSION['user_name'])){
    header("Location: login.php");
    exit();
}

$user_id = $_GET['user_id'] ?? '';
if(!$user_id){ header("Location: messages.php"); exit(); }

// Get user info
$uRes = $conn->query("SELECT * FROM users WHERE id='$user_id'");
if($uRes->num_rows == 0){ header("Location: messages.php"); exit(); }
$user = $uRes->fetch_assoc();

// Mark all user messages as read
$conn->query("UPDATE messages SET is_read=1 WHERE user_id='$user_id' AND sender_type='user'");

// Employee/Admin reply
if(isset($_POST['send'])){
    $message = trim($_POST['message']);
    if($message !== ''){
        $conn->query("INSERT INTO messages (sender_type, user_id, message) 
                      VALUES ('employee', '$user_id', '$message')");
    }
    header("Location: view_message.php?user_id=$user_id");
    exit();
}

// Load full conversation
$result = $conn->query("SELECT * FROM messages WHERE user_id='$user_id' ORDER BY sent_at ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat - <?php echo htmlspecialchars($user['full_name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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

        .chat-wrapper {
            flex: 1;
            max-width: 700px;
            width: 100%;
            margin: 30px auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 0 16px 30px;
        }

        .back-link {
            font-size: 13px;
            color: #888;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .back-link:hover { color: #c8860a; }

        .user-bar {
            background: white;
            border-radius: 12px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .avatar {
            width: 42px;
            height: 42px;
            background: #2c1a0e;
            color: #e6a820;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-bar h3 {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .user-bar p {
            font-size: 12px;
            color: #888;
        }

        .chat-box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            min-height: 380px;
            max-height: 420px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }

        .bubble {
            max-width: 75%;
            padding: 10px 16px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.5;
        }

        .bubble .time {
            font-size: 10px;
            margin-top: 4px;
            opacity: 0.6;
        }

        .bubble .sender-label {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 3px;
            opacity: 0.7;
        }

        /* User bubble — left */
        .bubble.user {
            align-self: flex-start;
            background: #f0f0f0;
            color: #1a1a1a;
            border-bottom-left-radius: 4px;
        }

        /* Employee bubble — right */
        .bubble.employee {
            align-self: flex-end;
            background: #2c1a0e;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .bubble.employee .time { text-align: right; }

        .no-msg {
            text-align: center;
            color: #aaa;
            font-size: 14px;
            margin: auto;
        }

        .input-area {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .input-area textarea {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 14px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            resize: none;
            height: 52px;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-area textarea:focus {
            border-color: #c8860a;
        }

        .input-area button {
            background: #2c1a0e;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0 22px;
            height: 52px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .input-area button:hover {
            background: #c8860a;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="logo">ANIF<span>CO</span></div>
    <div class="badge"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
</div>

<div class="chat-wrapper">

    <a href="messages.php" class="back-link">← Back to Messages</a>

    <div class="user-bar">
        <div class="avatar"><?php echo strtoupper(substr($user['full_name'], 0, 1)); ?></div>
        <div>
            <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
            <p><?php echo htmlspecialchars($user['company_name']); ?> • <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
    </div>

    <div class="chat-box" id="chatBox">
        <?php
        if($result->num_rows == 0){
            echo '<p class="no-msg">No messages yet.</p>';
        } else {
            while($row = $result->fetch_assoc()){
                $type = $row['sender_type'];
                $label = ($type === 'user') ? htmlspecialchars($user['full_name']) : 'You (' . htmlspecialchars($_SESSION['user_name']) . ')';
                $time = date("d M, h:i A", strtotime($row['sent_at']));
                echo "
                <div class='bubble $type'>
                    <div class='sender-label'>$label</div>
                    " . htmlspecialchars($row['message']) . "
                    <div class='time'>$time</div>
                </div>";
            }
        }
        ?>
    </div>

    <form method="POST" class="input-area">
        <textarea name="message" placeholder="Reply to <?php echo htmlspecialchars($user['full_name']); ?>..." required></textarea>
        <button name="send">Reply ›</button>
    </form>

</div>

<script>
    const box = document.getElementById('chatBox');
    box.scrollTop = box.scrollHeight;
</script>

</body>
</html>