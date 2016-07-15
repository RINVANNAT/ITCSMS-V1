@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.choose_course'))

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.exams.secret_code.title') }}</h3>
            <input type="button" id="btn-auto" class="btn btn-success btn-xs pull-right" style="margin-left: 5px;" value="{{ trans('labels.backend.exams.secret_code.generate_auto') }}" />
            <input type="button" id="btn-manual" class="btn btn-info btn-xs pull-right" value="{{ trans('labels.backend.exams.secret_code.generate_manual') }}" />
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            @include("backend.course.courseAnnual.includes.index_table")
        </div>

    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="btn-cancel" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="btn-save" class="btn btn-danger btn-xs" value="{{ trans('buttons.general.save') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    <script>

    </script>
@stop