@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.configurations.title') . ' | ' . trans('labels.backend.configurations.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.configurations.title') }}
        <small>{{ trans('labels.backend.configurations.sub_create_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.configuration.configurations.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.configurations.sub_create_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include('backend.configuration.fields')
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.configuration.configurations.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop