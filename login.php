<?php
    include_once("connect.php");
    include_once("session.php");
    
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
                $_SESSION["success"] = true;
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