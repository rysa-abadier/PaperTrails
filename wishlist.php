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

    function shortListWishlist($conn) {
        wishlistHeaders(false);

        $sql = "SELECT `ID`, `Item`, `Amount` FROM Wishlist WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr point><td>' . $row["Item"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="2">0 results</td></tr>';
        }
        echo '</table>';
    }

    function listWishlist($conn) {
        wishlistHeaders(true);

        $sql = "SELECT `ID`, `Item`, `Amount`, `Shop` FROM Wishlist WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<tr point><td>' . $row["Item"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>' . $row["Shop"] . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="3">0 results</td></tr>';
        }
        echo '</table>';
    }

    function addWishlist() {
        echo '<form action="insert.php" method="POST">';
            echo '<tr><td style="width: 35%; padding-left: 4.95%;"><input type="text" name="name" placeholder="Item" required /></td>';
            echo '<td style="width: 30%;">P <input type="text" name="amount" placeholder="Amount" style="width: 95.85%" required /></td>';
            echo '<td style="width: 30%;"><input type="text" name="shop" placeholder="Shop" /></td>';
            echo '<td><button class="btn btn-outline-primary" type="submit" name="wishlist">Add</button></td></tr>';
        echo '</form>';
    }

    function editWishlist($conn, $page, $editID) {
        wishlistHeaders(true);

        $sql = "SELECT `ID`, `Item`, `Amount`, `Shop` FROM Wishlist WHERE `User_ID` = " . $_SESSION["id"];
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                if ($page == "wishlist" && $row["ID"] == $editID) {
                    echo '<form method="POST" class="edit-row delete-row">';
                        echo '<input type="hidden" name="edit_ID" value="' . $row["ID"] . '">';

                        echo '<td><input type="text" name="name" value="' . $row["Item"] . '" /></td>';
                        echo '<td>P <input style="width: 95.75%;" type="text" name="amount" value="' . $row["Amount"] . '" /></td>';
                        echo '<td><input type="text" name="shop" value="' . $row["Shop"] . '" /></td></tr>';
                    echo '</form>';
                } else {
                    echo '<tr data-id="' . $row["ID"] . '"><td>' . $row["Item"] . '</td>';
                    echo '<td>P ' . $row["Amount"] . '</td>';
                    echo '<td>' . $row["Shop"] . '</td></tr>';
                }
            }
        } else {
            echo '<tr><td colspan="3">0 results</td></tr>';
        }
        echo "</table>";
    }

    function wishlistHeaders($show) {
        echo '<table class="dashboard mx-auto"';
        if ($show) {
            echo ' style="font-size: 0.9rem; width: 90%;"';
        }
        echo '>';

        echo '<tr><th>Item</th>';
        echo '<th>Amount</th>';

        if ($show) {
            echo '<th>Shop</th></tr>';
        }
    }
?>