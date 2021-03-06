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
    {!! Html::style('plugins/webui-popover/jquery.webui-popover.css') !!}
    {!! Html::style('css/odoo1.css') !!}
    <style>
        .toolbar {
            float: left;
            width: 50%;
        }

        #filter_group {
            width: 2cm;
        }

        .slide_container {
            width: 50%;
            margin-right: 15px;
            position: absolute;
            background-color: white;
            border: 1px solid black;
            z-index: 999;
        }

        .filter{
            margin-bottom: 5px;
        }

    </style>
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <div class="mailbox-controls">
                @permission("create-students")
                <a href="{!! route('admin.studentAnnuals.create') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add
                    </button>
                </a>
                <a href="{!! route('admin.student.request_import') !!}">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Import
                    </button>
                </a>
                @endauth

                <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

            </div>
            <div class="box-tools pull-right">
                @include('backend.studentAnnual.includes.partials.header-buttons')
            </div>

        </div><!-- /.box-header -->

        <div class="box-body">
            @include('backend.studentAnnual.includes.partials.table-header-index')
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/webui-popover/jquery.webui-popover.js') !!}
    <script>
        var filter_box = '';
        function hideCustomExport(){
            $('.btn-export').hide();
        }

        $(document).ready(function(){
            var current_filtering = null;
            var custom_student_window = null;


            var oTable = $('#students-table').DataTable({
                dom: 'l<"toolbar">frtip',
                processing: true,
                serverSide: true,
                pageLength: 100,
                deferLoading: 0,
                ajax: {
                    url:"{!! route('admin.vocational_students.data') !!}",
                    method:'POST',
                    data:function(d){
                        // In case additional fields is added for filter, modify export view as well: popup_export.blade.php
                        d.academic_year = $('#filter_academic_year').val();
                        d.degree = $('#filter_degree').val();
                        d.grade = $('#filter_grade').val();
                        d.department = $('#filter_department').val();
                        d.gender = $('#filter_gender').val();
                        d.option = $('#filter_option').val();
                        d.origin = $('#filter_origin').val();
                        d.group = $('#filter_group').val();
                        d.semester = $('#filter_semester').val();
                        d.radie = $('#filter_radie').val();
                        d.redouble = $('#filter_redouble').val();
                    }
                },
                columns: [
                    { data: 'id_card', name: 'students.id_card'},
                    { data: 'name_kh', name: 'students.name_kh'},
                    { data: 'name_latin', name: 'students.name_latin'},
                    { data: 'dob', name: 'dob',searchable:false},
                    { data: 'gender', name: 'gender',searchable:false},
                    { data: 'class' , name: 'class',searchable:false},
                    { data: 'option' , name: 'option',searchable:false},
                    { data: 'group' , name: 'group',searchable:false},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ],
                order: [[2,"asc"],[5,"asc"]]
            });

            oTable.on( 'xhr', function () {
                current_filtering = oTable.ajax.params();
            } );

            $("div.toolbar").html(
                    '{!! Form::select('academic_year',$academicYears,null, array('class'=>'form-control filter','id'=>'filter_academic_year')) !!} '+
                    '{!! Form::select('degree',$degrees,null, array('class'=>'form-control filter','id'=>'filter_degree','placeholder'=>'Degree')) !!} '+
                    '{!! Form::select('grade',$grades,null, array('class'=>'form-control filter','id'=>'filter_grade','placeholder'=>'Grade')) !!} '+
                    '{!! Form::select('department',$departments,null, array('class'=>'form-control filter','id'=>'filter_department')) !!} ' +
                    '{!! Form::select('gender',$genders,null, array('class'=>'form-control filter','id'=>'filter_gender','placeholder'=>'Gender')) !!} '+
                    '{!! Form::select('option',$options,null, array('class'=>'form-control filter','id'=>'filter_option','placeholder'=>'Option')) !!} '+
                    '{!! Form::select('semester',$semesters,null, array('class'=>'form-control filter','id'=>'filter_semester')) !!} '+
                    '{!! Form::select('origin',$origins,null, array('class'=>'form-control filter','id'=>'filter_origin','placeholder'=>'Origin')) !!} '+
                    '{!! Form::text('group',null, array('class'=>'form-control filter','id'=>'filter_group','placeholder'=>'Group')) !!} '+
                    '{!! Form::select('radie',['with'=>'With radié','no'=>'No radié','only'=>'Only radié'],null, array('class'=>'form-control filter','id'=>'filter_radie')) !!} '+
                    '{!! Form::select('redouble',['with'=>'With redouble','no'=>'No redouble','only'=>'Only redouble'],null, array('class'=>'form-control filter','id'=>'filter_redouble')) !!} '
            );

            oTable.draw();

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
            $('#filter_semester').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_origin').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_group').on('input', function(e) {
                oTable.draw();
                e.preventDefault();
                //alert($('#filter_group').val());
            });

            $('#filter_radie').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_redouble').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            enableDeleteRecord($('#students-table'));
            viewPopUpStudent($('#students-table'));

            /*$('#export_student_list').click(function(e){
             e.preventDefault();
             window.location = '{{route("admin.student.export")}}'+
             "?academic_year="+$('#filter_academic_year').val()+
             '&degree='+ $('#filter_degree').val()+
             '&grade=' + $('#filter_grade').val()+
             '&department=' + $('#filter_department').val()+
             '&gender='+$('#filter_gender').val()+
             '&option='+$('#filter_option').val()+
             '&origin='+$('#filter_origin').val();
             });*/

            $(document).on('click', '#export_student_list', function (e) {
                e.preventDefault();
                var url = '{{route("admin.student.request_export_fields")}}'+
                        "?academic_year="+$('#filter_academic_year').val()+
                        '&degree='+ $('#filter_degree').val()+
                        '&grade=' + $('#filter_grade').val()+
                        '&department=' + $('#filter_department').val()+
                        '&gender='+$('#filter_gender').val()+
                        '&option='+$('#filter_option').val()+
                        '&semester='+$('#filter_semester').val()+
                        '&origin='+$('#filter_origin').val()+
                        '&group='+$('#filter_group').val()+
                        '&radie='+$('#filter_radie').val()+
                        '&redouble='+$('#filter_redouble').val();

                PopupCenterDual(url,'Select fields to export','1200','960');
            });

            $(document).on('click', '#export_student_list_custom', function (e) {
                e.preventDefault();
                $('.btn-export').show();
                var url = '{{route("admin.student.request_export_custom")}}';

                custom_student_window = PopupCenterDual(url,'Export student data','1200','960');

            });


        });
    </script>
@stop

