@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.add_course'))

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.exams.secret_code.title') }}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            {!! Form::model($entranceExamCourse, ['route' => ['admin.course.courseAnnuals.update', $courseAnnual->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch','id'=> 'form_entrance_exam_course']) !!}
            @include('backend.entranceExamCourse.fields')
            {!! Form::close() !!}
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
        $(function() {
            $("#btn-save").click(function () {
                $.ajax({
                    type: 'POST',
                    url: "{{route('admin.exam.save_entrance_exam_course',$exam_id)}}",
                    data: $("#form_entrance_exam_course").serialize(),
                    dataType: "json",
                    success: function(resultData) {
                        opener.update_ui_course();
                        window.close();
                    }
                });
            });

            $("#btn-cancel").click(function () {
                opener.update_ui_course();
                window.close();
            });
        });
    </script>
@stop