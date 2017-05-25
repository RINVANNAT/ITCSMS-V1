@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('page-header')


@endsection

@section('after-style-end')

@endsection

@section('content')

        {!! Form::open(['route' => ['course_annual.import_file', $courseAnnual->id],'id' => 'import_course_annual_score', 'role'=>'form','files' => true])!!}
        <div class="box box-success">

            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.rooms.sub_import_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="row no-margin">
                    <div class="form-group col-sm-12" style="padding: 20px;">
                        <span>Select the .CSV file to import. if you need a sample importable file, you can use the export tool to generate one.</span>
                    </div>
                </div>

                <div class="row no-margin" style="padding-left: 20px;padding-right: 20px;">
                    <div class="form-group col-sm-12 box-body with-border text-muted well well-sm no-shadow" style="padding: 20px;">
                        {!! Form::label('import','Selected File (csv, xls, xlsx)') !!}
                        {!! Form::file('import', null) !!}
                        {{ Form::hidden('group_id',  ($group !=null)?$group:null, ['class' => 'form-control', 'id'=>'name_kh', 'required' => 'required']) }}

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
                    <input type="submit" class="btn btn-success btn-xs" id = "submit_score" value="{{ trans('buttons.general.import') }}"/>
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


    {{--myscript--}}
    <script>
        $('#cancel_import').on('click', function(e) {
            e.preventDefault();
            window.open('{{route('admin.course.form_input_score_course_annual', $courseAnnual->id)}}', '_self');
        });

        $(function(){
            $('#submit_score').on('click',function(){
                toggleLoading(true);
            });
        });

        $(document).ready(function() {
            if($('.message').is(':visible')) {
                setTimeout(function() {
                    $('.message').fadeOut('slow');
                }, 3000)
            }
        })

        @if(session('warning'))

            swal({
            title: "Attention",
            text: " File Not Imported :Cell value must be bigger or equal 0, less than the header percentage and no string allowed!",
            type: "warning",
            confirmButtonColor: "red",
            confirmButtonText: "Close",
            closeOnConfirm: true
        }, function(confirmed) {
            if (confirmed) {

            }
        });
        @endif

        @if(session('status'))
            swal({
            title: "Attention",
            text: "{{session('status')}}",
            type: "warning",
            confirmButtonColor: "red",
            confirmButtonText: "Close",
            closeOnConfirm: true
        }, function(confirmed) {
            if (confirmed) {

            }
        });
        @endif




    </script>
@stop