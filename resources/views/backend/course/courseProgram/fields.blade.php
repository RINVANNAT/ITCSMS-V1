<div class="form-group">
    {!! Form::label('name_kh', trans('labels.backend.coursePrograms.fields.name_kh'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('name_kh', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name_en', trans('labels.backend.coursePrograms.fields.name_en'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {!! Form::text('name_en', null, ['class' => 'form-control','required'=>'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('name_fr', trans('labels.backend.coursePrograms.fields.name_fr'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {!! Form::text('name_fr', null, ['class' => 'form-control','required'=>'required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('code', trans('labels.backend.coursePrograms.fields.code'), ['class' => 'col-lg-2 control-label']) !!}
    <div class="col-lg-7">
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div id="creditholdertop">
<div id="credittemplate">

<div class="form-group">
    {!! Form::label('time_course', trans('labels.backend.coursePrograms.fields.time_course'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {!! Form::text('time_course', null, ['class' => 'form-control','required'=>'required', "v-model"=>"hourcourse"]) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('time_tp', trans('labels.backend.coursePrograms.fields.time_tp'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {!! Form::text('time_tp', null, ['class' => 'form-control','required'=>'required',"v-model"=>"hourtp"]) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('time_td', trans('labels.backend.coursePrograms.fields.time_td'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {!! Form::text('time_td', null, ['class' => 'form-control','required'=>'required',"v-model"=>"hourtd"]) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('credit', trans('labels.backend.coursePrograms.fields.credit'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        <div id="creditlabel">
            @{{credit}}
        </div>
        {!! Form::hidden('credit', null, ['class' => 'form-control','required'=>'required',"id"=>"credithidhen","v-model"=>"credit" ]) !!}
    </div>
</div>

</div>
</div>

<div class="form-group">
    {!! Form::label('degree', trans('labels.backend.coursePrograms.fields.degree'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('degree_id', $degrees, null, ['class' => 'form-control','required'=>'required']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('grades', trans('labels.backend.coursePrograms.fields.grade'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('grade_id', $grades, null, ['class' => 'form-control','required'=>'required']) }}
    </div>
</div>

<div class="form-group">
    {!! Form::label('departments', trans('labels.backend.coursePrograms.fields.department'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('department_id', $departments, null, ['class' => 'form-control','required'=>'required']) }}
    </div>
</div>
<div class="form-group dept_option_block">


</div>


<div class="form-group">
    {!! Form::label('semester', trans('labels.backend.coursePrograms.fields.semester'), ['class' => 'col-lg-2 control-label required']) !!}
    <div class="col-lg-7">
        {{ Form::select('semester_id', $semesters, null, ['class' => 'form-control']) }}
    </div>
</div>


