<?php
    include_once("connect.php");
    include_once("session.php");
    include_once("foreignkeys.php");

    $editID = 0;
    $page = "";
    if (isset($_SESSION["edit"]) && isset($_SESSION["edit_ID"])) {
        $editID = $_SESSION["edit_ID"];
        $page = $_SESSION["edit"];
    }

    $id = $_SESSION["id"];

    $sql = "SELECT `ID`, DATE_FORMAT(Expense_Date, '%m-%d-%Y') AS `Expense_Date`, `Amount`, `Expense`, `ExpenseType_ID`, `Source_ID` FROM DailyExpense_Log WHERE `User_ID` =" . $_SESSION["id"];
    $result = mysqli_query($conn, $sql);

    echo '<h2>Daily Expenses Log</h2>';
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

            if ($page == "dailyExpenses" && $row["ID"] == $editID) {
                echo '<form action="update.php" method="POST">';
                    echo '<input type="hidden" name="edit_ID" value="' . $row["ID"] . '">';
                    echo '<input type="hidden" name="date" value="' . $row["Expense_Date"] . '">';
                    echo '<input type="hidden" name="initial" value="' . $row["Amount"] . '">';
                    echo '<tr><td>' . $row["Expense_Date"] . '</td>';
                    echo '<td>P <input type="text" name="final" value="' . $row["Amount"] . '" /></td>';
                    echo '<td><input type="text" name="expense" value="' . $row["Expense"] . '" /></td>';
                    
                    echo '<td><select name="type">';
                    $ctr = 1;
                    foreach (expenseTypes($conn) as $expense) {
                        if ($expenseType == $expense) echo '<option value="'. $ctr++ . '" selected>' . $expense . '</option>';
                        else echo '<option value="'. $ctr++ . '">' . $expense . '</option>';
                    }
                    echo '</select></td>';
                    
                    echo '<td><select name="source">';
                    foreach (sourceFunds($conn, $_SESSION["id"]) as $id => $sourceFund) {
                        if ($assetSource == $sourceFund) echo '<option value="'. $id . '" selected>' . $sourceFund . '</option>';
                        else echo '<option value="'. $id . '">' . $sourceFund . '</option>';
                    }
                    echo '</select></td>';

                    echo '<td><button type="submit" name="dailyExpense">Update</button></td>';
                    echo '<td><button name="cancel">Cancel</button></td></tr>';
                echo '</form>';
            } else {
                echo '<tr><td>' . $row["Expense_Date"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>' . $row["Expense"] . '</td>';
                echo '<td>' . $expenseType . '</td>';
                echo '<td>' . $assetSource . '</td>';
                echo '<td><a style="text-decoration: none; color: inherit;" href="edit.php?id=' . $row["ID"] . '&page=dailyExpenses"><button name="dailyExpense">Edit</button></a></td></tr>';
            }
        }
    } else {
        echo '<tr><td colspan="6">0 results</td></tr>';
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
    </head>
    
    <body>
        <form action="insert.php" method="POST">
            <?php
                echo '<tr><td>' . date("m-d-Y") . '</td>';
                echo '<td>P <input type="text" name="amount" placeholder="Amount" required /></td>';
                echo '<td><input type="text" name="expense" placeholder="Spefic Expense" /></td>';
                
                echo '<td><select name="type" required>';
                $ctr = 1;
                foreach (expenseTypes($conn) as $expense) {
                    echo '<option value="'. $ctr++ . '">' . $expense . '</option>';
                }
                echo '<option class="placeholder" value="0" selected disabled hidden>Kind of Expenses</option>';
                echo '</select></td>';
                
                echo '<td><select name="source" required>';
                foreach (sourceFunds($conn, $_SESSION["id"]) as $id => $sourceFund) {
                    echo '<option value="'. $id . '">' . $sourceFund . '</option>';
                }
                echo '<option class="placeholder" value="0" selected disabled hidden>Source Fund</option>';
                echo '</select></td>';

                echo '<td><button type="submit" name="dailyExpense">Add</button></td></tr>';
            ?>
        </form></table>
    </body>
</html>