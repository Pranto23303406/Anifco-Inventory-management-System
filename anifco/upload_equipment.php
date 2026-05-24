<?php
include("db.php");

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $company = $_POST['company'];
    $stock = $_POST['stock'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "uploads/".$image);

    $conn->query("INSERT INTO equipments (name, company, stock, image)
                  VALUES ('$name', '$company', '$stock', '$image')");

    header("Location: equipments.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Equipment</title>

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

        /* TOP BAR */

        .topbar{
            width: 100%;
            background: #005b96;
            padding: 18px 40px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        /* MAIN CONTAINER */

        .container{
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        /* FORM CARD */

        .form-box{
            width: 100%;
            max-width: 550px;
            background: white;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            border-top: 6px solid #0077b6;
            position: relative;
        }

        .form-box h2{
            text-align: center;
            margin-bottom: 30px;
            color: #005b96;
            font-size: 30px;
        }

        /* INPUT GROUP */

        .input-group{
            margin-bottom: 22px;
        }

        .input-group label{
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 15px;
            font-weight: bold;
        }

        .input-group input{
            width: 100%;
            padding: 14px;
            border: 1px solid #cfd9e0;
            border-radius: 10px;
            font-size: 15px;
            transition: 0.3s;
            background: #fafafa;
        }

        .input-group input:focus{
            border-color: #0077b6;
            outline: none;
            background: white;
            box-shadow: 0 0 8px rgba(0,119,182,0.2);
        }

        /* FILE INPUT */

        input[type="file"]{
            background: white;
            padding: 10px;
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
            letter-spacing: 0.5px;
        }

        button:hover{
            background: #005b96;
            transform: translateY(-2px);
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

        @media(max-width: 600px){

            .form-box{
                padding: 25px;
            }

            .form-box h2{
                font-size: 24px;
            }

        }

    </style>

</head>
<body>

    <!-- TOPBAR -->

    <div class="topbar">
        ANIFCO Equipment Management
    </div>

    <!-- FORM SECTION -->

    <div class="container">

        <div class="form-box">

            <h2>Upload Equipment</h2>

            <form method="POST" enctype="multipart/form-data">

                <div class="input-group">
                    <label>Equipment Name</label>
                    <input type="text" name="name" placeholder="Enter Equipment Name" required>
                </div>

                <div class="input-group">
                    <label>Imported From (Company)</label>
                    <input type="text" name="company" placeholder="Enter Company Name" required>
                </div>

                <div class="input-group">
                    <label>Stock Available</label>
                    <input type="number" name="stock" placeholder="Enter Stock Quantity" required>
                </div>

                <div class="input-group">
                    <label>Equipment Image</label>
                    <input type="file" name="image" required>
                </div>

                <button name="submit">Submit Equipment</button>

            </form>

        </div>

    </div>

    <!-- BACK BUTTON -->

    <div class="back-btn">
        <a href="equipments.php">← Back</a>
    </div>

</body>
</html>