@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.schedule.timetable.meta_title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.schedule.timetable.title') }}
        <small>{{ trans('labels.backend.schedule.timetable.sub_index_title') }}</small>
    </h1>

@endsection

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    {!! Html::style('plugins/sweetalert2/dist/sweetalert2.css') !!}
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
                <div class="pull-right">
                    <a href="{{ route('admin.schedule.timetables.create') }}">
                        <button class="btn btn-primary btn-sm" data-toggle="tooltip"
                                data-placement="top" title="Create a new timetable"
                                data-original-title="Create a new timetable">
                            <i class="fa fa-plus-circle"
                            ></i> Create Timetable
                        </button>
                    </a>
                </div>

                <form name="filter-timetable-view"
                      id="filter-timetable-view"
                      method="POST"
                      action="{{ route('admin.schedule.timetables.filter') }}">
                    @include('backend.schedule.timetables.includes.partials.option')
                </form>

            </div>
        </div>

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" id="list-timetable">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.schedule.timetable.index_timetable.number') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.index_timetable.weekly') }}</th>
                        <th>{{ trans('labels.backend.schedule.timetable.index_timetable.status') }}</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for($i=0; $i<100; $i++)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>Weekly{{$i+1}}</td>
                            <td>
                                @if($i%2==0)
                                    <span class="btn btn-info btn-xs">
                                        <i class="fa fa-check"
                                           data-toggle="tooltip"
                                           data-placement="top" title="Completed"
                                           data-original-title="Completed"></i>
                                    </span>
                                @else
                                    <span class="btn btn-danger btn-xs">
                                        <i class="fa fa-times-circle"
                                           data-toggle="tooltip"
                                           data-placement="top" title="Uncompleted"
                                           data-original-title="Uncompleted"></i>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.schedule.timetables.show') }}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-share-square-o" data-toggle="tooltip"
                                       data-placement="top" title="View"
                                       data-original-title="View">

                                    </i>
                                </a>
                                <a href="{{ route('admin.schedule.timetables.show') }}" class="btn btn-xs btn-danger">
                                    <i class="fa fa-trash" data-toggle="tooltip"
                                       data-placement="top" title="Delete"
                                       data-original-title="Delete">

                                    </i>
                                </a>
                            </td>
                        </tr>
                    @endfor
                    </tbody>
                </table>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>

@stop

@section('after-scripts-end')

    {!! Html::script('plugins/datatables/jquery.dataTables.min.js') !!}
    {!! Html::script('plugins/datatables/dataTables.bootstrap.min.js') !!}
    {!! Html::script('plugins/sweetalert2/dist/sweetalert2.js') !!}
    <script type="text/javascript">
        $('#list-timetable').DataTable();

        $('#filter-timetable-view').on('change', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '/admin/schedule/timetables/filter',
                data: $('#filter-timetable-view').serialize(),
                success: function (response) {
                    console.log(response);
                },
                error: function () {
                    swal(
                        'Oops...',
                        'Something went wrong!',
                        'error'
                    );
                }
            });

        });
    </script>

@stop
