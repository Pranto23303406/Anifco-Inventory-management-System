<!DOCTYPE html>
<html>
<head>
    <title>Invoice System</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #0f4c75, #3282b8);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.2);
            text-align: center;
            width: 350px;
            position: relative;
        }

        h2 {
            color: #0f4c75;
            margin-bottom: 30px;
        }

        /* BACK BUTTON */
        .back-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #ccc;
            color: black;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
        }

        .back-btn:hover {
            background: #999;
        }

        button {
            width: 100%;
            padding: 15px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            background: #0f4c75;
            color: white;
            transition: 0.3s;
        }

        button:hover {
            background: #3282b8;
            transform: scale(1.05);
        }
    </style>

</head>

<body>

<div class="container">

    <a href="dashboard.php" class="back-btn">← Back</a>

    <h2>Invoice System</h2>

    <button onclick="location.href='sell_invoice.php'">Sell Invoice</button>

    <button onclick="location.href='order_invoice.php'">Order Invoice</button>
</div>

</body>
</html>