<?php
    function getExpenseType($conn, $id) {
        $sql = "SELECT `Name` FROM Expenses WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row > 0) return $row["Name"];
        else return deleteLogs($conn, $id);
    }

    function getAssetSource($conn, $id) {
        $sql = "SELECT `Name` FROM Source_Fund WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row > 0) return $row["Name"];
        else return deleteLogs($conn, $id);
    }

    function getFrequency($conn, $id) {
        $sql = "SELECT `Name` FROM Frequency WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row > 0) return $row["Name"];
        else return deleteLogs($conn, $id);
    }

    function getAssetType($conn, $id) {
        $sql = "SELECT `Name` FROM Assets WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row > 0) return $row["Name"];
        else return deleteLogs($conn, $id);
    }

    function getBudget($conn, $id) {
        $sql = "SELECT `Budget_Expense`, `Source_ID`, `Saving`, `ExpenseType_ID` FROM Budget WHERE `ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row > 0) {
            $save = $row["Saving"] == "Yes" ? true : false;

            $budget = [$row["Budget_Expense"] => $row["Source_ID"], $row["ExpenseType_ID"] => $save];
            return $budget;
        } 
        else return deleteLogs($conn, $id);
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

    function budgets($conn, $id) {
        $budgets = [];

        $sql = "SELECT `ID`, `Budget_Expense` FROM Budget WHERE `User_ID` = $id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $budgets += [$row["ID"] => $row["Budget_Expense"]];
            }
        }

        return $budgets;
    }

    function deleteLogs($conn, $id) {
        $sql = "SELECT `Deleted_Name` FROM Delete_Log WHERE `Deleted_ID` = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return '[DELETED] ' . $row["Deleted_Name"];
    }
?>