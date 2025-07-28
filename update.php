<?php
    include_once("connect.php");
    include_once("session.php");

    if (isset($_REQUEST['dailyExpenses'])) {
        $logID = $_POST['edit_ID'];
        $initial = $_POST['initial'];
        $final = $_POST['final'];
        $date = formatDate($_POST['date']);
        $expense = $_POST['expense'];
        $type = $_POST['type'];
        $source = $_POST['source'];
        $amount = $final - $initial;

        $sql = "UPDATE `DailyExpense_Log` SET `User_ID`= $id, `Amount`= $final, `Expense`= '$expense', `Source_ID`= $source, `ExpenseType_ID`= $type,`Expense_Date`= '$date' WHERE ID = $logID";
        update($conn, $sql, $directPage);

        if ($amount != 0) {
            recalculate($conn, $source, $amount, $directPage);
        } else {
            header("Location: $directPage.php");
            exit();
        }
    } else if (isset($_REQUEST['budget'])) {
        $logID = $_POST['edit_ID'];
        $date = formatDate($_POST['date']);
        $budget = $_POST['budgetExpense'];
        $initial = $_POST['initial'];
        $final = $_POST['final'];
        $total = $_POST['total'];
        $type = $_POST['type'];
        $source = $_POST['source'];
        $frequency = $_POST['frequency'];
        $amount = $final - $initial;

        $sql = "UPDATE `Budget` SET `User_ID`= $id,`Amount`= $final, `Total_Budget`= $total, `Budget_Expense`= '$budget',`ExpenseType_ID`= $type,`Source_ID`= $source,`Frequency_ID`= $frequency,`Update_Date`= '$date' WHERE ID = $logID";
        update($conn, $sql, $directPage);

        if ($amount != 0) {
            $sql = "INSERT INTO `DailyExpense_Log`(`User_ID`, `Amount`, `Expense`, `Source_ID`, `ExpenseType_ID`, `Expense_Date`) VALUES ($id, $amount, '$budget', $source, $type, '$date')";
            if (mysqli_query($conn, $sql)) {
                $_SESSION["insert"] = true;
            } else {
                error($conn, $sql, $directPage);
            }

            $sql = "UPDATE `Source_Fund` SET `Amount`= `Amount` - $amount WHERE ID = $source";
            if (mysqli_query($conn, $sql)) {
                header("Location: $directPage.php");
                exit();
            } else {
                error($conn, $sql, $directPage);
            }
        } else {
            header("Location: $directPage.php");
            exit();
        }
    } else if (isset($_REQUEST['sourceFunds'])) {
        $logID = $_POST['edit_ID'];
        $name = $_POST['name'];
        $type = $_POST['type'];

        $sql = "UPDATE `Source_Fund` SET `User_ID`= $id, `Name`= '$name', `Asset_ID`= $type WHERE ID = $logID";
        update($conn, $sql, $directPage);

        $sql = "UPDATE `Income` SET `Income`= '$name' WHERE `Source_ID` = $logID AND ID = $id";
        update($conn, $sql, $directPage);
        header("Location: $directPage.php");
        exit();
    } else if (isset($_REQUEST['wishlist'])) {
        $logID = $_POST['edit_ID'];
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $shop = $_POST['shop'];

        $sql = "UPDATE `Wishlist` SET `User_ID`= $id, `Item`= '$name', `Amount`= $amount, `Shop`= '$shop' WHERE ID = $logID";

        update($conn, $sql, $directPage);
    } else if (isset($_REQUEST['income'])) {
        $logID = $_POST['edit_ID'];
        $date = formatDate($_POST['date']);
        $initial = $_POST['initial'];
        $final = $_POST['final'];
        $name = $_POST['name'];
        $source = $_POST['source'];
        $amount = $final - $initial;

        $sql = "UPDATE `Income_Log` SET `User_ID`= $id, `Amount`= $final, `Income`= '$name', `Source_ID`= $source,`Income_Date`= '$date' WHERE ID = $logID";
        update($conn, $sql, $directPage);

        if ($amount != 0) {
            recalculate($conn, $source, -$amount, $directPage);
        } else {
            header("Location: $directPage.php");
            exit();
        }
    } else if (isset($_REQUEST['cancel'])) {
        unset($_SESSION["edit"]);
        unset($_SESSION["edit_ID"]);
        header("Location: $directPage.php");
        exit();
    }

    function update($conn, $sql, $header) {
        if (mysqli_query($conn, $sql)) {
            $_SESSION["update"] = true;
            unset($_SESSION["edit"]);
            unset($_SESSION["edit_ID"]);
        } else {
            error($conn, $sql, $header);
        }
    }

    function recalculate($conn, $source, $amount, $header) {
        $sql = "UPDATE `Source_Fund` SET `Amount`= `Amount` - $amount WHERE ID = $source";

        if (mysqli_query($conn, $sql)) {
            header("Location: $header.php");
            exit();
        } else {
            error($conn, $sql, $header);
        }
    }

    function formatDate($date){
        list($month, $day, $year) = explode('-', $date);
        return "$year-$month-$day";
    }

    function error($conn, $sql, $header) {
        echo '<script type="text/javascript">';
        echo 'alert("Error: "' . $sql . '"<br>"' . mysqli_error($conn) . '");';
        echo 'window.location.href = "' . $header . '.php";';
        echo '</script>';
    }

    mysqli_close($conn);
?>
