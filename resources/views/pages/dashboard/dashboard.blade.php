@extends('layouts.app')

@section('content')

    <strong>Total Worked Hours This Month:</strong>
    <span class="label label-success bigger">{{$totalHours}}</span>

    <hr>

    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="pull-left">
                <strong>Project Wise Distribution</strong>
            </div>
            <div class="pull-right">
                <a href="{{route('refresh_data')}}" id="btn_refresh_data" class="btn btn-success btn-sm">
                    <i class="glyphicon glyphicon-refresh"></i> Refresh
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Total Hours</th>
                </tr>
                </thead>
                <tbody>

                @foreach($projects as $project)
                    <tr>
                        <td><a href="javascript:void(0)">{{$project['project_name']}}</a></td>
                        <td><span class="label label-success">{{$project['hours']}}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div id="piechart"></div>

        </div>

    </div>

@endsection

@push('styles')
    <style>
        #piechart {
            width: 600px;
            height: 400px;
        }
    </style>
@endpush

@push('scripts')

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
                        top: "3%",
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

@endpush
