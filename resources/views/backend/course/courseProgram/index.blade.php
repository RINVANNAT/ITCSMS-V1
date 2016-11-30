@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.coursePrograms.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.coursePrograms.title') }}
        <small>{{ trans('labels.backend.coursePrograms.sub_index_title') }}</small>
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
                <a href="{!! route('admin.course.course_program.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <a href="{!! route('admin.course.course_program.request_import') !!}">
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
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="coursePrograms-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.coursePrograms.fields.name_kh') }}</th>
                        <th>Semester</th>
                        <th>Course Hour</th>
                        <th>TD Hour</th>
                        <th>TP Hour</th>
                        <th>{{ trans('labels.backend.coursePrograms.fields.code') }}</th>
                        <th>Credit</th>
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
            var oTable = $('#coursePrograms-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'l<"toolbar">frtip',
                pageLength: {!! config('app.records_per_page')!!},

                ajax: {
                    url:'{!! route('admin.course.course_program.data') !!}',
                    method:'POST',
                    data:function(d){
                        // In case additional fields is added for filter, modify export view as well: popup_export.blade.php
                        d.degree = $('#filter_degree').val();
                        d.grade = $('#filter_grade').val();
                        d.department = $('#filter_department').val();

                    }
                },
                columns: [
                    { data: 'name_kh', name: 'name_kh'},
                    { data: 'semester', name: 'semester'},
                    { data: 'time_course', name: 'time_course'},
                    { data: 'time_td', name: 'time_td'},
                    { data: 'time_tp', name: 'time_tp'},
                    { data: 'code', name: 'code'},
                    { data: 'credit', name: 'credit'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#coursePrograms-table'));

            $("div.toolbar").html(
                    '{!! Form::select('degree',$degrees,null, array('class'=>'form-control','id'=>'filter_degree','placeholder'=>'Degree')) !!} '+
                    '{!! Form::select('grade',$grades,null, array('class'=>'form-control','id'=>'filter_grade','placeholder'=>'Year')) !!} '+
                    '{!! Form::select('department',$departments,null, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'Department')) !!} '
            );



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
        });
    </script>
@stop

