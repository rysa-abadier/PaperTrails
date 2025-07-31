<?php
    include_once("connect.php");
    include_once("session.php");
    include_once("foreignkeys.php");

    if (isset($_SESSION["insert"])) {
        echo '<script>alert("New log recorded!");</script>';
        unset($_SESSION['insert']);
    } else if (isset($_SESSION["no_selection"])) {
        echo '<script>alert("You have not selected an option! Please try again.");</script>';
        unset($_SESSION['no_selection']);
    } else if (isset($_SESSION["update"])) {
        echo '<script>alert("Log record update successful!");</script>';
        unset($_SESSION['update']);
    } else if (isset($_SESSION["delete"])) {
        echo '<script>alert("You cannot delete a daily log record!");</script>';
        unset($_SESSION['delete']);
    }

    function listDaily($conn) {
        dailyHeaders();

        $sql = "SELECT `ID`, DATE_FORMAT(Expense_Date, '%m-%d-%Y') AS `Expense_Date`, `Amount`, `Expense`, `ExpenseType_ID`, `Source_ID` FROM DailyExpense_Log WHERE `User_ID` =" . $_SESSION["id"] . " ORDER BY `Expense_Date` DESC" ;
        $result = mysqli_query($conn, $sql);

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
            echo '<tr><td colspan="5">0 results</td></tr>';
        }
        echo '</table>';
    }

    function addDaily($conn) {
        echo '<form action="insert.php" method="POST">';
            echo '<tr><td style="width: 18.50%; padding-left: 4.90%;">' . date("m-d-Y") . '</td>';
            echo '<td style="width: 19.12%;">P <input style="width: 92%;" type="text" name="amount" placeholder="Amount" required /></td>';
            echo '<td style="width: 19.12%;"><input list="budgets" type="text" name="expense" placeholder="Spefic Expense" /></td>';
            
            echo '<datalist id="budgets" style="display: none;">';
                foreach (budgets($conn, $_SESSION["id"]) as $id => $budget) {
                    echo '<option value="'. $id . '">' . $budget . '</option>';
                }
            echo '</datalist>';

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

            echo '<td><button class="btn btn-outline-primary" type="submit" name="dailyExpenses">Add</button></td></tr>';
        echo '</form>';
    }

    function editDaily($conn, $page, $editID) {
        dailyHeaders();

        $sql = "SELECT `ID`, `Expense_Date`, `Amount`, `Expense`, `Budget`, `ExpenseType_ID`, `Source_ID` FROM DailyExpense_Log WHERE `User_ID` =" . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $expenseType = getExpenseType($conn, $row["ExpenseType_ID"]);
                $assetSource = getAssetSource($conn, $row["Source_ID"]);
                $date = date_create($row["Expense_Date"]);

                if ($page == "dailyExpenses" && $row["ID"] == $editID) {
                    echo '<tr><td>' . date_format($date,"m-d-Y") . '</td>';

                    echo '<form action="update.php" method="POST" class="edit-row">';
                        echo '<input type="hidden" name="edit_ID" value="' . $row["ID"] . '">';
                        echo '<input type="hidden" name="date" value="' . $row["Expense_Date"] . '">';
                        echo '<input type="hidden" name="budget" value="' . $row["Budget"] . '">';
                        echo '<input type="hidden" name="initial" value="' . $row["Amount"] . '">';
                        
                        echo '<td>P <input type="text" name="final" value="' . $row["Amount"] . '" style="width: 92%;" /></td>';
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
                        echo '</select></td></tr>';
                    echo '</form>';
                } else {
                    echo '<tr data-id="' . $row["ID"] . '"><td>' . date_format($date,"m-d-Y") . '</td>';
                    echo '<td>P ' . $row["Amount"] . '</td>';
                    echo '<td>' . $row["Expense"] . '</td>';
                    echo '<td>' . $expenseType . '</td>';
                    echo '<td>' . $assetSource . '</td></tr>';
                }
            }
        } else {
            echo '<tr><td colspan="5">0 results</td></tr>';
        }
        echo '</table>';
    }

    function dailyHeaders() {
        echo '<table class="dashboard mx-auto" style="font-size: 0.9rem; width: 90%;">';
        echo '<tr><th style="width: 15%;">Date</th>';
        echo '<th>Amount</th>';
        echo '<th>Specific Expense</th>';
        echo '<th>Kind of Expenses</th>';
        echo '<th>Source Fund</th></tr>';
    }
?>