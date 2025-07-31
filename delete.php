<?php
    include_once("connect.php");
    include_once("session.php");

    $id = $_POST['edit_ID'];

    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

    if (isset($_REQUEST["budget"])) {
        $saved = $_POST["final"];
        $source = $_POST["source"];

        $sql = "UPDATE `Source_Fund` SET `Amount`= `Amount` + $saved WHERE ID = $source";

        if (mysqli_query($conn, $sql)) {
            $_SESSION["update"] = true;
        } else {
            error($conn, $sql, $header);
        }

        $sql = "SELECT `Budget_Expense` FROM Budget WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        deleteLog($conn, $sql, $id, $row["Budget_Expense"], 'Budget', $directPage);
    } else if (isset($_REQUEST["sourceFunds"])) {
        $sql = "SELECT `Name` FROM `Source_Fund` WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        deleteLog($conn, $sql, $id, $row["Name"], 'Source_Fund', $directPage);
    } else if (isset($_REQUEST["wishlist"])) {
        delete($conn, $id, 'Wishlist', $directPage);
    } else {
        $_SESSION["delete"] = true;
        header("Location: $directPage.php");
        exit();
    }

    function deleteLog($conn, $sql, $id, $name, $table, $header) {
        if (mysqli_query($conn, $sql)) {
            $sql = "INSERT INTO `Delete_Log`(`Deleted_ID`, `Deleted_Name`, `Table`) VALUES ($id,'$name', '$table')";

            if (mysqli_query($conn, $sql)) {
                delete($conn, $id, $table, $header);
            } else {
                error($conn, $sql,$header);
            }
        } else {
            error($conn, $sql, $header);
        }
    }

    function delete($conn, $id, $table, $header) {
        $sql = "DELETE FROM $table WHERE id = $id";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION["delete"] = true;

            mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");

            header("Location: $header.php");
            exit();
        } else {
            error($conn, $sql, $header);
        }
    }

    function error($conn, $sql, $header) {
        echo '<script type="text/javascript">';
        echo 'alert("Error: "' . $sql . '"<br>"' . mysqli_error($conn) . '");';
        echo 'window.location.href = "' . $header . '.php";';
        echo '</script>';
    }

    mysqli_close($conn);
?>