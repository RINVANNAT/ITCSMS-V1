@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.students.title') . ' | ' . trans('labels.backend.students.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.students.title') }}
        <small>{{ trans('labels.backend.students.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.studentAnnuals.store', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true]) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.students.sub_create_title') }}</h3>
            </div>

            <div class="box-body">
                @include('backend.studentAnnual.show.fields')
            </div>
        </div>

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.studentAnnuals.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}
    <script>
        $(function(){

        });
    </script>
@stop