@extends('backend.layouts.master')

@section('after-styles-end')
    {!! Html::style('plugins/iCheck/square/red.css') !!}
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
@stop

@section('page-header')
    <h1>
        {!! app_name() !!}
        <small>Internship</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Internship</h3>
            <div class="box-tools pull-right">
                <a class="btn btn-default btn-sm" href="{{ route('internship.create') }}">
                    <i class="fa fa-print"></i>
                    Print Internship Certificate
                </a>
                <a class="btn btn-primary btn-sm" href="{{ route('internship.create') }}">
                    <i class="fa fa-plus-circle"></i>
                    Create an new internship
                </a>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-striped table-bordered table-hover dt-responsive nowrap"
                   id="internships">
                <thead>
                <tr>
                    <th></th>
                    <th>Internship title</th>
                    <th>Subject</th>
                    <th>Contact Name</th>
                    <th>Contact Detial</th>
                    <th>Period</th>
                    <th>Actions</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('after-scripts-end')
    {!! Html::script('plugins/iCheck/icheck.min.js') !!}
    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}

    <script>
        function initIcheker() {
            $('#internships input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red'
            });
        }

        $(function () {
            $('#internships').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('internship.data') }}',
                columns: [
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {data: 'internship_title', name: 'internship_title'},
                    {data: 'subject', name: 'subject'},
                    {data: 'contact_name', name: 'contact_name'},
                    {data: 'contact_detail', name: 'contact_detail', orderable: false, searchable: false},
                    {data: 'period', name: 'period', orderable: false, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],
                order: [[1, 'asc']],
                drawCallback: function() {
                    initIcheker();
                }
            })
        })
    </script>
@stop