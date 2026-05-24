<?php
include("db.php");

if(isset($_POST['submit'])){

    $type = $_POST['type'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $item = $_POST['item_name'];
    $qty = $_POST['quantity'];
    $price = $_POST['unit_price'];

    $discount = $_POST['discount'] ?? 0;
    $tax = $_POST['tax'] ?? 0;

    $invoice_date = $_POST['invoice_date'];
    $due_date = $_POST['due_date'] ?? NULL;

    $payment = $_POST['payment_method'];

    // ✅ নতুন: warranty
    $warranty = $_POST['warranty'] ?? '';

    // =========================
    // CALCULATIONS
    // =========================

    $qty      = (int)$qty;
    $price    = (float)$price;
    $discount = (float)$discount;
    $tax      = (float)$tax;

    $total = $qty * $price;
    $grand = $total - $discount + $tax;

    // ==============================
    // AUTO ORDER ID GENERATE
    // ==============================

    $order_id = "ORD-" . date("Ymd") . "-" . rand(1000,9999);

    $status = "Order Placed";

    // =========================
    // INSERT INTO DATABASE
    // =========================

    $sql = "INSERT INTO invoices 
    (
        order_id,
        type,
        name,
        address,
        contact,
        item_name,
        quantity,
        unit_price,
        total_price,
        discount,
        tax,
        grand_total,
        invoice_date,
        due_date,
        payment_method,
        status,
        warranty
    )
    VALUES 
    (
        '$order_id',
        '$type',
        '$name',
        '$address',
        '$contact',
        '$item',
        '$qty',
        '$price',
        '$total',
        '$discount',
        '$tax',
        '$grand',
        '$invoice_date',
        '$due_date',
        '$payment',
        '$status',
        '$warranty'
    )";

    $conn->query($sql);

    // =========================
    // TRACKING HISTORY INSERT
    // =========================

    $conn->query("INSERT INTO order_tracking 
    (order_id, status) 
    VALUES 
    ('$order_id', '$status')");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice Receipt</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 20px;
        }

        .invoice-box {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            color: #0f4c75;
        }

        .info {
            margin-top: 20px;
            line-height: 1.8;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th {
            background: #0f4c75;
            color: white;
            padding: 10px;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .total {
            text-align: right;
            margin-top: 20px;
        }

        .grand {
            font-size: 18px;
            font-weight: bold;
            color: #0f4c75;
        }

        .warranty-badge {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .no-warranty-badge {
            display: inline-block;
            background: #ffebee;
            color: #c62828;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .buttons {
            margin-top: 20px;
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
        }

        .back  { background: #ccc; }

        .print {
            background: #0f4c75;
            color: white;
        }

        .track-btn {
            background: green;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .track-btn:hover { background: darkgreen; }

    </style>

</head>

<body>

<div class="invoice-box">

    <h2>Invoice Receipt</h2>

    <div class="info">

        <p><b>Order ID:</b> <?php echo $order_id; ?></p>

        <p><b>Type:</b> <?php echo ucfirst($type); ?></p>

        <p><b>Name:</b> <?php echo $name; ?></p>

        <p><b>Address:</b> <?php echo $address; ?></p>

        <p><b>Contact:</b> <?php echo $contact; ?></p>

        <p><b>Invoice Date:</b> <?php echo $invoice_date; ?></p>

        <?php if($due_date){ ?>
        <p><b>Due Date:</b> <?php echo $due_date; ?></p>
        <?php } ?>

        <!-- ✅ Warranty দেখাবে -->
        <p>
            <b>Warranty:</b>
            <?php if(!empty($warranty) && $warranty !== 'No Warranty'): ?>
                <span class="warranty-badge">✅ <?php echo $warranty; ?></span>
            <?php else: ?>
                <span class="no-warranty-badge">❌ No Warranty</span>
            <?php endif; ?>
        </p>

        <p>
            <b>Tracking:</b>
            <a href="tracking_details.php?order_id=<?php echo $order_id; ?>" class="track-btn">
                View Live Status →
            </a>
        </p>

    </div>

    <table>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
        <tr>
            <td><?php echo $item; ?></td>
            <td><?php echo $qty; ?></td>
            <td><?php echo $price; ?></td>
            <td><?php echo $total; ?></td>
        </tr>
    </table>

    <div class="total">
        <p>Discount: <?php echo $discount; ?></p>
        <p>Tax/VAT: <?php echo $tax; ?></p>
        <p class="grand">Grand Total: <?php echo $grand; ?></p>
        <p><b>Payment:</b> <?php echo $payment; ?></p>
    </div>

    <div class="buttons">
        <button class="btn back" onclick="location.href='invoice.php'">Back</button>
        <button class="btn print" onclick="window.print()">Print</button>
    </div>

</div>

</body>
</html>