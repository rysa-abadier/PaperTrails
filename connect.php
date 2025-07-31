<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "papertrails_db";
    $port = "3306"; // "3307";

    $conn = mysqli_connect($servername, $username, $password, $db, $port);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    };
?>