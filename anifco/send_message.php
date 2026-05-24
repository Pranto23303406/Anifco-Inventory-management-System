<?php
session_start();
include("db.php");

// User login check
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Message send
if(isset($_POST['send'])){
    $message = trim($_POST['message']);
    if($message !== ''){
        $conn->query("INSERT INTO messages (sender_type, user_id, message) 
                      VALUES ('user', '$user_id', '$message')");
    }
    header("Location: send_message.php");
    exit();
}

// Load conversation
$result = $conn->query("SELECT * FROM messages WHERE user_id='$user_id' ORDER BY sent_at ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages - Anifco</title>
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

        .header .user-info {
            font-size: 13px;
            color: #ffffff70;
        }

        .chat-wrapper {
            flex: 1;
            max-width: 680px;
            width: 100%;
            margin: 30px auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding: 0 16px;
        }

        .chat-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c1a0e;
            padding-bottom: 10px;
            border-bottom: 2px solid #e6a820;
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
            position: relative;
        }

        .bubble .time {
            font-size: 10px;
            margin-top: 4px;
            opacity: 0.6;
        }

        /* User bubble — right */
        .bubble.user {
            align-self: flex-end;
            background: #2c1a0e;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .bubble.user .time { text-align: right; }

        /* Employee bubble — left */
        .bubble.employee {
            align-self: flex-start;
            background: #fff6e6;
            color: #2c1a0e;
            border: 1px solid #e6d8b0;
            border-bottom-left-radius: 4px;
        }

        .bubble .sender-label {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 3px;
            opacity: 0.7;
        }

        .no-msg {
            text-align: center;
            color: #aaa;
            font-size: 14px;
            margin: auto;
        }

        /* Input area */
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

        .back-link {
            font-size: 13px;
            color: #888;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .back-link:hover { color: #c8860a; }
    </style>
</head>
<body>

<div class="header">
    <div class="logo">ANIF<span>CO</span></div>
    <div class="user-info">👤 <?php echo htmlspecialchars($user_name); ?></div>
</div>

<div class="chat-wrapper">

    <a href="dashboard01.php" class="back-link">← Back to Dashboard</a>

    <div class="chat-title">💬 Messages with Anifco Team</div>

    <div class="chat-box" id="chatBox">
        <?php
        if($result->num_rows == 0){
            echo '<p class="no-msg">No messages yet. Send your first message below!</p>';
        } else {
            while($row = $result->fetch_assoc()){
                $type = $row['sender_type'];
                $label = ($type === 'user') ? 'You' : 'Anifco Team';
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
        <textarea name="message" placeholder="Type your message..." required></textarea>
        <button name="send">Send ›</button>
    </form>

</div>

<script>
    // Auto scroll to bottom
    const box = document.getElementById('chatBox');
    box.scrollTop = box.scrollHeight;
</script>

</body>
</html>