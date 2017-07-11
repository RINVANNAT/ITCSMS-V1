@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')


@endsection

@section('after-style-end')

@endsection

@section('content')

    <div class="box box-success">

        <div class="box-header with-border">
            <h3 class="box-title"> Successfully Imported Score </h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="row no-margin">
                <div class="form-group col-sm-12" style="padding: 20px;" id="alert_div">

                </div>
            </div>

            <div class="row no-margin" style="padding-left: 20px;padding-right: 20px;">
                <div class="form-group col-sm-12 box-body with-border text-muted well well-sm no-shadow" style="padding: 20px;">

                    <div class="alert alert-success">
                        <h1> <i class="fa fa-check-circle-o"> </i> </h1>
                        <p> Successfully Imported!</p>
                    </div>
                </div>

            </div>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-success">
        <div class="box-body">
            <div class="pull-left">
                <button class="btn btn-danger btn-xs" id="cancel_import">cancel</button>
            </div>

            <div class="pull-right">
              <a class="btn btn-primary btn-xs" href="{{route('admin.course.form_input_score_course_annual', $courseAnnualId)}}"> Process Score </a>
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
    {!! Form::close() !!}

@stop

@section('after-scripts-end')

    {!! Html::style('plugins/handsontable-test/handsontable.full.min.css') !!}
    {!! Html::script('plugins/handsontable-test/handsontable.full.min.js') !!}
    {!! Html::script('plugins/jpopup/jpopup.js') !!}

    <script>

        @if(session('status_student'))
        var str = ' ';
        @foreach(session('status_student') as $student)
                str = str + '{{$student['student_id']}}'+ ' ';
        @endforeach

        var div_message = '<div class="alert alert-warning">' +
                        '<h4><i class="icon fa fa-info"></i> Import Score Warning!</h4>' +
                            'The scores are imported but there are some missing students'+
                            '<p>' +
                                str +
                            '</p>' +
                        '</div>';

        $('#alert_div').html(div_message)

        @endif





    </script>
@stop