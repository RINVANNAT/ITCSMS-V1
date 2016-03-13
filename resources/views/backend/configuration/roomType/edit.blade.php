@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.roomTypes.title') . ' | ' . trans('labels.backend.roomTypes.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.roomTypes.title') }}
        <small>{{ trans('labels.backend.roomTypes.sub_edit_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::model($roomType, ['route' => ['admin.configuration.roomTypes.update', $roomType->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.roomTypes.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.configuration.roomType.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.configuration.roomTypes.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop
