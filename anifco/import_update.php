<?php
include("db.php");

// ONLY invoices table (clean system)
$sql = "SELECT * FROM invoices ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import / Orders List</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0f4c75;
            margin-bottom: 20px;
        }

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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #0f4c75;
            color: white;
            padding: 12px;
            text-align: left;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f1f1f1;
        }

        a {
            color: #0f4c75;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            color: white;
            display: inline-block;
        }

        .orderplaced { background: gray; }
        .processing { background: orange; }
        .shipped { background: blue; }
        .delivered { background: green; }
    </style>
</head>

<body>

<div class="container">

<a href="dashboard.php" class="back-btn">← Back</a>

<h2>Import / Orders List</h2>

<table>
<tr>
    <th>Order ID</th>
    <th>Name</th>
    <th>Item</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
    <td><?= $row['order_id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['item_name'] ?></td>

    <td>
        <span class="status <?= strtolower(str_replace(' ','',$row['status'])) ?>">
            <?= $row['status'] ?>
        </span>
    </td>

    <td>
        <a href="tracking.php?order_id=<?= $row['order_id'] ?>">Details</a>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>