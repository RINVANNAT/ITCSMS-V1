@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.courseAnnuals.title') }}
        <small>{{ trans('labels.backend.courseAnnuals.sub_edit_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::model($courseAnnual, ['route' => ['admin.course.course_annual.update', $courseAnnual->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

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
        })
    </script>
@stop