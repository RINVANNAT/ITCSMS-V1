
@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.courseAnnuals.title') . ' | ' . trans('labels.backend.courseAnnuals.sub_edit_title'))

@section('content')

    <div class="box box-success">

        <div class="box-body">

            <div id="score_table">
                <center><h3>You don't have permission to score this course</h3></center>
            </div>
        </div>

    </div>
@stop