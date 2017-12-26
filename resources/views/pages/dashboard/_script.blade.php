@if (count($projects))

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        // Load google charts
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Draw the chart and set the chart values
        function drawChart() {
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
    </script>

@endif