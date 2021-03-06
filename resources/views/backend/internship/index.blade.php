@extends('backend.layouts.master')

@section('after-styles-end')
    {!! Html::style('plugins/iCheck/square/red.css') !!}
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
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
                <a class="btn btn-default btn-sm"
                   id="print"
                   target="_blank">
                    <i class="fa fa-print"></i>
                    Print
                </a>
                <a class="btn btn-default btn-sm"
                   id="printed_date"
                   target="_blank">
                    <i class="fa fa-calendar"></i>
                    Mark as printed
                </a>
                <a class="btn btn-primary btn-sm"
                   href="{{ route('internship.create') }}">
                    <i class="fa fa-plus-circle"></i>
                    Create
                </a>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-striped table-bordered table-hover dt-responsive nowrap"
                   id="internships">
                <thead>
                <tr>
                    <th></th>
                    <th>Number</th>
                    <th>Title</th>
                    <th>Training Fields</th>
                    <th>Students</th>
                    <th>Company Info</th>
                    <th>Printed Date</th>
                    <th>Actions</th>
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
            });
        }

        $(function () {
            let oTable = $('#internships').DataTable({
                processing: true,
                serverSide: true,
                saveState: true,
                ajax: '{{ route('internship.data') }}',
                columns: [
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {data: 'number', name: 'number'},
                    {data: 'title', name: 'title'},
                    {data: 'training_field', name: 'training_field'},
                    {data: 'students', name: 'students', orderable: false, searchable: false},
                    {data: 'company_info', name: 'company_info', orderable: false, searchable: false},
                    {data: 'printed_at', name: 'printed_at'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false},
                ],
                order: [[1, 'asc']],
                drawCallback: function() {
                    initIcheker();
                }
            });

            $(document).on('click', '#print', function (e) {
                e.preventDefault();
                let selected_ids = [];
                $('#internships input:checked').each(function(){
                    selected_ids.push($(this).data('id'));
                });
                window.open('{{ env('MY_DOMAIN') }}/admin/internship/'+encodeURIComponent(JSON.stringify(selected_ids))+'/print_internship');
            });

            $(document).on('click', '#printed_date', function (e) {
                e.preventDefault();
                let selected_ids = [];
                $('#internships input:checked').each(function(){
                    selected_ids.push($(this).data('id'));
                });
                swal({
                    title: 'Mark selected row as printed',
                    text: "Do you want to mark selected row as printed?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes'
                }).then(function () {
                    toggleLoading(true);
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('internship.mark_as_printed') }}',
                        data: {internship_ids: selected_ids},
                        success: function () {
                            notify('info', 'The marked row has been mark as printed.', 'Mark internship letter as printed');
                        },
                        error: function () {
                            notify('error', 'Something went wrong', 'Mark internship letter as printed');
                        },
                        complete: function () {
                            oTable.draw(true);
                            toggleLoading(false);
                        }
                    })
                })
            })
        });
    </script>
@stop
