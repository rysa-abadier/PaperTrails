<?php
    include_once("connect.php");
    include_once("session.php");

    $id = $_SESSION["id"];

    if (isset($_REQUEST['dailyExpense'])) {
        $date = date("Y-m-d");
        $amount = $_POST['amount'];
        $expense = $_POST['expense'];
        $type = validate($_POST['type']);
        $source = validate($_POST['source']);

        $sql = "INSERT INTO `DailyExpense_Log`(`User_ID`, `Amount`, `Expense`, `Source_ID`, `ExpenseType_ID`, `Expense_Date`) VALUES ($id, $amount, '$expense', $source, $type, '$date')";

        insert($conn, $sql, "dashboard.php");
    } else if (isset($_REQUEST['budget'])) {
        $budget = $_POST['budgetExpense'];
        $amount = $_POST['amount'];
        $type = validate($_POST['type']);
        $source = validate($_POST['source']);
        $frequency = validate($_POST['frequency']);
        $date = date("Y-m-d");

        $sql = "INSERT INTO `Budget`(`user_id`, `amount`, `budget_expense`, `expensetype_id`, `source_id`, `frequency_id`, `update_date`) VALUES ($id, $amount, '$budget', $type, $source, $frequency,'$date')";

        insert($conn, $sql, "dashboard.php");
    } else if (isset($_REQUEST['sourceFunds'])) {
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $type = validate($_POST['type']);

        $sql = "INSERT INTO `Source_Fund`(`user_id`, `name`, `amount`, `asset_id`) VALUES ($id, '$name', $amount, $type)";

        insert($conn, $sql, "dashboard.php");
    } else if (isset($_REQUEST['wishlist'])) {
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $shop = $_POST['shop'];

        $sql = "INSERT INTO `Wishlist`(`user_id`, `item`, `amount`, `shop`) VALUES ($id, '$name', $amount, '$shop')";

        insert($conn, $sql, "dashboard.php");
    } else if (isset($_REQUEST['income'])) {
        $amount = $_POST['amount'];
        $name = $_POST['name'];
        $source = validate($_POST['source']);
        $date = date("Y-m-d");

        $sql = "INSERT INTO `Income_Log`(`user_id`, `amount`, `income`, `source_id`, `income_date`) VALUES ($id, $amount, '$name', $source, '$date')";

        insert($conn, $sql, "dashboard.php");
    }

    function insert($conn, $sql, $header) {
        if (mysqli_query($conn, $sql)) {
            $_SESSION["insert"] = true;
            header("Location: $header");
            exit();
        } else {
            echo '<script type="text/javascript">';
            echo 'alert("Error: "' . $sql . '"<br>"' . mysqli_error($conn) . '");';
            echo 'window.location.href = "' . $header . '";';
            echo '</script>';
        }
    }

    function validate($selection) {
        if ($selection != 0) {
            return $selection;
        } else {
            $_SESSION["no_selection"] = true;
            header("Location: dashboard.php");
        }
    }
?>
