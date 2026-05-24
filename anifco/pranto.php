<?php
// You can add dynamic stuff here later if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work in Progress</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 50px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 90%;
        }

        h1 {
            font-size: 40px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .loader {
            border: 6px solid rgba(255,255,255,0.3);
            border-top: 6px solid #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #fff;
            color: #333;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn:hover {
            background: #333;
            color: #fff;
        }

        footer {
            margin-top: 20px;
            font-size: 12px;
            opacity: 0.7;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="loader"></div>

    <h1>🚧 Work in Progress</h1>
    <p>We are working hard to bring something amazing. Stay tuned!</p>

    <a href="#" class="btn">Go Back</a>

    <footer>
        &copy; <?php echo date("Y"); ?> Your Company
    </footer>
</div>

</body>
</html>