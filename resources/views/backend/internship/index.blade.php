@extends('backend.layouts.master')

@section('after-styles-end')

@stop

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>Internship</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Index Internship Module</h3>
            <div class="box-tools pull-right">
                <a class="btn btn-primary btn-sm" href="{{ route('internship.create') }}">
                    <i class="fa fa-plus-circle"></i>
                    Create an new internship
                </a>
            </div>
        </div>
        <div class="box-body">
            <h1>Listing Internship records here</h1>
        </div>
    </div>
@endsection

@section('after-scripts-end')

@stop