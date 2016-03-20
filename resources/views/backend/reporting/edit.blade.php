@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.error.reporting.title') . ' | ' . trans('labels.backend.error.reporting.sub_edit_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.error.reporting.title') }}
        <small>{{ trans('labels.backend.error.reporting.sub_edit_title') }}</small>
    </h1>
@endsection

@section('content')
    {!! Form::model($reporting, ['route' => ['admin.reporting.update', $reporting->id],'class' => 'form-horizontal', 'role'=>'form', 'method' => 'patch']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.error.reporting.sub_edit_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include ("backend.reporting.fields")
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.reporting.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-info btn-xs" value="{{ trans('buttons.general.crud.update') }}" />
                </div>
                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->
    {!! Form::close() !!}
@stop

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
