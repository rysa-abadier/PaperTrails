<?php
    include_once("connect.php");
    include_once("foreignkeys.php");
    
    if (!isset($_SESSION)) {
        session_start();
    }
    
    $id = $_SESSION["id"];

    $sql = "SELECT `Budget_Expense`, `Amount`, `ExpenseType_ID`, `Source_ID`, `Frequency_ID` FROM Budget_Log WHERE `User_ID` = $id";
    $result = mysqli_query($conn, $sql);

    echo '<table>';
    echo '<tr><th>Budget</th>';
    echo '<th>Amount</th>';
    echo '<th>Kind of Expenses</th>';
    echo '<th>Source Fund</th>';
    echo '<th>Frequency</th></tr>';
    
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $expenseType = getExpenseType($conn, $row["ExpenseType_ID"]);
            $assetSource = getAssetSource($conn, $row["Source_ID"]);
            $frequency = getFrequency($conn, $row["Source_ID"]);

            echo '<tr><td>' . $row["Budget_Expense"] . '</td>';
            echo '<td>P ' . $row["Amount"] . '</td>';
            echo '<td>' . $expenseType . '</td>';
            echo '<td>' . $assetSource . '</td>';
            echo '<td>' . $frequency . '</td></tr>';
        }
    } else {
        echo '<tr><td colspan="5">0 results</td></tr>';
    }
    echo '</table>';
?>