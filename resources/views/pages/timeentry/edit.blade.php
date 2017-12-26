@extends('layouts.app')

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

@endsection
