<?php
include("db.php");

session_start();

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $company = $_POST['company'];
    $password = $_POST['password'];

    //  Default Admin Login
    if($email === "admin" && $company === "admin" && $password === "88888888"){
        $_SESSION['user_name'] = "Admin";
        header("Location: index01.php");
        exit();
    }

    //  Default Employee Login
    elseif($email === "employee" && $company === "admin" && $password === "999999999"){
        $_SESSION['user_name'] = "Employee";
        header("Location: dashboard.php");
        exit();
    }

    //  Normal Database Login
    else{

        $sql = "SELECT * FROM users WHERE email='$email' AND company_name='$company'";
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            $user = $result->fetch_assoc();

            if(password_verify($password, $user['password'])){

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];

                header("Location: dashboard01.php");
                exit();

            } else {
                $error = "Wrong password!";
            }

        } else {
            $error = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Anifco Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #0c83df, #102ef3);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 380px;
            margin: 80px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            color: #0f4c75;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #0f4c75;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background: #3282b8;
        }

        .links {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .links a {
            color: #0f4c75;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>

</head>

<body>

<div class="container">
    <h2>Login</h2>

    <!-- Error Message -->
    <?php
    if(isset($error)){
        echo "<p class='error'>$error</p>";
    }
    ?>

    <form method="POST">
        <input type="text" name="email" placeholder="Email / Username" required>
        <input type="text" name="company" placeholder="Company Name" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>

    <div class="links">
        <p><a href="#">Forgot Password?</a></p>
        <p>Not registered? <a href="register.php">Register</a></p>
    </div>
</div>

</body>
</html>