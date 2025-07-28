<?php
    include_once("connect.php");
    include_once("session.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Big Interactive Calendar</title>

        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

        <link rel="stylesheet" href="styles.css" />
        <script type="text/javascript" src="script.js"></script>

        <style>
            #calendar {
                max-width: 1000px;
                margin: auto;
            }
        </style>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="dashboard.php">PaperTrails</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="dashboard.php">Home</a></li>
                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Trackers</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="dailyPage.php">Daily Expenses</a></li>
                                <li><a class="dropdown-item" href="budgetPage.php">Budget</a></li>
                                <li><a class="dropdown-item" href="wishlistPage.php">Wishlist</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="incomePage.php">Income</a></li>
                                <li><a class="dropdown-item" href="fundsPage.php">Source Funds</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link active" href="#">Calendar</a></li>
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

        <h2>Big Interactive Calendar</h2>
        <div id="calendar"></div>

        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('calendar');

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',  // Other options: timeGridWeek, dayGridDay
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    selectable: true,
                    dateClick: function(info) {
                        alert('Clicked on: ' + info.dateStr);
                    }
                });

                calendar.render();
            });
        </script>

    </body>
</html>
