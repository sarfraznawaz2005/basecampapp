@extends('layouts.app')

@section('content')

    @include('pages.timeentry._form', ['action' => route('timeentry'), 'method' => 'PATCH'])

@endsection
