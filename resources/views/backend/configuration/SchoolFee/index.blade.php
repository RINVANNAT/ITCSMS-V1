@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.schoolFees.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.schoolFees.title') }}
        <small>{{ trans('labels.backend.schoolFees.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <!-- Check all button -->
                <a href="{!! route('admin.configuration.schoolFees.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> {{trans('buttons.general.crud.create')}}
                    </button>
                </a>
                <a href="{!! route('admin.configuration.schoolFee.request_import') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> {{trans('buttons.general.import')}}
                    </button>
                </a>
                <div class="btn-group">
                    <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                    <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                    <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#school_fee" aria-controls="generals" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.schoolFees.index_tabs.school_fee') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#scholarship_fee" aria-controls="candidates" role="tab" data-toggle="tab">
                            {{ trans('labels.backend.schoolFees.index_tabs.scholarship_fee') }}
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="school_fee" style="padding-top:20px">
                        <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="schoolFees-table">
                            <thead>
                            <tr>
                                <th>{{ trans('labels.backend.schoolFees.fields.group') }}</th>
                                <th>{{ trans('labels.backend.schoolFees.fields.promotion_id') }}</th>
                                <th>{{ trans('labels.backend.schoolFees.fields.to_pay') }}</th>
                                <th>{{ trans('labels.backend.schoolFees.fields.department_id') }}</th>
                                <th>{{ trans('labels.backend.schoolFees.fields.grade_id') }}</th>
                                <th>{{ trans('labels.general.actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="scholarship_fee" style="padding-top:20px">
                        <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="scholarshipFees-table">
                            <thead>
                            <tr>
                                <th>{{ trans('labels.backend.schoolFees.fields.group') }}</th>
                                <th>{{ trans('labels.backend.schoolFees.fields.scholarship_id') }}</th>
                                <th>{{ trans('labels.backend.schoolFees.fields.promotion_id') }}</th>
                                <th>{{ trans('labels.backend.schoolFees.fields.to_pay') }}</th>
                                <th>{{ trans('labels.backend.schoolFees.fields.department_id') }}</th>
                                <th>{{ trans('labels.backend.schoolFees.fields.grade_id') }}</th>
                                <th>{{ trans('labels.general.actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    <script>
        $(function() {
            $('#schoolFees-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.configuration.schoolFee.data',"false") !!}', // parameter 0 represent scholarship id, which mean all scholarships
                    method: 'POST'
                },
                columns: [
                    { data: 'degree_name_kh', name: 'degrees.name_kh', orderable:false},
                    { data: 'promotion_name', name: 'promotions.name', orderable:false},
                    { data: 'to_pay', name: 'to_pay', orderable:false, searchable:false},
                    { data: 'departments', name: 'departments', orderable:false, searchable:false},
                    { data: 'grades', name: 'grades', orderable:false, searchable:false},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            $('#scholarshipFees-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.configuration.schoolFee.data',"true") !!}',
                    method: 'POST'
                },
                columns: [
                    { data: 'degree_name_kh', name: 'degrees.name_kh', orderable:false},
                    { data: 'scholarship_code', name: 'scholarships.code', orderable:false},
                    { data: 'promotion_name', name: 'promotions.name', orderable:false},
                    { data: 'to_pay', name: 'to_pay', orderable:false, searchable:false},
                    { data: 'departments', name: 'departments', orderable:false, searchable:false},
                    { data: 'grades', name: 'grades', orderable:false, searchable:false},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            enableDeleteRecord($('#schoolFees-table'));
        });
    </script>
@stop
