@extends('layouts.app')

@section('title_area')
    <strong>Total Worked Hours This Month</strong>
    <span class="label label-success bigger">{{$totalHours}}</span>
    <strong>of possible</strong>
    <span class="label label-success bigger">{{getWorkingDaysCount(true) * 8}}</span>
@endsection

@section('content')

    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="pull-left" style="padding-top: 5px !important;">
                <strong>Project Wise Distribution</strong>
            </div>
            <div class="pull-right">
                <a href="{{route('refresh_data')}}" id="btn_refresh_data" class="btn btn-primary btn-sm">
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
                    <th style="text-align: right;">Total Hours</th>
                </tr>
                </thead>
                <tbody>

                @foreach($projects as $project)
                    <tr>
                        <td>
                            <a href="{{route('project_hours', $project['project_id'])}}">
                                {{$project['project_name']}}
                            </a>
                        </td>
                        <td style="text-align: right;">
                            <span class="label label-success big">{{$project['hours']}}</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <br><br>

            <div id="piechart"></div>
            <div id="linechart"></div>

        </div>

    </div>

@endsection

@push('styles')
    <style>
        #piechart {
            width: 600px;
            height: 400px;
            margin-left: 100px !important;
        }
    </style>
@endpush

@push('scripts')
    @include('pages.dashboard._script')
@endpush
