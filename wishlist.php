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

    $sql = "SELECT `ID`, `Item`, `Amount`, `Shop` FROM Wishlist WHERE `User_ID` = $id";
    $result = mysqli_query($conn, $sql);

    echo '<table>';
    echo '<tr><th>Item</th>';
    echo '<th>Amount</th>';
    echo '<th>Shop</th></tr>';
    
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            if ($page == "wishlist" && $row["ID"] == $editID) {
                echo '<form action="update.php" method="POST">';
                    echo '<input type="hidden" name="edit_ID" value="' . $row["ID"] . '">';
                    echo '<td><input type="text" name="name" value="' . $row["Item"] . '" /></td>';
                    echo '<td>P <input type="text" name="amount" value="' . $row["Amount"] . '" /></td>';
                    echo '<td><input type="text" name="shop" value="' . $row["Shop"] . '" /></td>';

                    echo '<td><button type="submit" name="wishlist">Update</button></td>';
                    echo '<td><button name="cancel">Cancel</button></td></tr>';
                echo '</form>';
            } else {
                echo '<tr><td>' . $row["Item"] . '</td>';
                echo '<td>P ' . $row["Amount"] . '</td>';
                echo '<td>' . $row["Shop"] . '</td>';
                echo '<td><a style="text-decoration: none; color: inherit;" href="edit.php?id=' . $row["ID"] . '&page=wishlist"><button name="wishlist">Edit</button></a></td>';
                echo '<td><a style="text-decoration: none; color: inherit;" href="delete.php?id=' . $row["ID"] . '&page=wishlist"><button name="wishlist">Delete</button></a></td></tr>';
            }
        }
    } else {
        echo '<tr><td colspan="3">0 results</td></tr>';
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
                echo '<td><input type="text" name="name" placeholder="Item" required /></td>';
                echo '<td>P <input type="text" name="amount" placeholder="Amount" required /></td>';
                echo '<td><input type="text" name="shop" placeholder="Shop" /></td>';
                echo '<td><button type="submit" name="wishlist">Add</button></td></tr>';
                
            ?>
        </form></table>
    </body>
</html>