@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.degrees.title') . ' | ' . trans('labels.backend.degrees.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.degrees.title') }}
        <small>{{ trans('labels.backend.degrees.sub_create_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/select2/select2.min.css') !!}
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
        .select2-result-repository {
            padding-top: 4px;
            padding-bottom: 3px;
        }
        .select2-result-repository__avatar {
            float: left;
            width: 60px;
            margin-right: 10px;
        }
        .select2-result-repository__avatar img {
            width: 100%;
            height: auto;
            border-radius: 2px;
        }
        img {
            vertical-align: middle;
        }
        img {
            border: 0;
        }
        .select2-result-repository__meta {
            margin-left: 70px;
        }
        .select2-result-repository__title {
            color: black;
            font-weight: bold;
            word-wrap: break-word;
            line-height: 1.1;
            margin-bottom: 4px;
        }

        .select2-result-repository__description {
            font-size: 13px;
            color: #777;
            margin-top: 4px;
        }
        .select2-result-repository__forks, .select2-result-repository__stargazers, .select2-result-repository__watchers {
            display: inline-block;
            color: #aaa;
            font-size: 11px;
        }
        .select2-result-repository__forks, .select2-result-repository__stargazers {
            margin-right: 1em;
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
    {!! HTML::script('plugins/select2/select2.full.min.js') !!}
    <script>
        var $search_url = "{{route('admin.client.search')}}";

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


        $(".select_client").select2({
            placeholder: 'Enter name ...',
            allowClear: true,
            tags: true,
            createTag: function (params) {
                return {
                    id: params.term,
                    name: params.term,
                    group:'customer',
                    newOption: true
                }
            },
            ajax: {
                url: $search_url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term || '', // search term
                        page: params.page || 1
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 3,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection, // omitted for brevity, see the source of this page
        });

        function formatRepo (repo) {
            console.log(repo);
            if (repo.loading) {
                return repo.text;
            }
            if (repo.newOption) {
                return '<em>Add new customer</em> "'+repo.name+'"';
            } else {
                var markup = "<div class='select2-result-repository clearfix'>" +
                                "<div class='select2-result-repository__avatar'><img src='{{url('/img/profiles/avatar.png')}}' /></div>" +
                                "<div class='select2-result-repository__meta'>" +
                                    "<div class='select2-result-repository__title'>" + repo.name + "</div>"+
                                    "<div class='select2-result-repository__description'>" + repo.group + "</div>"+
                                    "<div class='select2-result-repository__statistics'>" +
                                        "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + "sdfsdf" + " Forks</div>" +
                                        "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + "sdfsdf" + " Stars</div>" +
                                    "</div>" +
                                "</div>"+
                            "</div>";
                return markup;

            }


        }

        function formatRepoSelection (repo) {
            return repo.name || repo.group;
        }


        $('#btn_modal_client_search').on('click',function(){
            $search_url = $search_url+"/"+$('#client_type').val()+"/search";
            $.ajax({
                type:'POST',
                data:function(d){
                    d.academic_year = $('#filter_academic_year').val();
                    d.degree = $('#filter_degree').val();
                    d.grade = $('#filter_grade').val();
                    d.department = $('#filter_department').val();
                    d.gender = $('#filter_gender').val();
                },
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