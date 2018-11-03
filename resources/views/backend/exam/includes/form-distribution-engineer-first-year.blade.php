@extends ('backend.layouts.popup_master')

@section ('title', trans('labels.backend.exams.title') . ' | ' . 'Form Distribution Engineer First Year')

@section('after-styles-end')
    <style>
        .text_font {
            font-size: 14pt;
        }

    </style>
@endsection

@section('content')
    <form action="{{route('admin.exam.generate-distribution-engineer-first-year')}}"
          method="POST"
          class="table_number_candidate_pass">
        <div class="box box-success">
            <div class="box-header with-border text_font">
                <h1 class="box-title"><span class="text_font">Input Number of Student Pass In Each Dept</span></h1>
            </div>
            <div class="box-body">
                <input type="hidden" name="exam_id" value="{{ $examId }}">
                {{ csrf_field() }}
                @foreach($departments as $department)
                    <div class="col-md-6 col-sm-6 form-group">
                        {!! Form::label("department[".$department->department_id."][success]", $department->name_abr, ['class' => 'col-md-4 col-sm-4 control-label required']) !!}
                        <div class="col-md-4 col-sm-4">
                            {{ Form::number("department[".$department->department_id."][success]", 0, ['class' => 'form-control number_only']) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    <a href="#" id="btn_cancel" class="btn btn-default btn-xs">Cancel</a>
                </div>

                <div class="pull-right">
                    <input type="submit" class="btn btn-primary btn-xs" value="OK"/>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </form>
@stop

@section('after-scripts-end')
    <script>

    </script>
@stop