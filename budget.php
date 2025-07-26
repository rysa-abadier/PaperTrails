<?php
    include_once("connect.php");
    include_once("session.php");
    include_once("foreignkeys.php");

    function partialBudgetList($conn, $id) {
        $sql = "SELECT `ID`, `Update_Date`, `Budget_Expense`, `Amount`, `Total_Budget`, `ExpenseType_ID`, `Source_ID`, `Frequency_ID` FROM Budget WHERE `User_ID` = $id";
        $result = mysqli_query($conn, $sql);

        echo '<a class="icon-link icon-link-hover" href="#">
            <h2>Budget</h2>
            <i class="bi bi-arrow-right display-icon" style="margin-bottom: 0.75rem;"></i>
        </a>';
        echo '<table class="dashboard">';
        echo '<tr><th>Budget</th>';
        echo '<th>Saved</th>';
        echo '<th>Alloted Money </th>';
        echo '<th>Payment Date</th></tr>';
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $frequency = $row["Frequency_ID"];
                $date = date_create($row["Update_Date"]);

                echo '<tr point><td>' . $row["Budget_Expense"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>P ' . $row["Total_Budget"] . '</td>';

                switch ($frequency) {
                    case 1:
                        date_add($date,date_interval_create_from_date_string("1"));
                        break;
                    case 2:
                        date_add($date,date_interval_create_from_date_string("1 week"));
                        break;
                    case 3:
                        date_add($date,date_interval_create_from_date_string("2 weeks"));
                        break;
                    case 4:
                        date_add($date,date_interval_create_from_date_string("1 month"));
                        break;
                    case 5:
                        date_add($date,date_interval_create_from_date_string("4 months"));
                        break;
                    case 6:
                        date_add($date,date_interval_create_from_date_string("1 year"));
                        break;
                }

                echo '<td>' . date_format($date,"m-d-Y") . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="5">0 results</td></tr>';
        }
        echo '</table>';
    }

    function fullBudgetList($conn, $id, $page, $editID) {
        $sql = "SELECT `ID`, `Update_Date`, `Budget_Expense`, `Amount`, `Total_Budget`, `ExpenseType_ID`, `Source_ID`, `Frequency_ID` FROM Budget WHERE `User_ID` = $id";
        $result = mysqli_query($conn, $sql);

        echo '<h2>Budget</h2>';
        echo '<table>';
        echo '<tr><th>Budget Name</th>';
        echo '<th>Saved</th>';
        echo '<th>Total Budget</th>';
        echo '<th>Kind of Expenses</th>';
        echo '<th>Source Fund</th>';
        echo '<th>Frequency</th></tr>';
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $expenseType = getExpenseType($conn, $row["ExpenseType_ID"]);
                $assetSource = getAssetSource($conn, $row["Source_ID"]);
                $frequency = getFrequency($conn, $row["Frequency_ID"]);

                if ($page == "budget" && $row["ID"] == $editID) {
                    echo '<form action="update.php" method="POST">';
                        echo '<input type="hidden" name="edit_ID" value="' . $row["ID"] . '">';
                        echo '<input type="hidden" name="date" value="' . $row["Update_Date"] . '">';
                        echo '<input type="hidden" name="initial" value="' . $row["Amount"] . '">';
                        echo '<tr><td><input type="text" name="budgetExpense" value="' . $row["Budget_Expense"] . '" /></td>';
                        echo '<td>P <input type="text" name="final" value="' . $row["Amount"] . '" /></td>';
                        echo '<td>P <input type="text" name="total" value="' . $row["Total_Budget"] . '" /></td>';

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

                        echo '<td><select name="frequency">';
                        $ctr = 1;
                        foreach (frequencies($conn) as $frequent) {
                            if ($frequency == $frequent) echo '<option value="'. $ctr++ . '" selected>' . $frequent . '</option>';
                            else echo '<option value="'. $ctr++ . '">' . $frequent . '</option>';
                        }
                        echo '</select></td>';

                        echo '<td><button type="submit" name="budget">Update</button></td>';
                        echo '<td><button name="cancel">Cancel</button></td></tr>';
                    echo '</form>';
                } else {
                    echo '<tr point><td>' . $row["Budget_Expense"] . '</td>';
                    echo '<td>P ' . $row["Amount"] . '</td>';
                    echo '<td>P ' . $row["Total_Budget"] . '</td>';
                    echo '<td>' . $expenseType . '</td>';
                    echo '<td>' . $assetSource . '</td>';
                    echo '<td>' . $frequency . '</td>';
                    echo '<td><a style="text-decoration: none; color: inherit;" href="edit.php?id=' . $row["ID"] . '&page=budget"><button name="budget">Edit</button></a></td>';
                    echo '<td><a style="text-decoration: none; color: inherit;" href="delete.php?id=' . $row["ID"] . '&page=budget"><button name="budget">Delete</button></a></td></tr>';
                }
            }
        } else {
            echo '<tr><td colspan="5">0 results</td></tr>';
        }
    }

    function addBudget($conn, $id) {
        echo '<form action="insert.php" method="POST">';
            echo '<tr><td><input type="text" name="budgetExpense" placeholder="Budget" required /></td>';
            echo '<td>P <input type="text" name="saved" placeholder="Saved" /></td>';
            echo '<td>P <input type="text" name="total" placeholder="Total Budget" required /></td>';

            echo '<td><select name="type" required>';
            $ctr = 1;
            foreach (expenseTypes($conn) as $expense) {
                echo '<option value="'. $ctr++ . '">' . $expense . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Kind of Expenses</option>';
            echo '</select></td>';

            echo '<td><select name="source" required>';
            foreach (sourceFunds($conn, $id) as $id => $sourceFund) {
                echo '<option value="'. $id . '">' . $sourceFund . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Source Fund</option>';
            echo '</select></td>';

            echo '<td><select name="frequency" required>';
            $ctr = 1;
            foreach (frequencies($conn) as $frequency) {
                echo '<option value="'. $ctr++ . '">' . $frequency . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Frequency</option>';
            echo '</select></td>';

            echo '<td><button type="submit" name="budget">Add</button></td></tr>';
        echo '</form>';
    }
    
?>