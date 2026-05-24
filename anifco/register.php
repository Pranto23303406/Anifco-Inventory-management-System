<?php
include("db.php");

$message = "";

if(isset($_POST['register'])){

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $company = $_POST['company'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // password match check
    if($password != $confirm_password){
        $message = "<p class='error'>Passwords do not match!</p>";
    } else {

        // check duplicate user
        $check = $conn->query("SELECT * FROM users WHERE email='$email' AND company_name='$company'");
        
        if($check->num_rows > 0){
            $message = "<p class='error'>User already exists!</p>";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (full_name, email, contact_number, company_name, address, password)
                    VALUES ('$full_name', '$email', '$contact', '$company', '$address', '$hashed_password')";

            if($conn->query($sql)){
                // redirect to login with message
                header("Location: login.php?success=1");
                exit();
            } else {
                $message = "<p class='error'>Error: " . $conn->error . "</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Anifco Register</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #0f4c75, #3282b8);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 60px auto;
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

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 5px;
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

        .success {
            color: green;
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            text-decoration: none;
            color: #0f4c75;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>

<div class="container">
    <h2>Register</h2>

    <?php echo $message; ?>

    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="contact" placeholder="Contact Number">
        <input type="text" name="company" placeholder="Company Name" required>
        <textarea name="address" placeholder="Address"></textarea>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button name="register">Register</button>
    </form>

    <div class="login-link">
        Already registered? <a href="login.php">Login</a>
    </div>
</div>

</body>
</html>