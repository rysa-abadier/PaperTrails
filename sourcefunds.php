<?php
    include_once("connect.php");
    include_once("session.php");
    include_once("foreignkeys.php");
    
    function partialFundsList($conn, $id) {
        $sql = "SELECT `ID`, `Name`, `Amount`, `Asset_ID` FROM Source_Fund WHERE `User_ID` = $id";
        $result = mysqli_query($conn, $sql);

        echo '<a class="icon-link icon-link-hover" href="#">
            <h2>Funds</h2>
            <i class="bi bi-arrow-right display-icon" style="margin-bottom: 0.75rem;"></i>
        </a>';
        echo '<table class="dashboard">';
        echo '<tr><th style="width: 55%;">Source Fund</th>';
        echo '<th>Amount</th></tr>';
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $assetType = getAssetType($conn, $row["Asset_ID"]);

                echo '<tr point><td>' . $row["Name"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="3">0 results</td></tr>';
        }
        echo '</table>';
    }

    function fullFundsList($conn, $id, $page, $editID) {
        $sql = "SELECT `ID`, `Name`, `Amount`, `Asset_ID` FROM Source_Fund WHERE `User_ID` = $id";
        $result = mysqli_query($conn, $sql);

        echo '<h2>Funds</h2>';
        echo '<table>';
        echo '<tr><th>Source Fund</th>';
        echo '<th>Amount</th>';
        echo '<th>Kind of Asset</th></tr>';
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $assetType = getAssetType($conn, $row["Asset_ID"]);

                if ($page == "sourceFunds" && $row["ID"] == $editID) {
                    echo '<form action="update.php" method="POST">';
                        echo '<input type="hidden" name="edit_ID" value="' . $row["ID"] . '">';
                        echo '<tr><td><input type="text" name="name" value="' . $row["Name"] . '" /></td>';
                        echo '<td>P ' . $row["Amount"] . '</td>';

                        echo '<td><select name="type" required>';
                        $ctr = 1;
                        foreach (assets($conn) as $asset) {
                            if ($assetType == $asset) echo '<option value="'. $ctr++ . '" selected>' . $asset . '</option>';
                            else echo '<option value="'. $ctr++ . '">' . $asset . '</option>';
                        }
                        echo '</select></td>';

                        echo '<td><button type="submit" name="sourceFunds">Update</button></td>';
                        echo '<td><button name="cancel">Cancel</button></td></tr>';
                    echo '</form>';
                } else {
                    echo '<tr point><td>' . $row["Name"] . '</td>';
                    echo '<td>P ' . $row["Amount"] . '</td>';
                    echo '<td>' . $assetType . '</td>';
                    echo '<td><a style="text-decoration: none; color: inherit;" href="edit.php?id=' . $row["ID"] . '&page=sourceFunds"><button name="sourceFunds">Edit</button></a></td>';
                    echo '<td><a style="text-decoration: none; color: inherit;" href="delete.php?id=' . $row["ID"] . '&page=sourceFunds"><button name="sourceFunds">Delete</button></a></td></tr>';
                }
            }
        } else {
            echo '<tr><td colspan="3">0 results</td></tr>';
        }
    }

    function addFunds($conn) {
        echo '<form action="insert.php" method="POST">';
            echo '<tr><td><input type="text" name="name" placeholder="Source Fund" required /></td>';
            echo '<td>P <input type="text" name="amount" placeholder="Amount" required /></td>';

            echo '<td><select name="type" required>';
            $ctr = 1;
            foreach (assets($conn) as $asset) {
                echo '<option value="'. $ctr++ . '">' . $asset . '</option>';
            }
            echo '<option class="placeholder" value="0" selected disabled hidden>Kind of Asset</option>';
            echo '</select></td>';

            echo '<td><button type="submit" name="sourceFunds">Add</button></td></tr>';
        echo '</form>';
    }
?>