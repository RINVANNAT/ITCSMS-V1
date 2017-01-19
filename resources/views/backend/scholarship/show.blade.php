@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.scholarships.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.scholarships.title') }}
        <small>{{ trans('labels.backend.scholarships.sub_index_title') }}</small>
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
            <h3 style="font-size: 20px;"><i class="fa fa-info-circle"></i> {{trans('labels.backend.scholarships.general_information')}}
            </h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                @include('backend.scholarship.includes.show_general_fields')
            </div>

            <div class="clearfix"></div>
        </div><!-- /.box-body -->
        <div class="box-header with-border">
            <h3 style="font-size: 20px;"><i class="fa fa-forumbee"></i> {{trans('labels.backend.scholarships.more_information')}}
            </h3>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div>
                @include('backend.scholarship.includes.show_more_fields')
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
            $('#school_fee_and_award_table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    data: {
                      scholarship_id:  "{{$scholarship->id}}"
                    },
                    url:"{!! route('admin.configuration.schoolFee.data',["true"]) !!}",
                    method:"post"
                },
                columns: [
                    { data: 'degree_name_kh', name: 'degree_name_kh', orderable:false, searchable:false},
                    { data: 'promotion_name', name: 'promotion_name', orderable:false, searchable:false},
                    { data: 'to_pay', name: 'to_pay', orderable:false, searchable:false},
                ]
            });

            var oTable = $('#scholarship_holder_table').DataTable({
                dom: 'l<"toolbar">frtip',
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url:"{!! route('admin.student.data')."?scholarship=".$scholarship->id !!}",
                    method:"post",
                    data:function(d){
                        d.academic_year = $('#filter_academic_year').val();
                        d.degree = $('#filter_degree').val();
                        d.grade = $('#filter_grade').val();
                        d.department = $('#filter_department').val();
                        d.gender = $('#filter_gender').val();
                        d.option = $('#filter_option').val();
                        d.origin = $('#filter_origin').val();
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
                ]
            });

            $("div.toolbar").html(
                    '{!! Form::select('academic_year',$academicYears,null, array('class'=>'form-control','id'=>'filter_academic_year')) !!} '+
                    '{!! Form::select('degree',$degrees,null, array('class'=>'form-control','id'=>'filter_degree','placeholder'=>'Degree')) !!} '+
                    '{!! Form::select('grade',$grades,null, array('class'=>'form-control','id'=>'filter_grade','placeholder'=>'Grade')) !!} '+
                    '{!! Form::select('department',$departments,null, array('class'=>'form-control','id'=>'filter_department','placeholder'=>'Department')) !!} ' +
                    '{!! Form::select('gender',$genders,null, array('class'=>'form-control','id'=>'filter_gender','placeholder'=>'Gender')) !!} '+
                    '{!! Form::select('option',$options,null, array('class'=>'form-control','id'=>'filter_option','placeholder'=>'Option')) !!} '+
                    '{!! Form::select('origin',$origins,null, array('class'=>'form-control','id'=>'filter_origin','placeholder'=>'Origin')) !!} '
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
            $('#filter_origin').on('change', function(e) {
                oTable.draw();
                e.preventDefault();
            });

            enableDeleteRecord($('#scholarships-table'));
            $(document).on('click', '#btn_add_more_scholarship_holder', function (e) {
                PopupCenterDual('{{route("admin.student.popup_index")."?scholarship_id=".$scholarship->id}}','Add new scholarship holder','1200','960');
            });

            $(document).on('click', '#btn_import_scholarship_holder', function (e) {
                PopupCenterDual('{{route("admin.scholarship.request_import_holder")}}','Add new scholarship holder','1200','960');
            });
        });
    </script>
@stop
