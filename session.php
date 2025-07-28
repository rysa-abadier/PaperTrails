<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $editID = 0;
    $page = "";
    $directPage = "";

    if (isset($_SESSION["page"])) {
        $directPage = $_SESSION["page"];
    }

    if (isset($_SESSION["edit"])) {
        $page = $_SESSION["edit"];
    }

    if (isset($_SESSION["edit_ID"])) {
        $editID = $_SESSION["edit_ID"];
    }

    $id = $_SESSION["id"];
?>