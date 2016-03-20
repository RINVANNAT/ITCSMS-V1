@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.incomes.title') . ' | ' . trans('labels.backend.incomes.sub_create_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.incomes.title') }}
        <small>{{ trans('labels.backend.incomes.sub_create_title') }}</small>
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
    {!! Form::open(['route' => 'admin.accounting.incomes.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'create-role']) !!}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.incomes.sub_create_title') }}</h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                @include('backend.accounting.income.fields')
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="{!! route('admin.access.roles.index') !!}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
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
        $(function () {
            var $search_url = "{{route('admin.client.search')}}";

            $('#amount_dollar').on('change', function () {
                $('#amount_kh').val(convertMoney($('#amount_dollar').val())+" ដុល្លា");
                $('#amount_riel').prop('disabled', true);
            });
            $('#amount_riel').on('change', function () {
                $('#amount_kh').val(convertMoney($('#amount_riel').val())+" រៀល");
                $('#amount_dollar').prop('disabled', true);
            });

            var client_search_box = $(".select_client").select2({
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

            $(document).on('mouseup', '.btn_add_new_customer', function (e) {
                PopupCenterDual('{{route("admin.accounting.customer.popup_create")}}','Add new customer','570','822');
            });

            function formatRepo (repo) {
                console.log(repo);
                if (repo.loading) {
                    return repo.text;
                }
                if (repo.newOption) {
                    return '<a href="#" class="btn_add_new_customer"><em>Add new customer</em> "'+repo.name+'"</a>';
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

                $('#client_name').val(repo.name);
                $('#department').val(repo.department);
                $('#payment_payslip_client_id').val(repo.payslip_client_id);
                $('#form_client_type').val(repo.group);
                $('#client_id').val(repo.id);

                return repo.name || repo.group;
            }
        });
    </script>
@stop