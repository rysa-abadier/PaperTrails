<?php
    include_once("connect.php");
    include_once("foreignkeys.php");

    if (!isset($_SESSION)) {
        session_start();
    }
    
    $id = $_SESSION["id"];

    $sql = "SELECT DATE_FORMAT(Expense_Date, '%m-%d-%Y') AS `Expense_Date`, `Amount`, `Expense`, `ExpenseType_ID`, `Source_ID` FROM DailyExpense_Log WHERE `User_ID` = $id";
    $result = mysqli_query($conn, $sql);

    echo '<table>';
    echo '<tr><th>Date</th>';
    echo '<th>Amount</th>';
    echo '<th>Specific Expense</th>';
    echo '<th>Kind of Expenses</th>';
    echo '<th>Source Fund</th></tr>';
    
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $expenseType = getExpenseType($conn, $row["ExpenseType_ID"]);
            $assetSource = getAssetSource($conn, $row["Source_ID"]);

            echo '<tr><td>' . $row["Expense_Date"] . '</td>';
            echo '<td>P ' . $row["Amount"] . '</td>';
            echo '<td>' . $row["Expense"] . '</td>';
            echo '<td>' . $expenseType . '</td>';
            echo '<td>' . $assetSource . '</td></tr>';
        }
    } else {
        echo '<tr><td colspan="5">0 results</td></tr>';
    }
    echo '</table>';
?>