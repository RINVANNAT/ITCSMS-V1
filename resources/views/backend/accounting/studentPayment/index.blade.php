@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.studentPayments.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.studentPayments.title') }}
        <small>{{ trans('labels.backend.studentPayments.sub_index_title') }}</small>
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
            background-image: url("/img/bg_contit.gif");
        }

        table.bg-payment-detail{
            background-image: url("/img/bg_contit.gif");
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

        .refund_1 {
            background-color: #9FAFD1;
        }
    </style>

@stop

@section('content')
    @include('backend.accounting.studentPayment.modal_income')
    @include('backend.accounting.studentPayment.modal_outcome')
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
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="students-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.students.fields.id_card') }}</th>
                        <th>{{ trans('labels.backend.students.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.students.fields.name_latin') }}</th>
                        <th>{{ trans('labels.backend.students.fields.dob') }}</th>
                        <th>{{ trans('labels.backend.students.fields.gender_id') }}</th>
                        <th>{{ trans('labels.backend.students.fields.class') }}</th>
                        <th>{{ trans('labels.backend.students.fields.department_option_id') }}</th>
                        <th>{{ trans('labels.backend.students.fields.to_pay') }}</th>
                        <th>{{ trans('labels.backend.students.fields.debt') }}</th>
                        <th></th>
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
        $(document).ready(function(){
            var template = Handlebars.compile($("#details-template").html());
            var current_id = null;
            var base_url = "{{url('/')}}";
            var payslip_history_url = "{{route('admin.accounting.payslipHistory.data')}}";
            var payment_tables = [];
            var opening_tr = null;
            var opening_row = null;

            function initTable(tableId, data) {
                payment_tables[tableId] = $('#' + tableId).DataTable({
                    dom: 'i<"#payment_info_'+data.id+'">t<"payment_export_print btn-group"><"payment_btn">',
                    processing: true,
                    serverSide: true,
                    ajax: payslip_history_url+"?payslip_client_id="+data.payslip_client_id+"&type=student&user_id="+data.id,
                    initComplete: function() {
                        $('#payment_info_'+data.id).addClass('payment_info');
                    },
                    drawCallback: function (settings) {
                        //$('#total_dollar').html(json.total_dollar);
                        //$('#total_riel').html(json.total_riel);
                        this.api().columns(1).every(function () {
                            var column = this;
                            var sum1 = column.data();
                            if (sum1.length > 0) {
                                sum1 = sum1.reduce(function (a, b) {
                                    return parseInt(a, 10) + parseInt(b, 10);
                                });
                            } else {
                                sum1 = 0;
                            }

                            to_pay = data.to_pay.split(" ");
                            try{
                                sum1 = sum1.split(" ");
                                $('#debt-'+data.id).html((to_pay[0]-sum1[0])+" "+to_pay[1]);
                            } catch (err) {
                                $('#debt-'+data.id).html((to_pay[0]-sum1)+" "+to_pay[1]);
                            }

                        });
                    },
                    columns: [
                        { data: 'number', name: 'number',searchable:false },
                        { data: 'income', name: 'income' ,searchable:false},
                        { data: 'outcome', name: 'outcome' ,searchable:false},
                        { data: 'created_at', name: 'created_at',searchable:false },
                        { data: 'action', name: 'action', orderable:false,searchable:false }
                    ]
                })

                refundStudent($('#' + tableId));
                //console.log(payment_table);
            }

            var oTable = $('#students-table').DataTable({
                dom: '<"toolbar">frtip',
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url:"{!! route('admin.accounting.studentPayment.data') !!}",
                    data:function(d){
                        d.academic_year = $('#filter_academic_year').val();
                        d.degree = $('#filter_degree').val();
                        d.grade = $('#filter_grade').val();
                        d.department = $('#filter_department').val();
                        d.gender = $('#filter_gender').val();
                    }
                },
                columns: [
                    { data: 'id_card', name: 'students.id_card'},
                    { data: 'name_kh', name: 'students.name_kh'},
                    { data: 'name_latin', name: 'students.name_latin'},
                    { data: 'dob', name: 'dob'},
                    { data: 'gender', name: 'gender',orderable:false,searchable:false},
                    { data: 'class' , name: 'class',orderable:false,searchable:false},
                    { data: 'option' , name: 'option',orderable:false,searchable:false},
                    { data: 'to_pay' , name: 'to_pay',orderable:false,searchable:false},
                    { data: 'debt' , name: 'debt',orderable:false,searchable:false},
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "searchable":       false,
                        "data":           null,
                        "defaultContent": ''
                    },
                ]
            });
            $("div.toolbar").html(
                    ' {!! Form::select('academic_year',$academicYears,null, array('class'=>'form-control','id'=>'filter_academic_year')) !!} '+
                    ' &nbsp;<label for="name">Class</label> '+
                    '{!! Form::select('degree',$degrees,null, array('class'=>'form-control','id'=>'filter_degree','placeholder'=>'')) !!} '+
                    '{!! Form::select('grade',$grades,null, array('class'=>'form-control','id'=>'filter_grade','placeholder'=>'')) !!} '+
                    '{!! Form::select('department',$departments,null, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'')) !!}' +
                    '&nbsp;&nbsp; <label for="name">Gender</label> '+
                    '{!! Form::select('gender',$genders,null, array('class'=>'form-control','id'=>'filter_gender','placeholder'=>'')) !!} '+
                    '&nbsp;&nbsp; <label for="name">Option</label> '+
                    '{!! Form::select('option',$options,null, array('class'=>'form-control','id'=>'filter_option','placeholder'=>'')) !!} '
            );

            $('#filter_academic_year').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            $('#filter_degree').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_grade').on('change', function(e) {
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
            $('#filter_option').on('change', function(e) {
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

            $('#amount_dollar').on('change', function () {
                $('#amount_kh').val(convertMoney($('#amount_dollar').val())+" ដុល្លា");
                $('#amount_riel').prop('disabled', true);
            });
            $('#amount_riel').on('change', function () {
                $('#amount_kh').val(convertMoney($('#amount_riel').val())+" រៀល");
                $('#amount_dollar').prop('disabled', true);
            });
            $('.current_date').html(getKhmerCurrentDate());

            $('#submit_payment').on('click',function(){
                submitIncome();
            });

            $('#submit_outcome').on('click',function(){
                submitOutcome();
            });

            $('#refresh_table').on('click',function(){
                oTable.draw();
            });

            function submitIncome(){
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    data:$('#payslip_income_form').serialize(),
                    url:'{{route('admin.accounting.incomes.store')."?type=Student"}}', // Will be passed to table payslipClient as a type
                    beforeSend:function(){
                        // do nothing for now
                    },
                    success:function(data) {
                        $('#add_payment_modal').modal('toggle');
                        //payment_tables["students-"+data.candidate_id].ajax.url(payslip_history_url+"?payslip_client_id="+data.payslip_client_id+"&type=candidate&user_id="+data.candidate_id).load();
                        //payment_table.ajax.url( data.payslip_client_id ).load();
                        oTable.draw();
                    },
                    error:function(error){
                        alert(error);
                    }
                });
            }

            function submitOutcome(){
                $.ajax({
                    type:'POST',
                    dataType:'json',
                    data:$('#payslip_outcome_form').serialize(),
                    url:'{{route('admin.accounting.outcomes.store')}}', // Will be passed to table payslipClient as a type
                    beforeSend:function(){
                        // do nothing for now
                    },
                    success:function(data) {
                        $('#add_outcome_modal').modal('toggle');
                        payment_table.ajax.url( data.payslip_client_id ).load();
                    },
                    error:function(error){
                        alert(error);
                    }
                });
            }

            // Add event listener for opening and closing details
            $('#students-table tbody').on('click', 'td.details-control', function () {
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
                    initTable(tableId, row.data());
                    $("div.payment_export_print").html(
                        '<button class="btn btn-default btn-sm print_all"><i class="fa fa-print"></i></button>'+
                        '<button class="btn btn-default btn-sm export_all"><i class="fa fa-file-excel-o"></i></button>'
                    );
                    $("div.payment_btn").html(
                            '<button class="btn btn-sm btn-primary btn_income_student">Income</button> &nbsp; <button class="btn btn-sm btn-success btn_outcome_student">Outcome</button>'
                    );
                    $("#payment_info_"+row.data().id).html(
                            '<span>To Pay: </span> '+row.data().to_pay+' / <span>Debt: </span> <span id="debt-'+row.data().id+'"></span>'
                    );
                    $(".btn_income_student").click(function(){
                        preparePayment(row.data());
                        current_id = tableId;
                    });

                    $(".btn_outcome_student").click(function(){
                        prepareOutcome(row.data());
                        current_id = tableId;
                    });

                    $(".print_all").click(function(){
                        console.log('print');
                        window.open(base_url+'/admin/accounting/studentPayments/'+row.data().id+'/print','_blank');
                    });

                    tr.addClass('shown');
                    tr.next().find('td').addClass('no-padding bg-payment-detail');
                }
            });

            enableDeleteRecord($('#students-table'));


            // This for payment part
            function preparePayment(data){
                //console.log(data);

                var onlyBirthDate = data.dob;
                var khmerBirthYear = convertKhmerNumber(onlyBirthDate.split('/')[2]);
                var khmerBirthMonth = convertKhmerMonth(onlyBirthDate.split('/')[1]);
                var khmerBirthDay = convertKhmerNumber(onlyBirthDate.split('/')[0]);

                $("#payment_student_annual_id").val(data.id) ;
                $("#payment_payslip_client_id").val(data.payslip_client_id) ;
                $("#client_name_kh").html(data.name_kh);
                $("#client_name_latin").html(data.name_latin);
                $("#client_gender").html(convertKhmerGender(data.gender));
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

            function prepareOutcome(data){
                console.log(data);
                $("#client_box").html("<input type='text' class='form-control' value='"+data.name_kh+"'/>") ;
                $("#department").val(data.department_name_kh);
                $('#outcome_payslip_client_id').val(data.payslip_client_id);
                $('#form_client_type').val("Student");
                $('#client_id').val(data.id);
                $("#add_outcome_modal").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }

        });
    </script>

    <script id="details-template" type="text/x-handlebars-template">
        <div class="label label-info">@{{name_kh}}'s Payments</div>
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
