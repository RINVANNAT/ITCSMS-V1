@if(isset($courseAnnual))

    <div class="form-group">
        {!! Form::label('course', trans('labels.backend.courseAnnuals.fields.course'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-7">
            <select name="course_id" id="course_id" class="form-control" required>
                <option value="" disabled selected>Course</option>
                @foreach($courses as $course)
                    @if($course->id == $courseAnnual->course_id)
                        <option selected value="{{$courseAnnual->course_id}}" time_course="{{$courseAnnual->time_course}}" time_tp="{{$courseAnnual->time_tp}}" time_td="{{$courseAnnual->time_td}}" name_kh="{{$courseAnnual->name_kh}}" name_en="{{$courseAnnual->name_en}}" name_fr="{{$courseAnnual->name_fr}}" credit="{{$courseAnnual->credit}}">{{$courseAnnual->name_en}}</option>
                    @else
                        <option value="{{$course->id}}" time_course="{{$course->time_course}}" time_tp="{{$course->time_tp}}" time_td="{{$course->time_td}}" name_kh="{{$course->name_kh}}" name_en="{{$course->name_en}}" name_fr="{{$course->name_fr}}" credit="{{$course->credit}}" dept="{{$course->department_id}}" grade="{{$course->grade_id}}" degree="{{$course->degree_id}}" dept_option="{{$course->department_option_id}}" semester="{{$course->semester_id}}">{{$course->name_en}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

@else
    <div class="form-group">
        {!! Form::label('course', trans('labels.backend.courseAnnuals.fields.course'), ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-7">
            <select name="course_id" id="course_id" class="form-control" required>
                <option value="" disabled selected>Course</option>
                @foreach($courses as $course)
                    <option value="{{$course->id}}" time_course="{{$course->time_course}}" time_tp="{{$course->time_tp}}" time_td="{{$course->time_td}}" name_kh="{{$course->name_kh}}" name_en="{{$course->name_en}}" name_fr="{{$course->name_fr}}" credit="{{$course->credit}}" dept="{{$course->department_id}}" grade="{{$course->grade_id}}" degree="{{$course->degree_id}}" dept_option="{{$course->department_option_id}}" semester="{{$course->semester_id}}">{{$course->name_en}}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

<div class="form-group">
    {!! Form::label('course', trans('labels.backend.courseAnnuals.fields.employee'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('employee_id', $employees, null, ['class' => 'form-control', 'id'=>'lecturer_lists', 'placeholder' => 'Lecturer', 'required'=> 'required']) }}
    </div>

    @permission('view-all-score-course-annual')
        <button class="btn btn-sm btn-info pull-left label-control" id="other_dept">Others</button>
        <div class="col-lg-2 other_department">

        </div>
    @endauth

</div>

<div class="form-group">
    {!! Form::label('semester', trans('labels.backend.courseAnnuals.fields.academic_year'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('academic_year_id', $academicYears, null, ['class' => 'form-control', 'placeholder' => 'Academic year']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('semester', trans('labels.backend.courseAnnuals.fields.semester'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('semester_id', $semesters, null, ['class' => 'form-control', 'placeholder' => 'Semester', 'required' => 'required']) }}
    </div>
</div>



<div class="form-group">
    {!! Form::label('degree', trans('labels.backend.courseAnnuals.fields.degree'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('degree_id', $degrees, isset($courseAnnual->courseAnnualClass[0])?$courseAnnual->courseAnnualClass[0]->degree_id:null, ['class' => 'form-control','required'=>'required', 'placeholder' => 'Degree']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('grades', trans('labels.backend.courseAnnuals.fields.grade'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('grade_id', $grades, isset($courseAnnual->courseAnnualClass[0])?$courseAnnual->courseAnnualClass[0]->grade_id:null, ['class' => 'form-control','required'=>'required', 'placeholder' => 'Grade']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('departments', trans('labels.backend.courseAnnuals.fields.department'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('department_id', $departments, isset($courseAnnual->courseAnnualClass[0])?$courseAnnual->courseAnnualClass[0]->department_id:null, ['class' => 'form-control','required'=>'required']) }}
    </div>
</div>
<div class="form-group dept_option_block">


</div>

@if(($deptOptions != null) && (count($deptOptions) > 0))
    <div class="form-group">
        {!! Form::label('dept_option','Division', ['class' => 'col-lg-2 control-label required']) !!}
        <div class="col-lg-7">
            {{ Form::select('department_option_id', $deptOptions, isset($courseAnnual->courseAnnualClass[0])?$courseAnnual->courseAnnualClass[0]->department_option_id:null, ['class' => 'form-control','required'=>'required']) }}
        </div>
    </div>
@endif

<div class="form-group">
    {!! Form::label('credit', 'Credit', ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::text('credit',  isset($courseAnnual)?$courseAnnual->credit:null, ['class' => 'form-control' , 'id'=> 'credit', 'required' => 'required']) }}
    </div>
</div>



{{--<div class="form-group">--}}
    {{--{!! Form::label('', "Absence score percentage", ['class' => 'col-lg-2 control-label required']) !!}--}}
    {{--<div class="col-lg-7">--}}
        {{--{{ Form::text('score_percentage_column_1',  null, ['class' => 'form-control']) }}--}}
    {{--</div>--}}
{{--</div>--}}

{{--<div class="form-group">--}}
    {{--{!! Form::label('departments', "TP score percentage", ['class' => 'col-lg-2 control-label required']) !!}--}}
    {{--<div class="col-lg-7">--}}
        {{--{{ Form::text('score_percentage_column_2',  null, ['class' => 'form-control']) }}--}}
    {{--</div>--}}
{{--</div>--}}

{{--<div class="form-group">--}}
    {{--{!! Form::label('departments', "Final score percentage", ['class' => 'col-lg-2 control-label required']) !!}--}}
    {{--<div class="col-lg-7">--}}
        {{--{{ Form::text('score_percentage_column_3',  null, ['class' => 'form-control']) }}--}}
    {{--</div>--}}
{{--</div>--}}



{{--add new row--}}

<div class="form-group">
    {!! Form::label('time_course', "Time Course", ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-2">
        {{ Form::text('time_course',  null, ['class' => 'form-control', 'id'=>'time_course', 'required' => 'required']) }}
    </div>

    {!! Form::label('name_kh', "Name Khmer", ['class' => 'col-lg-2 control-label']) !!}

    <div class="col-lg-3">
        {{ Form::text('name_kh',  null, ['class' => 'form-control', 'id'=>'name_kh', 'required' => 'required']) }}
    </div>
</div>


<div class="form-group">
    {!! Form::label('time_td', "Time TD", ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-2">
        {{ Form::text('time_td',  null, ['class' => 'form-control', 'id'=> 'time_td', 'required' => 'required']) }}
    </div>

    {!! Form::label('name_en', "Name English", ['class' => 'col-lg-2 control-label']) !!}

    <div class="col-lg-3">
        {{ Form::text('name_en',  null, ['class' => 'form-control', 'id'=> 'name_en', 'required' => 'required']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('time_tp', "Time TP", ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-2">
        {{ Form::text('time_tp',  null, ['class' => 'form-control', 'id'=>'time_tp', 'required' => 'required']) }}
    </div>

    {!! Form::label('name_fr', "Name France", ['class' => 'col-lg-2 control-label']) !!}

    <div class="col-lg-3">
        {{ Form::text('name_fr',  null, ['class' => 'form-control' , 'id'=> 'name_fr', 'required' => 'required']) }}
    </div>
</div>



