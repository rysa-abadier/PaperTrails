<?php
    function getExpenseType($conn, $id) {
        $sql = "SELECT `Name` FROM Expenses WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return $row["Name"];
    }

    function getAssetSource($conn, $id) {
        $sql = "SELECT `Name` FROM Income_Log WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return $row["Name"];
    }

    function getFrequency($conn, $id) {
        $sql = "SELECT `Name` FROM Frequency WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return $row["Name"];
    }
?>