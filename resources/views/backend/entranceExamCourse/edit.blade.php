@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.add_course'))

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.exams.course.edit_course') }}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            {!! Form::model($entranceExamCourse, ['route' => ['admin.entranceExamCourses.update', $entranceExamCourse->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch','id'=> 'form_entrance_exam_course']) !!}
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
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Backend\EntranceExamCourse\UpdateEntranceExamCourseRequest') !!}
    <script>
        function save_course(){
            var status = $( "#form_entrance_exam_course" ).validate().form();
            if(status ==true){
                $.ajax({
                    type: 'POST',
                    url: $("#form_entrance_exam_course").attr('action'),
                    data: $("#form_entrance_exam_course").serialize(),
                    dataType: "json",
                    success: function(resultData) {
                        opener.update_ui_course();
                        self.close();
                    }
                });
            }
        }
        $(function() {
            $('#form_entrance_exam_course input').keypress(function (e) {
                if (e.which == 13) {
                    save_course();
                    return false;
                }
            });

            $("#btn-save").click(function () {
                save_course();
            });

            $("#btn-cancel").click(function () {
                opener.update_ui_course();
                window.close();
            });
        });
    </script>
@stop