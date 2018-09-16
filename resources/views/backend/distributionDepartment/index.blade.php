@extends('backend.layouts.master')

@section('after-styles-end')
    {!! Html::style('plugins/iCheck/square/red.css') !!}
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>Distribution Department Engineer Level</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Distribution Department</h3>
            <div class="box-tools pull-right">
                <a class="btn btn-primary btn-sm"
                   href="{{ route('distribution-department.get-generate-page') }}">
                    <i class="fa fa-refresh"></i> Generate</a>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-striped table-bordered table-hover dt-responsive nowrap"
                   id="distribution-department">
                <thead>
                <tr>
                    <th>ID Card</th>
                    <th>Name Khmer</th>
                    <th>Name Latin</th>
                    <th>Department</th>
                    <th>Department Option</th>
                    <th>Score</th>
                    <th>Priority</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    {!! Html::script('plugins/toastr/toastr.min.js') !!}
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}

    <script>
        function initIcheker() {
            $('#internships input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            })
        }

        $(function () {
            let oTable = $('#distribution-department').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('distribution-department.data') }}',
                columns: [
                    {data: 'student_annual.student.id_card', name: 'student_annual.student.id_card'},
                    {data: 'student.name_kh', name: 'student.name_kh'},
                    {data: 'student.full_name_latin', name: 'student.full_name_latin'},
                    {data: 'department.name_en', name: 'department.name_en'},
                    {data: 'department_option', name: 'department_option'},
                    {data: 'total_score', name: 'total_score'},
                    {data: 'priority', name: 'priority', orderable: false, searchable: false}
                ],
                order: [[5, 'desc']]
            })
        })
    </script>
@stop
