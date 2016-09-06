
@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Missing candidates')

@section('content')

    <div class="box box-success">

        <style>


        </style>
        <div class="box-header with-border">
            <h3 class="box-title">Missing Register IDs</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="col-md-12">
                @foreach($missing as $item)
                    <div class="badge bg-info">{{$item}}</div>
                @endforeach
            </div>

        </div>

    </div>
    <div class="box box-success" style="margin-bottom: 0px !important;">
        <div class="box-body">
            <div class="pull-left">
                <input type="button" class="btn btn-default btn-xs" onclick="window.close()" value="{{ trans('buttons.general.close') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop
