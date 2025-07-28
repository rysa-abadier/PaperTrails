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
        echo '<script>alert("Record delete successful!");</script>';
        unset($_SESSION['delete']);
    }

    function shortListBudget($conn) {
        $sql = "SELECT `ID`, `Update_Date`, `Budget_Expense`, `Amount`, `Total_Budget`, `Frequency_ID` FROM Budget WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);

        budgetHeaders(false);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $payDate = $row["Frequency_ID"];
                $date = date_create($row["Update_Date"]);

                echo '<tr point><td>' . $row["Budget_Expense"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>P ' . $row["Total_Budget"] . '</td>';

                switch ($payDate) {
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
            echo '<tr><td colspan="4">0 results</td></tr>';
        }
        echo '</table>';
    }

    function listBudget($conn) {
        budgetHeaders(true);
        
        $sql = "SELECT `ID`, `Update_Date`, `Budget_Expense`, `Amount`, `Total_Budget`, `ExpenseType_ID`, `Source_ID`, `Frequency_ID` FROM Budget WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $expenseType = getExpenseType($conn, $row["ExpenseType_ID"]);
                $assetSource = getAssetSource($conn, $row["Source_ID"]);
                $frequency = getFrequency($conn, $row["Frequency_ID"]);
                $payDate = $row["Frequency_ID"];
                $date = date_create($row["Update_Date"]);

                echo '<tr point><td>' . $row["Budget_Expense"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>P ' . $row["Total_Budget"] . '</td>';
                echo '<td>' . $expenseType . '</td>';
                echo '<td>' . $assetSource . '</td>';
                echo '<td>' . date_format(calculatePayDate($payDate, $date),"m-d-Y") . '</td>';
                echo '<td>' . $frequency . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="4">0 results</td></tr>';
        }
        echo '</table>';
    }

    function addBudget($conn) {
        echo '<form action="insert.php" method="POST">';
            echo '<tr><td style="width: 17.90%; padding-left: 5%;"><input type="text" name="budgetExpense" placeholder="Budget" required /></td>';
            echo '<td style="width: 12.775%;">P <input type="text" name="saved" placeholder="Saved" style="width: 89%" /></td>';
            echo '<td style="width: 12.9%;">P <input type="text" name="total" placeholder="Total Budget" style="width: 89%;" required /></td>';

            echo '<td style="width: 12.85%;"><select name="type" required>';
            $ctr = 1;
            foreach (expenseTypes($conn) as $expense) {
                echo '<option value="'. $ctr++ . '">' . $expense . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Kind of Expenses</option>';
            echo '</select></td>';

            echo '<td style="width: 12.9%;"><select name="source" required>';
            foreach (sourceFunds($conn, $_SESSION["id"]) as $id => $sourceFund) {
                echo '<option value="'. $id . '">' . $sourceFund . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Source Fund</option>';
            echo '</select></td>';

            echo '<td style="width: 12.8%;">' . date("m-d-Y") . '</td>';

            echo '<td style="width: 12.85%;"><select name="frequency" required>';
            $ctr = 1;
            foreach (frequencies($conn) as $frequency) {
                echo '<option value="'. $ctr++ . '">' . $frequency . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Frequency</option>';
            echo '</select></td>';

            echo '<td><button class="btn btn-outline-primary" type="submit" name="budget">Add</button></td></tr>';
        echo '</form>';
    }

    function editBudget($conn, $page, $editID) {
        budgetHeaders(true);

        $sql = "SELECT `ID`, `Update_Date`, `Budget_Expense`, `Amount`, `Total_Budget`, `ExpenseType_ID`, `Source_ID`, `Frequency_ID` FROM Budget WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $expenseType = getExpenseType($conn, $row["ExpenseType_ID"]);
                $assetSource = getAssetSource($conn, $row["Source_ID"]);
                $frequency = getFrequency($conn, $row["Frequency_ID"]);
                $payDate = $row["Frequency_ID"];
                $date = date_create($row["Update_Date"]);

                if ($page == "budget" && $row["ID"] == $editID) {
                    echo '<form method="POST" class="edit-row delete-row">';
                        echo '<input type="hidden" name="edit_ID" value="' . $row["ID"] . '">';
                        echo '<input type="hidden" name="date" value="' . $row["Update_Date"] . '">';
                        echo '<input type="hidden" name="initial" value="' . $row["Amount"] . '">';
                        
                        echo '<tr><td><input type="text" name="budgetExpense" value="' . $row["Budget_Expense"] . '" /></td>';
                        echo '<td>P <input style="width: 88.9%;" type="text" name="final" value="' . $row["Amount"] . '" /></td>';
                        echo '<td>P <input style="width: 88.9%;" type="text" name="total" value="' . $row["Total_Budget"] . '" /></td>';

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

                        echo '<td>' . date_format(calculatePayDate($payDate, $date),"m-d-Y") . '</td>';

                        echo '<td><select name="frequency">';
                        $ctr = 1;
                        foreach (frequencies($conn) as $frequent) {
                            if ($frequency == $frequent) echo '<option value="'. $ctr++ . '" selected>' . $frequent . '</option>';
                            else echo '<option value="'. $ctr++ . '">' . $frequent . '</option>';
                        }
                        echo '</select></td></tr>';
                    echo '</form>';
                } else {
                    echo '<tr data-id="' . $row["ID"] . '"><td>' . $row["Budget_Expense"] . '</td>';
                    echo '<td>P ' . $row["Amount"] . '</td>';
                    echo '<td>P ' . $row["Total_Budget"] . '</td>';
                    echo '<td>' . $expenseType . '</td>';
                    echo '<td>' . $assetSource . '</td>';
                    echo '<td>' . date_format(calculatePayDate($payDate, $date),"m-d-Y") . '</td>';
                    echo '<td>' . $frequency . '</td></tr>';
                }
            }
        } else {
            echo '<tr><td colspan="7">0 results</td></tr>';
        }
        echo '</table>';
    }
 
    function budgetHeaders($show) {
        echo '<table class="dashboard mx-auto"';
        if ($show) {
            echo ' style="font-size: 0.9rem; width: 90%;"';
        }
        echo '>';

        echo '<tr><th>Budget</th>';
        echo '<th>Saved</th>';
        echo '<th>Allotted Money </th>';

        if ($show) {
            echo '<th>Kind of Expense</th>';
            echo '<th>Source Fund</th>';
        }

        echo '<th>Payment Date</th>';

        if ($show) {
            echo '<th>Frequency</th>';
        }
        echo '</tr>';
    }

    function calculatePayDate($payDate, $date) {
        switch ($payDate) {
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
        return $date;
    }
?>