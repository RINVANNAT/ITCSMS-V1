@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.scholarships.title') . ' | ' . trans('labels.backend.scholarships.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.scholarships.title') }}
        <small>{{ trans('labels.backend.scholarships.sub_edit_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
@endsection

@section('content')
    {!! Form::model($scholarship, ['route' => ['admin.scholarships.update', $scholarship->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.scholarships.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.scholarship.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.scholarships.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
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
    <script>
        $(function(){
            $('#date_start_end').daterangepicker({
                format: 'DD/MM/YYYY',
            });
        });
    </script>
@stop
