@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Message Error')

@section('content')

    <div class="box box-success">

        <div class="box-header with-border">
            <h3 class="box-title">Existing Error</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="col-sm-12">
                <h3>Woow! there are inputed score errors.</h3>
            </div>

            @foreach($courses as $course)

                <div class="alert-danger col-sm-12">
                    <h3>please check on {{$course}}</h3>
                </div>
            @endforeach


        </div>

    </div>

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                {{--<a href="#" id="btn_cancel_candidate_result" class="btn btn-default btn-xs">{{ trans('buttons.general.cancel') }}</a>--}}
            </div>

            <div class="pull-right">
                {{--<input type="button" id="btn_get_factor_ok" class="btn btn-danger btn-xs" value="OK" />--}}
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')

    {{--myscript--}}

    <script>
        $( document ).ready(function() {
            setTimeout(function(){
                window.close();
            },3000);
        });
    </script>


@stop