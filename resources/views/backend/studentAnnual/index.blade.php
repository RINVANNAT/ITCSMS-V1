@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.students.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.students.title') }}
        <small>{{ trans('labels.backend.students.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>
        .toolbar {
            float: left;
        }
    </style>
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                <!-- Check all button -->
                <a href="{!! route('admin.studentAnnuals.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <a href="{!! route('admin.student.request_import') !!}">
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
            <div class="box-tools pull-right">
                @include('backend.studentAnnual.includes.partials.header-buttons')
            </div>

        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover" id="students-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.students.fields.id_card') }}</th>
                        <th>{{ trans('labels.backend.students.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.students.fields.name_latin') }}</th>
                        <th>{{ trans('labels.backend.students.fields.dob') }}</th>
                        <th>{{ trans('labels.backend.students.fields.gender_id') }}</th>
                        <th>{{ trans('labels.backend.students.fields.class') }}</th>
                        <th>{{ trans('labels.backend.students.fields.department_option_id') }}</th>
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
        $(document).ready(function(){
            var oTable = $('#students-table').DataTable({
                dom: '<"toolbar">frtip',
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url:"{!! route('admin.student.data',0) !!}",
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
                    { data: 'gender', name: 'gender',searchable:false},
                    { data: 'class' , name: 'class',searchable:false},
                    { data: 'option' , name: 'option',searchable:false},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });
            $("div.toolbar").html(
                    '&nbsp;&nbsp; <label for="name">Academic Year</label> '+
                    '{!! Form::select('academic_year',$academicYears,null, array('class'=>'form-control','id'=>'filter_academic_year')) !!} '+
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

            enableDeleteRecord($('#students-table'));
        });
    </script>
@stop
