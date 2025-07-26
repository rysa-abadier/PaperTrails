<?php
    session_start();

    include_once("connect.php");
    
    if (isset($_SESSION["invalid"])) {
        echo '<script>alert("Invalid password! Please try again.");</script>';
        unset($_SESSION['invalid']);
    } else if (isset($_SESSION["success"])) {
        echo '<script>alert("Create new account successful!");</script>';
        unset($_SESSION['invalid']);
    } else if (isset($_SESSION["existing"])) {
        echo '<script>alert("Email already exists! Try logging in.");</script>';
        unset($_SESSION['invalid']);
    }

    if (isset($_REQUEST['login'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];

        $sql = "SELECT `ID`, `First_Name`, `Last_Name`, `Password` FROM Users WHERE Email='${email}';";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) > 0) {
            if (password_verify($pass, $row['Password'])) {
                $_SESSION["id"] = (int) $row['ID'];
                $_SESSION["name"] = $row['First_Name'] . ' ' . $row['Last_Name'];
                $_SESSION["login"] = true;
                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION["invalid"] = true;
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION["invalid"] = true;
            header("Location: register.php");
            exit();
        }
    } else if (isset($_REQUEST['register'])) {
        header("Location: register.php");
        exit();
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="styles.css" />
    </head>
    
    <body class="d-flex align-items-center">
        <div class="card mx-auto p-2" style="width: 20%; background: white; border-radius: 0.75rem;">
            <form action="index.php" method="POST" class="card-body p-2 mx-auto" style="width: 75%; background: white;">
                <label>Email:</label><br>
                <input type="text" name="email" id="email" required /><br>

                <label>Password:</label><br>
                <input type="password" name="password" id="password" required /><br>

                <div class="d-grid gap-2 justify-content-md-end">
                    <button type="submit" name="login" class="btn btn-outline-primary mt-3">Login</button>
                </div>

                <div class="d-grid gap-2 justify-content-md-end mt-1">
                    <button id="register" name="register" class="btn btn-outline-secondary" style="border: none; font-style: italic;">Don't have an account?</button>
                </div>
            </form>
        </div>
        
    </body>
</html>