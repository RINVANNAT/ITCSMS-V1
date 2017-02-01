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
    <style>
        #filter_dept_option {
            margin-left: 5px;
        }
        .toolbar {
            float: left;
        }
    </style>
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
                {{--<a href="{!! route('admin.course.course_program.request_import') !!}">--}}
                    {{--<button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Import--}}
                    {{--</button>--}}
                {{--</a>--}}

                {{--<div class="btn-group">--}}
                    {{--<button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>--}}
                    {{--<button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>--}}
                    {{--<button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>--}}
                {{--</div>--}}
                {{--<button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>--}}

            </div>


        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%" id="coursePrograms-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.coursePrograms.fields.name_kh') }}</th>
                        <th>{{ trans('labels.backend.coursePrograms.fields.code') }}</th>
                        <th>Class</th>
                        <th>{{ trans('labels.backend.coursePrograms.fields.semester') }}</th>
                        <th width="20px;">{{ trans('labels.backend.coursePrograms.fields.time_course') }}</th>
                        <th width="20px;">{{ trans('labels.backend.coursePrograms.fields.time_td') }}</th>
                        <th width="20px;">{{ trans('labels.backend.coursePrograms.fields.time_tp') }}</th>
                        <th>{{ trans('labels.backend.coursePrograms.fields.credit') }}</th>
                        <th  width="50px;">{{ trans('labels.general.actions') }}</th>
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

            var toolbar_html =
                    @if($department_id != null)
                            ''+
                        @if(isset($deptOptions))
                                '{!! Form::select('dept_option',$deptOptions,null, array('class'=>'form-control','id'=>'filter_dept_option','placeholder'=>'Division')) !!} '+
                        @endif
                    @else
                            ' {!! Form::select('department',$departments,$department_id, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'Department')) !!} '+
                    @endif
                        '{!! Form::select('semester',$semesters,null, array('class'=>'form-control','id'=>'filter_semester','placeholder'=>'Semester')) !!} '+
                        '{!! Form::select('degree',$degrees,null, array('class'=>'form-control','id'=>'filter_degree','placeholder'=>'Degree')) !!} '+
                        '{!! Form::select('grade',$grades,null, array('class'=>'form-control','id'=>'filter_grade','placeholder'=>'Year')) !!} '


            var oTable = $('#coursePrograms-table').DataTable({
                processing: true,
                serverSide: true,
                dom: 'l<"toolbar">frtip',
                //deferLoading: true,
                pageLength: {!! config('app.records_per_page')!!},

                ajax: {
                    url:'{!! route('admin.course.course_program.data') !!}',
                    method:'POST',
                    data:function(d){
                        // In case additional fields is added for filter, modify export view as well: popup_export.blade.php
                        d.degree = $('#filter_degree').val();
                        d.grade = $('#filter_grade').val();
                        d.department = $('#filter_department').val();
                        d.department_option = $('#filter_dept_option').val();
                        d.semester = $('#filter_semester').val();

                    }
                },
                columns: [
                    { data: 'name_kh', name: 'name_en'},
                    { data: 'code', name: 'code'},
                    { data: 'class', name: 'class', searchable:false},
                    { data: 'semester', name: 'semesters.id'},
                    { data: 'time_course', name: 'time_course'},
                    { data: 'time_td', name: 'time_td'},
                    { data: 'time_tp', name: 'time_tp'},
                    { data: 'credit', name: 'credit'},
                    { data: 'action', name: 'action',orderable: false, searchable: false}
                ]
            });

            enableDeleteRecord($('#coursePrograms-table'));

            $("div.toolbar").html(toolbar_html);



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
                hasDeptOption();
                e.preventDefault();
            });

            $(document).on('change', '#filter_dept_option', function() {
                oTable.draw();
                e.preventDefault();
            })

            $('#filter_semester').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });
        });



        function hasDeptOption() {
            var dept_option_url = '{{route('course_program.dept_option')}}';
            var department_id = $('#filter_department :selected').val();

            $.ajax({
                type: 'GET',
                url: dept_option_url,
                data: {department_id: department_id},
                dataType: "html",
                success: function(resultData) {

//                    console.log(resultData);
                    if($('#filter_dept_option').is(':visible')) {
                        $('#filter_dept_option').html(resultData);
                    } else {
                        $("div.toolbar > select#filter_department").after(resultData);
                    }

                }
            });

        }
    </script>
@stop

