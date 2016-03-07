@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.students.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.students.title') }}
        <small>{{ trans('menus.backend.reporting.title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    <style>
        .vcenter {
            display: inline-block;
            vertical-align: middle;
            float: none;
        }
        #search_btn {
            float: right;
        }
    </style>
@stop

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            {!! Form::open(['route' => ['admin.student.reporting_data',1,1,1], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'id' => 'report_search']) !!}
            <div class="row">
                <div class="col-lg-9 form-horizontal vcenter">
                    <div class="form-group">
                        {!! Form::label('name', trans('labels.backend.reporting.academic_year_id'), ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-4">
                            {!! Form::select('academic_year_id', $academicYears,null, ['class' => 'form-control']) !!}
                        </div>
                        {!! Form::label('name', trans('labels.backend.reporting.degree_id'), ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-4">
                            {!! Form::select('degree_id', $degrees,null, ['class' => 'form-control']) !!}
                        </div>
                    </div><!--form control-->
                    <div class="form-group">
                        {!! Form::label('name', trans('labels.backend.reporting.department_id'), ['class' => 'col-lg-2 control-label']) !!}
                        <div class="col-lg-4">
                            {!! Form::select('department_id', $departments,null, ['class' => 'form-control','placeholder'=>trans('labels.backend.reporting.all_department')]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 vcenter">
                    <a class="btn btn-app" id="search_btn">
                        <i class="fa fa-search"></i> {{trans('buttons.general.search')}}
                    </a>
                </div>
            </div>
            {!! Form::close() !!}


        </div><!-- /.box-header -->

        <div class="box-body">

            @include('backend.studentAnnual.reporting.template_report_student_by_age')
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@stop

@section('after-scripts-end')
    <script>
        $(document).ready(function() {
            var report_data_url = "{!! url('admin/student/'.$id.'/reporting-data') !!}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#search_btn").click(function (e) {
                e.preventDefault();

                $.ajax({
                    url: report_data_url,
                    type: 'POST',
                    dataType: 'json',
                    data: $("#report_search").serialize(),
                });
            });
        });
    </script>


@stop
