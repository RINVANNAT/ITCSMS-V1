@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.employees.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.employees.title') }}
        <small>{{ trans('labels.backend.employees.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        .toolbar {
            float:left;
        }
    </style>
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <!-- Check all button -->
                <a href="{!! route('admin.employees.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <a href="{!! route('admin.employee.request_import') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Import
                    </button>
                </a>

                {{--<div class="btn-group">--}}
                    {{--<button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>--}}
                    {{--<button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>--}}
                    {{--<button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>--}}
                {{--</div>--}}
                {{--<!-- /.btn-group -->--}}
                {{--<button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>--}}

            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="employees-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.employees.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.name_latin') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.gender_id') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.department_id') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.birthdate') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.contact') }}</th>
                        <th>{{ trans('labels.backend.employees.fields.role_id') }}</th>
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
            @if(count($departments) > 1)
            var toolbar_html =  '{!! Form::select('department',$departments,null, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'Department')) !!} '+
                            '{!! Form::select('gender',$genders,null, array('class'=>'form-control','id'=>'filter_gender','placeholder'=>'Gender')) !!} ';

            @else
            var toolbar_html =  '{!! Form::select('department',$departments,null, array('class'=>'form-control','id'=>'filter_department')) !!} '+
                            '{!! Form::select('gender',$genders,null, array('class'=>'form-control','id'=>'filter_gender','placeholder'=>'Gender')) !!} ';

            @endif

            var oTable = $('#employees-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'l<"toolbar">frtip',
                pageLength: {!! config('app.records_per_page')!!},
                deferLoading:true,
                ajax: {
                    url:'{!! route('admin.employee.data') !!}',
                    method:'POST',
                    data:function(d){
                        d.department = $('#filter_department').val();
                        d.gender = $('#filter_gender').val();
                    }
                },
                columns: [
                    { data: 'name_kh', name: 'name_kh'},
                    { data: 'name_latin', name: 'name_latin'},
                    { data: 'gender', name: 'genders.code', searchable:false},
                    { data: 'department', name: 'department_id',searchable:false},
                    { data: 'birthdate', name: 'birthdate',orderable:false},
                    { data: 'contact', name: 'address',orderable:false},
                    { data: 'positions', name: 'positions', orderable:false,searchable:false},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#employees-table'));

            $("div.toolbar").html(toolbar_html);

            oTable.draw();

            $('#filter_department').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_gender').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
        });
    </script>
@stop
