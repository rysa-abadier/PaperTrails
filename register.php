<?php
    include_once("connect.php");
    
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_REQUEST['register'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO `Users`(`First_Name`, `Last_Name`,`Email`, `Password`) VALUES ('$fname','$lname','$email','$pass')";

        echo '<script type="text/javascript">';
        if (mysqli_query($conn, $sql)) {
            echo 'alert("Create new record successful!");';
            echo 'window.location.href = "index.php";';
        } else {
            echo 'alert("Error: "' . $sql . '"<br>"' . mysqli_error($conn) . '");';
        }
        echo '</script>';
    } else if (isset($_REQUEST['login'])) {
        echo '<script type="text/javascript"> window.location.href = "index.php"; </script>';
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>

        <style>
            body{
                margin: 30px;
                font-family: Arial, sans-serif;
            }
            form {
                font-family: 'Courier New', Courier, monospace;
            }
            button{
                margin: 5px 5px 5px 0;
                padding: 8px 12px;
            }
            input {
                margin: 5px 0;
            }
        </style>
    </head>
    
    <body>
        <form action="register.php" method="POST">
            First Name: <input type="text" name="fname" /><br>
            Last Name: <input type="text" name="lname" /><br>
            Email: <input type="text" name="email" /><br>
            Password: <input type="password" name="password" /><br>

            <button type="submit" name="register">Register</button>
            <button name="login">Login</button>
        </form>
    </body>
</html>