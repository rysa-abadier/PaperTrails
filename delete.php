<?php
    include_once("connect.php");
    include_once("session.php");

    $id = $_GET['id'];
    $page = $_GET['page'];

    if ($page == "budget") {
        $sql = "SELECT `Budget_Expense` FROM Budget WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        log($conn, $sql, $id, $row["Budget_Expense"], `Budget`);
    } else if ($page == "sourceFunds") {
        $sql = "SELECT `Name` FROM `Source_Fund` WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        log($conn, $sql, $id, $row["Name"], `Source_Fund`);
    } else if ($page == "wishlist") {
        delete($conn, $id, `Wishlist`);
    }

    function log($conn, $sql, $id, $name, $table) {
        if (mysqli_query($conn, $sql)) {
            $sql = "INSERT INTO `Delete_Log`(`Deleted_ID`, `Deleted_Name`, `Table`) VALUES ($id,'$name','$table')";

            if (mysqli_query($conn, $sql)) {
                delete($conn, $id, $table);
            } else {
                error($conn, $sql);
            }
        } else {
            error($conn, $sql);
        }
    }

    function delete($conn, $id, $table) {
        $sql = "DELETE FROM $table WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION["delete"] = true;
            header("Location: dashboard.php");
            exit();
        } else {
            error($conn, $sql);
        }
    }

    function error($conn, $sql) {
        echo '<script type="text/javascript">';
        echo 'alert("Error: "' . $sql . '"<br>"' . mysqli_error($conn) . '");';
        echo 'window.location.href = "dashboard.php";';
        echo '</script>';
    }

    mysqli_close($conn);
?>