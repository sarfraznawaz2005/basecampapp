@extends('layouts.app')

@section('title_area')
    <a data-toggle="modal" href="#modal-entry" class="btn btn-success btn-sm">
        <i class="glyphicon glyphicon-plus-sign"></i> Add New Entry
    </a>
@endsection

@section('content')

    <div class="row" align="center">
        <div class="col-md-4" align="center">
            <span class="label label-warning big" style="border-radius: 0;">
                Pending Todos Hours Today
            </span>
            <span class="label label-success big" style="border-radius: 0;">
                {{user()->pendingTodosHoursToday()}}
            </span>
        </div>
        <div class="col-md-4" align="center">
            <span class="label label-warning big" style="border-radius: 0;">
                Pending Todos Hours Total
            </span>
            <span class="label label-success big" style="border-radius: 0;">
                {{user()->pendingTodosHours()}}
            </span>
        </div>
        <div class="col-md-4" align="center">
            <span class="label label-warning big" style="border-radius: 0;">
                Posted Todos Hours Total
            </span>
            <span class="label label-success big" style="border-radius: 0;">
                {{user()->postedTodosHours()}}
            </span>
        </div>
    </div>
    <hr>

    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#pending" role="tab" data-toggle="tab">Pending Todos</a></li>
        <li><a href="#posted" role="tab" data-toggle="tab">Posted Todos</a></li>
    </ul>
    <div class="tab-content">
        <div class="active tab-pane fade in" id="pending">
            <br>

            <form action="#" id="pendingTodosForm">
                @widget('App.Widgets.PendingTodosWidget')
            </form>

            <button class="btn btn-primary" id="btnPost">
                <i class="glyphicon glyphicon-upload"></i> Post Selected
            </button>
        </div>
        <div class="tab-pane fade" id="posted">
            <br>
            @widget('App.Widgets.PostedTodosWidget')
        </div>
    </div>

    <div class="modal fade" id="modal-entry">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{route('timeentry')}}">
                    {{ csrf_field() }}

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Add New Entry</h4>
                    </div>

                    <div class="modal-body">
                        @include('pages.timeentry._form')
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnAddEntry">
                            <i class="glyphicon glyphicon-plus-sign"></i> Add Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    @include('pages.timeentry._script')
@endpush

