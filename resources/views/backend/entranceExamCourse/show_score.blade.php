@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . trans('labels.backend.exams.course.view_score'))

@section('after-styles-end')
    {!! Html::style('plugins/datatables/dataTables.bootstrap.css') !!}
    <style>

    </style>
@endsection
@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $entranceExamCourse->name_kh }}</h3>
        </div>

        <div class="box-body">
            <div>
                <table class="table table-striped table-bordered" cellspacing="0" width="100%" id="course-score-table">
                    <thead>
                    <tr>
                        <th>{{ trans('labels.backend.exams.course.fields.roomcode') }}</th>
                        <th>{{ trans('labels.backend.exams.course.fields.order') }}</th>
                        <th>{{ trans('labels.backend.exams.course.fields.sequence') }}</th>
                        <th>{{ trans('labels.backend.exams.course.fields.correct') }}</th>
                        <th>{{ trans('labels.backend.exams.course.fields.wrong') }}</th>
                        <th>{{ trans('labels.backend.exams.course.fields.na') }}</th>
                        <th>{{ trans('labels.backend.exams.course.fields.corrector') }}</th>
                        <th>{{ trans('labels.backend.exams.course.fields.register') }}</th>

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
            $('#course-score-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: {!! config('app.records_per_page')!!},
                ajax: {
                    url: '{!! route('admin.entranceExamCourses.data_score',$entranceExamCourse->id) !!}',
                    method: 'POST'
                },
                columns: [
                    { data: 'roomcode', name: 'roomcode'},
                    { data: 'order_in_room', name: 'order_in_room'},
                    { data: 'sequence', name: 'sequence'},
                    { data: 'score_c', name: 'score_c', searchable:false, orderable:false},
                    { data: 'score_w', name: 'score_w',searchable:false, orderable:false},
                    { data: 'score_na', name: 'score_na',searchable:false, orderable:false},
                    { data: 'corrector_name', name: 'corrector_name'},
                    { data: 'register_user', name: 'register_user'},
                ]
            });

        });
    </script>
@stop