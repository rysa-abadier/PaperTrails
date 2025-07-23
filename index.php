<?php
    include_once("connect.php");
    
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_REQUEST['login'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];

        $sql = "SELECT `ID`, `First_Name`, `Last_Name`, `Password` FROM Users WHERE Email='${email}';";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) > 0) {
            if (password_verify($pass, $row['Password'])) {
                $_SESSION['name'] = $row['First_Name'] . ' ' . $row['Last_Name'];
                $_SESSION['id'] = $row['ID'];

                echo '<script type="text/javascript">
                    alert("Login successful!");
                    window.location.href = "dashboard.php";
                </script>';
            } else {
                echo '<script type="text/javascript">
                    alert("Invalid password! Please try again.");
                    window.location.href = "index.php";
                </script>';
            }
        } else {
            echo '<script type="text/javascript">
                alert("Email not found! Try creating an account.");
                window.location.href = "register.php";
            </script>';
        }
    } else if (isset($_REQUEST['register'])) {
        echo '<script type="text/javascript"> window.location.href = "register.php"; </script>';
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
        <form action="index.php" method="POST">
            <label>Email:</label><br>
            <input type="text" name="email" id="email" /><br>

            <label>Password:</label><br>
            <input type="password" name="password" id="password" /><br>

            <button type="submit" name="login">Login</button>
            <button name="register">Register</button>
        </form>

    </body>
</html>