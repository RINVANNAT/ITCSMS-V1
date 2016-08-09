<div class="form-group">
    {!! Form::label('course', trans('labels.backend.courseAnnuals.fields.course'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('course_id', $courses, null, ['class' => 'form-control']) }}
    </div>
</div>
<div class="form-group">
    {!! Form::label('course', trans('labels.backend.courseAnnuals.fields.employee'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('employee_id', $employees, null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('semester', trans('labels.backend.courseAnnuals.fields.academic_year'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('academic_year_id', $academicYears, null, ['class' => 'form-control']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('semester', trans('labels.backend.courseAnnuals.fields.semester'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('semester_id', $semesters, null, ['class' => 'form-control']) }}
    </div>
</div>



<div class="form-group">
    {!! Form::label('degree', trans('labels.backend.courseAnnuals.fields.degree'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('degree_id', $degrees, null, ['class' => 'form-control','required'=>'required']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('grades', trans('labels.backend.courseAnnuals.fields.grade'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('grade_id', $grades, null, ['class' => 'form-control','required'=>'required']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('departments', trans('labels.backend.courseAnnuals.fields.department'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('department_id', $departments, null, ['class' => 'form-control','required'=>'required']) }}
    </div>
</div>


