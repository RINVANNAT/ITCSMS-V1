@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.degrees.title') . ' | ' . trans('labels.backend.degrees.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.degrees.title') }}
        <small>{{ trans('labels.backend.degrees.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    <style>
        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #3c8dbc;
        }

        #table_modal_payment {
            border: 1px solid;
            border-collapse: separate;
            border-spacing: 0 0.3em;
        }

        #table_modal_payment td {
            padding-left: 20px;
        }

        .outcome_label{
            padding-top: 10px;
        }

        #table_modal_payment strong {
            padding-left: 15px;
            padding-right: 15px;
        }


        .tr_active {
            background-color: #00c0ef;
            color: #fff;
        }

        .btn_outcome_student a:hover {
            background-image:none; !important;
            background-color: #00a65a; !important;
        }

    </style>
@stop

@section('content')
    @include('backend.accounting.outcome.includes.modal_find_client')
    {!! Form::open(['route' => 'admin.accounting.outcomes.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.degrees.sub_create_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include('backend.accounting.outcome.fields')
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.accounting.outcomes.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
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
        var $search_url = "{{url('/')}}"";

        $('#amount_dollar').on('change', function () {
            $('#amount_kh').val(convertMoney($('#amount_dollar').val())+" ដុល្លា");
        });
        $('#amount_riel').on('change', function () {
            $('#amount_kh').val(convertMoney($('#amount_riel').val())+" រៀល");
        });
        $('#current_date').html(getKhmerCurrentDate());
        $('#btn_search_client').on('click',function(){
            $("#modal_find_client").modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $('#btn_modal_client_search').on('click',function(){
            $search_url = $search_url+"/"+$('#client_type').val()+"/search";
            $.ajax({
                type:'POST',
                data:$('#form_client_search').serialize(),
                dataType:'json',
                url:$search_url,
                beforeSend:function(){
                    //console.log($('#client_type').val());
                    client_type = $('#client_type').val();
                    $('#client_result').html("<div class='overlay' style='width: 100%;height: 100px;'><i class='fa fa-refresh fa-spin'></i></div>");
                },
                success:function(data)
                {
                    clients = data;

                    var table_view = "";
                    console.log(data);
                    if(data.total != 0){
                        table_view = "<table class='table table-condensed' id='table_student_bac2_result'>" +
                                "<thead>" +
                                "<th>"+"ឈ្មោះខ្មែរ"+"</th>" +
                                "<th>"+"ឈ្មោះឡាតាំង"+"</th>" +
                                "<th>"+"ថ្ងៃខែឆ្នាំកំណើត"+"</th>" +
                                "<th>"+"ភេទ"+"</th>" +
                                "<th>"+"ដេប៉ាតីម៉ង់"+"</th>" +
                                "<th width='50px'>"+"សកម្មភាព"+"</th>" +
                                "</thead>" +
                                "<tbody>";
                        $.each(clients.data, function(index, element){
                            table_view = table_view +
                                    "<tr>"+
                                    "<td>"+element.name_kh+"</td>"+
                                    "<td>"+element.name_latin+"</td>"+
                                    "<td>"+element.birthdate+"</td>"+
                                    "<td>"+element.gender.name_kh+"</td>"+
                                    "<td>"+element.department.name_kh+"</td>"+
                                    "<td>" +
                                    "<a href='"+index+"' class='btn_client_export'><i class='glyphicon glyphicon-export'></i></a>"+
                                    "</td>" +
                                    "</tr>";
                        });
                        table_view = table_view + "</tbody></table>";
                    } else {
                        table_view = '<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">'
                                +no_result+'. </p>'
                    }

                    $('#client_result').html(table_view);
                },
                error:function(error){
                    $('#client_result').html('<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">'
                            +no_result+'</p>');
                }
            });
        });
    </script>
@stop