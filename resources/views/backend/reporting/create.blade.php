@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.error.reporting.title') . ' | ' . trans('labels.backend.error.reporting.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.error.reporting.title') }}
        <small>{{ trans('labels.backend.error.reporting.sub_create_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.reporting.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.error.reporting.sub_create_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include('backend.reporting.fields')
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.reporting.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop

@section('after-scripts-end')
    <script>
        $(function(){
            //var report_path = "{{url('img/reporting/')}}";
            $('input[type=file]').change(function (event) {
                //console.log($(this).mozFullPath);
                var img =URL.createObjectURL(event.target.files[0]);
                $('#preview').html('<div class="col-lg-2"></div><img style="padding: 15px;" src="'+img+'"/>');
            });
        });
    </script>
@stop