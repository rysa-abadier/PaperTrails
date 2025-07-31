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

    function shortListFunds($conn) {
        fundsHeaders(false);

        $sql = "SELECT `ID`, `Name`, `Amount` FROM Source_Fund WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr point><td>' . $row["Name"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="2">0 results</td></tr>';
        }
        echo '</table>';
    }

    function listFunds($conn) {
        fundsHeaders(true);

        $sql = "SELECT `ID`, `Name`, `Amount`, `Asset_ID` FROM Source_Fund WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $assetType = getAssetType($conn, $row["Asset_ID"]);

                echo '<tr point><td>' . $row["Name"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>' . $assetType . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="3">0 results</td></tr>';
        }
        echo '</table>';
    }

    function addFunds($conn) {
        echo '<form action="insert.php" method="POST">';
            echo '<tr><td style="width: 35%; padding-left: 4.90%;"><input type="text" name="name" placeholder="Source Fund" required /></td>';
            echo '<td style="width: 30%;">P <input style="width: 95.50%;" type="text" name="amount" placeholder="Amount" required /></td>';

            echo '<td style="width: 30%;"><select name="type" required>';
            $ctr = 1;
            foreach (assets($conn) as $asset) {
                echo '<option value="'. $ctr++ . '">' . $asset . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Kind of Asset</option>';
            echo '</select></td>';

            echo '<td><button class="btn btn-outline-primary" type="submit" name="sourceFunds">Add</button></td></tr>';
        echo '</form>';
    }

    function editFunds($conn, $page, $editID) {
        fundsHeaders(true);

        $sql = "SELECT `ID`, `Name`, `Amount`, `Asset_ID` FROM Source_Fund WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $assetType = getAssetType($conn, $row["Asset_ID"]);

                if ($page == "sourceFunds" && $row["ID"] == $editID) {
                    echo '<form method="POST" class="edit-row delete-row">';
                        echo '<input type="hidden" name="edit_ID" value="' . $row["ID"] . '">';

                        echo '<tr><td><input type="text" name="name" value="' . $row["Name"] . '" /></td>';
                        echo '<td>P ' . $row["Amount"] . '</td>';

                        echo '<td><select name="type" required>';
                        $ctr = 1;
                        foreach (assets($conn) as $asset) {
                            if ($assetType == $asset) echo '<option value="'. $ctr++ . '" selected>' . $asset . '</option>';
                            else echo '<option value="'. $ctr++ . '">' . $asset . '</option>';
                        }
                        echo '</select></td></tr>';
                    echo '</form>';
                } else {
                    echo '<tr data-id="' . $row["ID"] . '"><td>' . $row["Name"] . '</td>';
                    echo '<td>P ' . $row["Amount"] . '</td>';
                    echo '<td>' . $assetType . '</td></tr>';
                }
            }
        } else {
            echo '<tr><td colspan="3">0 results</td></tr>';
        }
        echo "</table>";
    }

    function fundsHeaders($show) {
        echo '<table class="dashboard mx-auto"';
        if ($show) {
            echo ' style="font-size: 0.9rem; width: 90%;"';
        }
        echo '>';

        echo '<tr><th>Source Fund</th>';
        echo '<th>Amount</th>';

        if ($show) {
            echo '<th>Kind of Asset</th></tr>';
        }
    }
?>