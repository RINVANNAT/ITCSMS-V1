@extends ('backend.layouts.popup_master')

@section ('title', 'ITC-SMIS' . ' | ' . 'Print Certificate')

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidates.title') }}
        <small>{{ trans('labels.backend.candidates.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/iCheck/square/red.css') !!}
    {!! Html::style('plugins/DataTables-1.10.15/media/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('plugins/daterangepicker/daterangepicker-bs3.css') !!}
    {!! Html::style('plugins/datetimepicker/bootstrap-datetimepicker.min.css') !!}

    <style>
        .text-10{
            font-size: 9pt !important;
        }
        .toolbar {
            float: left;
        }
        .daterange {
            border: none;
            text-decoration: underline;
            width: 220px;
            padding: 0px;
            color: red;
        }

        .action_buttons, .btn-print {
            margin-left: 10px;
            margin-right: 10px;
        }

        .checkbox-toggle{
            margin-left: 5px;
        }
        select[name="decision"] {
            margin-left: 3mm;
            height: 8mm;
        }
    </style>
@stop

@section('content')

    <div class="box box-success">
        <div class="box-header with-border">
            <h2 class="box-title pull-left" style="padding-top: 8px;">Printing Certificate for the exam in </h2>
            <div class="pull-left" style="padding-left: 10px;">
                <input class="form-control col-md-8 daterange" type="text" name="daterange" placeholder="Please provide exam date"/>
            </div>

            {{--<div class="pull-right">--}}
                {{--<input type="text" name="issued_number" class="form-control"  placeholder="Issued number"/>--}}
            {{--</div>--}}
            <div class="pull-right" style="margin-right: 5px;">
                <input type="text" name="issued_by" class="form-control"  placeholder="Issued by"/>
            </div>
            <div class="pull-right" style="margin-right: 5px;">
                <input type="text" name="issued_date" class="form-control"  placeholder="Issued date"/>
            </div>

        </div><!-- /.box-header -->

        <div class="box-body certificate_table">
            <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="student-table">
                <thead>
                <tr>
                    <th></th>
                    <th>ID Card</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Score</th>
                    <th>Ref. Number</th>
                    <th>Printed Date</th>
                    <th></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

@stop

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('plugins/DataTables-1.10.15/media/js/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/DataTables-1.10.15/media/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/daterangepicker/moment.min.js') !!}
    {!! Html::script('plugins/daterangepicker/daterangepicker.js') !!}
    {!! Html::script('plugins/datetimepicker/bootstrap-datetimepicker.min.js') !!}
    <script>
        var selected_ids = null;
        var print_url = "{{ route('course_annual.competency.print_certificate') }}";
        var mark_url = "{{ route('course_annual.competency.mark_printed_certificate') }}";
        var course_annual_id = "{{$course_annual_id}}";
        var oTable;

        function initIcheker() {
            $('.certificate_table input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            });
        }
        function focus_date_exam(){
            $('input[name="daterange"]').focus();
        }
        function print(selected_ids,course_annual_id){
            // Check if exam date is selected
            if($('input[name="daterange"]').val().length<10){
                alert_error("","Please select exam date",focus_date_exam);
            } else if (selected_ids.length === 0) {
                alert_error("","You need to select some students",null);
            } else {
                var exam_start = $('input[name="daterange"]').data('daterangepicker').startDate;
                var exam_end = $('input[name="daterange"]').data('daterangepicker').endDate;
                var issued_by = $('input[name="issued_by"]').val();
                var issued_date = $('input[name="issued_date"]').val();
                //var issued_number = $('input[name="issued_number"]').val();
                // Open new window to print
                PopupCenterDual(
                        print_url
                        +"?course_annual_id="+course_annual_id
                        +"&issued_by="+issued_by
                        +"&issued_date="+issued_date
                        //+"&issued_number="+issued_number
                        +'&ids='+JSON.stringify(selected_ids)
                        +'&exam_start='+ exam_start.format("DD/MM/YYYY")
                        +'&exam_end='+ exam_end.format("DD/MM/YYYY"),
                        'Printing','1200','800');
            }
        }
        function mark_printed_certificate(){
            selected_ids = [];
            $('.certificate_table input:checked').each(function(){
                selected_ids.push($(this).data('id'));
            });
            $.ajax({
                url: mark_url+ "?course_annual_id="+course_annual_id +'&ids='+JSON.stringify(selected_ids),
                cache: false,
                success: function(response){
                    alert(response.message);
                    $(".fa", $(".checkbox-toggle")).data("clicks", true);
                    $(".fa", $(".checkbox-toggle")).removeClass("fa-square-o").addClass('fa-check-square-o');
                    oTable.draw("page");
                }
            });
        }

        $(function() {
            $('input[name="daterange"]').daterangepicker({
                format: 'DD MMM YYYY'
            });
            $('input[name="issued_date"]').datetimepicker({
                format: 'DD/MM/YYYY'
            });
            oTable = $('#student-table').DataTable({
                processing: true,
                serverSide: true,
                dom: '<"toolbar">frtip',
                pageLength: {!! config('app.records_per_page')!!},
                deferLoading:true,
                ajax: {
                    url:'{!! route('course_annual.competency.data_for_request_print_certificate') !!}',
                    method:'POST',
                    data:function(d){
                        d.course_annual_id = course_annual_id;
                        d.decision = $('select[name="decision"]').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable:false,searchable:false},
                    { data: 'id_card', name: 'id_card'},
                    { data: 'name', name: 'name', searchable:false},
                    { data: 'class', name: 'class',orderable:false},
                    { data: 'score_prop', name: 'score_prop',orderable:false},
                    { data: 'ref_number', name: 'ref_number',orderable:false,searchable:true},
                    { data: 'printed_certificate', name: 'printed_certificate',orderable:false, searchable:false},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ],
                drawCallback: function(){
                    initIcheker();
                    $(".btn-print").off("click");
                    $(".btn-print").on("click",function(){
                        selected_ids = [];
                        $('.certificate_table input:checked').each(function(){
                            selected_ids.push($(this).data('id'));
                        });
                        print(selected_ids,course_annual_id);
                    });
                    $(".btn-single-print").off("click");
                    $(".btn-single-print").on("click", function(){
                        var selected_ids = [$(this).data('id')];
                        print(selected_ids,course_annual_id);
                    });
                    $(".btn-mark-printed-date").off("click");
                    $(".btn-mark-printed-date").on("click", function(){
                        alert_confirm("Confirm","The selected students' certificate will be marked as printed. Are you sure?",mark_printed_certificate);
                    });

                    //Enable check and uncheck all functionality
                    $(".checkbox-toggle").off('click');
                    $(".checkbox-toggle").on('click',function () {
                        var clicks = $(this).data('clicks');
                        if (clicks) {
                            //Check all checkboxes
                            $(".certificate_table input[type='checkbox']").iCheck("check");
                            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                        } else {
                            //Uncheck all checkboxes
                            $(".certificate_table input[type='checkbox']").iCheck("uncheck");
                            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                        }
                        $(this).data("clicks", !clicks);
                    });

                    $('select[name="decision"]').off("change");
                    $('select[name="decision"]').on("change", function(){
                       oTable.draw();
                    });
                }
            });
            $(".toolbar").html(
                    '<button type="button" class="btn btn-default btn-sm checkbox-toggle">'+
                        '<i class="fa fa-check-square-o"></i>'+
                    '</button>'+
                    '<button type="button" data-toggle="tooltip" data-placement="right" title="Print certificate on selected students " class="btn btn-default btn-sm btn-print"><i class="fa fa-print"></i> Print Selected</button>'+
                    '<button type="button" data-toggle="tooltip" data-placement="right" title="You can mark the printed date on every certificate " class="btn btn-default btn-sm btn-mark-printed-date"><i class="fa fa-calendar"></i> Mark Printed Date</button>'+
                    '<select class="form-control" name="decision">' +
                            '<option value="" selected>Filter by result</option>'+
                            '<option value="admis">Admis</option>'+
                            '<option value="non admis">Non Admis</option>'+
                    '</select>'
            );
            oTable.draw();
        });

    </script>
@stop