@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.students.title') . ' | ' . trans('labels.backend.students.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.students.title') }}
        <small>{{ trans('labels.backend.students.sub_edit_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
    {!! Html::style('plugins/select2/select2.min.css') !!}
@endsection

@section('content')
    {!! Form::model($studentAnnual, ['route' => ['admin.studentAnnuals.update', $studentAnnual->id],'class' => 'form-horizontal','id'=>"student_form", 'role'=>'form', 'method' => 'patch','files' => true]) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.students.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.studentAnnual.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.studentAnnuals.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}
    <script>
        var upload_photo_url = "{{config('app.smis_server')."/upload_photo"}}";
        $(function(){
            $(".select2").select2();
            $('#date_start_end').daterangepicker({
                format: 'DD/MM/YYYY',
            });
        });


    </script>
@stop
