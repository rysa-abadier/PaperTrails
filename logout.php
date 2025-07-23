<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    echo '<button onclick="logout()" name="logout" style="float: right;">Logout</button>';
    echo '<h3 style="float: right; margin: 15px 10px;">Welcome, ' . $_SESSION['name'] . '!</h3>';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body>
        <script type="text/javascript">
            function logout() {
                <?php
                    session_destroy();
                    echo 'alert("Logged out successfully! Thank you!");';
                    echo 'window.location.href = "index.php";';
                ?>
            }
        </script>
    </body>
</html>