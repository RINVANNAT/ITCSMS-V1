@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.students.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.students.title') }}
        <small>{{ trans('labels.backend.students.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        .toolbar {
            float: left;
        }
    </style>
@stop


@section('content')
<div class="container">
    {!! Form::model($score, ['route' => ['backend.score.update', $score->id], 'method' => 'patch']) !!}
        @include('backend.score.fields')
    {!! Form::close() !!}
</div>
@endsection
