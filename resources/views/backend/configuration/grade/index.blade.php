@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.grades.index_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.grades.index_title') }}
        <small>{{ trans('labels.backend.grades.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.grades.index_title') }}</h3>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover" id="grades-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.grades.fields.code') }}</th>
                        <th>{{ trans('labels.backend.grades.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.grades.fields.name_en') }}</th>
                        <th>{{ trans('labels.backend.grades.fields.name_fr') }}</th>
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
            $('#grades-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.configuration.grade.data') !!}',
                columns: [
                    { data: 'code', name: 'code'},
                    { data: 'name_kh', name: 'name_kh'},
                    { data: 'name_en', name: 'name_en'},
                    { data: 'name_fr', name: 'name_fr'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
        });
    </script>
@stop
