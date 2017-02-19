@extends ('backend.layouts.popup_master')

@section ('title', 'Course Annual' . ' | ' . 'Form Request All Score Property')

@section('content')

    <div class="box box-success">

        <style>

        </style>
        <div class="box-header with-border">
            <h3 class="box-title">Selection</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body panel">

            {!! Form::open(['route' => ['admin.course.get_form_evaluation_score'], 'class' => 'form-horizontal form_request_total_score_course_annual', 'role' => 'form', 'method' => 'put']) !!}

                <div class="form-group">
                    {!! Form::label('academic_year', 'Academic year', ['class' => 'col-lg-3 control-label required']) !!}
                    <div class="col-lg-7">
                        {{ Form::select('academic_year_id', $academicYears, null, ['class' => 'form-control', 'id' => 'academic_year_id','required'=>'required', 'placeholder' => 'Academic Year']) }}
                    </div>
                </div>

            <div class="form-group">
                {!! Form::label('department', 'Department', ['class' => 'col-lg-3 control-label required']) !!}
                <div class="col-lg-7">
                    {{ Form::select('department_id', $departments, isset($user_department_id)?$user_department_id:null, ['class' => 'form-control', 'id' => 'department_id','required'=>'required', 'placeholder' => 'Department']) }}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('department_option', 'Option', ['class' => 'col-lg-3 control-label required']) !!}
                <div class="col-lg-7">
                    <select name="department_option_id" class="form-control">
                        <option value=""> Option </option>
                        @foreach($departmentOptions as $option)
                            <option value="{{$option->id}}" class="dept_option department_{{$option->department_id}}">{{$option->code}}</option>
                        @endforeach
                    </select>
{{--                    {{ Form::select('department_option_id', $departmentOptions, null, ['class' => 'form-control', 'id' => 'department_option_id','required'=>'required', 'placeholder' => 'Option']) }}--}}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('semester', 'Semester', ['class' => 'col-lg-3 control-label required']) !!}
                <div class="col-lg-7">
                    {{ Form::select('semester_id', $semesters, null, ['class' => 'form-control', 'id' => 'semester_id','required'=>'required', 'placeholder' => 'Semester']) }}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('degree', 'Degree', ['class' => 'col-lg-3 control-label required']) !!}
                <div class="col-lg-7">
                    {{ Form::select('degree_id', $degrees, null, ['class' => 'form-control', 'id' => 'degree_id','required'=>'required', 'placeholder' => 'Degree']) }}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('year', 'Year', ['class' => 'col-lg-3 control-label required']) !!}
                <div class="col-lg-7">
                    {{ Form::select('grade_id', $grades, null, ['class' => 'form-control', 'id' => 'grade_id','required'=>'required', 'placeholder' => 'Year']) }}
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('group', 'Student Group', ['class' => 'col-lg-3 control-label required']) !!}
                <div class="col-lg-7">
                    {{ Form::select('group', [], null, ['class' => 'form-control', 'id' => 'group','required'=>'required', 'placeholder' => 'Group']) }}
                </div>
            </div>


            {!! Form::close() !!}
        </div>

    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <a href="#" id="cancel_form_selection" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="button" id="ok_form_selection" class="btn btn-danger btn-xs" value="OK" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')

    {{--myscript--}}

    <script>

        function ajaxRequest(method, baseUrl, baseData){

            $.ajax({
                type: method,
                url: baseUrl,
                data: baseData,
                success: function(result) {
                    console.log(result);

                    if(result.status== true) {
                        notify('success', 'info', result.message);
                        window.opener.refresh_course_tree();

//                        window.parent.$("#annual_course");

                        window.setTimeout(function(){
                            window.close();
                        }, 2000);
                    } else {
                        notify('error', 'info', result.message);
                    }

                }
            });
        }

        $('#cancel_form_selection').on('click', function() {
            window.close();
        })
        $('#ok_form_selection').on('click', function() {
           var data = $('form.form_request_total_score_course_annual').serialize();
            var submit_url = $('form.form_request_total_score_course_annual').attr('action');

            var height = $(window).height();
            var width = $(window).width();

            PopupCenterDual(submit_url+'?'+data,'All Score Course Annual',width,height);
            window.close();
        })


        $('.dept_option').hide();

        $(document).ready(function() {
            if(val = $('select[name=department_id] :selected').val()) {
                $('.department_'+ val).show();
            }
        });

        $('select[name=department_id]').on('change', function() {
            $('.department_'+ $(this).val()).show();
        })

    </script>


@stop