@extends('layouts.app')

@section('title_area')
    <a data-toggle="modal" href="#modal-entry" class="btn btn-success btn-sm">
        <i class="glyphicon glyphicon-plus-sign"></i> Add New Entry
    </a>
@endsection

@section('content')

    <form method="POST" action="{{route('timeentry.edit', $todo)}}">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        @include('pages.timeentry._form')

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="btnAddEntry">
                <i class="glyphicon glyphicon-ok-sign"></i> Update Entry
            </button>
        </div>
    </form>

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
