@if (count($projects))

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        // Load google charts
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawPieChart);

        function drawPieChart() {
            var data = google.visualization.arrayToDataTable([
                ['Project', 'Hours'],
                <?php
                foreach ($projects as $project) {
                    echo "['$project[project_name]', $project[hours]],\n";
                }
                ?>
            ]);

            // Optional; add a title and set the width and height of the chart
            var options = {
                'title': 'Project Wise Hours Distribution',
                'width': '30%',
                'height': '30%',
                'legend': 'left',
                'chartArea': {
                    left: "0",
                    top: "0",
                    height: "100%",
                    width: "100%"
                }
            };

            // Display the chart inside the <div> element with id="piechart"
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }

        @if (user()->isAdmin() || user()->basecamp_api_user_id === '11816315')
        google.charts.setOnLoadCallback(function () {
            var data = google.visualization.arrayToDataTable([
                ['Person', 'Hours', {role: 'style'}],
                <?php
                foreach ($allUsersHours as $user) {
                    echo "['$user[name]', $user[hours], '$user[color]'],\n";
                }
                ?>
            ]);

            // Optional; add a title and set the width and height of the chart
            var options = {
                "legend": "none",
                "title": "All Users Hours",
                "vAxis": {title: "Hours"},
                "hAxis": {title: "User", "minValue": 1, "maxValue": 5},
                "width": "100%",
                "height": 500
            };

            // Display the chart inside the <div> element with id="piechart"
            var chart = new google.visualization.ColumnChart(document.getElementById('linechart'));
            chart.draw(data, options);
        });

        @endif

        function random_rgba() {
            var o = Math.round, r = Math.random, s = 255;
            return 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ',' + r().toFixed(1) + ')';
        }
    </script>

@endif