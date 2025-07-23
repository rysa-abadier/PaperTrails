<?php
    include_once('connect.php');
    
    if (!isset($_SESSION)) {
        session_start();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bio Data</title>

        <style>
            body{
                margin: 30px;
                font-family: Arial, sans-serif;
            }
            table, tr, td {
                padding: 5px;
                font-family: 'Courier New', Courier, monospace;
            }
        </style>
    </head>
    
    <body>
        <?php
            echo '<div>'; 
                include("logout.php");
            echo '</div>';

            echo '<div><h2>Daily Expenses Log</h2>'; 
                include("dailyexpenses.php");
            echo '</div>';

            echo '<div><h2>Budget</h2>'; 
                include("budget.php");
            echo '</div>';
        ?>
    </body>
</html>