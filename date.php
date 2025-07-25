<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Big Interactive Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        #calendar {
            max-width: 1000px;
            margin: auto;
        }
    </style>
</head>
<body>

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
