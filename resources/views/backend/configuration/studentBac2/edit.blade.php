@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.studentBac2s.title') . ' | ' . trans('labels.backend.studentBac2s.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.studentBac2s.title') }}
        <small>{{ trans('labels.backend.studentBac2s.sub_edit_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::model($studentBac2, ['route' => ['admin.configuration.studentBac2s.update', $studentBac2->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.studentBac2s.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.configuration.studentBac2.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.configuration.studentBac2s.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop
