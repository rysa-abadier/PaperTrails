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
        echo '<script>alert("You cannot delete an income log record!");</script>';
        unset($_SESSION['delete']);
    }

    function listIncome($conn) {
        incomeHeaders();

        $sql = "SELECT `ID`, DATE_FORMAT(Income_Date, '%m-%d-%Y') AS `Income_Date`, `Amount`, `Income`, `Source_ID` FROM Income_Log WHERE `User_ID` = " . $_SESSION;
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $assetSource = getAssetSource($conn, $row["Source_ID"]);

                echo '<tr data-id="' . $row["ID"] . '"><td>' . $row["Income_Date"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>' . $row["Income"] . '</td>';
                echo '<td>' . $assetSource . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="4">0 results</td></tr>';
        }
        echo '</table>';
    }

    function addIncome($conn) {
        echo '<form action="insert.php" method="POST">';
            echo '<tr><td style="width: 27.5%; padding-left: 4.90%;">' . date("m-d-Y") . '</td>';
            echo '<td style="width: 22.5%;">P <input style="width: 94%;" type="text" name="amount" placeholder="Amount" required /></td>';
            echo '<td style="width: 22.5%;"><input type="text" name="name" placeholder="Specific Income" /></td>';
            
            echo '<td style="width: 22.5%;"><select name="source" required>';
            foreach (sourceFunds($conn, $_SESSION["id"]) as $id => $sourceFund) {
                echo '<option value="'. $id . '">' . $sourceFund . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Source Fund</option>';
            echo '</select></td>';

            echo '<td><button class="btn btn-outline-primary" type="submit" name="income">Add</button></td></tr>';
        echo '</form>';
    }

    function editIncome($conn, $page, $editID) {
        incomeHeaders();

        $sql = "SELECT `ID`, DATE_FORMAT(Income_Date, '%m-%d-%Y') AS `Income_Date`, `Amount`, `Income`, `Source_ID` FROM Income_Log WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $assetSource = getAssetSource($conn, $row["Source_ID"]);

                if ($page == "income" && $row["ID"] == $editID) {
                    echo '<form action="update.php" method="POST" class="edit-row">';
                        echo '<input type="hidden" name="edit_ID" value="' . $row["ID"] . '">';
                        echo '<input type="hidden" name="date" value="' . $row["Income_Date"] . '">';
                        echo '<input type="hidden" name="initial" value="' . $row["Amount"] . '">';
                        echo '<tr><td>' . $row["Income_Date"] . '</td>';
                        echo '<td>P <input type="text" name="final" value="' . $row["Amount"] . '" /></td>';
                        echo '<td><input type="text" name="name" value="' . $row["Income"] . '" /></td>';
                        
                        echo '<td><select name="source">';
                        foreach (sourceFunds($conn, $_SESSION["id"]) as $id => $sourceFund) {
                            if ($assetSource == $sourceFund) echo '<option value="'. $id . '" selected>' . $sourceFund . '</option>';
                            else echo '<option value="'. $id . '">' . $sourceFund . '</option>';
                        }
                        echo '</select></td></tr>';
                    echo '</form>';
                } else {
                    echo '<tr data-id="' . $row["ID"] . '"><td>' . $row["Income_Date"] . '</td>';
                    echo '<td>P ' . $row["Amount"] . '</td>';
                    echo '<td>' . $row["Income"] . '</td>';
                    echo '<td>' . $assetSource . '</td></tr>';
                }
            }
        } else {
            echo '<tr><td colspan="4">0 results</td></tr>';
        }
        echo '</table>';
    }

    function incomeHeaders() {
        echo '<table class="dashboard mx-auto" style="font-size: 0.9rem; width: 90%;">';
        echo '<tr><th>Date</th>';
        echo '<th>Amount</th>';
        echo '<th>Specific Income</th>';
        echo '<th>Source Fund</th></tr>';
    }
?>