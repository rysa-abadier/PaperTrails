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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="styles.css" />
    </head>
    
    <body class="d-flex align-items-center">
        <div class="card mx-auto p-2" style="width: 20%; background: white; border-radius: 0.75rem;">
            <form action="register.php" method="POST" class="card-body p-2 mx-auto" style="width: 75%; background: white;">
                First Name: <input type="text" name="fname" /><br>
                Last Name: <input type="text" name="lname" /><br>
                Email: <input type="text" name="email" /><br>
                Password: <input type="password" name="password" /><br>

                <div class="d-grid gap-2 justify-content-md-end">
                    <button type="submit" name="register" class="btn btn-outline-primary mt-3">Register</button>
                </div>

                <div class="d-grid gap-2 justify-content-md-end mt-1">
                    <button id="login" name="login" class="btn btn-outline-secondary" style="border: none; font-style: italic;">Already have an account?</button>
                </div>
            </form>
        </div>
    </body>
</html>