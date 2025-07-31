<?php
    include_once('connect.php');
    include_once('session.php');
    include_once("wishlist.php");
    include_once("sourcefunds.php");
    include_once("budget.php");

    // var_dump($_SESSION);

    if (isset($_SESSION["page"])) {
        unset($_SESSION["page"]);
    }
    echo '<script>globalThis.currentPage = "wishlist";</script>';
    $_SESSION["page"] = "wishlistPage";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Wishlist Tracker</title>
        
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
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="dashboard.php">Home</a></li>
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Trackers</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="dailyPage.php">Daily Expenses</a></li>
                                <li><a class="dropdown-item" href="budgetPage.php">Budget</a></li>
                                <li><a class="dropdown-item" href="#">Wishlist</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="incomePage.php">Income</a></li>
                                <li><a class="dropdown-item" href="fundsPage.php">Source Funds</a></li>
                            </ul>
                        </li>
                        <!-- <li class="nav-item"><a class="nav-link" href="calendarPage.php">Calendar</a></li> -->
                    </ul>

                    <span class="navbar-text" style="color: #F6F6EE;">HELLO, <strong><?php echo $_SESSION['name']; ?></strong> !</span>
                    <ul class="navbar-nav navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <li class="nav-item dropdown"><a class="nav-link disable-hover" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0 0 0 0.5rem;"><i class="bi bi-person-circle fs-3" style="color: white;"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="card d-flex flex-row mt-5 px-2 py-4 align-items-center mx-auto" style="width: 80%; border-radius: 1rem;">
            <div class="d-flex flex-row ms-3 justify-content-center">
                <?php
                    echo '<div class="d-inline p-2" style="width: 30%">';
                        echo '<a class="icon-link icon-link-hover" href="fundsPage.php">
                            <h3>Funds</h3>
                            <i class="bi bi-arrow-right display-icon" style="margin-bottom: 0.75rem;"></i>
                        </a>';
                        shortListFunds($conn);
                    echo '</div>';

                    echo '<div class="d-inline p-2 me-5" style="width: 40%">'; 
                        echo '<a class="icon-link icon-link-hover" href="budgetPage.php">
                            <h3>Budget</h3>
                            <i class="bi bi-arrow-right display-icon" style="margin-bottom: 0.75rem;"></i>
                        </a>';
                        shortListBudget($conn);
                    echo '</div>';
                ?>

                <!-- <div class="vr mx-3 my-4"></div>

                <div class="d-flex flex-row-reverse py-2 me-3">
                    <div class="d-inline" id="chart" style="width: 20em; height: 15em;"></div>
                        <script>
                            google.charts.load("current", { packages: ["corechart"] });
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {
                                const data = google.visualization.arrayToDataTable([
                                    ['Task', 'Hours per Day'],
                                    ['Work', 8],
                                    ['Eat', 2],
                                    ['TV', 4],
                                    ['Gym', 2],
                                    ['Sleep', 8]
                                ]);

                                const options = {
                                    pieHole: 0.4,
                                    colors: ['#748459', '#A3B18A', '#C7C4BA', '#588157', '#344E41'],
                                    chartArea: {width: '100%', height: '90%', left: 0},
                                    legend: 'none',
                                    backgroundColor: 'transparent'
                                };

                                const chart = new google.visualization.PieChart(document.getElementById('chart'));

                                chart.draw(data, options);
                            }
                        </script> 
                </div> -->
            </div>
        </div>

        <div class="d-flex mt-4 px-2 align-items-center">
            <div class="d-flex flex-row justify-content-center">
                <?php
                    echo '<div class="p-2" style="width: 80%;">';
                        echo '<h2>Wishlist Log</h2>';

                        echo '<table class="dashboard-form mb-2">';
                            addWishlist();
                        echo '</table>';

                        editWishlist($conn,$page, $editID);
                    echo '</div>';
                ?>
            </div>
        </div>
    </body>
</html>