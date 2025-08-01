<?php
    include_once("connect.php");
    include_once("session.php");
    include_once("foreignkeys.php");

    if (isset($_REQUEST['dailyExpenses'])) {
        $date = date("Y-m-d");
        $amount = $_POST['amount'];
        $expense = "";
        $saving = true;
        $type = 0;
        $source = 0;
        $budget = 'No';
        
        if (is_numeric($_POST["expense"])) {
            $budget = getBudget($conn, $_POST['expense']);

            $keys = array_keys($budget);
            $values = array_values($budget);

            $expense = $keys[0] ?? null;
            $source = $values[0] ?? null;
            $type = $keys[1] ?? null;
            $saving = $values[1] ?? null;
            $budget = 'Yes';
        } else {
            $expense = $_POST["expense"];
            $type = validate($_POST['type'], $directPage);
            $source = validate($_POST['source'], $directPage);
        }

        $sql = "INSERT INTO `DailyExpense_Log`(`User_ID`, `Amount`, `Expense`, `Budget`, `Source_ID`, `ExpenseType_ID`, `Expense_Date`) VALUES ($id, $amount, '$expense', '$budget', $source, $type, '$date')";

        if (is_numeric($_POST["expense"])) {
            if ($saving) {
                budget($conn, $_POST["expense"], -$amount, $directPage);
            } else {
                budget($conn, $_POST["expense"], $amount, $directPage);
            }
        }

        insert($conn, $sql, $directPage);
        expense($conn, $source, $amount, $directPage);
    } else if (isset($_REQUEST['budget'])) {
        $budget = $_POST['budgetExpense'];
        $amount = $_POST['saved'] == null ? 0 : $_POST['saved'];
        $total = $_POST['total'];
        $type = validate($_POST['type'], $directPage);
        $source = validate($_POST['source'], $directPage);
        $frequency = validate($_POST['frequency'], $directPage);
        $date = date("Y-m-d");

        if ($amount == $total || $total == null) {
            $sql = "INSERT INTO `Budget`(`user_id`, `amount`, `total_budget`, `budget_expense`, `saving`, `expensetype_id`, `source_id`, `frequency_id`, `update_date`) VALUES ($id, $amount, $amount, '$budget', 'No', $type, $source, $frequency,'$date')";
            
            insert($conn, $sql, $directPage);
            expense($conn, $source, $amount, $directPage);
        } else {
            $sql = "INSERT INTO `Budget`(`user_id`, `amount`, `total_budget`, `budget_expense`, `expensetype_id`, `source_id`, `frequency_id`, `update_date`) VALUES ($id, $amount, $total, '$budget', $type, $source, $frequency,'$date')";
            insert($conn, $sql, $directPage);
        }

        if ($amount != 0.00) {
            $sql = "INSERT INTO `DailyExpense_Log`(`User_ID`, `Amount`, `Expense`, `Source_ID`, `ExpenseType_ID`, `Expense_Date`) VALUES ($id, $amount, '$budget', $source, $type, '$date')";
            insert($conn, $sql, $directPage);
            expense($conn, $source, $amount, $directPage);
        }
    } else if (isset($_REQUEST['sourceFunds'])) {
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $type = validate($_POST['type'], $directPage);
        $date = date("Y-m-d");

        $sql = "INSERT INTO `Source_Fund`(`user_id`, `name`, `amount`, `asset_id`) VALUES ($id, '$name', $amount, $type)";
        insert($conn, $sql, $directPage);

        if ($amount != 0.00) {
            $sql = "SELECT `ID` FROM Source_Fund WHERE `User_ID` = $id AND `Name` = '$name'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            $source = 0;
            
            if ($row > 0) $source = $row['ID'];
            
            $sql = "INSERT INTO `Income_Log`(`user_id`, `amount`, `income`, `source_id`, `income_date`) VALUES ($id, $amount, '$name', $source, '$date')";
            insert($conn, $sql, $directPage);
            header("Location: $directPage.php");
            exit();
        }
    } else if (isset($_REQUEST['wishlist'])) {
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $shop = $_POST['shop'];

        $sql = "INSERT INTO `Wishlist`(`user_id`, `item`, `amount`, `shop`) VALUES ($id, '$name', $amount, '$shop')";

        insert($conn, $sql, $directPage);
        header("Location: $directPage.php");
        exit();
    } else if (isset($_REQUEST['income'])) {
        $amount = $_POST['amount'];
        $name = $_POST['name'];
        $source = validate($_POST['source'], $directPage);
        $date = date("Y-m-d");

        $sql = "INSERT INTO `Income_Log`(`user_id`, `amount`, `income`, `source_id`, `income_date`) VALUES ($id, $amount, '$name', $source, '$date')";

        insert($conn, $sql, $directPage);
        expense($conn, $source, -$amount, $directPage);
    }

    function insert($conn, $sql, $header) {
        if (mysqli_query($conn, $sql)) {
            $_SESSION["insert"] = true;
        } else {
            error($conn, $sql, $header);
        }
    }

    function validate($selection, $header) {
        if ($selection != null) {
            return $selection;
        } else {
            $_SESSION["no_selection"] = true;
            header("Location: $header.php");
            exit();
        }
    }

    function expense($conn, $source, $amount, $header) {
        $sql = "UPDATE `Source_Fund` SET `Amount`= `Amount` - $amount WHERE ID = $source";

        if (mysqli_query($conn, $sql)) {
            header("Location: $header.php");
            exit();
        } else {
            error($conn, $sql, $header);
        }
    }

    function budget($conn, $source, $amount, $header) {
        $sql = "UPDATE `Budget` SET `Amount`= `Amount` - $amount WHERE ID = $source";

        if (mysqli_query($conn, $sql)) {
            $_SESSION["update"] = true;
        } else {
            error($conn, $sql, $header);
        }
    }

    function error($conn, $sql, $header) {
        echo '<script type="text/javascript">';
        echo 'alert("Error: "' . $sql . '"<br>"' . mysqli_error($conn) . '");';
        echo 'window.location.href = "' . $header . '.php";';
        echo '</script>';
    }

    mysqli_close($conn);
?>
