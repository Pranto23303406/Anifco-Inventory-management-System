<?php include("db.php"); ?>

<?php

if(isset($_POST['update'])){

    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $conn->query("INSERT INTO order_tracking(order_id,status)
    VALUES('$order_id','$status')");

    $conn->query("UPDATE invoices
    SET status='$status'
    WHERE order_id='$order_id'");

    echo "Status Updated";
}

?>

<form method="POST">

<input type="text" name="order_id" placeholder="Order ID">

<select name="status">

<option>Order Confirmed</option>
<option>Processing</option>
<option>Out for Delivery</option>
<option>Delivered</option>

</select>

<button name="update">Update</button>

</form>