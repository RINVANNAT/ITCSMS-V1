@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.incomes.title') . ' | ' . trans('labels.backend.incomes.sub_import_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.incomes.title') }}
        <small>{{ trans('labels.backend.incomes.sub_import_title') }}</small>
    </h1>
@endsection


@section('content')

    Operation done.

@stop