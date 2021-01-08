@extends('layouts.app')

@section('title_area')
    <a data-toggle="modal" href="#modal-entry" class="btn btn-success btn-sm">
        <i class="glyphicon glyphicon-plus-sign"></i> Add New Entry
    </a>
@endsection

@section('content')

    <a href="{{route('timeentry')}}" class="btn btn-primary btn-sm">Back</a>
    <br><br>

    <div class="panel panel-info">
        <div class="panel-heading">
            <strong>Todo Details</strong>
        </div>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-condensed table-bordered">
            <tbody>
            {!! tr($todo->dated, 'Dated', true, 'N/A') !!}
            {!! tr($todo->project->project_name, 'Project', true, 'N/A') !!}
            {!! tr($todolistName, 'Todolist', true, 'N/A') !!}
            {!! tr($todoName, 'Todo', true, 'N/A') !!}
            {!! tr($todo->description, 'Description', true, 'N/A') !!}
            {!! tr($todo->time_start, 'Time Start', true, 'N/A') !!}
            {!! tr($todo->time_end, 'Time End', true, 'N/A') !!}
            {!! tr(tdLabel('success', getBCHoursDiff($todo->dated, $todo->time_start, $todo->time_end)), 'Total', true, 'N/A') !!}
            </tbody>
        </table>

    </div>

@endsection
