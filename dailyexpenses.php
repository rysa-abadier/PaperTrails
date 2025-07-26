<?php
    include_once("connect.php");
    include_once("session.php");
    include_once("foreignkeys.php");

    function dashboardDailyList($conn, $id) {
        $sql = "SELECT `ID`, DATE_FORMAT(Expense_Date, '%m-%d-%Y') AS `Expense_Date`, `Amount`, `Expense`, `ExpenseType_ID`, `Source_ID` FROM DailyExpense_Log WHERE `User_ID` =" . $_SESSION["id"] . " ORDER BY `Expense_Date` DESC" ;
        $result = mysqli_query($conn, $sql);

        echo '<a class="icon-link icon-link-hover" href="#" style="width: fit-content;">
            <h2>Daily Expense Log</h2>
            <i class="bi bi-arrow-right display-icon" style="margin-bottom: 0.75rem;"></i>
        </a>';

        echo '<table class="dashboard-form">';
            addDailyDashboard($conn, $id);
        echo '</table>';

        echo '<table class="dashboard mx-auto" style="font-size: 0.9rem; width: 90%;">';
        echo '<tr><th style="width: 15%;">Date</th>';
        echo '<th>Amount</th>';
        echo '<th>Specific Expense</th>';
        echo '<th>Kind of Expenses</th>';
        echo '<th>Source Fund</th></tr>';
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $expenseType = getExpenseType($conn, $row["ExpenseType_ID"]);
                $assetSource = getAssetSource($conn, $row["Source_ID"]);

                echo '<tr point><td>' . $row["Expense_Date"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>' . $row["Expense"] . '</td>';
                echo '<td>' . $expenseType . '</td>';
                echo '<td>' . $assetSource . '</td>';
            }
        } else {
            echo '<tr><td colspan="6">0 results</td></tr>';
        }
        echo '</table>';
    }

    function partialDailyList($conn, $id) {
        $sql = "SELECT `ID`, DATE_FORMAT(Expense_Date, '%m-%d-%Y') AS `Expense_Date`, `Amount`, `Expense`, `ExpenseType_ID`, `Source_ID` FROM DailyExpense_Log WHERE `User_ID` =" . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);

        echo '<h2>Daily Expenses Log</h2>';

        echo '<table>';
            addDaily($conn, $id);
        echo '</table>';

        echo '<table class="dashboard mx-auto" style="font-size: 0.9rem; width: 90%;">';
        echo '<tr><th style="width: 15%;">Date</th>';
        echo '<th>Amount</th>';
        echo '<th>Specific Expense</th>';
        echo '<th>Kind of Expenses</th>';
        echo '<th>Source Fund</th></tr>';
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $expenseType = getExpenseType($conn, $row["ExpenseType_ID"]);
                $assetSource = getAssetSource($conn, $row["Source_ID"]);

                echo '<tr point><td>' . $row["Expense_Date"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>' . $row["Expense"] . '</td>';
                echo '<td>' . $expenseType . '</td>';
                echo '<td>' . $assetSource . '</td>';
            }
        } else {
            echo '<tr><td colspan="6">0 results</td></tr>';
        }
        echo '</table>';
    }

    function fullDailyList($conn, $id, $page, $editID) {
        $sql = "SELECT `ID`, DATE_FORMAT(Expense_Date, '%m-%d-%Y') AS `Expense_Date`, `Amount`, `Expense`, `ExpenseType_ID`, `Source_ID` FROM DailyExpense_Log WHERE `User_ID` =" . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);

        echo '<h2>Daily Expenses Log</h2>';
        echo '<table class="dashboard-list">';
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
                    echo '<tr point><td>' . $row["Expense_Date"] . '</td>';
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
    }

    function addDailyDashboard($conn, $id) {
        echo '<form action="insert.php" method="POST">';
            echo '<tr><td style="width: 18.50%; padding-left: 4.325rem;">' . date("m-d-Y") . '</td>';
            echo '<td style="width: 19.12%;">P <input type="text" name="amount" placeholder="Amount" style="width: 90%;" required /></td>';
            echo '<td style="width: 19.12%;"><input type="text" name="expense" placeholder="Spefic Expense" /></td>';
            
            echo '<td style="width: 19.13%;"><select name="type" required>';
            $ctr = 1;
            foreach (expenseTypes($conn) as $expense) {
                echo '<option value="'. $ctr++ . '">' . $expense . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Kind of Expenses</option>';
            echo '</select></td>';
            
            echo '<td style="width: 19.13%;"><select name="source" required>';
            foreach (sourceFunds($conn, $_SESSION["id"]) as $id => $sourceFund) {
                echo '<option value="'. $id . '">' . $sourceFund . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Source Fund</option>';
            echo '</select></td>';

            echo '<td><button class="btn btn-outline-primary" type="submit" name="dailyExpense">Add</button></td></tr>';
        echo '</form>';
    }

    function addDaily($conn, $id) {
        echo '<form action="insert.php" method="POST">';
            echo '<tr><td>' . date("m-d-Y") . '</td>';
            echo '<td>P <input type="text" name="amount" placeholder="Amount" style="width: 90%;" required /></td>';
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

            echo '<td><button class="btn" type="submit" name="dailyExpense">Add</button></td></tr>';
        echo '</form>';
    }
?>