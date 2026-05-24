<?php
include("db.php");

$order_id = $_GET['order_id'];

$sql = "SELECT * FROM orders WHERE order_id='$order_id'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

$steps = [
    "Order Placed",
    "Confirmed",
    "Processing",
    "Packed",
    "Shipped",
    "Out for Delivery",
    "Delivered"
];

$current = array_search($data['status'], $steps);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Tracking</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0f4c75;
            margin-bottom: 20px;
        }

        /* BACK BUTTON */
        .back-btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 15px;
            background: #ccc;
            color: black;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .back-btn:hover {
            background: #999;
        }

        .track-box {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .step {
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            color: white;
        }

        .done {
            background: green;
        }

        .pending {
            background: #ccc;
            color: black;
        }

        .status {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: #0f4c75;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container">

<a href="import_update.php" class="back-btn">← Back</a>

<h2>Tracking Order: <?= $order_id ?></h2>

<div class="track-box">

<?php foreach($steps as $i => $step){ ?>

    <div class="step <?= ($i <= $current) ? 'done' : 'pending' ?>">
        <?= $step ?>
    </div>

<?php } ?>

</div>

<div class="status">
    Current Status: <?= $data['status'] ?>
</div>

</div>

</body>
</html>