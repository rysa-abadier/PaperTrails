<?php
    include_once('connect.php');
    include_once('session.php');

    if (isset($_SESSION["login"])) {
        echo '<script>alert("Login successful!");</script>';
        unset($_SESSION['login']);
    } else if (isset($_SESSION["insert"])) {
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

    // var_dump($_SESSION);
    
    $editID = 0;
    $page = "";
    if (isset($_SESSION["edit"]) && isset($_SESSION["edit_ID"])) {
        $editID = $_SESSION["edit_ID"];
        $page = $_SESSION["edit"];
    }

    $id = $_SESSION["id"];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

        <link rel="stylesheet" href="styles.css" />
        <script type="text/javascript" src="script.js"></script>
    </head>
    
    <body>
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="">PaperTrails</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Home</a></li>
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Trackers</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Daily Expenses</a></li>
                                <li><a class="dropdown-item" href="#">Budget</a></li>
                                <li><a class="dropdown-item" href="#">Wishlist</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Income</a></li>
                                <li><a class="dropdown-item" href="#">Source Funds</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="date.php">Calendar</a></li>
                    </ul>

                    <span class="navbar-text" style="color: #F6F6EE;">HELLO, <strong><?php echo $_SESSION['name']; ?></strong> !</span>
                    <ul class="navbar-nav navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <li class="nav-item dropdown"><a class="nav-link disable-hover" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0 0 0 0.5rem;"><i class="bi bi-person-circle fs-3" style="color: white;"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="card d-flex flex-row m-4 px-2 align-items-center mx-auto" style="width: 80%; border-radius: 1rem;">
            <div class="d-flex flex-row ms-3" style="width: 55%;">
                <?php
                    echo '<div class="d-inline p-2" style="width: 65%">';
                        include_once("budget.php");
                        partialBudgetList($conn, $id);
                    echo '</div>';

                    echo '<div class="d-inline p-2" style="width: 35%">'; 
                        include_once("sourcefunds.php");
                        partialFundsList($conn, $id);
                    echo '</div>';
                ?>
            </div>

            <div class="vr my-5 mx-3"></div>
            <div class="d-flex flex-row-reverse py-4" style="width: 40%;">
                <div class="d-inline" id="chart1" style="width: 20em; height: 18em;"></div>
                <div class="d-inline" id="chart2" style="width: 20em; height: 18em;"></div>
                    <script>
                        google.charts.load("current", { packages: ["corechart"] });
                        google.charts.setOnLoadCallback(drawCharts);

                        function drawCharts() {
                            const data1 = google.visualization.arrayToDataTable([
                                ['Task', 'Hours per Day'],
                                ['Work', 8],
                                ['Eat', 2],
                                ['TV', 4],
                                ['Gym', 2],
                                ['Sleep', 8]
                            ]);

                            const data2 = google.visualization.arrayToDataTable([
                                ['Category', 'Amount'],
                                ['Food', 300],
                                ['Rent', 700],
                                ['Utilities', 200],
                                ['Transport', 150]
                            ]);

                            const options1 = {
                                pieHole: 0.4,
                                colors: ['#748459', '#A3B18A', '#C7C4BA', '#588157', '#344E41'],
                                chartArea: {width: '100%', height: '90%', left: 0},
                                legend: 'none',
                                backgroundColor: 'transparent'
                            };

                            const options2 = {
                                pieHole: 0.4,
                                colors: ['#748459', '#A3B18A', '#C7C4BA', '#588157', '#344E41'],
                                chartArea: {width: '100%', height: '90%', left: 0},
                                legend: 'none',
                                backgroundColor: 'transparent'
                            };

                            const chart1 = new google.visualization.PieChart(document.getElementById('chart1'));
                            const chart2 = new google.visualization.PieChart(document.getElementById('chart2'));

                            chart1.draw(data1, options1);
                            chart2.draw(data2, options2);
                        }
                    </script> 
            </div>
        </div>

        <div class="d-flex m-4 px-2 align-items-center">
            <div class="d-flex flex-row justify-content-center">
                <?php
                    echo '<div class="p-2" style="width: 80%;">';
                        include_once("dailyexpenses.php");
                        dashboardDailyList($conn, $id);
                    echo '</div>';
                ?>
            </div>
        </div>
    </body>
</html>