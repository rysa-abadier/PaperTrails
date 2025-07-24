<?php
    include_once("connect.php");
    include_once("session.php");

    $id = $_SESSION["id"];

    if (isset($_REQUEST['dailyExpense'])) {
        $logID = $_POST['edit_ID'];
        $amount = $_POST['amount'];
        $date = $_POST['date'];
        $expense = $_POST['expense'];
        $type = $_POST['type'];
        $source = $_POST['source'];

        $sql = "UPDATE `DailyExpense_Log` SET `User_ID`= $id, `Amount`= $amount, `Expense`= '$expense', `Source_ID`= $source, `ExpenseType_ID`= $type,`Expense_Date`= '$date' WHERE ID = $logID";

        update($conn, $sql, "dashboard.php");
    } else if (isset($_REQUEST['budget'])) {
        $logID = $_POST['edit_ID'];
        $date = $_POST['date'];
        $budget = $_POST['budgetExpense'];
        $amount = $_POST['amount'];
        $type = $_POST['type'];
        $source = $_POST['source'];
        $frequency = $_POST['frequency'];

        $sql = "UPDATE `Budget` SET `User_ID`= $id',`Amount`= $amount,`Budget_Expense`= '$budget',`ExpenseType_ID`= $type,`Source_ID`= $source,`Frequency_ID`= $frequency,`Update_Date`= '$date' WHERE ID = $logID";

        update($conn, $sql, "dashboard.php");
    } else if (isset($_REQUEST['sourceFunds'])) {
        $logID = $_POST['edit_ID'];
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $type = $_POST['type'];

        $sql = "UPDATE `Source_Fund` SET `User_ID`= $id, `Name`= '$name', `Amount`= $amount, `Asset_ID`= $type WHERE ID = $logID";

        update($conn, $sql, "dashboard.php");
    } else if (isset($_REQUEST['wishlist'])) {
        $logID = $_POST['edit_ID'];
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $shop = $_POST['shop'];

        $sql = "UPDATE `Wishlist` SET `User_ID`= $id, `Item`= '$name', `Amount`= $amount, `Shop`= '$shop' WHERE ID = $logID";

        update($conn, $sql, "dashboard.php");
    } else if (isset($_REQUEST['income'])) {
        $logID = $_POST['edit_ID'];
        $date = $_POST['date'];
        $amount = $_POST['amount'];
        $name = $_POST['name'];
        $source = $_POST['source'];

        $sql = "UPDATE `Income_Log` SET `User_ID`= $id, `Amount`= $amount, `Income`= '$name', `Source_ID`= $source,`Income_Date`= '$date' WHERE ID = $logID";

        update($conn, $sql, "dashboard.php");
    } else if (isset($_REQUEST['cancel'])) {
        unset($_SESSION["edit"]);
        unset($_SESSION["edit_ID"]);
        header("Location: dashboard.php");
        exit();
    }

    function update($conn, $sql, $header) {
        if (mysqli_query($conn, $sql)) {
            $_SESSION["update"] = true;
            unset($_SESSION["edit"]);
            unset($_SESSION["edit_ID"]);
            header("Location: $header");
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Error: "' . $sql . '"<br>"' . mysqli_error($conn) . '");';
            echo 'window.location.href = "' . $header . '";';
            echo '</script>';
        }
    }
?>
