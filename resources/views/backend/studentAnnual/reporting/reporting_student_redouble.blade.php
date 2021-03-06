@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.students.title')." | Redouble ".trans('menus.backend.reporting.title') )

@section('page-header')
    <h1>
        {{ trans('labels.backend.students.title') }}
        <small>Redouble {{ trans('menus.backend.reporting.title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    <style>
        .vcenter {
            display: inline-block;
            vertical-align: middle;
            float: none;
        }
        #search_btn {
            float: right;
        }
        table{
            border: 1px;
            border-collapse: collapse;
            margin: 0px auto;
            width: 100%;
        }
        *{
            font-family: "Khmer OS";
        }
        .font-muol, .font-head{
            font-family: "Khmer OS Muol Light" !important;
        }
        .font-head{
            font-size: 20px;
        }
        table tr.insertBorder td{
            border: 1px solid black;
        }
        .blue{
            color: blue;
        }
    </style>
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-lg-9 form-horizontal vcenter">
                    <form id="reporting-form">
                        <div class="form-group">
                            {!! Form::label('name', trans('labels.backend.reporting.academic_year_id'), ['class' => 'col-lg-2 control-label']) !!}
                            <div class="col-lg-4">
                                {!! Form::select('academic_year_id', $academicYears,null, ['class' => 'form-control','id' => 'input_academic_year']) !!}
                            </div>
                            {!! Form::label('name', trans('labels.backend.reporting.degree_id'), ['class' => 'col-lg-2 control-label']) !!}
                            <div class="col-lg-4">
                                {!! Form::select('degree_id', $degrees,null, ['class' => 'form-control', 'id'=>'input_degree']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('semester', trans('labels.backend.reporting.semester_id'), ['class' => 'col-lg-2 control-label']) !!}
                            <div class="col-lg-4">
                                {!! Form::select('semester_id', ["1" => "Semester 1", "2" => "Semester 2","3" => "End Year"],null, ['class' => 'form-control', 'id'=>'input_semester']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('scholarships', trans('labels.backend.reporting.scholarships'), ['class' => 'col-lg-2 control-label']) !!}
                            <div class="col-lg-10" style="padding: 0px">
                                @foreach($scholarships as $scholarship)
                                    <div class="col-md-4">
                                        <label><input type="checkbox" name="scholarships[]" value="{{$scholarship->id}}"> {{$scholarship->code}}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-2 vcenter">
                    <a class="btn btn-app" id="search_btn">
                        <i class="fa fa-search"></i> {{trans('buttons.general.search')}}
                    </a>
                </div>
            </div>


        </div><!-- /.box-header -->

        <div class="box-body">

            <div id="data">
                <div class="form-group col-sm-12 box-body with-border text-muted well well-sm no-shadow" style="padding: 20px;">
                    Please select the fields above to apply filter and then click search.
                </div>
            </div>
            <div class="clearfix"></div>
            <iframe name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-success">
        <div class="box-body">

            <div class="pull-right">
                <button class="btn btn-info btn-xs" id="export_btn"> <i class="fa fa-sign-out"></i> {{ trans('buttons.general.export') }} </button>
                <button class="btn btn-success btn-xs" id="print_btn"> <i class="fa fa-print"></i> {{ trans('buttons.general.print') }} </button>
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    <script>
        var report_data_url = "{!! url('admin/student/'.$id.'/reporting-data') !!}";
        var export_data_url = "{!! url('admin/student/'.$id.'/reporting/export') !!}";
        var print_url = "{!! url('admin/student/'.$id.'/reporting/print') !!}";
        var preview_url = "{!! url('admin/student/'.$id.'/reporting/preview') !!}";

        /* ----------------------Page functions---------------------*/
        function preview(link){
            $.ajax({
                url: link +"?data="+JSON.stringify($("#reporting-form").serializeArray()),
                type: 'GET',
                dataType: 'text',
                success: function(data) {
                    $('#data').html(data);
                },
                error: function() {
                    alert('Something is wrong');
                }
            });
        }
        function submitForm(link){

            window.location = link +"?data="+JSON.stringify($("#reporting-form").serializeArray());
        }


        /* --------------------When page ready, start some action---------------------- */
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Start load data when page loaded
            //preview(preview_url);
            $("#search_btn").click(function (e) {
                e.preventDefault();
                preview(preview_url);
            });

            $("#export_btn").click(function (e) {
                e.preventDefault();
                // Refresh data if button search is clicked
                submitForm(export_data_url);
            });

            $("#print_btn").click(function (e) {
                e.preventDefault();
                // Refresh data if button search is clicked
                submitForm(print_url);
            });

        });
    </script>


@stop
