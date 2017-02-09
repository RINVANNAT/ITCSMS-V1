@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.coursePrograms.title') . ' | ' . trans('labels.backend.coursePrograms.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.coursePrograms.title') }}
        <small>{{ trans('labels.backend.coursePrograms.sub_edit_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::model($courseProgram, ['route' => ['admin.course.course_program.update', $courseProgram->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.coursePrograms.sub_edit_title') }}</h3>
            </div>

            <div class="box-body">
                @include ("backend.course.courseProgram.fields")
            </div>
        </div>

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.course.course_program.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    {!! Form::close() !!}
@stop


@section('after-scripts-end')
    {!! Html::script('plugins/moment/moment.min.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}


    <script>
        $(function(){

            if($('select[name=department_id] :selected').val()) {
                var department_id = $('select[name=department_id] :selected').val();
                $(".department_"+department_id).show();
            }
            $('select[name=department_id]').on('change', function() {
                $(".department_option").hide();
                var department_id = $('select[name=department_id] :selected').val();
                $(".department_"+department_id).show();

            });
        });
    </script>
@stop