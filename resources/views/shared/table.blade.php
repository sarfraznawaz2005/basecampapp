@extends('layouts.app')

@section('content')
    {!! $dataTable->table(['class' => 'table table-condensed table-striped table-bordered table-hover'])  !!}
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush