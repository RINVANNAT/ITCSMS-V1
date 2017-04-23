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
                    <a href="{{ route('admin.schedule.timetables.create') }}">
                        <button class="btn btn-primary btn-sm" data-toggle="tooltip"
                                data-placement="left" title="Create a new timetable"
                                data-original-title="Create a new timetable">
                            <i class="fa fa-plus-circle"
                            ></i> Create Timetable
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
                            <td>
                                @if($i%2==0)
                                    {{--<label class="label label-success label-lg">Completed</label>--}}
                                    <span class="btn btn-info btn-xs">
                                        <i class="fa fa-check"
                                           data-toggle="tooltip"
                                           data-placement="right" title="Completed"
                                           data-original-title="Completed"></i>
                                    </span>
                                @else
                                    {{--<label class="label label-warning">Uncompleted</label>--}}
                                    <span class="btn btn-danger btn-xs">
                                        <i class="fa fa-times-circle"
                                           data-toggle="tooltip"
                                           data-placement="right" title="Uncompleted"
                                           data-original-title="Uncompleted"></i>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-xs btn-primary">
                                    <i class="fa fa-share-square-o" data-toggle="tooltip"
                                       data-placement="top" title="View"
                                       data-original-title="View">

                                    </i>
                                </a>
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
