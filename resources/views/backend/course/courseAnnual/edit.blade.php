@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.courseAnnuals.title') }}
        <small>{{ trans('labels.backend.courseAnnuals.sub_edit_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::model($courseAnnual, ['route' => ['admin.course.course_annual.update', $courseAnnual->id],'class' => 'form-horizontal edit_course_annual', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.courseAnnuals.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.course.courseAnnual.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.course.course_annual.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
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
    {!! Html::script('js/backend/course/courseAnnual/course_annual.js') !!}

    <script>
        $(document).ready(function() {

            $('#other_dept').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'GET',
                    url: '{{route('course_annual.get_other_dept')}}',
                    data: {department_id: 'request'},
                    dataType: "html",
                    success: function(resultData) {
                        if($('select[name=other_department_id]').is(':visible')) {

                            $(this).html(resultData);
                        } else {
                            $('.other_department').append(resultData);
                        }

                    }
                });
            })


            $(document).on('change', 'select[name=other_department_id]', function() {
                var route = '{{route('course_annual.get_other_lecturer')}}';

                $.ajax({
                    type: 'GET',
                    url: route,
                    data: {department_id: $(this).val()},
                    dataType: "html",
                    success: function(resultData) {
                        $('#lecturer_lists').html(resultData);
                    }
                });
            })





            $('form.edit_course_annual').on('submit', function(e) {

                var credit = $('input#credit').val();

                if(credit != '') {
                    if($.isNumeric(credit)) {
                        return true;
                    } else {

                        notify('error', 'Not a numeric!')
                        e.preventDefault();

                    }
                } else {
                    notify('error', 'Field credit is required!')
                    e.preventDefault();
                }
//                e.preventDefault();
//                var form_url = $(this).attr('action');
//                var baseData = {
//                    group_selected: ($('#jstree_group').is(':visible'))?JSON.stringify($('#jstree_group').jstree("get_selected")):''
//                };
//
//                $.ajax({
//                    type: 'POST',
//                    url: form_url+'?'+$('form.create_course_annual').serialize(),
//                    data: baseData,
//                    dataType: 'JSON',
//                    success: function(resultData) {
//                        console.log(resultData);
//
//                    }
//                });
            })
            

            $('.dept_option_block').hide();

            $('select[name=department_id]').on('change', function (e) {

                var request_url = '{{route('course_annual.dept_option')}}';

                $.ajax({
                    type: 'GET',
                    url: request_url,
                    data: {department_id: $(this).val()},
                    dataType: "html",
                    success: function(resultData) {
                        if($('select[name=department_option_id]').is(':visible')) {

                            $('select[name=department_option_id]').html(resultData);
                        } else {
                            $('.dept_option_block').show();
                            $('.dept_option_block').append('<label class="col-lg-2 control-label required" style="margin-top: 5px"> Department Option </label>');
                            $(".dept_option_block").append('<div class="col-lg-7">'+resultData +'</div>');
                        }
                    }
                });


            })
        })
    </script>
@stop