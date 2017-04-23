@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.schedule.timetable.title'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.schedule.timetable.title') }}
        <small>{{ trans('labels.backend.schedule.timetable.sub_index_title') }}</small>
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
                <div class="pull-right">
                    <a href="#">
                        <button class="btn btn-primary btn-sm">
                            <i class="fa fa-plus-circle"></i> Create Timetable
                        </button>
                    </a>
                </div>

                {{--Option--}}
                <select name="academicYear">
                    <option selected disabled>Academic</option>
                    @foreach($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}">{{ $academicYear->name_latin }}</option>
                    @endforeach
                </select>

                <select name="degree">
                    <option selected disabled>Degree</option>
                    @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}">{{ $degree->name_en }}</option>
                    @endforeach
                </select>

                <select name="grade">
                    <option selected disabled>Year</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
                    @endforeach
                </select>

                <select name="grade">
                    <option selected disabled>Option</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
                    @endforeach
                </select>

                <select name="grade">
                    <option selected disabled>Semester</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
                    @endforeach
                </select>

                <select name="grade">
                    <option selected disabled>Group</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->name_en }}</option>
                    @endforeach
                </select>

            </div>
        </div>

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0"
                       id="employees-table">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Weekly</th>
                        <th>Status</th>
                        <th>{{ trans('labels.general.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for($i=0; $i<100; $i++)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>Weekly{{$i+1}}</td>
                            <td><span class="btn btn-success btn-xs"><i class="fa fa-check"></i> </span></td>
                            <td><a href="http://localhost:8000/admin/access/users/1/edit"
                                   class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip"
                                                                     data-placement="top" title=""
                                                                     data-original-title="Edit"></i></a> <a
                                        href="http://localhost:8000/admin/access/user/1/password/change"
                                        class="btn btn-xs btn-info"><i class="fa fa-refresh" data-toggle="tooltip"
                                                                       data-placement="top" title="Change Password"></i></a>
                                <a href="http://localhost:8000/admin/access/user/1/mark/0"
                                   class="btn btn-xs btn-warning"><i class="fa fa-pause" data-toggle="tooltip"
                                                                     data-placement="top" title="Deactivate"></i></a> <a
                                        href="http://localhost:8000/admin/access/users/1" data-method="delete"
                                        class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip"
                                                                         data-placement="top" title="Delete"></i></a>
                            </td>
                        </tr>
                    @endfor
                    </tbody>
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
        $('#employees-table').DataTable();
    </script>
@stop
