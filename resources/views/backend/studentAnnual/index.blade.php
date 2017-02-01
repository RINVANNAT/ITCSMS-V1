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
                pageLength: {!! config('app.records_per_page')!!},
                deferLoading: 0,
                ajax: {
                    url:"{!! route('admin.student.data') !!}",
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
                ]
            });

            oTable.on( 'xhr', function () {
                current_filtering = oTable.ajax.params();
            } );

            $("div.toolbar").html(
                    '{!! Form::select('academic_year',$academicYears,null, array('class'=>'form-control','id'=>'filter_academic_year')) !!} '+
                    '{!! Form::select('degree',$degrees,null, array('class'=>'form-control','id'=>'filter_degree','placeholder'=>'Degree')) !!} '+
                    '{!! Form::select('grade',$grades,null, array('class'=>'form-control','id'=>'filter_grade','placeholder'=>'Grade')) !!} '+
                    '{!! Form::select('department',$departments,null, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'Department')) !!} ' +
                    '{!! Form::select('gender',$genders,null, array('class'=>'form-control','id'=>'filter_gender','placeholder'=>'Gender')) !!} '+
                    '{!! Form::select('option',$options,null, array('class'=>'form-control','id'=>'filter_option','placeholder'=>'Option')) !!} '+
                    '{!! Form::select('origin',$origins,null, array('class'=>'form-control','id'=>'filter_origin','placeholder'=>'Origin')) !!} '+
                    '{!! Form::text('group',null, array('class'=>'form-control','id'=>'filter_group','placeholder'=>'Group')) !!} '
            );
//            $("div.toolbar").html(
//                    get_filter_box()
//            );
//
//            $('.o_searchview_more').on("click", function() {
//                if ($('.slide_container').is(':hidden')) {
//                    $('.slide_container').slideDown(300);
//                } else {
//                    $('.slide_container').slideUp(300);
//                    //layer.remove();
//                }
//            });



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
            $('#filter_origin').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
            $('#filter_group').on('input', function(e) {
                oTable.draw();
                e.preventDefault();

                //alert($('#filter_group').val());
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
                        '&origin='+$('#filter_origin').val()+
                        '&group='+$('#filter_group').val();

                PopupCenterDual(url,'Select fields to export','1200','960');
            });

            $(document).on('click', '#export_student_list_custom', function (e) {
                e.preventDefault();
                $('.btn-export').show();
                var url = '{{route("admin.student.request_export_custom")}}';

                custom_student_window = PopupCenterDual(url,'Export student data','1200','960');

            });

            $('#students-table').on('click', '.btn-export[data-remote]', function (e) {
                var data = $(this).data('remote');
                custom_student_window.addRow(data);
            });

            window.onbeforeunload = function(event)
            {
                if(custom_student_window!= null){
                    custom_student_window.close();
                }
            };

            $('#print_id_card').on('click',function(e){
                e.preventDefault();
                var url = "{{ route('admin.student.request_print_id_card') }}";

                PopupCenterDual(
                        url
                        + '?academic_year='+current_filtering.academic_year
                        + '&degree='+current_filtering.degree
                        + '&grade='+current_filtering.grade
                        + '&department='+current_filtering.department
                        + '&gender='+current_filtering.gender
                        + '&option='+current_filtering.option
                        + '&origin='+current_filtering.origin
                        + '&group='+current_filtering.group
                        + '&search='+current_filtering.search.value,
                        'Print ID Card','900','800');
            });

            $('#generate_id_card').on('click', function(e) {
                e.preventDefault();
                var baseUrl = " {{ route('admin.student.generate_student_id_card',1) }}";

                var baseData = {
                    degree_id: $('#filter_degree').val(),
                };


                swal({
                    title: "Confirm",
                    text: "Errase The Previouse and Generate New IDs !!",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Errase?",
                    closeOnConfirm: true
                }, function(confirmed) {
                    if (confirmed) {

                        $.ajax({
                            type: 'POST',
                            url: baseUrl,
                            data: baseData,
                            dataType:"json",
                            success: function(resultData) {
                                console.log(resultData.success);
                                if(resultData.success == true) {
                                    notify("success","info", resultData.message);
                                    oTable.draw();
                                } else {
                                    notify("error","info", resultData.message);
                                }


                            }
                        });

                    }
                });
            });



            $('#generate_student_group').on('click', function() {


            var  academic_year = $('#filter_academic_year').val(),
                 academic_year_name = $('#filter_academic_year option:selected').text(),
                 degree =  $('#filter_degree').val(),
                 degree_name =  $('#filter_degree option:selected').text(),
                 grade =  $('#filter_grade').val(),
                 grade_name =  $('#filter_grade option:selected').text(),
                 department = $('#filter_department').val(),
                 department_name = $('#filter_department option:selected').text();

                var url = "{{ route('admin.student.form_generate_student_group',1) }}";
                var window_generate_group = PopupCenterDual(
                                                url
                                                + '?academic_year_id='+academic_year
                                                + '&academic_year_name='+academic_year_name
                                                + '&degree_id='+degree
                                                + '&degree_name='+degree_name
                                                + '&grade_id='+grade
                                                + '&grade_name='+grade_name
                                                + '&department_id='+department
                                                + '&department_name='+department_name,
                                                'Generate Group','750','300');

            });





        });
    </script>
@stop

