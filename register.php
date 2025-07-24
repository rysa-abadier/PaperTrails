<?php
    session_start();

    include_once("connect.php");
    
    if (isset($_SESSION["invalid"])) {
        echo '<script>alert("Email not found! Try creating an account.");</script>';
        unset($_SESSION['invalid']);
    }

    if (isset($_REQUEST['register'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "SELECT * FROM Users WHERE Email='${email}';";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION["existing"] = true;
            header("Location: index.php");
            exit();
        } else {
            $sql = "INSERT INTO `Users`(`First_Name`, `Last_Name`,`Email`, `Password`) VALUES ('$fname','$lname','$email','$pass')";

            if (mysqli_query($conn, $sql)) {
                $_SESSION["success"] = true;
                header("Location: index.php");
                exit();
            } else {
                echo '<script type="text/javascript">alert("Error: "' . $sql . '"<br>"' . mysqli_error($conn) . '");</script>';
            }
        }
    } else if (isset($_REQUEST['login'])) {
        header("Location: index.php");
        exit();
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" href="styles.css">
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