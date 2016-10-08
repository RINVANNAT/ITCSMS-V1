@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.add_course'))

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.exams.secret_code.title') }}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            test
        </div>

    </div>
@stop

@section('after-scripts-end')

    <script>

    </script>
@stop