@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.candidatePayments.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.candidatePayments.title') }}
        <small>{{ trans('labels.backend.candidatePayments.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        .toolbar {
            float: left;
        }
        .payment_export_print {
            float: left;
        }
        .payment_btn {
            float: right;
        }
        .payment_info {
            float: right;
        }
        td.details-control {
            background: url('/img/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('/img/details_close.png') no-repeat center center;
        }
        .bg-payment-detail {
            padding: 10px !important;
            /*background-image: url("/img/bg_contit.gif");*/
            background-color: #84D0EF;
        }

        table.bg-payment-detail{
            background-color: #84D0EF;
            /*background-image: url("/img/bg_contit.gif");*/
            border: solid 1px black;
        }

        table.bg-payment-detail td,th{
            border: solid 1px black; !important;
            border-bottom:1pt solid black !important;
        }

        #table_modal_payment {
            border: 1px solid;
        }

        #table_modal_payment strong {
            padding-left: 15px;
            padding-right: 15px;
        }

        #table_modal_payment div {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .btn_outcome_student a:hover {
            background-image:none; !important;
            background-color: #00a65a; !important;
        }


    </style>
@stop

@section('content')
    @include('backend.accounting.studentPayment.modal_income')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <button id="refresh_table" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

            </div>
            <div class="box-tools pull-right">
                @include('backend.studentAnnual.includes.partials.header-buttons')
            </div>

        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="candidates-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.candidates.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.name_latin') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.gender_id') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.dob') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.province_id') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.class') }}</th>
                        <th>{{ trans('labels.backend.candidates.fields.result') }}</th>
                        <th>To Pay</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/handlebars.js') !!}
    <script>
        var payment_tables = [];
        var opening_tr = null;
        var opening_row = null;
        var payslip_history_url = "{{route('admin.accounting.payslipHistory.data')}}";
        var btn_income_last_click = null;

        function initTable(tableId, data,tr) {

            payment_tables[tableId] = $('#' + tableId).DataTable({
                dom: 'i<"payment_info">t',
                processing: true,
                serverSide: true,
                ajax: payslip_history_url+"?payslip_client_id="+data.payslip_client_id+"&type=candidate&user_id="+data.id,
                columns: [
                    { data: 'number', name: 'number',searchable:false },
                    { data: 'income', name: 'income' ,searchable:false},
                    { data: 'outcome', name: 'outcome' ,searchable:false},
                    { data: 'created_at', name: 'created_at',searchable:false },
                    { data: 'action', name: 'action', orderable:false,searchable:false }
                ]
            })

            var btn_income = tr.next('tr').find(".btn_income_candidate");
            if(data.payslip_client_id != "0"){ // There are history payslip record in here
                btn_income.hide(); // so hide the button income because candidate can only pay once
            }

        }

        var oTable = $('#candidates-table').DataTable({
            dom: '<"toolbar">frtip',
            processing: true,
            serverSide: true,
            pageLength: {!! config('app.records_per_page')!!},
            deferLoading: 0,
            ajax: {
                url:"{!! route('admin.accounting.candidatePayment.data') !!}",
                data:function(d){
                    d.academic_year_id = $('#filter_academic_year').val();
                    d.degree_id = $('#filter_degree').val();
                    d.exam_id = $('#filter_exam').val();
                    d.department_id = $('#filter_department').val();
                    d.gender_id = $('#filter_gender').val();
                }
            },
            columns: [
                { data: 'name_kh', name: 'candidates.name_kh'},
                { data: 'name_latin', name: 'candidates.name_latin'},
                { data: 'gender_name_kh', name: 'genders.name_kh'},
                { data: 'dob', name: 'candidates.dob'},
                { data: 'province', name: 'origins.name_kh'},
                { data: 'class', name: 'class',searchable:false,orderable:false},
                { data: 'result', name: 'candidates.result'},
                { data: 'to_pay', name: 'to_pay',searchable:false,orderable:false},
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "searchable":       false,
                    "data":           null,
                    "defaultContent": ''
                },
            ]
        });

        $(document).ready(function(){
            var template = Handlebars.compile($("#details-template").html());
            var current_id = null;
            var base_url = "{{url('/')}}";


            $("div.toolbar").html(
                    ' {!! Form::select('academic_year',$academicYears,null, array('class'=>'form-control','id'=>'filter_academic_year')) !!} '+
                    '{!! Form::select('exam',$exams,null, array('class'=>'form-control','id'=>'filter_exam')) !!} - '+
                    '{!! Form::select('degree',$degrees,null, array('class'=>'form-control','id'=>'filter_degree','placeholder'=>'Degree')) !!} '+
                    '{!! Form::select('department',$departments,null, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'Department')) !!} ' +
                    '{!! Form::select('gender',$genders,null, array('class'=>'form-control','id'=>'filter_gender','placeholder'=>'Gender')) !!} '
            );
            oTable.draw();

            $('#filter_academic_year').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#filter_degree').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#filter_department').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_gender').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#filter_exam').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $("#income_dollar").keydown(function (e) {
                allowNumberOnly(e);
            });

            $("#income_riel").keydown(function (e) {
                allowNumberOnly(e);
            });

            $('#income_dollar').on('change', function () {
                $('#income_dollar_kh').val(convertMoney($('#income_dollar').val())+" ដុល្លា");
            });
            $('#income_riel').on('change', function () {
                $('#income_riel_kh').val(convertMoney($('#income_riel').val())+" រៀល");
            });

            $('#submit_payment').on('click',function(){
                submitIncome();
            });

            $('#refresh_table').on('click',function(){
                oTable.draw();
            });

            function submitIncome(){
                if($("#invoice_number").val() == ""){
                    swal("មានបញ្ហា", "ត្រូវការលេខរបស់វិក័យបត្រ", "error");
                } else {
                    $.ajax({
                        type:'POST',
                        dataType:'json',
                        data:$('#payslip_income_form').serialize(),
                        url:'{{route('admin.accounting.incomes.store')."?type=Candidate"}}',
                        beforeSend:function(){
                            // do nothing for now
                        },
                        success:function(data) {
                            //console.log("Loaded ID: "+ "students-"+data.candidate_id);
                            $('#add_payment_modal').modal('toggle');
                            payment_tables["students-"+data.candidate_id].ajax.url(payslip_history_url+"?payslip_client_id="+data.payslip_client_id+"&type=candidate&user_id="+data.candidate_id).load();
                            btn_income_last_click.hide();
                            //oTable.draw();
                        },
                        error:function(error){
                            alert(error);
                        }
                    });
                }

            }

            // Add event listener for opening and closing details
            $('#candidates-table tbody').on('click', 'td.details-control', function () {

                var tr = $(this).closest('tr');
                var row = oTable.row(tr);
                var tableId = 'students-' + row.data().id;

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                    opening_tr = null;
                    opening_row = null;
                } else {

                    if(opening_tr != null){ // Some tr is openning, close it
                        opening_row = oTable.row(opening_tr);
                        opening_row.child.hide();
                        opening_tr.removeClass('shown');
                    }

                    opening_tr = tr;
                    opening_row = row;

                    // Open this row
                    row.child(template(row.data())).show();
                    initTable(tableId, row.data(),tr);

                    $(".btn_income_candidate").click(function(){
                        btn_income_last_click = $(this);
                        preparePayment(row.data());
                        current_id = tableId;
                    });

                    $(".print_all").click(function(){
                        console.log('print');
                        window.open(base_url+'/admin/accounting/candidatePayments/'+row.data().id+'/print','_blank');
                    });

                    tr.addClass('shown');
                    tr.next().find('td').addClass('no-padding bg-payment-detail');
                }
            });


            // This for payment part
            function preparePayment(data){
                //console.log(data);
                $('#payslip_income_form')[0].reset();

                var onlyBirthDate = data.dob;
                var khmerBirthYear = convertKhmerNumber(onlyBirthDate.split('/')[2]);
                var khmerBirthMonth = convertKhmerMonth(onlyBirthDate.split('/')[1]);
                var khmerBirthDay = convertKhmerNumber(onlyBirthDate.split('/')[0]);

                $("#payment_candidate_id").val(data.id) ;
                $("#payment_payslip_client_id").val(data.payslip_client_id) ;
                $("#client_name_kh").html(data.name_kh);
                $("#client_name_latin").html(data.name_latin);
                $("#client_gender").html(data.gender_name_kh);
                $("#client_birthdate").html(khmerBirthDay+' '+khmerBirthMonth+' '+khmerBirthYear);
                $("#client_department").html(data.department_name_kh);
                $("#client_grade").html(convertKhmerNumber(data.grade_id));
                $("#client_degree").html(data.degree_name_kh);
                $("#client_degree_id").val(data.degree_id);
                $("#client_promotion").html(convertKhmerNumber(data.promotion_name));
                $("#client_payment_sequence").html(convertKhmerNumber(data.count_income+1));
                $("#client_academic_year").html(data.academic_year_name_kh);
                $('#client_current_date').html(getKhmerCurrentDate());
                $("#add_payment_modal").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }


        });
    </script>

    <script id="details-template" type="text/x-handlebars-template">
        <div class="label label-info">@{{name_kh}}'s Payments</div>
        <button class="btn btn-sm btn-primary btn_income_candidate pull-right">Income</button>
        <table class="table details-table col-lg-8 bg-payment-detail dt-responsive nowrap" cellspacing="0" width="100%" id="students-@{{id}}">
            <thead>
            <tr>
                <th>Number</th>
                <th>Income</th>
                <th>Outcome</th>
                <th>Date</th>
                <th></th>
            </tr>
            </thead>
        </table>
    </script>

@stop
