<?php
    include_once("session.php");

    $id = $_GET["id"];
    $page = $_GET["page"];

    if ($page == "dailyExpenses") {
        edit($page, $id);
        header("Location: dashboard.php");
        exit();
    } else if ($page == "budget") {
        edit($page, $id);
        header("Location: dashboard.php");
        exit();
    } else if ($page == "sourceFunds") {
        edit($page, $id);
        header("Location: dashboard.php");
        exit();
    } else if ($page == "wishlist") {
        edit($page, $id);
        header("Location: dashboard.php");
        exit();
    } else if ($page == "income") {
        edit($page, $id);
        header("Location: dashboard.php");
        exit();
    }

    function edit($page, $id) {
        $_SESSION["edit"] = $page;
        $_SESSION["edit_ID"] = $id;
    }
?>