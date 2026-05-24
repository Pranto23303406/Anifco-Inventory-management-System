<?php include("db.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Invoice</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #0f4c75, #3282b8);
        }

        .container {
            width: 450px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.2);
            position: relative;
        }

        h2 {
            text-align: center;
            color: #0f4c75;
            margin-bottom: 20px;
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

        label {
            display: block;
            margin-top: 12px;
            font-size: 14px;
            color: #333;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        textarea {
            resize: none;
            height: 70px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #0f4c75;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #3282b8;
            transform: scale(1.03);
        }
    </style>

</head>

<body>

<div class="container">

<a href="invoice.php" class="back-btn">← Back</a>

<h2>Order Invoice</h2>

<form method="POST" action="invoice_result.php">

<input type="hidden" name="type" value="order">

<label>Company Name</label>
<input name="name" required>

<label>Address</label>
<textarea name="address" required></textarea>

<label>Contact</label>
<input name="contact" required>

<label>Item Name</label>
<input name="item_name" required>

<label>Quantity</label>
<input type="number" name="quantity" required>

<label>Unit Price</label>
<input type="number" name="unit_price" required>

<label>Discount (optional)</label>
<input type="number" name="discount">

<label>Tax / VAT (optional)</label>
<input type="number" name="tax">

<label>Invoice Date</label>
<input type="date" name="invoice_date" required>

<label>Payment Method</label>
<select name="payment_method" required>
    <option>Mobile Banking</option>
</select>

<button name="submit">Submit</button>

</form>

</div>

</body>
</html>