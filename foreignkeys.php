<?php
    function getExpenseType($conn, $id) {
        $sql = "SELECT `Name` FROM Expenses WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return $row["Name"];
    }

    function getAssetSource($conn, $id) {
        $sql = "SELECT `Name` FROM Source_Fund WHERE `ID` = $id";
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

    function getAssetType($conn, $id) {
        $sql = "SELECT `Name` FROM Assets WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return $row["Name"];
    }

    function expenseTypes($conn) {
        $expenses = [];

        $sql = "SELECT `Name` FROM Expenses";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($expenses, $row["Name"]);
            }
        }

        return $expenses;
    }

    function sourceFunds($conn, $id) {
        $sourceFunds = [];

        $sql = "SELECT `ID`, `Name` FROM Source_Fund WHERE `User_ID` = $id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $sourceFunds += [$row["ID"] => $row["Name"]];
            }
        }

        return $sourceFunds;
    }

    function frequencies($conn) {
        $frequencies = [];

        $sql = "SELECT `Name` FROM Frequency";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($frequencies, $row["Name"]);
            }
        }

        return $frequencies;
    }

    function assets($conn) {
        $assets = [];

        $sql = "SELECT `Name` FROM Assets";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($assets, $row["Name"]);
            }
        }

        return $assets;
    }
?>