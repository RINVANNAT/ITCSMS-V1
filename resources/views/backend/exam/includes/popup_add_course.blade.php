@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.add_course'))

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.exams.secret_code.title') }}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            {!! Form::open(['route' => ['admin.exam.save_entrance_exam_course',$exam_id], 'id'=> 'form_entrance_exam_course', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post']) !!}
            <form class="form-horizontal">
                <div class="form-group">
                    <label for="name_kh" class="col-sm-3 control-label">Course Name - khmer</label>

                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name_kh" name="name_kh" placeholder="Name in Khmer" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name_en" class="col-sm-3 control-label">Course Name - English</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name_en" name="name_en" placeholder="Name in English">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name_fr" class="col-sm-3 control-label">Course Name - French</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name_fr" name="name_fr" placeholder="Name in French">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">Description</label>

                    <div class="col-sm-9">
                        <textarea class="form-control" id="description" name="description" placeholder="Description"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="total_score" class="col-sm-3 control-label">Total Questions</label>

                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="total_score" name="total_score" placeholder="30">
                    </div>
                </div>
            </form>
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