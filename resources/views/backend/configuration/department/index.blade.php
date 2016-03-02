@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.access.permissions.management'))

@section('page-header')
    <h1>{{ trans('labels.backend.access.permissions.management') }}</h1>
@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.access.users.active') }}</h3>

            <div class="box-tools pull-right">
                @include('backend.access.includes.partials.header-buttons')
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive datatable">
                <table class="table table-striped table-bordered table-hover" id="departments-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.access.users.table.id') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.name') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.email') }}</th>
                        <th>{{ trans('labels.backend.access.users.table.confirmed') }}</th>
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
            $('#departments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.configuration.department.data') !!}',
                columns: [
                    { data: 'code', name: 'code'},
                    { data: 'name_kh', name: 'name_kh'},
                    { data: 'name_en', name: 'name_en'}
                ]
            });
        });
    </script>
@stop
