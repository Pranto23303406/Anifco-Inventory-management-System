<?php
include("db.php");

if(isset($_POST['update'])){

    $id = $_POST['id'];
    $stock = $_POST['stock'];

    $conn->query("UPDATE equipments SET stock='$stock' WHERE id='$id'");

    // 🔔 STOCK ALERT
    if($stock <= 2){
        $message = "Stock low for equipment ID: $id";
        $conn->query("INSERT INTO notifications (message) VALUES ('$message')");
    }

    header("Location: equipments.php");
}

$result = $conn->query("SELECT * FROM equipments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body{
            background: #f4f7fb;
        }

        /* TOPBAR */

        .topbar{
            width: 100%;
            background: #005b96;
            padding: 18px 40px;
            color: white;
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        /* MAIN CONTAINER */

        .container{
            width: 100%;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        /* FORM BOX */

        .form-box{
            width: 100%;
            max-width: 700px;
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            border-top: 6px solid #0077b6;
        }

        .form-box h2{
            text-align: center;
            color: #005b96;
            margin-bottom: 30px;
            font-size: 32px;
        }

        /* TABLE */

        table{
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table th{
            background: #0077b6;
            color: white;
            padding: 14px;
            font-size: 15px;
        }

        table td{
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            font-size: 14px;
        }

        table tr:hover{
            background: #f8fbff;
        }

        /* FORM */

        .update-form{
            margin-top: 20px;
        }

        .input-group{
            margin-bottom: 20px;
        }

        .input-group label{
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        select,
        input{
            width: 100%;
            padding: 14px;
            border: 1px solid #cfd9e0;
            border-radius: 10px;
            font-size: 15px;
            background: #fafafa;
            transition: 0.3s;
        }

        select:focus,
        input:focus{
            border-color: #0077b6;
            outline: none;
            background: white;
            box-shadow: 0 0 8px rgba(0,119,182,0.2);
        }

        /* BUTTON */

        button{
            width: 100%;
            padding: 15px;
            border: none;
            background: #0077b6;
            color: white;
            font-size: 17px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
        }

        button:hover{
            background: #005b96;
        }

        /* BACK BUTTON */

        .back-btn{
            position: fixed;
            bottom: 25px;
            right: 25px;
        }

        .back-btn a{
            text-decoration: none;
            background: #005b96;
            color: white;
            padding: 12px 22px;
            border-radius: 10px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            transition: 0.3s;
        }

        .back-btn a:hover{
            background: #003f6b;
        }

        /* RESPONSIVE */

        @media(max-width: 768px){

            .container{
                padding: 20px;
            }

            .form-box{
                padding: 20px;
                overflow-x: auto;
            }

            table{
                min-width: 600px;
            }

        }

    </style>

</head>
<body>

    <!-- TOPBAR -->

    <div class="topbar">
        ANIFCO Equipment Stock Update
    </div>

    <!-- MAIN SECTION -->

    <div class="container">

        <div class="form-box">

            <h2>Update Equipment Stock</h2>

            <!-- EQUIPMENT LIST -->

            <table>

                <tr>
                    <th>ID</th>
                    <th>Equipment Name</th>
                    <th>Company Name</th>
                    <th>Current Stock</th>
                </tr>

                <?php
                $show = $conn->query("SELECT * FROM equipments");

                while($item = $show->fetch_assoc()){
                ?>

                <tr>
                    <td><?php echo $item['id']; ?></td>

                    <td><?php echo $item['name']; ?></td>

                    <td><?php echo $item['company']; ?></td>

                    <td><?php echo $item['stock']; ?></td>
                </tr>

                <?php } ?>

            </table>

            <!-- UPDATE FORM -->

            <form method="POST" class="update-form">

                <div class="input-group">

                    <label>Select Equipment</label>

                    <select name="id">

                        <?php while($row = $result->fetch_assoc()){ ?>

                            <option value="<?php echo $row['id']; ?>">

                                <?php echo $row['name']; ?>
                                -
                                <?php echo $row['company']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

                <div class="input-group">

                    <label>New Stock Quantity</label>

                    <input type="number" name="stock" placeholder="Enter New Stock" required>

                </div>

                <button name="update">Update Stock</button>

            </form>

        </div>

    </div>

    <!-- BACK BUTTON -->

    <div class="back-btn">
        <a href="equipments.php">← Back</a>
    </div>

</body>
</html>