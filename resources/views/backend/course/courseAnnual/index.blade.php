@extends ('backend.layouts.master')
@section ('title', trans('labels.backend.courseAnnuals.title'))
@section('page-header')
    <h1>
        {{ trans('labels.backend.courseAnnuals.title') }}
        <small>{{ trans('labels.backend.courseAnnuals.sub_index_title') }}</small>
    </h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop
@section('content')
    @if (Session::has('flash_notification.message'))
        <div class="alert alert-{{ Session::get('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            {{ Session::get('flash_notification.message') }}
        </div>
    @endif

    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <!-- Check all button -->
                <a href="{!! route('admin.course.course_annual.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <a href="{!! route('admin.course.course_annual.request_import') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Import
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
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="courseAnnuals-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.courseAnnuals.fields.name') }}</th>
                        <th>{{ trans('labels.backend.courseAnnuals.fields.semester') }}</th>
                        <th>{{ trans('labels.backend.courseAnnuals.fields.academic_year_id') }}</th>
                        <th>{{ trans('labels.backend.courseAnnuals.fields.department_id') }}</th>
                        <th>{{ trans('labels.backend.courseAnnuals.fields.degree_id') }}</th>
                        <th>{{ trans('labels.backend.courseAnnuals.fields.grade_id') }}</th>
                        <th>{{ trans('labels.backend.courseAnnuals.fields.employee_id') }}</th>
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
    <script>
        $(function() {
            $('#courseAnnuals-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: '{!! route('admin.course.course_annual.data') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'semester', name: 'semester'},
                    { data: 'academic_year_id', name: 'academic_year_id'},
                    { data: 'department_id', name: 'department_id'},
                    { data: 'degree_id', name: 'degree_id'},
                    { data: 'grade_id', name: 'grade_id'},
                    { data: 'employee_id', name: 'employee_id'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#courseAnnuals-table'));
        });
    </script>
@stop
