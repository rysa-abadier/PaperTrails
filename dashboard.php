<?php
    include_once('connect.php');
    include_once('session.php');

    if (isset($_SESSION["login"])) {
        echo '<script>alert("Login successful!");</script>';
        unset($_SESSION['login']);
    } else if (isset($_SESSION["insert"])) {
        echo '<script>alert("New log recorded!");</script>';
        unset($_SESSION['insert']);
    } else if (isset($_SESSION["no_selection"])) {
        echo '<script>alert("You have not selected an option! Please try again.");</script>';
        unset($_SESSION['no_selection']);
    } else if (isset($_SESSION["update"])) {
        echo '<script>alert("Log record update successful!");</script>';
        unset($_SESSION['update']);
    } else if (isset($_SESSION["delete"])) {
        echo '<script>alert("Record delete successful!");</script>';
        unset($_SESSION['delete']);
    }

    var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    
    <body>
        <button onclick="document.location='logout.php'" name="logout" style="float: right;">Logout</button>
        <h3 style="float: right; margin: 15px 10px;">Welcome, <?php echo $_SESSION['name']; ?>!</h1>
        <?php

            echo '<div>';
                include_once("dailyexpenses.php");
            echo '</div>';

            echo '<div>';
                include_once("budget.php");
            echo '</div>';

            echo '<div><h2>Funds</h2>'; 
                include_once("sourcefunds.php");
            echo '</div>';

            echo '<div><h2>Wishlist</h2>'; 
                include_once("wishlist.php");
            echo '</div>';

            echo '<div><h2>Income</h2>'; 
                include_once("income.php");
            echo '</div>';
        ?>
    </body>
</html>